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

                    <!-- Khung Áp Dụng Mã Giảm Giá -->
                    <div class="py-4 border-b border-surface-container">
                        <label class="block text-xs font-bold text-primary mb-2 flex items-center justify-between">
                            <span>Mã Giảm Giá & Ưu Đãi</span>
                            <?php if ($appliedCoupon): ?>
                                <span class="text-secondary font-bold text-[11px] flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                    Đã áp dụng
                                </span>
                            <?php endif; ?>
                        </label>

                        <form action="<?= base_url('gio-hang/ap-dung-voucher') ?>" method="POST" class="flex gap-2">
                            <div class="relative flex-1">
                                <span class="material-symbols-outlined text-[18px] text-on-surface-variant absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">sell</span>
                                <input type="text" name="coupon_code" 
                                       placeholder="Nhập mã ưu đãi..." 
                                       value="<?= htmlspecialchars($appliedCoupon['ma'] ?? '') ?>"
                                       class="w-full pl-9 pr-3 py-2 bg-surface-container-low text-primary text-xs font-bold uppercase tracking-wider rounded-lg border border-surface-container focus:outline-none focus:ring-2 focus:ring-secondary focus:bg-surface-container-lowest transition-all">
                            </div>
                            <button type="submit" class="px-4 py-2 bg-primary text-on-primary hover:bg-secondary rounded-lg font-bold text-xs transition-colors whitespace-nowrap shadow-xs">
                                Áp Dụng
                            </button>
                        </form>

                        <!-- Tag Voucher đã áp dụng -->
                        <?php if ($appliedCoupon): ?>
                            <div class="mt-3 p-2.5 rounded-lg bg-secondary-container/30 border border-secondary/20 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-[18px]">loyalty</span>
                                    <div>
                                        <span class="font-bold text-primary block leading-tight"><?= htmlspecialchars($appliedCoupon['ma']) ?></span>
                                        <span class="text-[11px] text-on-surface-variant">Giảm <?= format_currency($discount) ?></span>
                                    </div>
                                </div>
                                <a href="<?= base_url('gio-hang/go-voucher') ?>" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Gỡ mã">
                                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Gợi ý các mã ưu đãi đang diễn ra -->
                        <?php if (!empty($activeCoupons)): ?>
                            <div class="mt-3">
                                <p class="text-[11px] text-on-surface-variant mb-1.5 flex items-center gap-1 font-semibold">
                                    <span class="material-symbols-outlined text-[14px] text-secondary">local_activity</span>
                                    <span>Voucher có sẵn từ Thư Quán:</span>
                                </p>
                                <div class="flex flex-wrap gap-1.5">
                                    <?php foreach ($activeCoupons as $ac): ?>
                                        <button type="button" 
                                                onclick="document.querySelector('input[name=coupon_code]').value='<?= $ac['ma'] ?>'; document.querySelector('form[action*=\'ap-dung-voucher\']').submit();"
                                                class="px-2 py-1 bg-surface-container-low hover:bg-secondary-container/40 text-primary text-[11px] font-bold rounded border border-dashed border-secondary/40 transition-colors">
                                            <?= htmlspecialchars($ac['ma']) ?> 
                                            <span class="text-secondary font-normal">(<?= ($ac['loai'] === 'phan_tram') ? '-' . (int)$ac['gia_tri'] . '%' : '-' . format_currency($ac['gia_tri']) ?>)</span>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
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
                            <div class="flex items-center justify-between text-secondary">
                                <span class="flex items-center gap-1">
                                    <span>Voucher giảm giá:</span>
                                </span>
                                <span class="font-bold text-sm">-<?= format_currency($discount) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-between">
                            <span class="text-on-surface-variant">Phí vận chuyển dự kiến:</span>
                            <span>
                                <?php if ($shippingFee == 0): ?>
                                    <span class="text-secondary font-bold">Miễn phí</span>
                                <?php else: ?>
                                    <span class="font-semibold"><?= format_currency($shippingFee) ?></span>
                                <?php endif; ?>
                            </span>
                        </div>

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

                        <?php if ($discount > 0): ?>
                            <div class="p-2 rounded-lg bg-tertiary-fixed/60 text-on-tertiary-fixed flex items-center justify-center gap-1.5 text-xs font-semibold mb-4">
                                <span class="material-symbols-outlined text-[16px]">savings</span>
                                <span>Tiết kiệm được <strong class="font-bold"><?= format_currency($discount) ?></strong></span>
                            </div>
                        <?php endif; ?>

                        <!-- Nút Chuyển Tiếp Sang Checkout -->
                        <a href="<?= base_url('thanh-toan') ?>" 
                           class="w-full py-3.5 px-6 rounded-xl bg-primary hover:bg-secondary text-on-primary font-bold text-sm flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transition-all duration-300">
                            <span>TIẾN HÀNH ĐẶT HÀNG</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>

                        <p class="text-[11px] text-center text-on-surface-variant mt-3">
                            Cam kết giao dịch an toàn & bảo mật thông tin chuẩn SSL.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
