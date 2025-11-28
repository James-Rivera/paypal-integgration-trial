<?php
require_once __DIR__ . '/../../config/bootstrap.php';

use Avera\Utils\Response;

header('Content-Type: application/json');

$body = json_decode(file_get_contents('php://input'), true);

if (!isset($body['amount']) || !isset($body['currency'])) {
    Response::error('Amount and currency are required.', 400);
}

$amount = $body['amount'];
$currency = $body['currency'];

// PayPal API endpoint for creating an order
$paypalUrl = "https://api-m.sandbox.paypal.com/v2/checkout/orders";

$clientId = defined('PAYPAL_CLIENT_ID') ? PAYPAL_CLIENT_ID : getenv('PAYPAL_CLIENT_ID');
$clientSecret = defined('PAYPAL_CLIENT_SECRET') ? PAYPAL_CLIENT_SECRET : getenv('PAYPAL_CLIENT_SECRET');

$ch = curl_init($paypalUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode("$clientId:$clientSecret")
]);

$data = [
    'intent' => 'CAPTURE',
    'purchase_units' => [[
        'amount' => [
            'currency_code' => $currency,
            'value' => $amount
        ]
    ]]
];

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    Response::error('Failed to create order.', 500);
}

$orderData = json_decode($response, true);

if (isset($orderData['id'])) {
    Response::success(['id' => $orderData['id']], 200);
} else {
    Response::error('Failed to create order.', 500);
}
?>