<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$pdo = \Core\Database::getInstance()->getConnection();
$cols = $pdo->query('DESCRIBE ma_giam_gia')->fetchAll();
print_r($cols);
