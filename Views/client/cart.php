<?php
/**
 * Giao diện Giỏ Hàng - DQP Book Store
 * Phong cách: Athenaeum Modern
 */
function getCartBookCover(?string $fileName, string $title): string {
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
            <div class="absolute left-8 top-1/2 -translate-y-1/2 h-1 bg-secondary z-0 transition-all duration-500 w-1/3"></div>

            <!-- Bước 1: Giỏ hàng (Đang chọn) -->
            <div class="flex flex-col items-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm shadow-lg ring-4 ring-secondary-container">
                    <span>1</span>
                </div>
                <span class="mt-2 text-xs font-bold text-primary tracking-wide">1. Giỏ Hàng</span>
            </div>

            <!-- Bước 2: Thông tin giao nhận -->
            <div class="flex flex-col items-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-surface-container-high text-on-surface-variant flex items-center justify-center font-bold text-sm shadow-sm ring-4 ring-surface-container-low">
                    <span>2</span>
                </div>
                <span class="mt-2 text-xs font-semibold text-on-surface-variant tracking-wide">2. Vận Chuyển & Địa Chỉ</span>
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

<!-- Nội Dung Giỏ Hàng Chính -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 w-full">
    <?php if (empty($items)): ?>
        <!-- Trạng Thái Giỏ Hàng Trống -->
        <div class="bg-surface-container-lowest rounded-2xl p-12 text-center max-w-2xl mx-auto shadow-sm border border-surface-container">
            <div class="w-24 h-24 mx-auto rounded-full bg-surface-container-low flex items-center justify-center text-primary mb-6">
                <span class="material-symbols-outlined text-[48px] text-secondary">production_quantity_limits</span>
            </div>
            <h2 class="text-2xl font-bold text-primary mb-2">Giỏ hàng của bạn đang trống</h2>
            <p class="text-on-surface-variant text-sm mb-8 leading-relaxed max-w-md mx-auto">
                Hãy dạo quanh không gian Thư quán DQP Book Store để khám phá những tựa sách kinh điển, phát triển tư duy và bồi đắp tâm hồn.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="<?= base_url('tim-kiem') ?>" class="w-full sm:w-auto px-8 py-3.5 bg-primary text-on-primary rounded-xl font-bold text-sm hover:bg-secondary transition-all shadow-md flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">auto_stories</span>
                    <span>Khám Phá Sách Ngay</span>
                </a>
                <a href="<?= base_url('trang-chu') ?>" class="w-full sm:w-auto px-6 py-3.5 bg-surface-container-low text-primary rounded-xl font-bold text-sm hover:bg-surface-container-high transition-colors">
                    Về Trang Chủ
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- CỘT TRÁI: DANH SÁCH SÁCH TRONG GIỎ (8 Cột) -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-surface-container">
                    <!-- Thanh tiêu đề bảng giỏ hàng -->
                    <div class="flex items-center justify-between pb-4 border-b border-surface-container">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[22px]">shopping_cart</span>
                            <h2 class="text-lg font-bold text-primary">Sản Phẩm Trong Giỏ</h2>
                            <span class="bg-secondary-container/40 text-on-secondary-container text-xs font-bold px-2.5 py-0.5 rounded-full">
                                <?= $totalItems ?> cuốn sách
                            </span>
                        </div>
                        <a href="<?= base_url('gio-hang/xoa-tat-ca') ?>" 
                           onclick="return confirm('Bạn có chắc chắn muốn dọn sạch toàn bộ giỏ hàng?');"
                           class="text-xs font-semibold text-error hover:underline flex items-center gap-1 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">delete_sweep</span>
                            <span>Xóa tất cả</span>
                        </a>
                    </div>

                    <!-- Danh sách các cuốn sách -->
                    <div class="divide-y divide-surface-container-low">
                        <?php foreach ($items as $item): 
                            $coverUrl = getCartBookCover($item['anh_bia'] ?? null, $item['tieu_de']);
                        ?>
                            <div class="py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <!-- Thông tin sách & Bìa -->
                                <div class="flex items-start gap-4 min-w-0 flex-1">
                                    <a href="<?= base_url('chi-tiet/' . $item['sach_id']) ?>" class="w-20 h-28 flex-shrink-0 rounded-lg overflow-hidden shadow-sm bg-surface-container group">
                                        <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($item['tieu_de']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </a>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-[11px] font-bold text-secondary uppercase tracking-wider block">
                                            <?= htmlspecialchars($item['the_loai_ten'] ?? 'Sách Hay') ?>
                                        </span>
                                        <h3 class="text-base font-bold text-primary hover:text-secondary transition-colors line-clamp-1 mt-0.5">
                                            <a href="<?= base_url('chi-tiet/' . $item['sach_id']) ?>">
                                                <?= htmlspecialchars($item['tieu_de']) ?>
                                            </a>
                                        </h3>
                                        <p class="text-xs text-on-surface-variant mt-1 line-clamp-1">
                                            Tác giả: <?= htmlspecialchars($item['tac_gia_ten'] ?? 'Nhiều tác giả') ?>
                                        </p>
                                        <div class="flex items-baseline gap-2 mt-1 sm:hidden">
                                            <span class="font-bold text-secondary text-sm"><?= format_currency($item['gia']) ?></span>
                                            <span class="text-xs text-on-surface-variant line-through"><?= format_currency($item['gia'] * 1.25) ?></span>
                                        </div>
                                        <div class="mt-2">
                                            <a href="<?= base_url('gio-hang/xoa/' . $item['sach_id']) ?>" 
                                               onclick="return confirm('Xóa cuốn sách này khỏi giỏ?');"
                                               class="text-[11px] font-semibold text-on-surface-variant hover:text-error inline-flex items-center gap-1 transition-colors">
                                                <span class="material-symbols-outlined text-[14px]">delete</span>
                                                <span>Xóa khỏi giỏ</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Giá đơn vị & Bộ chọn số lượng -->
                                <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto gap-2">
                                    <div class="hidden sm:block text-right">
                                        <span class="text-sm font-bold text-primary block"><?= format_currency($item['gia']) ?></span>
                                        <span class="text-xs text-on-surface-variant line-through"><?= format_currency($item['gia'] * 1.25) ?></span>
                                    </div>

                                    <!-- Bộ điều chỉnh số lượng (+ / -) -->
                                    <div class="flex items-center bg-surface-container-low rounded-lg p-0.5 border border-surface-container">
                                        <!-- Nút Giảm -->
                                        <form action="<?= base_url('gio-hang/cap-nhat') ?>" method="POST" class="inline">
                                            <input type="hidden" name="sach_id" value="<?= $item['sach_id'] ?>">
                                            <input type="hidden" name="so_luong" value="<?= max(0, $item['so_luong'] - 1) ?>">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-on-surface hover:bg-surface-container-lowest rounded transition-colors" title="Giảm số lượng">
                                                <span class="material-symbols-outlined text-[16px]">remove</span>
                                            </button>
                                        </form>

                                        <!-- Số lượng hiện tại -->
                                        <span class="w-10 text-center font-bold text-sm text-primary">
                                            <?= $item['so_luong'] ?>
                                        </span>

                                        <!-- Nút Tăng -->
                                        <form action="<?= base_url('gio-hang/cap-nhat') ?>" method="POST" class="inline">
                                            <input type="hidden" name="sach_id" value="<?= $item['sach_id'] ?>">
                                            <input type="hidden" name="so_luong" value="<?= $item['so_luong'] + 1 ?>">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-on-surface hover:bg-surface-container-lowest rounded transition-colors" title="Tăng số lượng">
                                                <span class="material-symbols-outlined text-[16px]">add</span>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Thành tiền -->
                                    <div class="text-right">
                                        <span class="text-base font-bold text-secondary">
                                            <?= format_currency($item['thanh_tien']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Cam kết chất lượng dịch vụ -->
                    <div class="mt-6 pt-4 border-t border-surface-container grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-on-surface-variant">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[18px]">verified</span>
                            <span>100% Sách Thật Chuẩn NXB</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[18px]">inventory_2</span>
                            <span>Đóng gói 2 lớp carton chống móp</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[18px]">published_with_changes</span>
                            <span>Đổi trả 7 ngày nếu lỗi in ấn</span>
                        </div>
                    </div>
                </div>

                <!-- Nút Tiếp tục chọn sách -->
                <div class="flex items-center justify-between">
                    <a href="<?= base_url('tim-kiem') ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-secondary hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        <span>Tiếp tục chọn thêm sách</span>
                    </a>
                </div>
            </div>

            <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG & MÃ GIẢM GIÁ (4 Cột - Sticky) -->
            <div class="lg:col-span-4 flex flex-col gap-6 sticky top-28">
                <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-surface-container">
                    <h2 class="text-lg font-bold text-primary pb-3 border-b border-surface-container flex items-center justify-between">
                        <span>Tóm Tắt Giỏ Hàng</span>
                        <span class="text-xs text-on-surface-variant font-normal"><?= $totalItems ?> cuốn</span>
                    </h2>

                    <!-- KHUNG VOUCHER -->
                    <div class="py-4 border-b border-surface-container">
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
                                        <a href="<?= base_url('gio-hang/go-voucher?type=freeship') ?>" class="text-rose-500 hover:text-rose-700 font-bold p-1" title="Gỡ mã Freeship">
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
                                        <a href="<?= base_url('gio-hang/go-voucher?type=discount') ?>" class="text-rose-500 hover:text-rose-700 font-bold p-1" title="Gỡ mã giảm giá">
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Bảng tính toán chi tiết số tiền -->
                    <div class="py-4 space-y-2.5 text-xs text-on-surface border-b border-surface-container">
                        <div class="flex items-center justify-between">
                            <span class="text-on-surface-variant">Tổng tiền sách:</span>
                            <span class="font-bold text-sm text-primary"><?= format_currency($subtotal) ?></span>
                        </div>

                        <?php if ($discount > 0): ?>
                            <div class="flex items-center justify-between text-rose-600">
                                <span class="flex items-center gap-1">
                                    <span>Voucher giảm giá (<?= htmlspecialchars($appliedDiscountCoupon['ma'] ?? '') ?>):</span>
                                </span>
                                <span class="font-bold text-sm">-<?= format_currency($discount) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-between">
                            <span class="text-on-surface-variant">Phí vận chuyển dự kiến:</span>
                            <span>
                                <?php if ($shippingFee == 0): ?>
                                    <span class="text-emerald-600 font-bold">Miễn phí toàn quốc</span>
                                <?php else: ?>
                                    <span class="font-semibold"><?= format_currency($shippingFee) ?></span>
                                <?php endif; ?>
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

                        <?php if ($subtotal < 250000 && $subtotal > 0): ?>
                            <p class="text-[11px] text-amber-700 bg-amber-50 p-2 rounded border border-amber-200 mt-1">
                                Mua thêm <strong><?= format_currency(250000 - $subtotal) ?></strong> để nhận <strong>Miễn phí vận chuyển toàn quốc</strong>!
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Tổng thanh toán cuối cùng -->
                    <div class="pt-4 pb-2">
                        <div class="flex items-baseline justify-between mb-2">
                            <div>
                                <span class="text-sm font-bold text-primary block leading-tight">Tổng Cộng:</span>
                                <span class="text-[11px] text-on-surface-variant">(Đã bao gồm VAT 8%)</span>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-secondary block leading-none">
                                    <?= format_currency($grandTotal) ?>
                                </span>
                            </div>
                        </div>

                        <?php if ($discount > 0 || $shippingDiscount > 0): ?>
                            <div class="p-2 rounded-lg bg-tertiary-fixed/60 text-on-tertiary-fixed flex items-center justify-center gap-1.5 text-xs font-semibold mb-4">
                                <span class="material-symbols-outlined text-[16px]">savings</span>
                                <span>Tiết kiệm được <strong class="font-bold"><?= format_currency($discount + $shippingDiscount) ?></strong></span>
                            </div>
                        <?php endif; ?>

                        <!-- Nút Chuyển Tiếp Sang Checkout -->
                        <a href="<?= base_url('thanh-toan') ?>" 
                           class="w-full py-3.5 px-6 rounded-xl bg-primary hover:bg-secondary text-on-primary font-bold text-sm flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transition-all duration-300">
                            <span>TIẾN HÀNH ĐẶT HÀNG</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>

                        <p class="text-[11px] text-center text-on-surface-variant mt-3">
                            Cam kết giao dịch an toàn &amp; bảo mật thông tin chuẩn SSL.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- MODAL CHỌN VOUCHER (Giỏ Hàng) -->
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
                                    <div class="w-24 bg-emerald-600 text-white p-3 flex flex-col items-center justify-center text-center shrink-0">
                                        <span class="material-symbols-outlined text-[22px]">local_shipping</span>
                                        <span class="text-[9px] font-black uppercase mt-0.5">Freeship</span>
                                    </div>
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

                <!-- PHẦN 2: MÃ GIẢM GIÁ ĐƠN HÀNG -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-black uppercase tracking-wider text-amber-800 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-amber-600">loyalty</span>
                            <span>Mã Giảm Giá Đơn Hàng (Voucher Thư Quán)</span>
                        </span>
                        <span class="text-[11px] text-slate-400">Chọn 1 mã</span>
                    </div>

                    <div class="space-y-2.5">
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
                                    <div class="w-24 bg-gradient-to-br from-amber-500 to-rose-500 text-white p-3 flex flex-col items-center justify-center text-center shrink-0">
                                        <span class="material-symbols-outlined text-[22px]">loyalty</span>
                                        <span class="text-[9px] font-black uppercase mt-0.5">Giảm Giá</span>
                                    </div>
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
        <input type="hidden" name="redirect_to" value="gio-hang">
    </form>
</div>

<script>
const baseSubtotal = <?= (float)$subtotal ?>;
let currentShippingFee = <?= (float)$shippingFee ?>;

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

    const formData = new FormData();
    formData.append('coupon_code', code);
    formData.append('redirect_to', 'gio-hang');

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
        document.getElementById('hidden-discount-code').value = code;
        document.getElementById('apply-shopee-coupon-form').submit();
    });
}
</script>
