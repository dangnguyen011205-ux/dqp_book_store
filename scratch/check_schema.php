<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$pdo = \Core\Database::getInstance()->getConnection();
echo "--- SACH ---\n";
foreach ($pdo->query("DESCRIBE sach")->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo "{$col['Field']} - {$col['Type']} - Null:{$col['Null']} - Key:{$col['Key']} - Default:{$col['Default']}\n";
}

echo "\n--- PHIEU_KHO ---\n";
foreach ($pdo->query("DESCRIBE phieu_kho")->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo "{$col['Field']} - {$col['Type']} - Null:{$col['Null']} - Key:{$col['Key']} - Default:{$col['Default']}\n";
}

echo "\n--- SACH_TAC_GIA ---\n";
foreach ($pdo->query("DESCRIBE sach_tac_gia")->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo "{$col['Field']} - {$col['Type']} - Null:{$col['Null']} - Key:{$col['Key']} - Default:{$col['Default']}\n";
}

echo "\n--- COUNTS ---\n";
echo "Total books: " . $pdo->query("SELECT COUNT(*) FROM sach")->fetchColumn() . "\n";
echo "Total slips: " . $pdo->query("SELECT COUNT(*) FROM phieu_kho")->fetchColumn() . "\n";
$slips = $pdo->query("SELECT pk.*, s.tieu_de, u.ho_ten FROM phieu_kho pk LEFT JOIN sach s ON pk.sach_id = s.id LEFT JOIN nguoi_dung u ON pk.nguoi_dung_id = u.id ORDER BY pk.id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
print_r($slips);
