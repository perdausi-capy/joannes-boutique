<?php
require_once __DIR__ . '/../config/paymongo_config.php';

function paymongo_api_post($endpoint, $body = []) {
    $keys = getPayMongoKeys();
    $url = "https://api.paymongo.com/v1/$endpoint";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode($keys['secret_key'] . ':'),
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    $result = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['body' => $result, 'status' => $status];
}

function paymongo_api_get($endpoint) {
    $keys = getPayMongoKeys();
    $url = "https://api.paymongo.com/v1/$endpoint";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode($keys['secret_key'] . ':'),
        'Content-Type: application/json',
    ]);
    $result = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['body' => $result, 'status' => $status];
}

function createPaymentLink($amount, $description, $reference, $successUrl, $failureUrl) {
    // PayMongo expects amount in centavos
    $body = [
        'data' => [
            'attributes' => [
                'amount' => (int)($amount * 100),
                'description' => $description,
                'reference_number' => $reference,
                'success_url' => $successUrl,
                'cancel_url' => $failureUrl
            ]
        ]
    ];
    return paymongo_api_post('links', $body);
}

function verifyPayment($paymentId) {
    return paymongo_api_get('payments/' . $paymentId);
}

function validateWebhookSignature($payload, $signature) {
    // Placeholder: Implement using your webhook secret for production!
    return true;
}
