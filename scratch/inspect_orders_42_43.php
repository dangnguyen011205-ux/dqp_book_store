<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();
$stmt = $db->query("SELECT id, nguoi_dung_id, ma_giam_gia_id, ma_freeship_id, tong_thanh_toan, trang_thai, ngay_dat FROM don_hang WHERE id IN (42, 43)");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
