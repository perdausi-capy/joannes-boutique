<?php
/**
 * API endpoint to fetch only verified bookings
 * Returns JSON list of verified bookings sorted by latest first
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Models/BookingOrder.php';

header('Content-Type: application/json');

try {
    $model = new BookingOrder();
    
    // Get all verified bookings
    $bookings = $model->findAllVerified();
    
    if (!$bookings) {
        $bookings = [];
    }
    
    // Sort by latest first
    usort($bookings, function($a, $b) {
        return strtotime($b['created_at'] ?? 0) - strtotime($a['created_at'] ?? 0);
    });
    
    echo json_encode([
        'success' => true,
        'count' => count($bookings),
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