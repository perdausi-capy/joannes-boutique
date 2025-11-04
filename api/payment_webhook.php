<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/paymongo_config.php';
require_once __DIR__ . '/paymongo_helper.php';
require_once __DIR__ . '/../src/Models/BookingOrder.php';

$payload = file_get_contents('php://input');
$headers = getallheaders();
$signature = $headers['Paymongo-Signature'] ?? '';
if (!validateWebhookSignature($payload, $signature)) {
    http_response_code(400);
    echo 'Invalid signature.';
    exit;
}
$data = json_decode($payload, true);
if (!isset($data['data']['id'])) {
    http_response_code(400);
    echo 'Missing payment/link ID.';
    exit;
}
$paymongoId = $data['data']['id'];
$status = $data['data']['attributes']['status'] ?? null;
$model = new BookingOrder();
if (method_exists($model, 'updatePaymentStatusByPaymongoId')) {
    $model->updatePaymentStatusByPaymongoId($paymongoId, $status);
}
http_response_code(200);
echo 'OK';
