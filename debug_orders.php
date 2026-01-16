<?php
require 'vendor/autoload.php';

$orderItemModel = new \App\Models\OrderItem();

// Check what orders exist vs what order_details exist
$db = \App\Core\Database::getInstance();

echo "=== ORDERS ===\n";
$orders = $db->fetchAll('SELECT id FROM orders ORDER BY id DESC LIMIT 10');
print_r(array_column($orders, 'id'));

echo "\n=== ORDER_DETAILS order_ids ===\n";
$details = $db->fetchAll('SELECT DISTINCT order_id FROM order_details ORDER BY order_id DESC LIMIT 10');
print_r(array_column($details, 'order_id'));

// Test with actual order IDs from the list
if (!empty($orders)) {
    $testId = (int)$orders[0]['id'];
    echo "\n=== Testing getByOrderId({$testId}) ===\n";
    $items = $orderItemModel->getByOrderId($testId);
    echo "Found " . count($items) . " items:\n";
    print_r($items);
}
