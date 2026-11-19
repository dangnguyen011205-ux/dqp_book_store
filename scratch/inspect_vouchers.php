<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';

class Inspector extends Core\Model {
    public function desc($table) {
        return $this->fetchAll("DESCRIBE `$table`");
    }
    public function rows($table) {
        return $this->fetchAll("SELECT * FROM `$table` LIMIT 10");
    }
}

$ins = new Inspector();
echo "--- DESCRIBE voucher_nguoi_dung ---\n";
print_r($ins->desc('voucher_nguoi_dung'));
echo "--- SAMPLE voucher_nguoi_dung ---\n";
print_r($ins->rows('voucher_nguoi_dung'));
echo "--- SAMPLE ma_giam_gia ---\n";
print_r($ins->rows('ma_giam_gia'));
