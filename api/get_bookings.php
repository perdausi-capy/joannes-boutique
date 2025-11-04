<?php
/**
 * API endpoint to fetch bookings
 * Returns JSON list of all bookings
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Models/BookingOrder.php';

header('Content-Type: application/json');

try {
    $model = new BookingOrder();
    
    // Get all bookings
    $bookings = $model->findAll();
    
    if (!$bookings) {
        $bookings = [];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $bookings
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch bookings: ' . $e->getMessage()
    ]);
}
?>