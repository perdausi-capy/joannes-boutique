<?php

require_once __DIR__ . '/BaseModel.php';

class BookingOrder extends BaseModel
{
    protected $table = 'booking_orders';
    protected $primaryKey = 'order_id';
    
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (
            user_id, order_type, item_id, event_date, rental_start, rental_end,
            total_amount, payment_status, payment_method, contact_name, contact_email,
            contact_phone, quantity, size
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['user_id'] ?? null,
            $data['order_type'],
            $data['item_id'],
            $data['event_date'] ?? null,
            $data['rental_start'] ?? null,
            $data['rental_end'] ?? null,
            $data['total_amount'],
            $data['payment_status'] ?? 'pending',
            $data['payment_method'] ?? 'GCash',
            $data['contact_name'] ?? null,
            $data['contact_email'] ?? null,
            $data['contact_phone'] ?? null,
            $data['quantity'] ?? 1,
            $data['size'] ?? null
        ]);
        return $this->db->lastInsertId();
    }
    
    public function updatePayment($orderId, $referenceNumber, $proofImage)
    {
        $sql = "UPDATE {$this->table} SET 
            payment_status = 'paid',
            reference_number = ?,
            proof_image = ?
            WHERE order_id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$referenceNumber, $proofImage, $orderId]);
    }
    
    public function findByOrderId($orderId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE order_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetch();
    }

    public function resolvePenalty(int $orderId): bool {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET penalty_amount = 0, is_penalty_paid = 1 
            WHERE order_id = :order_id
        ");
        return $stmt->execute(['order_id' => $orderId]);
    }
    
    
    
    
    public function findAllByUser($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findAllWithDetailsByUser($userId)
    {
        $sql = "SELECT bo.*, 
                CASE 
                    WHEN bo.order_type = 'rental' THEN p.name
                    WHEN bo.order_type = 'package' THEN pk.package_name
                END as item_name,
                CASE 
                    WHEN bo.order_type = 'rental' THEN p.image
                    WHEN bo.order_type = 'package' THEN pk.background_image
                END as item_image
                FROM {$this->table} bo
                LEFT JOIN products p ON bo.order_type = 'rental' AND bo.item_id = p.id
                LEFT JOIN packages pk ON bo.order_type = 'package' AND bo.item_id = pk.package_id
                WHERE bo.user_id = ?
                ORDER BY bo.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function findAllPending()
    {
        $sql = "SELECT * FROM {$this->table} WHERE payment_status = 'pending' ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findAllPaid()
    {
        $sql = "SELECT * FROM {$this->table} WHERE payment_status = 'paid' ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findAllVerified()
    {
        $sql = "SELECT * FROM {$this->table} WHERE payment_status = 'verified' ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findAll($limit = null, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
            if ($offset > 0) {
                $sql .= " OFFSET {$offset}";
            }
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getOrderWithDetails($orderId)
    {
        $sql = "SELECT bo.*, 
                CASE 
                    WHEN bo.order_type = 'rental' THEN p.name
                    WHEN bo.order_type = 'package' THEN pk.package_name
                END as item_name,
                CASE 
                    WHEN bo.order_type = 'rental' THEN p.image
                    WHEN bo.order_type = 'package' THEN pk.background_image
                END as item_image
                FROM {$this->table} bo
                LEFT JOIN products p ON bo.order_type = 'rental' AND bo.item_id = p.id
                LEFT JOIN packages pk ON bo.order_type = 'package' AND bo.item_id = pk.package_id
                WHERE bo.order_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetch();
    }
    
    public function verifyPayment($orderId)
    {
        $sql = "UPDATE {$this->table} SET payment_status = 'verified' WHERE order_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$orderId]);
    }

    public function updatePayMongoPayment($orderId, $paymongoPaymentId, $reference) {
        $sql = "UPDATE {$this->table} SET paymongo_payment_id = ?, reference_number = ? WHERE order_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$paymongoPaymentId, $reference, $orderId]);
    }
    public function updatePaymentStatusByPaymongoId($paymongoPaymentId, $status) {
        $statusMap = [
            'paid' => 'paid',
            'unpaid' => 'pending',
            'failed' => 'pending',
        ];
        $finalStatus = $statusMap[$status] ?? 'pending';
        $sql = "UPDATE {$this->table} SET payment_status = ? WHERE paymongo_payment_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$finalStatus, $paymongoPaymentId]);
    }

    /**
     * Check if an item is available for the given date range
     * 
     * @param int $itemId The specific product/package ID to check
     * @param string $orderType 'rental' or 'package'
     * @param string $startDate Rental start date or package event date
     * @param string|null $endDate Rental end date (required for rentals)
     * @param int|null $excludeOrderId Optional order ID to exclude from check (for updates)
     * @return array ['available' => bool, 'conflicting_orders' => array]
     */
    public function checkAvailability($itemId, $orderType, $startDate, $endDate = null, $excludeOrderId = null)
    {
        $conflictingOrders = [];
        
        // Log the check for debugging
        error_log("=== AVAILABILITY CHECK START ===");
        error_log("item_id: {$itemId}, order_type: {$orderType}, start_date: {$startDate}, end_date: " . ($endDate ?? 'NULL'));

        if ($orderType === 'rental') {
            if (empty($endDate)) {
                error_log("ERROR: end_date is required for rental");
                return ['available' => false, 'conflicting_orders' => [['msg' => 'end_date required for rental']]];
            }

            // Validate dates are not NULL
            if (empty($startDate) || empty($endDate)) {
                error_log("ERROR: Empty dates provided - startDate: " . ($startDate ?? 'NULL') . ", endDate: " . ($endDate ?? 'NULL'));
                return ['available' => false, 'conflicting_orders' => [['msg' => 'Invalid dates provided']]];
            }

            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
                error_log("ERROR: Invalid date format");
                return ['available' => false, 'conflicting_orders' => [['msg' => 'Invalid date format']]];
            }

            // Check for overlapping date ranges using the correct formula:
            // (start_date <= rental_end) AND (end_date >= rental_start)
            // Active statuses that block availability (only real statuses from the enum)
            $activeStatuses = ['pending', 'paid', 'verified'];
            
            // Build status placeholders for IN clause
            $statusPlaceholders = implode(',', array_fill(0, count($activeStatuses), '?'));
            
            // IMPORTANT: Also check for NULL or empty payment_status (new bookings may have empty status)
            $sql = "SELECT order_id, rental_start, rental_end, payment_status, contact_name
                    FROM {$this->table}
                    WHERE order_type = 'rental'
                      AND item_id = ?
                      AND (
                          payment_status IN ({$statusPlaceholders})
                          OR payment_status IS NULL
                          OR payment_status = ''
                      )
                      AND rental_start IS NOT NULL
                      AND rental_end IS NOT NULL
                      AND ? <= rental_end
                      AND ? >= rental_start";

            // Build parameters: itemId, statuses..., startDate, endDate
            $params = array_merge([$itemId], $activeStatuses, [$startDate, $endDate]);

            if ($excludeOrderId) {
                $sql .= " AND order_id != ?";
                $params[] = $excludeOrderId;
            }

            // Log the full SQL query and parameters for debugging
            error_log("--- RENTAL AVAILABILITY QUERY ---");
            error_log("SQL: " . $sql);
            error_log("Parameters:");
            error_log("  - item_id: {$itemId}");
            foreach ($activeStatuses as $idx => $status) {
                error_log("  - status[" . ($idx + 1) . "]: {$status}");
            }
            error_log("  - start_date (check <= rental_end): {$startDate}");
            error_log("  - end_date (check >= rental_start): {$endDate}");
            if ($excludeOrderId) {
                error_log("  - exclude_order_id: {$excludeOrderId}");
            }

            // Diagnostic: Check all bookings for this item to see what exists
            $diagSql = "SELECT order_id, rental_start, rental_end, payment_status, order_type 
                       FROM {$this->table} 
                       WHERE item_id = ? AND order_type = 'rental'";
            $diagStmt = $this->db->prepare($diagSql);
            $diagStmt->execute([$itemId]);
            $allBookings = $diagStmt->fetchAll(\PDO::FETCH_ASSOC);
            error_log("--- ALL BOOKINGS FOR ITEM_ID {$itemId} ---");
            error_log("Total bookings found: " . count($allBookings));
            foreach ($allBookings as $booking) {
                $rentalStart = $booking['rental_start'] ?? 'NULL';
                $rentalEnd = $booking['rental_end'] ?? 'NULL';
                $paymentStatus = $booking['payment_status'] ?? 'NULL';
                $orderId = $booking['order_id'] ?? 'NULL';
                
                error_log("  Order ID: {$orderId}");
                error_log("    - rental_start: {$rentalStart}");
                error_log("    - rental_end: {$rentalEnd}");
                error_log("    - payment_status: {$paymentStatus}");
                
                // Check if this booking would block (for manual verification)
                if ($rentalStart !== 'NULL' && $rentalEnd !== 'NULL' && in_array($paymentStatus, $activeStatuses)) {
                    $overlaps = ($startDate <= $rentalEnd) && ($endDate >= $rentalStart);
                    error_log("    - Would block? " . ($overlaps ? 'YES' : 'NO'));
                    if ($overlaps) {
                        error_log("    - Overlap check: ({$startDate} <= {$rentalEnd}) = " . ($startDate <= $rentalEnd ? 'TRUE' : 'FALSE'));
                        error_log("    - Overlap check: ({$endDate} >= {$rentalStart}) = " . ($endDate >= $rentalStart ? 'TRUE' : 'FALSE'));
                    }
                }
            }

            // Execute the query
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $conflictingOrders = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Log results
            error_log("--- QUERY RESULTS ---");
            error_log("Conflicting orders found: " . count($conflictingOrders));
            
            if (count($conflictingOrders) > 0) {
                error_log("Blocking bookings:");
                foreach ($conflictingOrders as $conflict) {
                    error_log("  - Order ID: {$conflict['order_id']}");
                    error_log("    rental_start: {$conflict['rental_start']}");
                    error_log("    rental_end: {$conflict['rental_end']}");
                    error_log("    payment_status: {$conflict['payment_status']}");
                    error_log("    contact_name: " . ($conflict['contact_name'] ?? 'N/A'));
                }
            } else {
                error_log("No blocking bookings found - item is available");
            }
            error_log("=== AVAILABILITY CHECK END ===");

        } elseif ($orderType === 'package') {
            // For packages, check if event_date conflicts with same item_id
            $activeStatuses = ['pending', 'paid', 'verified'];
            $statusPlaceholders = implode(',', array_fill(0, count($activeStatuses), '?'));
            
            // IMPORTANT: Also check for NULL or empty payment_status
            $sql = "SELECT order_id, event_date, payment_status, contact_name
                    FROM {$this->table}
                    WHERE order_type = 'package'
                      AND item_id = ?
                      AND event_date = ?
                      AND (
                          payment_status IN ({$statusPlaceholders})
                          OR payment_status IS NULL
                          OR payment_status = ''
                      )";

            $params = array_merge([$itemId, $startDate], $activeStatuses);
            
            if ($excludeOrderId) {
                $sql .= " AND order_id != ?";
                $params[] = $excludeOrderId;
            }

            error_log("--- PACKAGE AVAILABILITY QUERY ---");
            error_log("SQL: " . $sql);
            error_log("Parameters: item_id={$itemId}, event_date={$startDate}");

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $conflictingOrders = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            error_log("Package availability check for item_id={$itemId}, event_date={$startDate}: Found " . count($conflictingOrders) . " conflicting orders");
        }

        return [
            'available' => empty($conflictingOrders),
            'conflicting_orders' => $conflictingOrders
        ];
    }


    /**
     * Get all booked dates for an item (for calendar disable)
     */
    public function getBookedDates($itemId, $orderType)
    {
        $booked = [];

        if ($orderType === 'rental') {
            $sql = "SELECT rental_start, rental_end
                    FROM {$this->table}
                    WHERE order_type = 'rental'
                      AND item_id = ?
                      AND payment_status IN ('pending','paid','verified')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$itemId]);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($rows as $r) {
                $start = new \DateTime($r['rental_start']);
                $end = new \DateTime($r['rental_end']);
                $end->modify('+1 day');
                $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);
                foreach ($period as $d) {
                    $booked[] = $d->format('Y-m-d');
                }
            }
        } elseif ($orderType === 'package') {
            $sql = "SELECT event_date
                    FROM {$this->table}
                    WHERE order_type = 'package'
                      AND item_id = ?
                      AND payment_status IN ('pending','paid','verified')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$itemId]);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $r) $booked[] = $r['event_date'];
        }

        return array_values(array_unique($booked));
    }

    /**
     * Calculate late return penalty for a booking order
     * Penalty: ₱250 per day past rental_end date
     * 
     * @param int $orderId The booking order ID
     * @return array ['penalty_amount' => float, 'days_late' => int, 'is_overdue' => bool]
     */
    public function calculatePenalty($orderId)
    {
        $order = $this->findByOrderId($orderId);
        
        if (!$order || $order['order_type'] !== 'rental' || empty($order['rental_end'])) {
            return [
                'penalty_amount' => 0,
                'days_late' => 0,
                'is_overdue' => false
            ];
        }
        
        $rentalEnd = new \DateTime($order['rental_end']);
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $rentalEnd->setTime(0, 0, 0);
        
        // Only calculate if overdue and not returned
        if ($today <= $rentalEnd) {
            return [
                'penalty_amount' => 0,
                'days_late' => 0,
                'is_overdue' => false
            ];
        }
        
        $daysLate = $today->diff($rentalEnd)->days;
        $penaltyAmount = $daysLate * 250;
        
        return [
            'penalty_amount' => $penaltyAmount,
            'days_late' => $daysLate,
            'is_overdue' => true
        ];
    }
    
    /**
     * Calculate and update penalty for a booking order
     * Auto-updates the database if penalty has changed
     * 
     * @param int $orderId The booking order ID
     * @return array ['penalty_amount' => float, 'days_late' => int, 'updated' => bool]
     */
    public function calculateAndUpdatePenalty($orderId)
    {
        $order = $this->findByOrderId($orderId);
        
        if (!$order || $order['order_type'] !== 'rental') {
            return ['penalty_amount' => 0, 'days_late' => 0, 'updated' => false];
        }
        
        $penaltyInfo = $this->calculatePenalty($orderId);
        $currentPenalty = (float)($order['penalty_amount'] ?? 0);
        
        // Only update if penalty changed and order is still active
        if ($penaltyInfo['penalty_amount'] != $currentPenalty && 
            in_array($order['payment_status'] ?? '', ['pending', 'paid', 'verified'])) {
            
            // Don't update if penalty is already paid
            if (!empty($order['is_penalty_paid']) && $order['is_penalty_paid'] == 1) {
                return array_merge($penaltyInfo, ['updated' => false]);
            }
            
            $sql = "UPDATE {$this->table} SET penalty_amount = ? WHERE order_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$penaltyInfo['penalty_amount'], $orderId]);
            
            return array_merge($penaltyInfo, ['updated' => true]);
        }
        
        return array_merge($penaltyInfo, ['updated' => false]);
    }
    
    /**
     * Get total amount due (rental fee + penalty - if unpaid)
     * 
     * @param int $orderId The booking order ID
     * @return float Total amount due
     */
    public function getTotalAmountDue($orderId)
    {
        $order = $this->findByOrderId($orderId);
        
        if (!$order) {
            return 0;
        }
        
        $rentalAmount = (float)($order['total_amount'] ?? 0);
        $penaltyAmount = 0;
        
        // Only add penalty if it's unpaid
        if (!empty($order['penalty_amount']) && 
            (empty($order['is_penalty_paid']) || $order['is_penalty_paid'] == 0)) {
            $penaltyAmount = (float)$order['penalty_amount'];
        }
        
        return $rentalAmount + $penaltyAmount;
    }
    
    /**
     * Get overdue rentals for a specific item
     * Used to show penalty information on product details page
     * 
     * @param int $itemId The product/item ID
     * @param int|null $userId Optional: filter by user ID
     * @return array List of overdue bookings with penalty info
     */
    public function getOverdueRentalsForItem($itemId, $userId = null)
    {
        $sql = "SELECT order_id, user_id, rental_start, rental_end, total_amount, 
                       penalty_amount, is_penalty_paid, payment_status,
                       DATEDIFF(CURDATE(), rental_end) as days_late
                FROM {$this->table}
                WHERE order_type = 'rental'
                  AND item_id = ?
                  AND rental_end IS NOT NULL
                  AND CURDATE() > rental_end
                  AND payment_status IN ('pending', 'paid', 'verified')
                  AND (is_penalty_paid IS NULL OR is_penalty_paid = 0)";
        
        $params = [$itemId];
        
        if ($userId !== null) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        
        $sql .= " ORDER BY rental_end DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        $overdue = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Calculate current penalty for each order
        foreach ($overdue as &$booking) {
            $penaltyInfo = $this->calculatePenalty($booking['order_id']);
            $booking['current_penalty'] = $penaltyInfo['penalty_amount'];
            $booking['days_late'] = $penaltyInfo['days_late'];
            $booking['total_due'] = (float)$booking['total_amount'] + $penaltyInfo['penalty_amount'];
        }
        
        return $overdue;
    }
    
    /**
     * Mark penalty as paid and update payment status
     * Called after successful payment that includes penalty
     * 
     * @param int $orderId The booking order ID
     * @param float $penaltyAmount The penalty amount paid
     * @return bool Success status
     */
    public function markPenaltyAsPaid($orderId, $penaltyAmount = null)
    {
        if ($penaltyAmount === null) {
            // Get current penalty amount
            $penaltyInfo = $this->calculatePenalty($orderId);
            $penaltyAmount = $penaltyInfo['penalty_amount'];
        }
        
        $sql = "UPDATE {$this->table} 
                SET is_penalty_paid = 1,
                    penalty_amount = ?
                WHERE order_id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$penaltyAmount, $orderId]);
    }
    
    /**
     * Update payment with penalty handling
     * Extended version of updatePayment that handles penalties
     * 
     * @param int $orderId The booking order ID
     * @param string $referenceNumber Payment reference
     * @param string $proofImage Payment proof image path
     * @param bool $includePenalty Whether this payment includes penalty
     * @return bool Success status
     */
    public function updatePaymentWithPenalty($orderId, $referenceNumber, $proofImage, $includePenalty = false)
    {
        $penaltyPaid = 0;
        $penaltyAmount = 0;
        
        if ($includePenalty) {
            $penaltyInfo = $this->calculatePenalty($orderId);
            $penaltyAmount = $penaltyInfo['penalty_amount'];
            if ($penaltyAmount > 0) {
                $penaltyPaid = 1;
            }
        }
        
        $sql = "UPDATE {$this->table} SET 
                payment_status = 'paid',
                reference_number = ?,
                proof_image = ?,
                penalty_amount = ?,
                is_penalty_paid = ?
                WHERE order_id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $referenceNumber, 
            $proofImage, 
            $penaltyAmount,
            $penaltyPaid,
            $orderId
        ]);
    }
}
