<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';

class FixCoupons extends Core\Model {
    public function fix() {
        $this->execute("UPDATE `ma_giam_gia` SET `so_luot_toi_da` = 100 WHERE `ma` = 'WELCOME2026'");
        $this->execute("UPDATE `ma_giam_gia` SET `so_luot_toi_da` = 50 WHERE `ma` = 'GIAM30K'");
        $this->execute("UPDATE `ma_giam_gia` SET `so_luot_toi_da` = 100 WHERE `ma` = 'LANDAU'");
        return $this->fetchAll("SELECT id, ma, so_luot_toi_da, da_dung FROM `ma_giam_gia`");
    }
}

$f = new FixCoupons();
print_r($f->fix());
