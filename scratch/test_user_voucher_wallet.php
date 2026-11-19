<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../Models/Coupon.php';
require_once __DIR__ . '/../Models/Order.php';

use Models\Coupon;
use Models\Order;

function assertTest($description, $condition) {
    if ($condition) {
        echo "✅ PASS: $description\n";
    } else {
        echo "❌ FAIL: $description\n";
        exit(1);
    }
}

$couponModel = new Coupon();
$orderModel = new Order();

echo "=== KIỂM THỬ LOGIC KHO VOUCHER RIÊNG CHO MỖI TÀI KHOẢN ===\n\n";

// User 1 và User 2
$user1Id = 1;
$user2Id = 2;

// 1. Khởi tạo kho voucher cho cả 2 user
$couponModel->initUserVoucherWallet($user1Id);
$couponModel->initUserVoucherWallet($user2Id);

$wallet1 = $couponModel->getUserWalletCoupons($user1Id);
$wallet2 = $couponModel->getUserWalletCoupons($user2Id);

assertTest("User 1 có kho voucher riêng", count($wallet1) > 0);
assertTest("User 2 có kho voucher riêng", count($wallet2) > 0);

// Reset WELCOME2026 cho cả 2 user về chua_dung để test sạch sẽ
$coupon = $couponModel->findByCode('WELCOME2026');
assertTest("Tìm thấy mã WELCOME2026", $coupon !== null);
$couponId = (int)$coupon['id'];
$pdo = Core\Database::getInstance()->getConnection();
$stmt = $pdo->prepare("UPDATE `voucher_nguoi_dung` SET `trang_thai` = 'chua_dung' WHERE `ma_giam_gia_id` = ?");
$stmt->execute([$couponId]);

// 2. Kiểm tra tính hợp lệ trước khi dùng
$subtotal = 200000;
$valUser1 = $couponModel->validateCoupon('WELCOME2026', $subtotal, $user1Id);
assertTest("User 1 áp dụng WELCOME2026 hợp lệ lần đầu", $valUser1['valid'] === true);

$valUser2 = $couponModel->validateCoupon('WELCOME2026', $subtotal, $user2Id);
assertTest("User 2 áp dụng WELCOME2026 hợp lệ lần đầu", $valUser2['valid'] === true);

// 3. User 1 sử dụng voucher trong đơn hàng
$orderData = [
    'nguoi_dung_id'   => $user1Id,
    'nguoi_nhan'      => 'Độc giả 1 (0901234567)',
    'dia_chi_giao'    => 'Hà Nội',
    'tong_tien_hang'  => $subtotal,
    'tien_giam'       => (float)$valUser1['discount'],
    'phi_van_chuyen'  => 0,
    'tong_thanh_toan' => $subtotal - (float)$valUser1['discount'],
    'phuong_thuc'     => 'COD',
    'ghi_chu_khach'   => 'Test user voucher wallet'
];

$cartItems = [
    [
        'sach_id'   => 1,
        'so_luong'  => 1,
        'gia'       => 200000
    ]
];

$createResult = $orderModel->createOrder($orderData, $cartItems, $valUser1['coupon']);
if (!$createResult['success']) {
    echo "LỖI CREATE ORDER: " . ($createResult['message'] ?? 'Unknown') . "\n";
}
assertTest("Tạo đơn hàng User 1 thành công", $createResult['success'] === true);
$createdOrderId = (int)$createResult['order_id'];

// 4. Kiểm tra trạng thái voucher của User 1
$user1Voucher = $couponModel->getUserWalletCoupons($user1Id);
$user1Used = false;
foreach ($user1Voucher as $v) {
    if ($v['ma'] === 'WELCOME2026' && $v['tinh_trang'] === 'da_dung') {
        $user1Used = true;
        break;
    }
}
assertTest("Kho voucher của User 1: WELCOME2026 đã đổi sang 'da_dung'", $user1Used);

// 5. User 1 cố gắng áp dụng lại WELCOME2026 -> PHẢI BỊ TỪ CHỐI
$valUser1Again = $couponModel->validateCoupon('WELCOME2026', $subtotal, $user1Id);
assertTest("User 1 không thể áp dụng lại WELCOME2026", $valUser1Again['valid'] === false);
assertTest("Thông báo lỗi chỉ rõ mỗi tài khoản chỉ được dùng 1 lần", strpos($valUser1Again['message'], 'Mỗi tài khoản chỉ được sử dụng mã này 1 lần') !== false);

// 6. User 2 áp dụng WELCOME2026 -> PHẢI THÀNH CÔNG (User 2 có kho riêng độc lập)
$valUser2StillValid = $couponModel->validateCoupon('WELCOME2026', $subtotal, $user2Id);
assertTest("User 2 vẫn có thể sử dụng WELCOME2026 bình thường", $valUser2StillValid['valid'] === true);

// 7. User 1 hủy đơn hàng -> Voucher trong kho User 1 PHẢI ĐƯỢC HOÀN LẠI
$cancelResult = $orderModel->cancelOrder($createdOrderId, $user1Id, "Đổi ý muốn mua thêm");
assertTest("Hủy đơn hàng thành công", $cancelResult['success'] === true);

$user1VoucherAfterCancel = $couponModel->getUserWalletCoupons($user1Id);
$user1Restored = false;
foreach ($user1VoucherAfterCancel as $v) {
    if ($v['ma'] === 'WELCOME2026' && $v['tinh_trang'] === 'kha_dung') {
        $user1Restored = true;
        break;
    }
}
assertTest("Sau khi hủy đơn: WELCOME2026 trong kho User 1 đã được phục hồi thành 'kha_dung'", $user1Restored);

$valUser1Restored = $couponModel->validateCoupon('WELCOME2026', $subtotal, $user1Id);
assertTest("User 1 có thể sử dụng lại WELCOME2026 sau khi hủy đơn", $valUser1Restored['valid'] === true);

echo "\n🎉 TẤT CẢ TEST ĐÃ HOÀN TẤT THÀNH CÔNG 100%!\n";
