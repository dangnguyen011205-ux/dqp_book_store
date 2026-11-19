<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';

class Inspector extends Core\Model {
    public function getRows() {
        return $this->fetchAll("SELECT vnd.*, u.email, mg.ma FROM `voucher_nguoi_dung` vnd JOIN `nguoi_dung` u ON vnd.nguoi_dung_id = u.id JOIN `ma_giam_gia` mg ON vnd.ma_giam_gia_id = mg.id");
    }
}

$ins = new Inspector();
print_r($ins->getRows());
