<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();
$b10 = $db->query('SELECT * FROM sach WHERE id = 10')->fetch(PDO::FETCH_ASSOC);
print_r($b10);
