<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();
$db->exec("UPDATE danh_gia SET trang_thai = 'hien_thi' WHERE trang_thai = 'cho_duyet'");
$res = $db->query('SELECT trang_thai, count(*) as count FROM danh_gia GROUP BY trang_thai')->fetchAll(PDO::FETCH_ASSOC);
print_r($res);
