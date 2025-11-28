<?php
/**
 * Get Product Availability API
 * Returns general availability info for product cards (tooltips)
 */

// Start output buffering
ob_start();

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

// Suppress display of errors
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
    
    // Get product ID
    $productId = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
    
    // Validate product ID
    if ($productId <= 0) {
        throw new Exception("Invalid or missing product_id parameter.");
    }
    
    // Check if product exists and get its reserved status
    $stmt = $pdo->prepare("
        SELECT 
            id,
            name,
            is_reserved
        FROM products 
        WHERE id = ?
    ");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        throw new Exception("Product not found.");
    }
    
    // If product is marked as permanently reserved, return immediately
    if ($product['is_reserved']) {
        $responseData = [
            'success' => true,
            'is_reserved' => true,
            'product_id' => $productId,
            'product_name' => $product['name'],
            'bookings' => [],
            'next_available_date' => null
        ];
        
        // Clear buffer and send response
        ob_end_clean();
        echo json_encode($responseData);
        exit;
    }
    
    // Get upcoming bookings for this product (rentals only)
    // Using your existing booking_orders table structure
    $stmt = $pdo->prepare("
        SELECT 
            rental_start,
            rental_end,
            payment_status,
            event_date
        FROM booking_orders
        WHERE item_id = ?
        AND order_type = 'rental'
        AND payment_status IN ('pending', 'paid', 'verified')
        AND rental_end >= CURDATE()
        ORDER BY rental_start ASC
        LIMIT 5
    ");
    $stmt->execute([$productId]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Determine next available date
    $next_available_date = null;
    $today = date('Y-m-d');
    
    if (!empty($bookings)) {
        // Check if there's a booking that includes today
        $currentlyBooked = false;
        foreach ($bookings as $booking) {
            if ($booking['rental_start'] <= $today && $booking['rental_end'] >= $today) {
                $currentlyBooked = true;
                break;
            }
        }
        
        if ($currentlyBooked) {
            // Find the latest end date to determine when it becomes available
            $latest_end = $bookings[0]['rental_end'];
            foreach ($bookings as $booking) {
                if ($booking['rental_end'] > $latest_end) {
                    $latest_end = $booking['rental_end'];
                }
            }
            
            // Next available is the day after the latest booking
            $next_available_date = date('Y-m-d', strtotime($latest_end . ' +1 day'));
        } else {
            // Not currently booked, but has future bookings
            // Still available now, but show next booking info
            $next_available_date = $today;
        }
    } else {
        // No bookings at all - available now
        $next_available_date = $today;
    }
    
    // Prepare response
    $responseData = [
        'success' => true,
        'is_reserved' => false,
        'product_id' => $productId,
        'product_name' => $product['name'],
        'bookings' => $bookings,
        'next_available_date' => $next_available_date,
        'has_bookings' => !empty($bookings),
        'currently_booked' => $currentlyBooked ?? false
    ];
    
    // Clear buffer and send response
    ob_end_clean();
    echo json_encode($responseData);
    exit;
    
} catch (Exception $e) {
    // Log error
    error_log("❌ Get Product Availability Error: " . $e->getMessage());
    
    // Clear output buffer
    ob_end_clean();
    
    // Return error response
    http_response_code(500);
    $errorResponse = [
        'success' => false,
        'message' => $e->getMessage()
    ];
    
    echo json_encode($errorResponse);
    exit;
}
?>