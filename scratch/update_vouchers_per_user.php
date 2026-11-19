<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();
$db->exec("UPDATE `ma_giam_gia` SET `so_luot_toi_da` = 1 WHERE `so_luot_toi_da` > 1 OR `so_luot_toi_da` = 0");
echo "Updated so_luot_toi_da = 1 for all vouchers\n";
