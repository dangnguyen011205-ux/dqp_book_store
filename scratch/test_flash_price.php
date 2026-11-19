<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Models/Cart.php';

session_start();
// Test guest cart with sach_id = 1 (flash sale in sach table: gia_goc=85000, gia_flash=45000)
// and sach_id = 11 (flash sale in flash_sale table: gia_goc=..., gia_sale=35000)
$_SESSION['cart'] = [
    1 => ['so_luong' => 2],
    11 => ['so_luong' => 1]
];

$cart = new \Models\Cart();
$items = $cart->getItems(null);
echo "Guest cart items:\n";
foreach ($items as $it) {
    echo "- Sach {$it['sach_id']} ({$it['tieu_de']}): gia={$it['gia']}, gia_goc={$it['gia_goc']}, is_flash={$it['is_flash_sale']}, thanh_tien={$it['thanh_tien']}\n";
}
