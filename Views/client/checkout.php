<?php
/**
 * Giao diện Thanh Toán & Đặt Hàng - DQP Book Store
 * Phong cách: Athenaeum Modern
 */
function getCheckoutBookCover(?string $fileName, string $title): string {
    if (!empty($fileName)) {
        $localPath = ROOT_PATH . '/Public/uploads/books/' . $fileName;
        if (file_exists($localPath)) {
            return asset('uploads/books/' . $fileName);
        }
    }
    $coverMap = [
        'tuoi_tho'   => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&q=80&w=600',
        'mat_biec'   => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=600',
        'rung_na_uy' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3777f?auto=format&fit=crop&q=80&w=600',
        'dac_nhan'   => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&q=80&w=600',
        'clean_code' => 'https://images.unsplash.com/photo-1516259762381-22954d7d3ad2?auto=format&fit=crop&q=80&w=600',
        'gia_kim'    => 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&q=80&w=600'
    ];
    $lower = mb_strtolower($title);
    if (strpos($lower, 'tuổi thơ') !== false || strpos($lower, 'vé đi') !== false) return $coverMap['tuoi_tho'];
    if (strpos($lower, 'mắt biếc') !== false) return $coverMap['mat_biec'];
    if (strpos($lower, 'rừng na uy') !== false) return $coverMap['rung_na_uy'];
    if (strpos($lower, 'đắc nhân tâm') !== false) return $coverMap['dac_nhan'];
    if (strpos($lower, 'clean code') !== false) return $coverMap['clean_code'];
    if (strpos($lower, 'nhà giả kim') !== false) return $coverMap['gia_kim'];
    return 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&q=80&w=600';
}
?>

<!-- Tiến Trình Mua Sách (Progress Stepper) -->
<section class="w-full bg-surface-container-low py-6 shadow-sm border-b border-surface-container">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between max-w-3xl mx-auto relative">
            <!-- Đường nối tiến trình -->
            <div class="absolute left-8 right-8 top-1/2 -translate-y-1/2 h-1 bg-surface-container-high z-0"></div>
            <div class="absolute left-8 top-1/2 -translate-y-1/2 h-1 bg-secondary z-0 transition-all duration-500 w-2/3"></div>

            <!-- Bước 1: Giỏ Hàng (Đã xong) -->
            <a href="<?= base_url('gio-hang') ?>" class="flex flex-col items-center relative z-10 group cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-secondary text-on-secondary flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-surface-container-low">
                    <span class="material-symbols-outlined text-[20px]">check</span>
                </div>
                <span class="mt-2 text-xs font-bold text-secondary tracking-wide">1. Giỏ Hàng</span>
            </a>

            <!-- Bước 2: Thông tin giao nhận (Đang chọn) -->
            <div class="flex flex-col items-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm shadow-lg ring-4 ring-secondary-container">
                    <span>2</span>
                </div>
                <span class="mt-2 text-xs font-bold text-primary tracking-wide">2. Vận Chuyển & Địa Chỉ</span>
            </div>

            <!-- Bước 3: Xác nhận đơn -->
            <div class="flex flex-col items-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-surface-container-high text-on-surface-variant flex items-center justify-center font-bold text-sm shadow-sm ring-4 ring-surface-container-low">
                    <span>3</span>
                </div>
                <span class="mt-2 text-xs font-semibold text-on-surface-variant tracking-wide">3. Hoàn Tất Đơn Hàng</span>
            </div>
        </div>
    </div>
</section>

<!-- Form Đặt Hàng Chính -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 w-full">
    <form action="<?= base_url('thanh-toan/dat-hang') ?>" method="POST" id="checkout-form">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- CỘT TRÁI: ĐỊA CHỈ, VẬN CHUYỂN, THANH TOÁN (8 Cột) -->
            <div class="lg:col-span-8 flex flex-col gap-6">

                <!-- 1. ĐỊA CHỈ NHẬN HÀNG -->
                <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-surface-container">
                    <div class="flex items-center justify-between pb-4 border-b border-surface-container">
                        <div class="flex items-center gap-3">
                            <span class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">location_on</span>
                            </span>
                            <div>
                                <h2 class="text-lg font-bold text-primary">1. Địa Chỉ Nhận Hàng</h2>
                                <p class="text-xs text-on-surface-variant">Chọn từ sổ địa chỉ đã lưu hoặc thiết lập điểm nhận mới</p>
                            </div>
                        </div>
                        <a href="<?= base_url('tai-khoan/so-dia-chi') ?>" target="_blank" class="text-xs font-semibold text-secondary hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                            <span>Quản lý sổ địa chỉ</span>
                        </a>
                    </div>

                    <div class="pt-4 space-y-3">
                        <?php if (!empty($addresses)): ?>
                            <!-- Lựa chọn địa chỉ đã có -->
                            <?php foreach ($addresses as $index => $addr): 
                                $isChecked = ($defaultAddress && $defaultAddress['id'] == $addr['id']) || (!$defaultAddress && $index === 0);
                            ?>
                                <label class="checkout-option-card relative flex items-start gap-4 p-4 rounded-xl border transition-all cursor-pointer <?= $isChecked ? 'bg-secondary-container/10 border-secondary ring-1 ring-secondary/30' : 'bg-surface-container-lowest border-surface-container hover:border-secondary' ?>">
                                    <input type="radio" name="address_choice" value="existing" 
                                           <?= $isChecked ? 'checked' : '' ?> 
                                           onchange="handleAddressChange(this)"
                                           class="mt-1 w-4 h-4 text-secondary focus:ring-secondary">
                                    <input type="hidden" name="address_id" value="<?= $addr['id'] ?>" <?= $isChecked ? '' : 'disabled' ?> class="addr-id-input">
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            <span class="font-bold text-sm text-primary"><?= htmlspecialchars($addr['nguoi_nhan']) ?></span>
                                            <span class="text-xs text-on-surface-variant font-medium">(<?= htmlspecialchars($addr['so_dien_thoai']) ?>)</span>
                                            <?php if ((int)$addr['mac_dinh'] === 1): ?>
                                                <span class="px-2 py-0.5 rounded bg-secondary-container text-on-secondary-container text-[11px] font-bold uppercase tracking-wider">
                                                    Mặc định
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-xs text-on-surface leading-relaxed">
                                            <?= htmlspecialchars($addr['dia_chi']) ?>
                                        </p>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Tùy chọn: Nhập địa chỉ mới -->
                        <?php $isNewChecked = empty($addresses); ?>
                        <label class="checkout-option-card relative flex items-start gap-4 p-4 rounded-xl border border-dashed transition-all cursor-pointer <?= $isNewChecked ? 'bg-secondary-container/10 border-secondary ring-1 ring-secondary/30' : 'bg-surface-container-low/40 border-surface-container hover:border-secondary' ?>">
                            <input type="radio" name="address_choice" value="new" 
                                   <?= $isNewChecked ? 'checked' : '' ?> 
                                   onchange="handleAddressChange(this)"
                                   class="mt-1 w-4 h-4 text-secondary focus:ring-secondary">
                            <div class="flex-1">
                                <span class="font-bold text-sm text-primary flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-secondary">add_location_alt</span>
                                    <span>Giao đến một địa chỉ nhận hàng khác</span>
                                </span>
                                <p class="text-xs text-on-surface-variant mt-0.5">Nhập thông tin người nhận và vị trí nhận sách mới</p>
                            </div>
                        </label>

                        <!-- Form nhập địa chỉ mới (Mở ra khi chọn 'new' hoặc khi chưa có địa chỉ) -->
                        <div id="new-address-container" class="<?= !$isNewChecked ? 'hidden' : '' ?> pt-4 mt-2 border-t border-surface-container space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-1">Họ tên người nhận *</label>
                                    <input type="text" name="new_nguoi_nhan" placeholder="Ví dụ: Nguyễn Văn An" 
                                           class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-xs text-primary border border-surface-container focus:outline-none focus:ring-2 focus:ring-secondary focus:bg-surface-container-lowest">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-on-surface mb-1">Số điện thoại liên hệ *</label>
                                    <input type="tel" name="new_so_dien_thoai" placeholder="Ví dụ: 0912345678" 
                                           class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-xs text-primary border border-surface-container focus:outline-none focus:ring-2 focus:ring-secondary focus:bg-surface-container-lowest">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-on-surface mb-1">Địa chỉ chi tiết (Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành) *</label>
                                <textarea name="new_dia_chi" rows="2" placeholder="Ví dụ: Số 45 Lê Duẩn, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh"
                                          class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-xs text-primary border border-surface-container focus:outline-none focus:ring-2 focus:ring-secondary focus:bg-surface-container-lowest resize-none"></textarea>
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer text-xs text-on-surface-variant">
                                <input type="checkbox" name="save_new_address" value="1" checked class="w-4 h-4 rounded text-secondary focus:ring-secondary">
                                <span>Lưu địa chỉ này vào Sổ địa chỉ để sử dụng cho các lần mua tiếp theo</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 2. HÌNH THỨC GIAO HÀNG -->
                <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-surface-container">
                    <div class="flex items-center gap-3 pb-4 border-b border-surface-container">
                        <span class="w-9 h-9 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">local_shipping</span>
                        </span>
                        <div>
                            <h2 class="text-lg font-bold text-primary">2. Hình Thức Vận Chuyển</h2>
                            <p class="text-xs text-on-surface-variant">Kiện sách được đóng hộp carton 2 lớp chống va đập và móp góc gáy</p>
                        </div>
                    </div>

                    <div class="pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Giao tiêu chuẩn -->
                        <label class="checkout-option-card relative p-4 rounded-xl border border-secondary bg-secondary-container/10 ring-1 ring-secondary/30 cursor-pointer flex flex-col justify-between transition-all">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="shipping_method" value="standard" checked 
                                           onchange="updateShippingMethod('standard')"
                                           class="mt-1 w-4 h-4 text-secondary focus:ring-secondary">
                                    <div>
                                        <span class="font-bold text-sm text-primary flex items-center gap-1">
                                            Giao Tiêu Chuẩn
                                            <span class="material-symbols-outlined text-secondary text-[16px]">verified</span>
                                        </span>
                                        <span class="text-xs text-on-surface-variant block mt-0.5">Viettel Post / Giao Hàng Nhanh (2-3 ngày)</span>
                                    </div>
                                </div>
                                <span class="font-bold text-sm text-secondary">
                                    <?= ($subtotal >= 250000) ? 'Miễn phí' : '22.000đ' ?>
                                </span>
                            </div>
                            <div class="mt-3 pt-2 border-t border-secondary/20 flex items-center justify-between text-[11px] text-on-surface-variant">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px] text-secondary">schedule</span>
                                    Dự kiến nhận: 2 - 3 ngày tới
                                </span>
                                <?php if ($subtotal >= 250000): ?>
                                    <span class="text-secondary font-bold">Freeship ≥ 250k</span>
                                <?php endif; ?>
                            </div>
                        </label>

                        <!-- Giao Hỏa tốc -->
                        <label class="checkout-option-card relative p-4 rounded-xl border border-surface-container bg-surface-container-lowest hover:border-secondary cursor-pointer flex flex-col justify-between transition-all">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="shipping_method" value="express" 
                                           onchange="updateShippingMethod('express')"
                                           class="mt-1 w-4 h-4 text-secondary focus:ring-secondary">
                                    <div>
                                        <span class="font-bold text-sm text-primary flex items-center gap-1">
                                            Hỏa Tốc 2 Giờ (NowShip)
                                            <span class="px-1.5 py-0.2 rounded bg-tertiary-fixed text-on-tertiary-fixed text-[10px] font-bold">SIÊU TỐC</span>
                                        </span>
                                        <span class="text-xs text-on-surface-variant block mt-0.5">Áp dụng nội thành TP.HCM & Hà Nội</span>
                                    </div>
                                </div>
                                <span class="font-bold text-sm text-primary">40.000đ</span>
                            </div>
                            <div class="mt-3 pt-2 border-t border-surface-container flex items-center justify-between text-[11px] text-on-surface-variant">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px] text-secondary">bolt</span>
                                    Nhận sách trước 17:00 hôm nay
                                </span>
                                <span>Ưu tiên xuất kho</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 3. PHƯƠNG THỨC THANH TOÁN -->
                <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-surface-container">
                    <div class="flex items-center gap-3 pb-4 border-b border-surface-container">
                        <span class="w-9 h-9 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                        </span>
                        <div>
                            <h2 class="text-lg font-bold text-primary">3. Phương Thức Thanh Toán</h2>
                            <p class="text-xs text-on-surface-variant">Giao dịch được mã hóa chuẩn bảo mật quốc tế PCI-DSS</p>
                        </div>
                    </div>

                    <div class="pt-4 space-y-3">
                        <!-- Cổng VNPAY (Khuyên dùng) -->
                        <label class="checkout-option-card relative flex items-start gap-4 p-4 rounded-xl border border-secondary bg-secondary-container/10 ring-1 ring-secondary/30 cursor-pointer transition-all">
                            <input type="radio" name="payment_method" value="VNPAY" checked onchange="updateRadioSelection('payment_method')" class="mt-1 w-4 h-4 text-secondary focus:ring-secondary">
                            <div class="flex-1">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-10 h-7 bg-white rounded border border-surface-container flex items-center justify-center font-bold text-xs shadow-xs">
                                            <span class="text-blue-700">VN</span><span class="text-red-600">PAY</span>
                                        </div>
                                        <div>
                                            <span class="font-bold text-sm text-primary">Cổng Thanh Toán VNPAY</span>
                                            <span class="ml-2 px-2 py-0.5 rounded bg-tertiary-fixed text-on-tertiary-fixed text-[10px] font-bold">KHUYÊN DÙNG</span>
                                        </div>
                                    </div>
                                    <span class="text-xs text-secondary font-bold flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span>
                                        Quét QR / Thẻ ATM / Visa / Master
                                    </span>
                                </div>
                                <p class="text-xs text-on-surface-variant mt-2 pl-12">
                                    Thanh toán tức thì qua ứng dụng ngân hàng di động (Vietcombank, Techcombank, MB, BIDV...) và các loại thẻ nội địa / quốc tế.
                                </p>
                            </div>
                        </label>

                        <!-- Thanh toán khi nhận hàng (COD) -->
                        <label class="checkout-option-card relative flex items-start gap-4 p-4 rounded-xl border border-surface-container bg-surface-container-lowest hover:border-secondary cursor-pointer transition-all">
                            <input type="radio" name="payment_method" value="COD" onchange="updateRadioSelection('payment_method')" class="mt-1 w-4 h-4 text-secondary focus:ring-secondary">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-10 h-7 bg-surface-container-low rounded border border-surface-container flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined text-[18px]">payments</span>
                                        </div>
                                        <div>
                                            <span class="font-bold text-sm text-primary">Thanh toán khi nhận hàng (COD)</span>
                                            <span class="text-xs text-on-surface-variant ml-2">Tiền mặt tận tay</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-on-surface-variant mt-2 pl-12">
                                    Quý khách kiểm tra kiện sách cùng bưu tá trước khi thanh toán tiền mặt.
                                </p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 4. GHI CHÚ ĐƠN HÀNG -->
                <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-surface-container">
                    <label class="block text-xs font-bold text-primary mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[18px]">edit_note</span>
                        <span>Ghi Chú Đơn Hàng (Tùy chọn)</span>
                    </label>
                    <textarea name="order_notes" rows="2" 
                              placeholder="Ví dụ: Xin vui lòng đóng gói cẩn thận góc sách, giao trước 17h chiều hoặc gọi điện trước 15 phút..."
                              class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-xl text-xs text-primary border border-surface-container focus:outline-none focus:ring-2 focus:ring-secondary focus:bg-surface-container-lowest resize-none"></textarea>
                </div>
            </div>

            <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG & NÚT ĐẶT (4 Cột - Sticky) -->
            <div class="lg:col-span-4 flex flex-col gap-6 sticky top-28">
                <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-md border border-surface-container">
                    <h2 class="text-lg font-bold text-primary pb-3 border-b border-surface-container flex items-center justify-between">
                        <span>Đơn Hàng (<?= $totalQuantity ?> cuốn)</span>
                        <?php if (!empty($isBuyNow)): ?>
                            <span class="text-xs px-2.5 py-0.5 rounded-full bg-secondary/10 text-secondary font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">bolt</span>
                                <span>Mua Nhanh</span>
                            </span>
                        <?php else: ?>
                            <a href="<?= base_url('gio-hang') ?>" class="text-xs text-secondary font-semibold hover:underline">Sửa giỏ</a>
                        <?php endif; ?>
                    </h2>

                    <!-- Danh sách thu nhỏ các cuốn sách trong đơn -->
                    <div class="py-3 divide-y divide-surface-container-low max-h-60 overflow-y-auto pr-1">
                        <?php foreach ($items as $item): 
                            $coverUrl = getCheckoutBookCover($item['anh_bia'] ?? null, $item['tieu_de']);
                        ?>
                            <div class="py-2.5 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($item['tieu_de']) ?>" class="w-9 h-13 object-cover rounded shadow-2xs flex-shrink-0">
                                    <div class="min-w-0">
                                        <p class="font-bold text-primary truncate"><?= htmlspecialchars($item['tieu_de']) ?></p>
                                        <span class="text-[11px] text-on-surface-variant">Số lượng: <?= $item['so_luong'] ?></span>
                                    </div>
                                </div>
                                <span class="font-bold text-primary flex-shrink-0"><?= format_currency($item['thanh_tien']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- KHUNG VOUCHER -->
                    <div class="py-3.5 border-t border-surface-container">
                        <label class="block text-xs font-bold text-primary mb-2 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-rose-500">confirmation_number</span>
                            <span>Voucher Giảm Giá &amp; Vận Chuyển</span>
                        </label>

                        <!-- Hộp bấm mở Modal Chọn Voucher kiểu Shopee -->
                        <div class="p-3.5 rounded-xl border border-rose-200 bg-rose-50/50 hover:bg-rose-100/60 cursor-pointer transition-all shadow-xs flex items-center justify-between" onclick="openShopeeCouponModal()">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">sell</span>
                                </span>
                                <div>
                                    <span class="font-extrabold text-xs text-primary flex items-center gap-1.5">
                                        DQP Voucher
                                        <span class="px-1.5 py-0.2 rounded bg-rose-600 text-white text-[9px] font-black uppercase">Áp dụng kép</span>
                                    </span>
                                    <span class="text-[11px] text-slate-600 block mt-0.5">
                                        <?php 
                                        $appliedTags = [];
                                        if ($appliedFreeshipCoupon) $appliedTags[] = 'Freeship: ' . $appliedFreeshipCoupon['ma'];
                                        if ($appliedDiscountCoupon) $appliedTags[] = 'Giảm giá: ' . $appliedDiscountCoupon['ma'];
                                        ?>
                                        <?php if (!empty($appliedTags)): ?>
                                            <span class="text-rose-600 font-bold">Đã chọn: <?= implode(' + ', $appliedTags) ?></span>
                                        <?php else: ?>
                                            Chọn 1 Mã Giảm Giá + 1 Mã Freeship
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 text-xs font-bold text-rose-600">
                                <span><?= (!empty($appliedTags)) ? 'Thay đổi' : 'Chọn mã' ?></span>
                                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                            </div>
                        </div>

                        <!-- Danh sách các voucher đang được áp dụng -->
                        <?php if ($appliedFreeshipCoupon || $appliedDiscountCoupon): ?>
                            <div class="mt-2.5 space-y-1.5">
                                <?php if ($appliedFreeshipCoupon): ?>
                                    <div class="p-2 px-3 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-emerald-600 text-[16px]">local_shipping</span>
                                            <div>
                                                <span class="font-bold text-emerald-900">Freeship: <?= htmlspecialchars($appliedFreeshipCoupon['ma']) ?></span>
                                                <span class="text-[11px] text-emerald-700 ml-1">(-<?= format_currency($shippingDiscount) ?> phí ship)</span>
                                            </div>
                                        </div>
                                        <a href="<?= base_url('gio-hang/go-voucher?type=freeship&redirect_to=thanh-toan') ?>" class="text-rose-500 hover:text-rose-700 font-bold p-1" title="Gỡ mã Freeship">
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if ($appliedDiscountCoupon): ?>
                                    <div class="p-2 px-3 rounded-lg bg-amber-50 border border-amber-200 flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-amber-600 text-[16px]">loyalty</span>
                                            <div>
                                                <span class="font-bold text-amber-900">Giảm giá: <?= htmlspecialchars($appliedDiscountCoupon['ma']) ?></span>
                                                <span class="text-[11px] text-amber-700 ml-1">(-<?= format_currency($discount) ?> tiền hàng)</span>
                                            </div>
                                        </div>
                                        <a href="<?= base_url('gio-hang/go-voucher?type=discount&redirect_to=thanh-toan') ?>" class="text-rose-500 hover:text-rose-700 font-bold p-1" title="Gỡ mã giảm giá">
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Bảng tính toán tiền chi tiết -->
                    <div class="py-4 border-t border-b border-surface-container space-y-2.5 text-xs text-on-surface">
                        <div class="flex items-center justify-between">
                            <span class="text-on-surface-variant">Tổng tiền hàng:</span>
                            <span class="font-bold text-sm text-primary"><?= format_currency($subtotal) ?></span>
                        </div>

                        <?php if ($discount > 0): ?>
                            <div class="flex items-center justify-between text-rose-600">
                                <span class="flex items-center gap-1">
                                    <span>Giảm giá tiền hàng (<?= htmlspecialchars($appliedDiscountCoupon['ma'] ?? '') ?>):</span>
                                </span>
                                <span class="font-bold text-sm">-<?= format_currency($discount) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-between">
                            <span class="text-on-surface-variant">Phí vận chuyển:</span>
                            <span id="display-shipping-fee" class="font-semibold">
                                <?= format_currency($shippingFee) ?>
                            </span>
                        </div>

                        <?php if ($shippingDiscount > 0): ?>
                            <div class="flex items-center justify-between text-emerald-600">
                                <span class="flex items-center gap-1">
                                    <span>Giảm phí vận chuyển (<?= htmlspecialchars($appliedFreeshipCoupon['ma'] ?? '') ?>):</span>
                                </span>
                                <span class="font-bold text-sm">-<?= format_currency($shippingDiscount) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Tổng thanh toán cuối cùng -->
                    <div class="pt-4 pb-2">
                        <div class="flex items-baseline justify-between mb-3">
                            <div>
                                <span class="text-sm font-bold text-primary block leading-tight">Tổng Thanh Toán:</span>
                                <span class="text-[11px] text-on-surface-variant">(Đã gồm thuế GTGT &amp; cước phí)</span>
                            </div>
                            <div class="text-right">
                                <span id="display-grand-total" class="text-2xl font-bold text-secondary block leading-none">
                                    <?= format_currency($grandTotal) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Nút Xác Nhận Đặt Hàng -->
                        <button type="submit" 
                                class="w-full py-4 px-6 rounded-xl bg-primary hover:bg-secondary text-on-primary font-bold text-sm flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transition-all duration-300">
                            <span class="material-symbols-outlined text-[20px]">lock</span>
                            <span>XÁC NHẬN ĐẶT HÀNG</span>
                        </button>

                        <p class="text-[11px] text-center text-on-surface-variant mt-3 leading-relaxed">
                            Bằng việc nhấn đặt hàng, bạn đồng ý với <a href="#" class="text-secondary underline">Điều khoản dịch vụ</a> và <a href="#" class="text-secondary underline">Chính sách giao nhận</a> của DQP Book Store.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- MODAL CHỌN VOUCHER (Áp dụng đồng thời Freeship + Giảm giá) -->
    <div id="shopee-voucher-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs hidden">
        <div class="bg-white rounded-3xl max-w-xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden border border-slate-200 animate-in fade-in zoom-in duration-200">
            <!-- Modal Header -->
            <div class="p-5 border-b border-slate-200 flex items-center justify-between bg-surface-container-lowest">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center shadow-xs">
                        <span class="material-symbols-outlined text-[20px]">confirmation_number</span>
                    </span>
                    <div>
                        <h3 class="font-extrabold text-base text-primary">Chọn DQP Voucher</h3>
                        <p class="text-[11px] text-slate-500">Áp dụng tối đa 1 Mã Miễn Phí Vận Chuyển + 1 Mã Giảm Giá</p>
                    </div>
                </div>
                <button type="button" onclick="closeShopeeCouponModal()" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Top Input Bar: Nhập mã voucher trực tiếp -->
            <div class="p-4 bg-slate-50 border-b border-slate-200">
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <span class="material-symbols-outlined text-[18px] text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">sell</span>
                        <input type="text" id="modal-coupon-code-input" placeholder="Nhập mã voucher (Ví dụ: WELCOME2026, FREESHIP...)" 
                               class="w-full pl-9 pr-3 py-2.5 bg-white text-primary text-xs font-bold uppercase tracking-wider rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
                    </div>
                    <button type="button" onclick="applySingleCodeFromModal()" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all active:scale-95 shrink-0">
                        Áp Dụng
                    </button>
                </div>
                <div id="modal-coupon-msg" class="text-[11px] font-bold mt-1.5 hidden"></div>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-5 space-y-6">
                <!-- PHẦN 1: MÃ MIỄN PHÍ VẬN CHUYỂN -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-black uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">local_shipping</span>
                            <span>Mã Miễn Phí Vận Chuyển (Freeship)</span>
                        </span>
                        <span class="text-[11px] text-slate-400">Chọn 1 mã</span>
                    </div>

                    <div class="space-y-2.5">
                        <!-- Lựa chọn không dùng mã vận chuyển -->
                        <label class="p-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white flex items-center justify-between cursor-pointer transition-all text-xs">
                            <span class="text-slate-600 font-medium">Không sử dụng mã vận chuyển</span>
                            <input type="radio" name="modal_freeship_code" value="" <?= empty($appliedFreeshipCoupon) ? 'checked' : '' ?> onchange="updateModalDiscountPreview()" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        </label>

                        <?php if (empty($freeshipCoupons)): ?>
                            <div class="p-3 text-center text-xs text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                Bạn chưa lưu mã miễn phí vận chuyển nào. Hãy nhập mã <span class="font-bold text-emerald-700">FREESHIP</span> ở trên để sử dụng!
                            </div>
                        <?php else: ?>
                            <?php foreach ($freeshipCoupons as $fs): 
                                $isUsable = ($subtotal >= (float)$fs['don_toi_thieu']);
                                $isChecked = ($appliedFreeshipCoupon && $appliedFreeshipCoupon['id'] == $fs['id']);
                                $needed = (float)$fs['don_toi_thieu'] - $subtotal;
                            ?>
                                <label class="relative rounded-xl border flex overflow-hidden transition-all <?= $isChecked ? 'border-emerald-500 ring-2 ring-emerald-500/30 bg-emerald-50/20' : 'border-slate-200 bg-white hover:border-emerald-300' ?> <?= !$isUsable ? 'opacity-60 cursor-not-allowed bg-slate-50' : 'cursor-pointer' ?>">
                                    <!-- Khối màu trái -->
                                    <div class="w-24 bg-emerald-600 text-white p-3 flex flex-col items-center justify-center text-center shrink-0">
                                        <span class="material-symbols-outlined text-[22px]">local_shipping</span>
                                        <span class="text-[9px] font-black uppercase mt-0.5">Freeship</span>
                                    </div>
                                    <!-- Thân thông tin -->
                                    <div class="flex-1 p-3 flex items-center justify-between gap-2">
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-extrabold text-xs text-primary font-mono"><?= htmlspecialchars($fs['ma']) ?></span>
                                                <span class="text-[11px] font-bold text-emerald-700"><?= htmlspecialchars($fs['text_giam']) ?></span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 mt-0.5">
                                                Đơn tối thiểu <?= format_currency($fs['don_toi_thieu']) ?>
                                            </p>
                                            <?php if (!$isUsable): ?>
                                                <p class="text-[10px] text-rose-600 font-bold mt-0.5">
                                                    ⚠️ Mua thêm <?= format_currency($needed) ?> để sử dụng mã này
                                                </p>
                                            <?php else: ?>
                                                <p class="text-[10px] text-slate-400 mt-0.5">HSD: <?= date('d/m/Y', strtotime($fs['ket_thuc'])) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <input type="radio" name="modal_freeship_code" value="<?= htmlspecialchars($fs['ma']) ?>" 
                                               data-val="<?= (float)$fs['gia_tri'] ?>" data-type="<?= $fs['loai'] ?>"
                                               <?= $isChecked ? 'checked' : '' ?> <?= !$isUsable ? 'disabled' : '' ?>
                                               onchange="updateModalDiscountPreview()"
                                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- PHẦN 2: MÃ GIẢM GIÁ ĐƠN HÀNG (TIỀN HÀNG) -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-black uppercase tracking-wider text-amber-800 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-amber-600">loyalty</span>
                            <span>Mã Giảm Giá Đơn Hàng (Voucher Thư Quán)</span>
                        </span>
                        <span class="text-[11px] text-slate-400">Chọn 1 mã</span>
                    </div>

                    <div class="space-y-2.5">
                        <!-- Lựa chọn không dùng mã giảm giá -->
                        <label class="p-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white flex items-center justify-between cursor-pointer transition-all text-xs">
                            <span class="text-slate-600 font-medium">Không sử dụng mã giảm giá</span>
                            <input type="radio" name="modal_discount_code" value="" <?= empty($appliedDiscountCoupon) ? 'checked' : '' ?> onchange="updateModalDiscountPreview()" class="w-4 h-4 text-rose-600 focus:ring-rose-500">
                        </label>

                        <?php if (empty($discountCoupons)): ?>
                            <div class="p-3 text-center text-xs text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                Bạn chưa có mã giảm giá nào trong kho. Hãy nhập mã <span class="font-bold text-amber-700">WELCOME2026</span> ở trên để nhận ưu đãi!
                            </div>
                        <?php else: ?>
                            <?php foreach ($discountCoupons as $dc): 
                                $isUsable = ($subtotal >= (float)$dc['don_toi_thieu']);
                                $isChecked = ($appliedDiscountCoupon && $appliedDiscountCoupon['id'] == $dc['id']);
                                $needed = (float)$dc['don_toi_thieu'] - $subtotal;
                            ?>
                                <label class="relative rounded-xl border flex overflow-hidden transition-all <?= $isChecked ? 'border-rose-500 ring-2 ring-rose-500/30 bg-rose-50/20' : 'border-slate-200 bg-white hover:border-amber-300' ?> <?= !$isUsable ? 'opacity-60 cursor-not-allowed bg-slate-50' : 'cursor-pointer' ?>">
                                    <!-- Khối màu trái -->
                                    <div class="w-24 bg-gradient-to-br from-amber-500 to-rose-500 text-white p-3 flex flex-col items-center justify-center text-center shrink-0">
                                        <span class="material-symbols-outlined text-[22px]">loyalty</span>
                                        <span class="text-[9px] font-black uppercase mt-0.5">Giảm Giá</span>
                                    </div>
                                    <!-- Thân thông tin -->
                                    <div class="flex-1 p-3 flex items-center justify-between gap-2">
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-extrabold text-xs text-primary font-mono"><?= htmlspecialchars($dc['ma']) ?></span>
                                                <span class="text-[11px] font-bold text-rose-600"><?= htmlspecialchars($dc['text_giam']) ?></span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 mt-0.5">
                                                Đơn tối thiểu <?= format_currency($dc['don_toi_thieu']) ?>
                                            </p>
                                            <?php if (!$isUsable): ?>
                                                <p class="text-[10px] text-rose-600 font-bold mt-0.5">
                                                    ⚠️ Mua thêm <?= format_currency($needed) ?> để sử dụng mã này
                                                </p>
                                            <?php else: ?>
                                                <p class="text-[10px] text-slate-400 mt-0.5">HSD: <?= date('d/m/Y', strtotime($dc['ket_thuc'])) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <input type="radio" name="modal_discount_code" value="<?= htmlspecialchars($dc['ma']) ?>" 
                                               data-val="<?= (float)$dc['gia_tri'] ?>" data-type="<?= $dc['loai'] ?>"
                                               <?= $isChecked ? 'checked' : '' ?> <?= !$isUsable ? 'disabled' : '' ?>
                                               onchange="updateModalDiscountPreview()"
                                               class="w-4 h-4 text-rose-600 focus:ring-rose-500">
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Modal Footer (Fixed Bottom) -->
            <div class="p-4 px-5 border-t border-slate-200 bg-white flex items-center justify-between gap-4">
                <div>
                    <span class="text-xs text-slate-500 block">Số tiền dự kiến tiết kiệm:</span>
                    <span id="modal-savings-preview" class="text-lg font-black text-rose-600 block leading-tight">
                        <?= format_currency(($discount ?? 0) + ($shippingDiscount ?? 0)) ?>
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeShopeeCouponModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold text-xs transition-colors">
                        Trở lại
                    </button>
                    <button type="button" onclick="confirmShopeeModalSelection()" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md transition-all active:scale-95">
                        ĐỒNG Ý
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Form ẩn hỗ trợ submit voucher kép -->
    <form id="apply-shopee-coupon-form" action="<?= base_url('gio-hang/ap-dung-voucher') ?>" method="POST" style="display:none;">
        <?= csrf_field() ?>
        <input type="hidden" name="shopee_modal" value="1">
        <input type="hidden" name="discount_code" id="hidden-discount-code" value="">
        <input type="hidden" name="freeship_code" id="hidden-freeship-code" value="">
        <input type="hidden" name="redirect_to" value="thanh-toan">
    </form>
</div>

<script>
const baseSubtotal = <?= (float)$subtotal ?>;
let currentShippingFee = <?= (float)$shippingFee ?>;
let currentDiscount = <?= (float)($discount ?? 0) ?>;
let currentShippingDiscount = <?= (float)($shippingDiscount ?? 0) ?>;

function openShopeeCouponModal() {
    const modal = document.getElementById('shopee-voucher-modal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
}

function closeShopeeCouponModal() {
    const modal = document.getElementById('shopee-voucher-modal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

function updateModalDiscountPreview() {
    // Tự động tính nhẩm mức tiết kiệm trong modal khi click radio
    let disc = 0;
    let shipDisc = 0;

    const selectedDiscount = document.querySelector('input[name="modal_discount_code"]:checked');
    if (selectedDiscount && selectedDiscount.value) {
        const val = parseFloat(selectedDiscount.dataset.val || 0);
        const type = selectedDiscount.dataset.type;
        disc = (type === 'phan_tram') ? (baseSubtotal * val / 100) : val;
        disc = Math.min(disc, baseSubtotal);
    }

    const selectedFs = document.querySelector('input[name="modal_freeship_code"]:checked');
    if (selectedFs && selectedFs.value) {
        const val = parseFloat(selectedFs.dataset.val || 0);
        const type = selectedFs.dataset.type;
        shipDisc = (type === 'phan_tram') ? (currentShippingFee * val / 100) : val;
        shipDisc = Math.min(shipDisc, currentShippingFee);
    }

    const totalSave = disc + shipDisc;
    document.getElementById('modal-savings-preview').innerText = new Intl.NumberFormat('vi-VN').format(Math.round(totalSave)) + 'đ';
}

function confirmShopeeModalSelection() {
    const selectedDiscount = document.querySelector('input[name="modal_discount_code"]:checked');
    const selectedFs = document.querySelector('input[name="modal_freeship_code"]:checked');

    const discountCode = selectedDiscount ? selectedDiscount.value : '';
    const freeshipCode = selectedFs ? selectedFs.value : '';

    document.getElementById('hidden-discount-code').value = discountCode;
    document.getElementById('hidden-freeship-code').value = freeshipCode;
    document.getElementById('apply-shopee-coupon-form').submit();
}

function applySingleCodeFromModal() {
    const input = document.getElementById('modal-coupon-code-input');
    const code = input ? input.value.trim() : '';
    const msgEl = document.getElementById('modal-coupon-msg');

    if (!code) {
        if (msgEl) {
            msgEl.className = 'text-[11px] font-bold mt-1.5 text-rose-600 block';
            msgEl.innerText = 'Vui lòng nhập mã voucher.';
        }
        return;
    }

    // Gửi AJAX kiểm tra và nạp voucher
    const formData = new FormData();
    formData.append('coupon_code', code);
    formData.append('redirect_to', 'thanh-toan');

    fetch('<?= base_url('gio-hang/ap-dung-voucher') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (msgEl) {
                msgEl.className = 'text-[11px] font-bold mt-1.5 text-emerald-600 block';
                msgEl.innerText = '✅ ' + data.message;
            }
            setTimeout(() => {
                window.location.reload();
            }, 600);
        } else {
            if (msgEl) {
                msgEl.className = 'text-[11px] font-bold mt-1.5 text-rose-600 block';
                msgEl.innerText = '❌ ' + data.message;
            }
        }
    })
    .catch(() => {
        // Fallback reload
        document.getElementById('hidden-discount-code').value = code;
        document.getElementById('apply-shopee-coupon-form').submit();
    });
}

// Hàm tự động cập nhật viền và màu nền khi tích radio
function updateRadioSelection(name) {
    const radios = document.querySelectorAll(`input[name="${name}"]`);
    radios.forEach(radio => {
        const card = radio.closest('.checkout-option-card');
        if (!card) return;

        if (radio.checked) {
            card.classList.add('bg-secondary-container/10', 'border-secondary', 'ring-1', 'ring-secondary/30');
            card.classList.remove('bg-surface-container-lowest', 'bg-surface-container-low/40', 'border-surface-container');
        } else {
            card.classList.remove('bg-secondary-container/10', 'border-secondary', 'ring-1', 'ring-secondary/30');
            if (radio.name === 'address_choice' && radio.value === 'new') {
                card.classList.add('bg-surface-container-low/40', 'border-surface-container');
            } else {
                card.classList.add('bg-surface-container-lowest', 'border-surface-container');
            }
        }
    });
}

function handleAddressChange(radioInput) {
    updateRadioSelection('address_choice');
    
    const isNew = (radioInput.value === 'new');
    const container = document.getElementById('new-address-container');
    const addrInputs = document.querySelectorAll('.addr-id-input');
    
    if (isNew) {
        container.classList.remove('hidden');
        addrInputs.forEach(i => i.disabled = true);
    } else {
        container.classList.add('hidden');
        addrInputs.forEach(i => {
            const isCardChecked = i.closest('.checkout-option-card').querySelector('input[type="radio"]').checked;
            i.disabled = !isCardChecked;
        });
    }
}

function updateShippingMethod(method) {
    updateRadioSelection('shipping_method');
    
    let fee = (baseSubtotal >= 250000) ? 0 : 22000;
    if (method === 'express') {
        fee = 40000;
    }
    currentShippingFee = fee;

    const finalFee = Math.max(0, fee - currentShippingDiscount);
    const grand = Math.max(0, baseSubtotal - currentDiscount + finalFee);
    
    document.getElementById('display-shipping-fee').innerHTML = (fee === 0) 
        ? '<span class="text-secondary font-bold">Miễn phí</span>' 
        : new Intl.NumberFormat('vi-VN').format(fee) + 'đ';
        
    document.getElementById('display-grand-total').innerText = new Intl.NumberFormat('vi-VN').format(grand) + 'đ';
}
</script>