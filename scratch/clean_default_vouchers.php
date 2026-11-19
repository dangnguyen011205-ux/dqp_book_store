<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$pdo = Core\Database::getInstance()->getConnection();
// Xóa toàn bộ voucher chua_dung auto-insert để khách hàng có túi voucher trống mặc định
$pdo->prepare("DELETE FROM `voucher_nguoi_dung` WHERE `trang_thai` = 'chua_dung'")->execute();
echo "Đã xóa toàn bộ voucher mặc định trong túi khách hàng.\n";
$stmt = $pdo->query("SELECT * FROM `voucher_nguoi_dung`");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
