<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();
$stmt = $db->query("SHOW INDEX FROM voucher_nguoi_dung");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
