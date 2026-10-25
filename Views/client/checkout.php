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
                                <label class="relative flex items-start gap-4 p-4 rounded-xl border border-surface-container hover:border-secondary transition-all cursor-pointer <?= $isChecked ? 'bg-secondary-container/10 border-secondary ring-1 ring-secondary/30' : 'bg-surface-container-lowest' ?>">
                                    <input type="radio" name="address_choice" value="existing" 
                                           <?= $isChecked ? 'checked' : '' ?> 
                                           onchange="toggleNewAddressForm(false)"
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
                        <label class="relative flex items-start gap-4 p-4 rounded-xl border border-dashed border-surface-container hover:border-secondary transition-all cursor-pointer bg-surface-container-low/40">
                            <input type="radio" name="address_choice" value="new" 
                                   <?= empty($addresses) ? 'checked' : '' ?> 
                                   onchange="toggleNewAddressForm(true)"
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
                        <div id="new-address-container" class="<?= !empty($addresses) ? 'hidden' : '' ?> pt-4 mt-2 border-t border-surface-container space-y-4">
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
                        <label class="relative p-4 rounded-xl border border-secondary bg-secondary-container/10 ring-1 ring-secondary/30 cursor-pointer flex flex-col justify-between">
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
                        <label class="relative p-4 rounded-xl border border-surface-container hover:border-secondary bg-surface-container-lowest transition-all cursor-pointer flex flex-col justify-between">
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
                        <label class="relative flex items-start gap-4 p-4 rounded-xl border border-secondary bg-secondary-container/10 ring-1 ring-secondary/30 cursor-pointer">
                            <input type="radio" name="payment_method" value="VNPAY" checked class="mt-1 w-4 h-4 text-secondary focus:ring-secondary">
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
                        <label class="relative flex items-start gap-4 p-4 rounded-xl border border-surface-container hover:border-secondary bg-surface-container-lowest transition-all cursor-pointer">
                            <input type="radio" name="payment_method" value="COD" class="mt-1 w-4 h-4 text-secondary focus:ring-secondary">
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
                        <a href="<?= base_url('gio-hang') ?>" class="text-xs text-secondary font-semibold hover:underline">Sửa giỏ</a>
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

                    <!-- Bảng tính toán tiền -->
                    <div class="py-4 border-t border-b border-surface-container space-y-2.5 text-xs text-on-surface">
                        <div class="flex items-center justify-between">
                            <span class="text-on-surface-variant">Tổng tiền hàng:</span>
                            <span class="font-bold text-sm text-primary"><?= format_currency($subtotal) ?></span>
                        </div>

                        <?php if ($discount > 0): ?>
                            <div class="flex items-center justify-between text-secondary">
                                <span class="flex items-center gap-1">
                                    <span>Voucher (<?= htmlspecialchars($appliedCoupon['ma'] ?? '') ?>):</span>
                                </span>
                                <span class="font-bold text-sm">-<?= format_currency($discount) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-between">
                            <span class="text-on-surface-variant">Phí vận chuyển:</span>
                            <span id="display-shipping-fee" class="font-semibold">
                                <?= ($shippingFee == 0) ? '<span class="text-secondary font-bold">Miễn phí</span>' : format_currency($shippingFee) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Tổng thanh toán cuối cùng -->
                    <div class="pt-4 pb-2">
                        <div class="flex items-baseline justify-between mb-3">
                            <div>
                                <span class="text-sm font-bold text-primary block leading-tight">Tổng Thanh Toán:</span>
                                <span class="text-[11px] text-on-surface-variant">(Đã gồm thuế GTGT)</span>
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
</div>

<script>
const baseSubtotal = <?= (float)$subtotal ?>;
const baseDiscount = <?= (float)$discount ?>;

function updateShippingMethod(method) {
    let fee = (baseSubtotal >= 250000) ? 0 : 22000;
    if (method === 'express') {
        fee = 40000;
    }
    const grand = Math.max(0, baseSubtotal - baseDiscount + fee);
    
    document.getElementById('display-shipping-fee').innerHTML = (fee === 0) 
        ? '<span class="text-secondary font-bold">Miễn phí</span>' 
        : new Intl.NumberFormat('vi-VN').format(fee) + 'đ';
        
    document.getElementById('display-grand-total').innerText = new Intl.NumberFormat('vi-VN').format(grand) + 'đ';
}

function toggleNewAddressForm(isNew) {
    const container = document.getElementById('new-address-container');
    const addrInputs = document.querySelectorAll('.addr-id-input');
    if (isNew) {
        container.classList.remove('hidden');
        addrInputs.forEach(i => i.disabled = true);
    } else {
        container.classList.add('hidden');
        addrInputs.forEach(i => i.disabled = false);
    }
}
</script>
