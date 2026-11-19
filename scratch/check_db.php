<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$pdo = \Core\Database::getInstance()->getConnection();
$cols = $pdo->query('DESCRIBE sach')->fetchAll();
echo "Sach columns:\n";
foreach ($cols as $c) {
    if (strpos($c['Field'], 'flash') !== false || strpos($c['Field'], 'sale') !== false || strpos($c['Field'], 'gia') !== false) {
        echo " - " . $c['Field'] . " (" . $c['Type'] . ")\n";
    }
}

echo "\nFlash sale table rows:\n";
$fsRows = $pdo->query('SELECT * FROM flash_sale')->fetchAll();
echo "Count: " . count($fsRows) . "\n";
foreach ($fsRows as $r) {
    print_r($r);
}

echo "\nSach with is_flash_sale = 1:\n";
$sachFs = $pdo->query('SELECT id, tieu_de, gia, gia_khuyen_mai, is_flash_sale, gia_flash_sale, flash_sale_end FROM sach WHERE is_flash_sale = 1')->fetchAll();
print_r($sachFs);
