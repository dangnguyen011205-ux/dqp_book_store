<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$pdo = \Core\Database::getInstance()->getConnection();
$sachFs = $pdo->query('SELECT id, tieu_de, gia, is_flash_sale, gia_flash_sale, flash_sale_end FROM sach WHERE is_flash_sale = 1')->fetchAll();
echo "Sach with is_flash_sale = 1:\n";
print_r($sachFs);

$cartItems = $pdo->query('SELECT * FROM chi_tiet_gio_hang')->fetchAll();
echo "Cart items count: " . count($cartItems) . "\n";
print_r($cartItems);

$orders = $pdo->query('SELECT * FROM chi_tiet_don_hang ORDER BY id DESC LIMIT 5')->fetchAll();
echo "Recent order details:\n";
print_r($orders);
