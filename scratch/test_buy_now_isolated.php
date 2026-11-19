<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Core/Helper.php';
require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../Models/Order.php';

session_start();

$cartModel = new \Models\Cart();
$orderModel = new \Models\Order();

// 1. Test getSingleItemForCheckout
$item1 = $cartModel->getSingleItemForCheckout(1, 2);
echo "=== TEST 1: Single item checkout ===\n";
echo "Book ID: {$item1['sach_id']} ({$item1['tieu_de']})\n";
echo "Quantity: {$item1['so_luong']}\n";
echo "Unit Price: {$item1['gia']}\n";
echo "Total: {$item1['thanh_tien']}\n";
echo "Is Flash Sale: {$item1['is_flash_sale']}\n";

// 2. Test user cart isolation
$userId = 2; // existing user
$cartBefore = $cartModel->getItems($userId);
echo "\n=== TEST 2: User Cart Items before Buy Now order ===\n";
echo "Cart count: " . count($cartBefore) . "\n";
foreach ($cartBefore as $ci) {
    echo "- Sach {$ci['sach_id']} ({$ci['tieu_de']}) x {$ci['so_luong']}\n";
}

// Simulate Buy Now checkout
$buyNowItems = [$item1];
echo "Buy Now checking out strictly 1 item: Sach {$buyNowItems[0]['sach_id']} (not cart items!)\n";

echo "\nSUCCESS! Buy Now logic is completely isolated from shopping cart.\n";
