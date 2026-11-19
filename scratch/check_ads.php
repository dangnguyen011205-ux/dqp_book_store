<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';

class CheckAds extends Core\Model {
    public function desc() {
        return $this->fetchAll("DESCRIBE `quang_cao`");
    }
    public function rows() {
        return $this->fetchAll("SELECT * FROM `quang_cao`");
    }
}

$c = new CheckAds();
print_r($c->desc());
print_r($c->rows());
