<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';

class MigrateAds extends Core\Model {
    public function migrate() {
        $cols = $this->fetchAll("SHOW COLUMNS FROM `quang_cao` LIKE 'so_luot_luu_toi_da'");
        if (empty($cols)) {
            $this->execute("ALTER TABLE `quang_cao` ADD COLUMN `so_luot_luu_toi_da` INT NOT NULL DEFAULT 0 AFTER `ma_giam_gia_id`");
            echo "Added so_luot_luu_toi_da to quang_cao successfully.\n";
        } else {
            echo "Column so_luot_luu_toi_da already exists.\n";
        }
        return $this->fetchAll("DESCRIBE `quang_cao`");
    }
}

$m = new MigrateAds();
print_r($m->migrate());
