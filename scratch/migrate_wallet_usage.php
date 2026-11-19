<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();

// Check if column so_lan_da_dung exists in voucher_nguoi_dung
$cols = $db->query("SHOW COLUMNS FROM voucher_nguoi_dung LIKE 'so_lan_da_dung'")->fetchAll();
if (empty($cols)) {
    echo "Adding so_lan_da_dung to voucher_nguoi_dung...\n";
    $db->exec("ALTER TABLE `voucher_nguoi_dung` ADD COLUMN `so_lan_da_dung` INT NOT NULL DEFAULT 0 AFTER `ngay_luu`");
    $db->exec("UPDATE `voucher_nguoi_dung` SET `so_lan_da_dung` = 1 WHERE `trang_thai` = 'da_dung'");
    echo "Column added successfully!\n";
} else {
    echo "Column so_lan_da_dung already exists.\n";
}
