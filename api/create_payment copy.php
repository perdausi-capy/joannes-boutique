<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../config/paymongo_config.php';
require_once __DIR__ . '/paymongo_helper.php';
require_once __DIR__ . '/../src/Models/BookingOrder.php';

header('Content-Type: application/json');

error_log('=== create_payment.php Request ===');
error_log('Method: ' . $_SERVER['REQUEST_METHOD']);
error_log('Body: ' . file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'POST required']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$bookingId = (int)($data['booking_id'] ?? 0);
$amount = (float)($data['amount'] ?? 0);
$name = trim($data['customer_name'] ?? '');
$email = trim($data['customer_email'] ?? '');

error_log("Inputs - ID: $bookingId, Amount: $amount, Name: $name, Email: $email");

if (!$bookingId || !$amount || !$name || !$email) {
    error_log('Missing required fields');
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

try {
    $model = new BookingOrder();
    $order = $model->findByOrderId($bookingId);
    
    error_log('Booking found: ' . ($order ? 'YES' : 'NO'));
    
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Booking not found.']);
        exit;
    }

    $dbAmount = (float)$order['total_amount'];
    error_log("Amount check - DB: $dbAmount, Submitted: $amount");
    
    if ($dbAmount !== $amount) {
        error_log('Amount mismatch!');
        echo json_encode(['success' => false, 'message' => 'Amount mismatch.']);
        exit;
    }

    $desc = 'Joannes Boutique Booking #' . $bookingId;
    $reference = 'BOOKING_' . $bookingId . '_' . time();
    
    $baseURL = rtrim($_ENV['APP_URL'] ?? (BASE_URL ?? ''), '/');
    $successUrl = $baseURL . '/payment/success.php';
    $failUrl = $baseURL . '/payment/fail.php';
    
    error_log("Creating payment - Desc: $desc, Ref: $reference, Amount: $amount");
    error_log("URLs - Success: $successUrl, Fail: $failUrl");

    $response = createPaymentLink($amount, $desc, $reference, $successUrl, $failUrl);
    
    error_log("PayMongo Response Status: " . $response['status']);
    error_log("PayMongo Response Body: " . $response['body']);

    $resp_json = json_decode($response['body'], true);

    // Check for success - PayMongo returns 200 or 201 for successful link creation
    if (($response['status'] === 200 || $response['status'] === 201) && isset($resp_json['data']['attributes']['checkout_url'])) {
        $paymongoPaymentId = $resp_json['data']['id'] ?? '';
        
        error_log("Payment link created - ID: $paymongoPaymentId, Checkout URL: " . $resp_json['data']['attributes']['checkout_url']);

        if (method_exists($model, 'updatePayMongoPayment')) {
            $model->updatePayMongoPayment($bookingId, $paymongoPaymentId, $reference);
            error_log("Database updated successfully");
        }

        echo json_encode([
            'success' => true,
            'checkout_url' => $resp_json['data']['attributes']['checkout_url'],
            'payment_id' => $paymongoPaymentId
        ]);
        exit;
    }

    error_log("PayMongo Error: " . json_encode($resp_json));
    echo json_encode([
        'success' => false,
        'message' => 'Failed to generate payment link',
        'error' => $resp_json['data']['errors'] ?? $resp_json
    ]);

} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    error_log("Trace: " . $e->getTraceAsString());
    
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>