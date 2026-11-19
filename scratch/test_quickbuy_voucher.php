<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Core/Helper.php';
require_once __DIR__ . '/../Models/Coupon.php';
require_once __DIR__ . '/../Models/Advertisement.php';

$couponModel = new \Models\Coupon();
$coupons = $couponModel->getActiveCoupons();
echo "Active coupons found: " . count($coupons) . "\n";
if (!empty($coupons)) {
    echo "First coupon: " . $coupons[0]['ma'] . " (" . $coupons[0]['ten'] . ")\n";
    $validation = $couponModel->validateCoupon($coupons[0]['ma'], 500000);
    echo "Validate 500k: " . ($validation['valid'] ? 'VALID discount=' . $validation['discount'] : 'INVALID: ' . $validation['message']) . "\n";
}

$adModel = new \Models\Advertisement();
$userVouchers = $adModel->getUserSavedVouchers(1);
echo "User 1 vouchers found: " . count($userVouchers) . "\n";
echo "SUCCESS!\n";
