<?php
/**
 * Booking Availability API
 * Simplified direct PDO query approach
 */

// Start output buffering to prevent any accidental output
ob_start();

// Set JSON header immediately
header('Content-Type: application/json; charset=utf-8');

// Suppress display of errors (we'll return them as JSON)
ini_set('display_errors', '0');
error_reporting(E_ALL);

try {
    // Load config and database
    require_once __DIR__ . '/../config/config.php';
    $pdo = require __DIR__ . '/../config/database.php';
    
    // Validate HTTP method
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception("Method not allowed. GET required.");
    }
    
    // Get input params
    $itemId = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;
    $orderType = $_GET['order_type'] ?? 'rental';
    $startDate = $_GET['start_date'] ?? null;
    $endDate = $_GET['end_date'] ?? null;
    $eventDate = $_GET['event_date'] ?? $startDate; // For packages
    
    // Validate required parameters
    if (!$itemId || $itemId <= 0) {
        throw new Exception("Invalid or missing item_id parameter.");
    }
    
    if (!in_array($orderType, ['rental', 'package'])) {
        throw new Exception("Invalid order_type. Expected 'rental' or 'package'.");
    }
    
    // Log for debugging
    error_log("========================================");
    error_log("🔍 AVAILABILITY CHECK START");
    error_log("item_id: {$itemId}");
    error_log("order_type: {$orderType}");
    error_log("start_date: {$startDate}");
    error_log("end_date: {$endDate}");
    
    // First, let's see ALL bookings for this item (diagnostic)
    $diagSql = "SELECT order_id, order_type, item_id, rental_start, rental_end, event_date, payment_status 
                FROM booking_orders 
                WHERE item_id = :item_id AND order_type = :order_type
                ORDER BY order_id DESC";
    $diagStmt = $pdo->prepare($diagSql);
    $diagStmt->bindValue(':item_id', $itemId, PDO::PARAM_INT);
    $diagStmt->bindValue(':order_type', $orderType);
    $diagStmt->execute();
    $allBookings = $diagStmt->fetchAll();
    
    error_log("📊 ALL BOOKINGS FOR item_id={$itemId}, order_type={$orderType}:");
    error_log("   Total bookings found: " . count($allBookings));
    foreach ($allBookings as $booking) {
        $rentalStart = $booking['rental_start'] ?? 'NULL';
        $rentalEnd = $booking['rental_end'] ?? 'NULL';
        $status = $booking['payment_status'] ?? 'NULL';
        error_log("   - Order #{$booking['order_id']}: {$rentalStart} to {$rentalEnd}, Status: {$status}");
    }
    
    $conflict = null;
    
    if ($orderType === 'rental') {
        // Validate rental dates
        if (!$startDate || !$endDate) {
            throw new Exception("Missing required parameters: start_date and end_date are required for rental.");
        }
        
        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
            throw new Exception("Invalid date format. Expected YYYY-MM-DD.");
        }
        
        // Check for overlapping date ranges
        // Formula: (start_date <= rental_end) AND (end_date >= rental_start)
        // This detects ANY overlap between the two date ranges
        // IMPORTANT: Also check for NULL or empty payment_status (new bookings default to 'pending' but may be NULL)
        $sql = "
            SELECT order_id, rental_start, rental_end, payment_status
            FROM booking_orders
            WHERE order_type = 'rental'
              AND item_id = :item_id
              AND (
                  payment_status IN ('pending', 'paid', 'verified')
                  OR payment_status IS NULL
                  OR payment_status = ''
              )
              AND rental_start IS NOT NULL
              AND rental_end IS NOT NULL
              AND (
                  :start_date <= rental_end
                  AND :end_date >= rental_start
              )
            LIMIT 1
        ";
        
        error_log("📝 EXECUTING SQL QUERY:");
        error_log("   SQL: " . $sql);
        error_log("   Parameters:");
        error_log("     - item_id: {$itemId}");
        error_log("     - start_date: {$startDate}");
        error_log("     - end_date: {$endDate}");
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':item_id', $itemId, PDO::PARAM_INT);
        $stmt->bindValue(':start_date', $startDate, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $endDate, PDO::PARAM_STR);
        $stmt->execute();
        
        $conflict = $stmt->fetch();
        
        // Log detailed result
        if ($conflict) {
            error_log("❌ CONFLICT FOUND!");
            error_log("   Order ID: {$conflict['order_id']}");
            error_log("   Existing booking dates: {$conflict['rental_start']} to {$conflict['rental_end']}");
            error_log("   Requested dates: {$startDate} to {$endDate}");
            error_log("   Payment Status: {$conflict['payment_status']}");
            
            // Manual overlap verification
            $overlap1 = ($startDate <= $conflict['rental_end']) ? 'TRUE' : 'FALSE';
            $overlap2 = ($endDate >= $conflict['rental_start']) ? 'TRUE' : 'FALSE';
            error_log("   Overlap check 1 ({$startDate} <= {$conflict['rental_end']}): {$overlap1}");
            error_log("   Overlap check 2 ({$endDate} >= {$conflict['rental_start']}): {$overlap2}");
        } else {
            error_log("✅ NO CONFLICTS - Item is available");
            error_log("   Query returned no overlapping bookings");
        }
        
    } elseif ($orderType === 'package') {
        // Validate package event date
        if (!$eventDate) {
            throw new Exception("Missing required parameter: event_date is required for package.");
        }
        
        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $eventDate)) {
            throw new Exception("Invalid date format. Expected YYYY-MM-DD.");
        }
        
        // Check for same date bookings
        // IMPORTANT: Also check for NULL or empty payment_status
        $sql = "
            SELECT order_id, event_date, payment_status
            FROM booking_orders
            WHERE order_type = 'package'
              AND item_id = :item_id
              AND event_date = :event_date
              AND (
                  payment_status IN ('pending', 'paid', 'verified')
                  OR payment_status IS NULL
                  OR payment_status = ''
              )
            LIMIT 1
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':item_id', $itemId, PDO::PARAM_INT);
        $stmt->bindValue(':event_date', $eventDate);
        $stmt->execute();
        
        $conflict = $stmt->fetch();
        
        // Log result
        if ($conflict) {
            error_log("❌ Package conflict found - Order ID: {$conflict['order_id']}, Event Date: {$conflict['event_date']}, Status: {$conflict['payment_status']}");
        } else {
            error_log("✅ Package available - No conflicts found");
        }
    }
    
    // Prepare response
    $isAvailable = !$conflict;
    $responseData = [
        'available' => $isAvailable,
        'message' => $isAvailable 
            ? 'This item is available for rent.' 
            : 'This item is already rented within the selected dates.'
    ];
    
    error_log("📤 RETURNING RESPONSE:");
    error_log("   available: " . ($isAvailable ? 'true' : 'false'));
    error_log("   message: " . $responseData['message']);
    error_log("========================================");
    
    // Encode JSON
    $jsonOutput = json_encode($responseData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
    
    if ($jsonOutput === false) {
        throw new Exception("JSON encoding failed: " . json_last_error_msg());
    }
    
    // Clear output buffer and send response
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    echo $jsonOutput;
    exit;
    
} catch (Exception $e) {
    // Log error
    error_log("❌ Availability API Error: " . $e->getMessage());
    
    // Clear output buffer
    $output = ob_get_clean();
    if (!empty($output)) {
        error_log("Warning: Unexpected output captured: " . substr($output, 0, 200));
    }
    
    // Return error response
    http_response_code(500);
    $errorResponse = [
        'available' => false,
        'message' => 'Server error while checking availability: ' . $e->getMessage()
    ];
    
    $jsonOutput = json_encode($errorResponse, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
    
    if ($jsonOutput === false) {
        // Fallback if JSON encoding fails
        $jsonOutput = json_encode([
            'available' => false,
            'message' => 'Server error occurred'
        ]);
    }
    
    echo $jsonOutput;
    exit;
}

// Usage examples:
// GET /api/check_availability?item_id=1&order_type=rental&start_date=2025-11-02&end_date=2025-11-04
// GET /api/check_availability?item_id=5&order_type=package&event_date=2025-12-15