<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();

$c = $db->query("SELECT id, ten FROM the_loai WHERE ten LIKE '%Test%'")->fetchAll(PDO::FETCH_ASSOC);
$a = $db->query("SELECT id, ten FROM tac_gia WHERE ten LIKE '%Test%'")->fetchAll(PDO::FETCH_ASSOC);
$p = $db->query("SELECT id, ten FROM nha_xuat_ban WHERE ten LIKE '%Test%'")->fetchAll(PDO::FETCH_ASSOC);

echo "Categories with Test:\n";
print_r($c);
echo "Authors with Test:\n";
print_r($a);
echo "Publishers with Test:\n";
print_r($p);

// Clean them up
$db->exec("DELETE FROM the_loai WHERE ten LIKE '%Test%'");
$db->exec("DELETE FROM tac_gia WHERE ten LIKE '%Test%'");
$db->exec("DELETE FROM nha_xuat_ban WHERE ten LIKE '%Test%'");
echo "Cleaned up successfully.\n";
