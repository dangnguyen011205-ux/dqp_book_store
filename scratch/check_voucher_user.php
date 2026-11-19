<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$pdo = \Core\Database::getInstance()->getConnection();
$cols = $pdo->query('DESCRIBE voucher_nguoi_dung')->fetchAll();
echo "voucher_nguoi_dung columns:\n";
foreach ($cols as $c) {
    echo "- {$c['Field']} ({$c['Type']}) Null:{$c['Null']} Key:{$c['Key']} Default:{$c['Default']}\n";
}

$rows = $pdo->query('SELECT * FROM voucher_nguoi_dung')->fetchAll();
echo "Total rows in voucher_nguoi_dung: " . count($rows) . "\n";
print_r($rows);

$users = $pdo->query('SELECT id, ho_ten, email, vai_tro FROM nguoi_dung')->fetchAll();
echo "Total users: " . count($users) . "\n";
print_r($users);
