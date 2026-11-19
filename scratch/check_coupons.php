<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$pdo = \Core\Database::getInstance()->getConnection();
$coupons = $pdo->query('SELECT * FROM ma_giam_gia')->fetchAll();
echo "All coupons in ma_giam_gia:\n";
foreach ($coupons as $c) {
    echo "- ID: {$c['id']} | Ma: {$c['ma']} | Ten: {$c['ten']} | Loai: {$c['loai']} | Gia tri: {$c['gia_tri']} | Min: {$c['don_toi_thieu']} | Han: {$c['ket_thuc']} | Da dung: {$c['da_dung']}/{$c['so_luot_toi_da']}\n";
}
