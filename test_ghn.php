<?php

/**
 * Test GHN Service Integration
 * 
 * File này dùng để test GHN API trước khi tích hợp vào production.
 * Chạy: php test_ghn.php
 * 
 * @date 2026-01-13
 */

// Bootstrap
require_once __DIR__ . '/vendor/autoload.php';

// Load .env
$dotenv = __DIR__ . '/.env';
if (file_exists($dotenv)) {
    $lines = file($dotenv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0)
            continue;
        if (strpos($line, '=') === false)
            continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

use App\Services\GHNService;

echo "=== GHN Service Test ===\n\n";

try {
    $ghn = new GHNService();
    echo "✅ GHN Service initialized successfully\n";
    echo "   Environment: " . $ghn->getEnvironment() . "\n";
    echo "   Shop ID: " . $ghn->getShopId() . "\n\n";

    // Test 1: Get Provinces
    echo "1. Testing getProvinces()...\n";
    $provinces = $ghn->getProvinces();
    echo "   ✅ Got " . count($provinces) . " provinces\n";
    if (!empty($provinces)) {
        echo "   Sample: " . $provinces[0]['ProvinceName'] . " (ID: " . $provinces[0]['ProvinceID'] . ")\n";
    }
    echo "\n";

    // Test 2: Get Districts (HCM = 202)
    echo "2. Testing getDistricts(202) - Ho Chi Minh...\n";
    $districts = $ghn->getDistricts(202);
    echo "   ✅ Got " . count($districts) . " districts\n";
    if (!empty($districts)) {
        echo "   Sample: " . $districts[0]['DistrictName'] . " (ID: " . $districts[0]['DistrictID'] . ")\n";
    }
    echo "\n";

    // Test 3: Get Wards (Quan 10 = 1444)
    echo "3. Testing getWards(1444) - Quan 10...\n";
    $wards = $ghn->getWards(1444);
    echo "   ✅ Got " . count($wards) . " wards\n";
    if (!empty($wards)) {
        echo "   Sample: " . $wards[0]['WardName'] . " (Code: " . $wards[0]['WardCode'] . ")\n";
    }
    echo "\n";

    // Test 4: Calculate Fee
    echo "4. Testing calculateFee()...\n";
    $fee = $ghn->calculateFee([
        'to_district_id' => 1444,
        'to_ward_code' => '20308',
        'weight' => 500,
        'service_type_id' => 2,
    ]);
    echo "   ✅ Total fee: " . number_format($fee['total'] ?? 0) . " VND\n";
    echo "   Service fee: " . number_format($fee['service_fee'] ?? 0) . " VND\n";
    echo "\n";

    // Test 5: Create Test Order (Sandbox only!)
    echo "5. Testing createOrder() [SANDBOX]...\n";
    $orderResult = $ghn->createOrder([
        'to_name' => 'Nguyen Van Test',
        'to_phone' => '0987654321',
        'to_address' => '72 Thanh Thai, Phuong 14, Quan 10, TPHCM',
        'to_ward_code' => '20308',
        'to_district_id' => 1444,
        'weight' => 500,
        'cod_amount' => 100000,
        'content' => 'Test order from Zoldify',
        'client_order_code' => 'ZOLD-TEST-' . time(),
    ]);
    echo "   ✅ Order created!\n";
    echo "   Order Code: " . $orderResult['order_code'] . "\n";
    echo "   Total Fee: " . number_format($orderResult['total_fee']) . " VND\n";
    echo "   Expected Delivery: " . $orderResult['expected_delivery_time'] . "\n";
    echo "\n";

    // Test 6: Get Order Info
    if (!empty($orderResult['order_code'])) {
        echo "6. Testing getOrderInfo('" . $orderResult['order_code'] . "')...\n";
        $orderInfo = $ghn->getOrderInfo($orderResult['order_code']);
        echo "   ✅ Order Status: " . ($orderInfo['status'] ?? 'unknown') . "\n";
        echo "   COD Amount: " . number_format($orderInfo['cod_amount'] ?? 0) . " VND\n";
        echo "\n";
    }

    echo "=== All tests passed! ===\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
    echo "Make sure you have configured GHN_TOKEN and GHN_SHOP_ID in your .env file.\n";
}
