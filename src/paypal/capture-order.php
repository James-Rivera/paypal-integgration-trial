<?php
require_once __DIR__ . '/../../config/bootstrap.php';

use Avera\Utils\Response;

header('Content-Type: application/json');

$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    Response::error('Order ID is required', 400);
}

$paypalUrl = "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderId/capture"; // Sandbox for testing
$clientId = defined('PAYPAL_CLIENT_ID') ? PAYPAL_CLIENT_ID : getenv('PAYPAL_CLIENT_ID');
$clientSecret = defined('PAYPAL_CLIENT_SECRET') ? PAYPAL_CLIENT_SECRET : getenv('PAYPAL_CLIENT_SECRET');

$ch = curl_init($paypalUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode("$clientId:$clientSecret")
]);
curl_setopt($ch, CURLOPT_POST, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    Response::error('Failed to capture order', $httpCode ?: 500);
}

$responseData = json_decode($response, true);
// Attempt to extract a transaction/order identifier
$transactionId = $responseData['id'] ?? ($responseData['purchase_units'][0]['payments']['captures'][0]['id'] ?? null);

// Persist transaction id to a local log for records
try {
    $logDir = __DIR__ . '/../../storage';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }
    $logFile = $logDir . '/transactions.txt';
    $line = sprintf("%s\t%s\t%s\n", date('c'), $orderId, $transactionId ?: 'unknown');
    @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
} catch (\Throwable $e) {
    // Non-fatal: ignore logging errors
}

// Return response including a simple id field
if ($transactionId) {
    $responseData['transaction_id'] = $transactionId;
}
Response::json($responseData, 200);
?>