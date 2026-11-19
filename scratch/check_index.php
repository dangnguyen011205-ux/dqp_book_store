<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';

class CheckIndex extends Core\Model {
    public function showIndexes() {
        return $this->fetchAll("SHOW INDEX FROM `voucher_nguoi_dung`");
    }
}

$c = new CheckIndex();
print_r($c->showIndexes());
