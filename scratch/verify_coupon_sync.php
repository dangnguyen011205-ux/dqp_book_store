<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Models/Coupon.php';

$couponModel = new Models\Coupon();

// Sync all coupons
$couponModel->syncUsage();

echo "=== VERIFY SYNCED COUPONS ===\n";
$coupons = $couponModel->getAll();
foreach ($coupons as $c) {
    echo sprintf("Coupon [%s]: da_dung=%d, max=%d, so_don_hang=%d, so_nguoi_luu=%d, tong_giam=%s, status=%s\n",
        $c['ma'],
        $c['da_dung'],
        $c['so_luot_toi_da'],
        $c['so_don_hang'],
        $c['so_nguoi_luu'],
        number_format($c['tong_tien_giam_thuc_te']),
        $c['trang_thai_hieu_luc']
    );
}

// Verify statistics
$stats = $couponModel->getStatistics();
echo "\n=== STATISTICS ===\n";
print_r($stats);
