<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/Database.php';
$pdo = Core\Database::getInstance()->getConnection();
$pdo->prepare("UPDATE `ma_giam_gia` SET `so_luot_toi_da` = 100 WHERE `id` = 1")->execute();
$pdo->prepare("UPDATE `ma_giam_gia` SET `so_luot_toi_da` = 50 WHERE `id` = 2")->execute();
$pdo->prepare("UPDATE `ma_giam_gia` SET `so_luot_toi_da` = 50 WHERE `id` = 3")->execute();
echo "Updated coupons max limits.\n";
