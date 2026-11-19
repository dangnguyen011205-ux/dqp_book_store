<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Models/Coupon.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../Models/Advertisement.php';

use Models\Coupon;
use Models\Order;
use Models\Advertisement;

function assertCondition($name, $cond) {
    if ($cond) {
        echo "✅ PASS: $name\n";
    } else {
        echo "❌ FAIL: $name\n";
        exit(1);
    }
}

echo "=== TEST VOUCHER LOGIC (SO_LUOT_TOI_DA = SO LUOT DUNG / KHACH HANG KHI LUU) ===\n\n";

$db = Core\Database::getInstance()->getConnection();
$couponModel = new Coupon();
$orderModel = new Order();

// 1. Tạo voucher TEST1TIME có so_luot_toi_da = 1 (1 lượt / khách)
$db->exec("DELETE FROM `voucher_nguoi_dung` WHERE `ma_giam_gia_id` IN (SELECT id FROM ma_giam_gia WHERE ma = 'TEST1TIME')");
$db->exec("DELETE FROM `ma_giam_gia` WHERE `ma` = 'TEST1TIME'");

$couponId = $couponModel->create([
    'ma'            => 'TEST1TIME',
    'tieu_de'       => 'Giảm 20.000đ mỗi khách dùng 1 lần',
    'loai_voucher'  => 'giam_gia',
    'loai'          => 'tien_mat',
    'gia_tri'       => 20000,
    'don_toi_thieu' => 50000,
    'bat_dau'       => date('Y-m-d H:i:s', strtotime('-1 day')),
    'ket_thuc'      => date('Y-m-d H:i:s', strtotime('+30 days')),
    'so_luot_toi_da'=> 1 // Mỗi khách dùng 1 lần
]);

assertCondition("Tạo voucher TEST1TIME thành công", $couponId > 0);

// Lấy 2 user ID có thật trong hệ thống
$users = $db->query("SELECT id FROM nguoi_dung ORDER BY id ASC LIMIT 2")->fetchAll(PDO::FETCH_COLUMN);
$userA = (int)$users[0];
$userB = (int)$users[1];

echo "User A: ID $userA | User B: ID $userB\n";

// 2. User A lưu voucher vào ví
$saveA = $couponModel->claimVoucherByCode($userA, 'TEST1TIME');
assertCondition("User A lưu mã TEST1TIME thành công", $saveA['success'] === true);

// 3. User A validate lần 1 -> Hợp lệ
$valA1 = $couponModel->validateCoupon('TEST1TIME', 100000, $userA, 22000);
assertCondition("User A validate lần 1 hợp lệ", $valA1['valid'] === true);

// Giả lập User A đặt hàng thành công
$orderDataA = [
    'nguoi_dung_id'  => $userA,
    'nguoi_nhan'     => 'User A',
    'dia_chi_giao'   => 'Dia chi A',
    'tong_tien_hang' => 100000,
    'tien_giam'      => 20000,
    'tien_giam_ship' => 0,
    'phi_van_chuyen' => 22000,
    'tong_thanh_toan'=> 102000,
    'phuong_thuc'    => 'COD',
    'ghi_chu_khach'  => 'Test order A'
];
$sach = $db->query("SELECT id, gia FROM sach LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$items = [['sach_id' => $sach['id'], 'so_luong' => 1, 'gia' => 100000]];

$resA = $orderModel->createOrder($orderDataA, $items, $valA1['coupon'], false, null);
if (!$resA['success']) {
    echo "createOrder error: " . ($resA['message'] ?? 'Unknown error') . "\n";
}
assertCondition("User A đặt đơn thành công với mã TEST1TIME", $resA['success'] === true);
$orderIdA = $resA['order_id'];

// 4. Kiểm tra ví User A: voucher phải chuyển sang da_dung
$walletA = $db->query("SELECT * FROM voucher_nguoi_dung WHERE nguoi_dung_id = $userA AND ma_giam_gia_id = $couponId")->fetch(PDO::FETCH_ASSOC);
assertCondition("Ví User A ghi nhận so_lan_da_dung = 1", (int)$walletA['so_lan_da_dung'] === 1);
assertCondition("Ví User A chuyển trang_thai = da_dung", $walletA['trang_thai'] === 'da_dung');

// 5. User A thử validate lần 2 -> Bị chặn vì đã dùng hết 1 lượt
$valA2 = $couponModel->validateCoupon('TEST1TIME', 100000, $userA, 22000);
assertCondition("User A bị chặn khi dùng lần 2", $valA2['valid'] === false);

// 6. User B lưu và sử dụng mã TEST1TIME -> PHẢI THÀNH CÔNG (Không bị ảnh hưởng bởi User A!)
$saveB = $couponModel->claimVoucherByCode($userB, 'TEST1TIME');
assertCondition("User B lưu mã TEST1TIME thành công", $saveB['success'] === true);

$valB1 = $couponModel->validateCoupon('TEST1TIME', 100000, $userB, 22000);
assertCondition("User B validate lần 1 hợp lệ (Không bị User A chặn)", $valB1['valid'] === true);

$orderDataB = [
    'nguoi_dung_id'  => $userB,
    'nguoi_nhan'     => 'User B',
    'dia_chi_giao'   => 'Dia chi B',
    'tong_tien_hang' => 100000,
    'tien_giam'      => 20000,
    'tien_giam_ship' => 0,
    'phi_van_chuyen' => 22000,
    'tong_thanh_toan'=> 102000,
    'phuong_thuc'    => 'COD',
    'ghi_chu_khach'  => 'Test order B'
];
$resB = $orderModel->createOrder($orderDataB, $items, $valB1['coupon'], false, null);
assertCondition("User B đặt đơn thành công với mã TEST1TIME", $resB['success'] === true);

// 7. Kiểm tra tổng lượt dùng toàn sàn của mã TEST1TIME
$cTest = $db->query("SELECT * FROM ma_giam_gia WHERE id = $couponId")->fetch(PDO::FETCH_ASSOC);
assertCondition("Tổng đơn toàn sàn của TEST1TIME là 2 đơn", (int)$cTest['da_dung'] === 2);

// 8. Kiểm tra Admin getAll(): voucher vẫn là 'con_han' (Hoạt động) chứ không bị 'het_luot'
$adminCoupons = $couponModel->getAll('TEST1TIME');
assertCondition("Admin thấy voucher TEST1TIME vẫn có trạng thái 'con_han'", $adminCoupons[0]['trang_thai_hieu_luc'] === 'con_han');
assertCondition("Admin thấy số lượt tối đa là 1 (lượt/khách)", (int)$adminCoupons[0]['so_luot_toi_da'] === 1);
assertCondition("Admin thấy số đơn toàn sàn là 2", (int)$adminCoupons[0]['da_dung'] === 2);

// 9. Kiểm tra hủy đơn của User A: Voucher được hoàn lại vào ví của User A (so_lan_da_dung giảm về 0, trang_thai về chua_dung)
$cancelRes = $orderModel->cancelOrder($orderIdA, $userA, 'Tôi muốn đổi ý');
assertCondition("User A hủy đơn #$orderIdA thành công", $cancelRes['success'] === true);

$walletAAfterCancel = $db->query("SELECT * FROM voucher_nguoi_dung WHERE nguoi_dung_id = $userA AND ma_giam_gia_id = $couponId")->fetch(PDO::FETCH_ASSOC);
assertCondition("Sau khi hủy đơn, so_lan_da_dung của User A giảm về 0", (int)$walletAAfterCancel['so_lan_da_dung'] === 0);
assertCondition("Sau khi hủy đơn, ví User A trở lại trang_thai = chua_dung", $walletAAfterCancel['trang_thai'] === 'chua_dung');

// User A lại có thể validate và dùng lại mã!
$valA3 = $couponModel->validateCoupon('TEST1TIME', 100000, $userA, 22000);
assertCondition("User A có thể dùng lại mã sau khi hủy đơn", $valA3['valid'] === true);

// 10. Dọn dẹp bản ghi test
$db->exec("DELETE FROM chi_tiet_don_hang WHERE don_hang_id IN ({$resA['order_id']}, {$resB['order_id']})");
$db->exec("DELETE FROM thanh_toan WHERE don_hang_id IN ({$resA['order_id']}, {$resB['order_id']})");
$db->exec("DELETE FROM don_hang WHERE id IN ({$resA['order_id']}, {$resB['order_id']})");
$db->exec("DELETE FROM voucher_nguoi_dung WHERE ma_giam_gia_id = $couponId");
$db->exec("DELETE FROM ma_giam_gia WHERE id = $couponId");

echo "\n🎉 TẤT CẢ CÁC KIỂM THỬ VOUCHER (ĐẶT ĐƠN, LƯỢT DÙNG/KHÁCH, ĐỒNG BỘ TOÀN SÀN, HỦY ĐƠN HOÀN LƯỢT) ĐỀU ĐẠT 100% PASS!\n";
