<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Models/Coupon.php';

$couponModel = new Models\Coupon();
$result = $couponModel->validateCoupon('LANDAU', 150000, 19, 22000);
echo "Validate LANDAU when da_dung=2, max=1:\n";
print_r($result);
