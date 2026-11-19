<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Models/Advertisement.php';
require_once __DIR__ . '/../Models/Coupon.php';

use Models\Advertisement;
use Models\Coupon;

function assertTest($description, $condition) {
    if ($condition) {
        echo "✅ PASS: $description\n";
    } else {
        echo "❌ FAIL: $description\n";
        exit(1);
    }
}

echo "=== KIỂM THỬ GIỚI HẠN SỐ LƯỢNG LƯU VOUCHER TRONG QUẢNG CÁO ===\n\n";

$adModel = new Advertisement();
$couponModel = new Coupon();
$pdo = Core\Database::getInstance()->getConnection();

// Lấy coupon FREESHIP (id = 3) hoặc WELCOME2026 (id = 1)
$coupon = $couponModel->findByCode('FREESHIP');
assertTest("Tìm thấy voucher FREESHIP", $coupon !== null);
$couponId = (int)$coupon['id'];

// Cập nhật quảng cáo test có liên kết voucher này, đặt so_luot_luu_toi_da = 2
$pdo->prepare("UPDATE `quang_cao` SET `ma_giam_gia_id` = ?, `so_luot_luu_toi_da` = 2, `trang_thai` = 1 WHERE `id` = 1")->execute([$couponId]);

// Xóa các bản ghi lưu voucher này để test từ đầu
$pdo->prepare("DELETE FROM `voucher_nguoi_dung` WHERE `ma_giam_gia_id` = ?")->execute([$couponId]);

// 1. Kiểm tra getActiveAds trả về đúng so_luot_luu_toi_da và so_luot_da_luu = 0
$ads = $adModel->getActiveAds();
$ad1 = null;
foreach ($ads as $a) {
    if ((int)$a['id'] === 1) {
        $ad1 = $a;
        break;
    }
}
assertTest("Quảng cáo 1 đang hoạt động", $ad1 !== null);
assertTest("so_luot_luu_toi_da là 2", (int)$ad1['so_luot_luu_toi_da'] === 2);
assertTest("so_luot_da_luu ban đầu là 0", (int)$ad1['so_luot_da_luu'] === 0);

// 2. User 1 lưu voucher -> Thành công (lượt 1/2)
$res1 = $adModel->saveUserVoucher(1, $couponId);
assertTest("User 1 lưu voucher thành công", $res1['success'] === true);

// 3. User 2 lưu voucher -> Thành công (lượt 2/2)
$res2 = $adModel->saveUserVoucher(2, $couponId);
assertTest("User 2 lưu voucher thành công", $res2['success'] === true);

// Kiểm tra lại getActiveAds: so_luot_da_luu phải là 2
$ads = $adModel->getActiveAds();
foreach ($ads as $a) {
    if ((int)$a['id'] === 1) {
        $ad1 = $a;
        break;
    }
}
assertTest("so_luot_da_luu hiện tại là 2/2", (int)$ad1['so_luot_da_luu'] === 2);

// 4. User 3 cố gắng lưu voucher -> PHẢI BỊ TỪ CHỐI DO ĐÃ HẾT LƯỢT
$res3 = $adModel->saveUserVoucher(3, $couponId);
assertTest("User 3 bị chặn do hết lượt lưu", $res3['success'] === false);
assertTest("Kết quả trả về cờ is_full = true", ($res3['is_full'] ?? false) === true);
assertTest("Thông báo lỗi chỉ rõ đã hết lượt", strpos($res3['message'], 'đã được lưu hết') !== false);

// 5. Kiểm tra tính năng hiển thị số lượt còn lại của voucher
$walletCoupons = $couponModel->getUserWalletCoupons(1);
$foundFreeship = false;
foreach ($walletCoupons as $wc) {
    if ($wc['ma'] === 'FREESHIP') {
        $foundFreeship = true;
        // so_luot_toi_da và da_dung có tồn tại để tính số lượt còn lại
        $remain = ($wc['so_luot_toi_da'] > 0) ? max(0, (int)$wc['so_luot_toi_da'] - (int)$wc['da_dung']) : null;
        assertTest("Voucher FREESHIP có tính được số lượt sử dụng còn lại", $remain !== null && $remain >= 0);
        break;
    }
}
assertTest("Tìm thấy FREESHIP trong ví của User 1", $foundFreeship);

echo "\n🎉 TẤT CẢ TEST ĐÃ HOÀN TẤT THÀNH CÔNG 100%!\n";
