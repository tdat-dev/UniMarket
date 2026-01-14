<?php
/**
 * Test PayOS API directly
 */

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== PayOS Direct Test ===\n\n";

// Get credentials
$clientId = $_ENV['PAYOS_CLIENT_ID'] ?? '';
$apiKey = $_ENV['PAYOS_API_KEY'] ?? '';
$checksumKey = $_ENV['PAYOS_CHECKSUM_KEY'] ?? '';

echo "Client ID: $clientId\n";
echo "API Key: " . substr($apiKey, 0, 10) . "...\n";
echo "Checksum Key: " . substr($checksumKey, 0, 10) . "...\n\n";

if (empty($clientId) || empty($apiKey) || empty($checksumKey)) {
    die("ERROR: Missing PayOS credentials!\n");
}

// Test create payment
$orderCode = time() % 10000000; // Random order code
$amount = 10000; // 10,000 VND - min amount
$description = "Test";
$returnUrl = "http://localhost:8000/payment/return";
$cancelUrl = "http://localhost:8000/payment/cancel";

$items = [
    [
        'name' => 'Test Product',
        'quantity' => 1,
        'price' => $amount
    ]
];

$payload = [
    'orderCode' => $orderCode,
    'amount' => $amount,
    'description' => $description,
    'returnUrl' => $returnUrl,
    'cancelUrl' => $cancelUrl,
    'items' => $items
];

// Create signature
$signData = "amount=$amount&cancelUrl=$cancelUrl&description=$description&orderCode=$orderCode&returnUrl=$returnUrl";
$signature = hash_hmac('sha256', $signData, $checksumKey);
$payload['signature'] = $signature;

echo "Order Code: $orderCode\n";
echo "Sign Data: $signData\n";
echo "Signature: $signature\n\n";

echo "Payload:\n";
print_r($payload);
echo "\n";

// Call API
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api-merchant.payos.vn/v2/payment-requests',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'x-client-id: ' . $clientId,
        'x-api-key: ' . $apiKey
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
if ($curlError) {
    echo "cURL Error: $curlError\n";
}

echo "\nResponse:\n";
$data = json_decode($response, true);
print_r($data);
