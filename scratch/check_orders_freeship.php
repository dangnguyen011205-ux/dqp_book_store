<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();
$stmt = $db->query('SELECT id, ma_giam_gia_id, ma_freeship_id, tien_giam, tien_giam_ship, trang_thai FROM don_hang WHERE ma_giam_gia_id = 3 OR ma_freeship_id = 3');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
