<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();

echo "=== ORDERS USING LANDAU (ID 13) ===\n";
$stmt = $db->query("SELECT * FROM don_hang WHERE ma_giam_gia_id = 13 OR ma_freeship_id = 13");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n=== VOUCHER DETAILS FOR LANDAU ===\n";
$stmt = $db->query("SELECT * FROM ma_giam_gia WHERE id = 13");
print_r($stmt->fetch(PDO::FETCH_ASSOC));
