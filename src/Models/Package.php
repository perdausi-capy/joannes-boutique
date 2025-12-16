<?php
class Package extends BaseModel {
    protected $table = 'packages';
    protected $primaryKey = 'package_id';

    public function createPackage(array $data) {
        $payload = [
            'package_name' => $data['package_name'] ?? null,
            'hotel_name' => $data['hotel_name'] ?? null,
            'hotel_address' => $data['hotel_address'] ?? null,
            'hotel_description' => $data['hotel_description'] ?? null,
            'number_of_guests' => (int)($data['number_of_guests'] ?? 0),
            'inclusions' => json_encode($data['inclusions'] ?? new stdClass()),
            'freebies' => $data['freebies'] ?? null,
            'price' => $data['price'] ?? null,
        ];
        if (!empty($data['background_image'])) {
            $payload['background_image'] = $data['background_image'];
        }
        return $this->create($payload);
    }

    public function findRecent($limit = 50) {
        return $this->findAll($limit);
    }

    public function updatePackage(int $id, array $data) {
        $payload = [
            'package_name' => $data['package_name'] ?? null,
            'hotel_name' => $data['hotel_name'] ?? null,
            'hotel_address' => $data['hotel_address'] ?? null,
            'hotel_description' => $data['hotel_description'] ?? null,
            'number_of_guests' => (int)($data['number_of_guests'] ?? 0),
            'inclusions' => json_encode($data['inclusions'] ?? new stdClass()),
            'freebies' => $data['freebies'] ?? null,
            'price' => $data['price'] ?? null,
        ];
        if (!empty($data['background_image'])) {
            $payload['background_image'] = $data['background_image'];
        }
        return $this->update($id, $payload);
    }

    public function deletePackage(int $id) {
        return $this->delete($id);
    }

    /**
 * Add these methods to your Package model (src/Models/Package.php)
 * These methods handle automatic unlocking of packages after events
 */

/**
 * Get package reservation status similar to products
 * Checks if package is currently reserved and calculates next available date
 * 
 * @param int $packageId The package ID
 * @return array ['is_reserved' => bool, 'next_available_date' => string|null, 'event_date' => string|null]
 */
public function getPackageReservationStatus($packageId) {
    $currentDate = date('Y-m-d');
    
    // Find the LAST verified booking (most recent event_date) where event hasn't passed yet
    // OR find ANY verified booking in the future
    $sql = "SELECT event_date, payment_status
            FROM booking_orders
            WHERE order_type = 'package'
              AND item_id = ?
              AND event_date >= ?
              AND payment_status = 'verified'
            ORDER BY event_date DESC
            LIMIT 1";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$packageId, $currentDate]);
    $booking = $stmt->fetch();
    
    if ($booking) {
        // Package has a verified booking that hasn't happened yet
        // Next available = event_date + 1 day
        $eventDate = new \DateTime($booking['event_date']);
        $eventDate->modify('+2 days');
        
        return [
            'is_reserved' => true,
            'next_available_date' => $eventDate->format('Y-m-d'),
            'event_date' => $booking['event_date']
        ];
    }
    
    // No upcoming verified bookings - package is available
    return [
        'is_reserved' => false,
        'next_available_date' => null,
        'event_date' => null
    ];
}

/**
 * Auto-unlock packages where event date has passed
 * Should be called periodically (cron job, or on page load)
 * 
 * @return array ['unlocked_count' => int, 'unlocked_packages' => array]
 */
public function unlockExpiredPackages() {
    $currentDate = date('Y-m-d');
    $unlocked = [];
    
    error_log("=== UNLOCK EXPIRED PACKAGES CALLED ===");
    error_log("Current Date: " . $currentDate);
    
    // Find all reserved packages
    $sql = "SELECT package_id, package_name 
            FROM packages 
            WHERE is_reserved = 1";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $reservedPackages = $stmt->fetchAll();
    
    error_log("Found " . count($reservedPackages) . " reserved packages");
    
    foreach ($reservedPackages as $package) {
        $packageId = $package['package_id'];
        
        error_log("Checking package ID: " . $packageId . " (" . $package['package_name'] . ")");
        
        // Check if this package has any FUTURE verified bookings
        $checkSql = "SELECT COUNT(*) as count
                     FROM booking_orders
                     WHERE order_type = 'package'
                       AND item_id = ?
                       AND event_date >= ?
                       AND payment_status = 'verified'";
        
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->execute([$packageId, $currentDate]);
        $result = $checkStmt->fetch();
        
        error_log("  Future bookings for package {$packageId}: " . $result['count']);
        
        // If no future bookings, unlock the package
        if ($result['count'] == 0) {
            $updateSql = "UPDATE packages SET is_reserved = 0 WHERE package_id = ?";
            $updateStmt = $this->db->prepare($updateSql);
            
            if ($updateStmt->execute([$packageId])) {
                $unlocked[] = [
                    'package_id' => $packageId,
                    'package_name' => $package['package_name']
                ];
                
                error_log("✅ UNLOCKED package: {$package['package_name']} (ID: {$packageId})");
            } else {
                error_log("❌ FAILED to unlock package: {$package['package_name']} (ID: {$packageId})");
            }
        } else {
            error_log("  Package {$packageId} still has future bookings - keeping locked");
        }
    }
    
    error_log("=== UNLOCK COMPLETED - Unlocked " . count($unlocked) . " packages ===");
    
    return [
        'unlocked_count' => count($unlocked),
        'unlocked_packages' => $unlocked
    ];
}

/**
 * Update package with reservation status and next available date
 * Call this when retrieving package details
 * 
 * @param array $package Package data array
 * @return array Package data with reservation info added
 */
public function enrichPackageWithReservationStatus($package) {
    if (!$package) return $package;
    
    error_log("ENRICH: Starting for package " . $package['package_id']);
    
    $reservationStatus = $this->getPackageReservationStatus($package['package_id']);
    
    error_log("ENRICH: Got reservation status - is_reserved: " . ($reservationStatus['is_reserved'] ? 'TRUE' : 'FALSE'));
    
    // Merge reservation status into package data
    $package['is_reserved'] = $reservationStatus['is_reserved'] || ($package['is_reserved'] ?? false);
    $package['next_available_date'] = $reservationStatus['next_available_date'];
    $package['event_date'] = $reservationStatus['event_date'];
    
    error_log("ENRICH: Final package is_reserved: " . ($package['is_reserved'] ? 'TRUE' : 'FALSE'));
    
    return $package;
}

/**
 * Get all packages with enriched reservation status
 * Use this instead of findAll() to include availability info
 * 
 * @return array List of packages with reservation status
 */
public function findAllWithReservationStatus() {
    error_log("=== findAllWithReservationStatus CALLED ===");
    
    // First, auto-unlock any expired packages
    $this->unlockExpiredPackages();
    
    // Get all packages
    $packages = $this->findAll();
    error_log("Found " . count($packages) . " packages");
    
    // Enrich each package with reservation status
    foreach ($packages as &$package) {
        error_log("Processing package ID: " . $package['package_id']);
        $package = $this->enrichPackageWithReservationStatus($package);
        error_log("After enrichment - is_reserved: " . ($package['is_reserved'] ?? 'NOT SET'));
    }
    
    error_log("=== Returning packages ===");
    return $packages;
}

/**
 * Get single package by ID with reservation status
 * Use this instead of findById() to include availability info
 * 
 * @param int $packageId The package ID
 * @return array|false Package data with reservation info, or false if not found
 */
public function findByIdWithReservationStatus($packageId) {
    // First, auto-unlock any expired packages
    $this->unlockExpiredPackages();
    
    // Get package
    $package = $this->findById($packageId);
    
    if (!$package) {
        return false;
    }
    
    // Enrich with reservation status
    return $this->enrichPackageWithReservationStatus($package);
}
}


