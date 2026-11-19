<?php
/**
 * Views/client/account/vouchers.php
 * Giao diện Kho Voucher Cá Nhân Chuẩn Thương Mại Điện Tử (Shopee Style) - DQP Book Store
 * - Có ô nhập mã voucher lưu về ví
 * - Phân loại rõ: Tất Cả, Mã Vận Chuyển (Freeship), Mã Giảm Giá Đơn Hàng, Đã Sử Dụng, Hết Hạn
 * - Thẻ Voucher dạng vé coupon 2 khối viền răng cưa chuẩn E-commerce
 */

$now = time();
$currentTab = $currentTab ?? 'tat_ca';
if ($currentTab === 'kho_voucher') $currentTab = 'tat_ca';
$counts = $counts ?? ['tat_ca' => 0, 'van_chuyen' => 0, 'giam_gia' => 0, 'da_dung' => 0, 'het_han' => 0];
$coupons = $coupons ?? [];
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-6">
        <a href="<?= base_url('trang-chu') ?>" class="hover:text-primary transition-colors">Trang chủ</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <a href="<?= base_url('tai-khoan') ?>" class="hover:text-primary transition-colors">Tài khoản độc giả</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">Kho voucher cá nhân</span>
    </div>

    <!-- Layout 2 Cột: Sidebar & Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Sidebar Trái -->
        <?php require __DIR__ . '/_sidebar.php'; ?>

        <!-- Khu vực Nội dung Kho Voucher (9 Cột) -->
        <main class="lg:col-span-9 space-y-6">
            <!-- Header Banner Shopee Style -->
            <div class="relative overflow-hidden bg-gradient-to-r from-amber-600 via-rose-600 to-primary rounded-3xl p-6 sm:p-8 text-white shadow-lg">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-[11px] font-bold text-amber-200">
                            <span class="material-symbols-outlined text-[16px]">confirmation_number</span>
                            <span>Ví Ưu Đãi Độc Quyền Chuẩn E-Commerce</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Kho Voucher Cá Nhân</h1>
                        <p class="text-xs sm:text-sm text-white/90 max-w-xl leading-relaxed">
                            Mỗi tài khoản sở hữu một kho voucher riêng biệt. Bạn có thể lưu mã từ banner quảng cáo, sự kiện hoặc nhập trực tiếp mã ưu đãi bên dưới để dùng khi thanh toán.
                        </p>
                    </div>
                    <div class="flex sm:flex-col items-center sm:items-end justify-between gap-2 shrink-0 bg-white/15 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/20">
                        <span class="text-xs text-white/80 font-medium">Khả dụng trong kho:</span>
                        <span class="text-2xl font-black text-amber-300"><?= $counts['tat_ca'] ?> voucher</span>
                    </div>
                </div>
            </div>

            <!-- THANH NHẬP MÃ VOUCHER TRỰC TIẾP LƯU VỀ VÍ (SHOPEE STYLE) -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center gap-3">
                <div class="flex items-center gap-2 text-primary font-bold text-xs shrink-0 self-start sm:self-center">
                    <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">sell</span>
                    </span>
                    <span>Thêm Voucher:</span>
                </div>
                <form action="<?= base_url('tai-khoan/luu-voucher') ?>" method="POST" class="flex-1 flex gap-2 w-full">
                    <div class="relative flex-1">
                        <input type="text" name="coupon_code" placeholder="Nhập mã voucher để lưu vào kho (Ví dụ: WELCOME2026, FREESHIP...)" required
                               class="w-full px-4 py-2.5 bg-slate-50 text-primary text-xs font-bold uppercase tracking-wider rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-secondary focus:bg-white transition-all">
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition-all shadow-xs shrink-0 flex items-center gap-1.5 active:scale-95">
                        <span class="material-symbols-outlined text-[16px]">bookmark_add</span>
                        <span>Lưu Vào Ví</span>
                    </button>
                </form>
            </div>

            <!-- TABS PHÂN LOẠI KHO VOUCHER -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-1.5 flex flex-wrap gap-1.5">
                <a href="<?= base_url('tai-khoan/voucher?tab=tat_ca') ?>" 
                   class="flex-1 min-w-[110px] text-center px-3 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 <?= $currentTab === 'tat_ca' ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' ?>">
                    <span class="material-symbols-outlined text-[16px]">apps</span>
                    <span>Tất Cả</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] font-extrabold <?= $currentTab === 'tat_ca' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' ?>"><?= $counts['tat_ca'] ?></span>
                </a>
                <a href="<?= base_url('tai-khoan/voucher?tab=van_chuyen') ?>" 
                   class="flex-1 min-w-[125px] text-center px-3 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 <?= $currentTab === 'van_chuyen' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' ?>">
                    <span class="material-symbols-outlined text-[16px]">local_shipping</span>
                    <span>Vận Chuyển</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] font-extrabold <?= $currentTab === 'van_chuyen' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' ?>"><?= $counts['van_chuyen'] ?></span>
                </a>
                <a href="<?= base_url('tai-khoan/voucher?tab=giam_gia') ?>" 
                   class="flex-1 min-w-[125px] text-center px-3 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 <?= $currentTab === 'giam_gia' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' ?>">
                    <span class="material-symbols-outlined text-[16px]">loyalty</span>
                    <span>Giảm Giá</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] font-extrabold <?= $currentTab === 'giam_gia' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' ?>"><?= $counts['giam_gia'] ?></span>
                </a>
                <a href="<?= base_url('tai-khoan/voucher?tab=da_dung') ?>" 
                   class="flex-1 min-w-[120px] text-center px-3 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 <?= $currentTab === 'da_dung' ? 'bg-slate-700 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' ?>">
                    <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                    <span>Đã Dùng</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] font-extrabold <?= $currentTab === 'da_dung' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' ?>"><?= $counts['da_dung'] ?></span>
                </a>
                <a href="<?= base_url('tai-khoan/voucher?tab=het_han') ?>" 
                   class="flex-1 min-w-[110px] text-center px-3 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 <?= $currentTab === 'het_han' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' ?>">
                    <span class="material-symbols-outlined text-[16px]">history_toggle_off</span>
                    <span>Hết Hạn</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] font-extrabold <?= $currentTab === 'het_han' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-700' ?>"><?= $counts['het_han'] ?></span>
                </a>
            </div>

            <!-- DANH SÁCH VOUCHER THEO TAB -->
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <h2 class="text-sm font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[20px]">
                            <?php 
                            if ($currentTab === 'van_chuyen') echo 'local_shipping';
                            elseif ($currentTab === 'giam_gia') echo 'loyalty';
                            elseif ($currentTab === 'da_dung') echo 'task_alt';
                            elseif ($currentTab === 'het_han') echo 'event_busy';
                            else echo 'confirmation_number';
                            ?>
                        </span>
                        <span>
                            <?php 
                            if ($currentTab === 'van_chuyen') echo "Mã Miễn Phí Vận Chuyển Khả Dụng (" . count($coupons) . ")";
                            elseif ($currentTab === 'giam_gia') echo "Mã Giảm Giá Đơn Hàng Khả Dụng (" . count($coupons) . ")";
                            elseif ($currentTab === 'da_dung') echo "Voucher Đã Sử Dụng (" . count($coupons) . ")";
                            elseif ($currentTab === 'het_han') echo "Voucher Đã Hết Hạn (" . count($coupons) . ")";
                            else echo "Tất Cả Voucher Khả Dụng Trong Kho (" . count($coupons) . ")";
                            ?>
                        </span>
                    </h2>
                    <span class="text-xs text-slate-400">
                        <?= $currentTab === 'da_dung' ? 'Nhấn để xem chi tiết đơn hàng tương ứng' : 'Có thể áp dụng đồng thời mã Freeship + Giảm giá khi đặt hàng' ?>
                    </span>
                </div>

                <?php if (empty($coupons)): ?>
                    <div class="bg-surface-container-lowest rounded-2xl p-12 text-center border border-slate-100 shadow-sm flex flex-col items-center justify-center">
                        <div class="w-16 h-16 rounded-2xl <?= $currentTab === 'da_dung' ? 'bg-slate-100 text-slate-500' : 'bg-amber-100 text-amber-600' ?> flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-[36px]">
                                <?= $currentTab === 'da_dung' ? 'receipt_long' : 'confirmation_number' ?>
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-primary">
                            <?php 
                            if ($currentTab === 'da_dung') echo "Bạn chưa sử dụng voucher nào";
                            elseif ($currentTab === 'het_han') echo "Không có voucher nào hết hạn";
                            elseif ($currentTab === 'van_chuyen') echo "Không có mã miễn phí vận chuyển nào trong kho";
                            elseif ($currentTab === 'giam_gia') echo "Không có mã giảm giá đơn hàng nào trong kho";
                            else echo "Kho voucher của bạn đang trống";
                            ?>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 max-w-md">
                            <?php if ($currentTab === 'da_dung'): ?>
                                Khi bạn áp dụng voucher thành công cho đơn hàng, thông tin đơn hàng và mã giảm giá sẽ được hiển thị tại đây.
                            <?php else: ?>
                                Nhập mã ở ô phía trên hoặc khám phá banner quảng cáo tại trang chủ để lưu các mã ưu đãi độc quyền về kho nhé!
                            <?php endif; ?>
                        </p>
                        <a href="<?= base_url('trang-chu') ?>" class="mt-4 px-5 py-2.5 rounded-xl bg-primary hover:bg-secondary text-white font-bold text-xs shadow-md transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">home</span>
                            <span>Trang chủ săn voucher</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="vouchers-grid">
                        <?php foreach ($coupons as $c):
                            $tinhTrang = $c['tinh_trang'] ?? 'kha_dung';
                            $isUsed = ($currentTab === 'da_dung' || $tinhTrang === 'da_dung');
                            $isExpired = ($currentTab === 'het_han' || $tinhTrang === 'het_han');
                            $isAvailable = (!$isUsed && !$isExpired);
                            $isFreeship = (($c['loai_voucher'] ?? 'giam_gia') === 'mien_phi_van_chuyen');
                            $orderId = !empty($c['don_hang_id']) ? (int)$c['don_hang_id'] : null;

                            // Tông màu vé Shopee
                            if ($isUsed) {
                                $ticketBg = 'bg-slate-50 border-slate-200';
                                $leftBg = 'bg-slate-200 text-slate-600';
                                $badgeColor = 'bg-slate-200 text-slate-700';
                            } elseif ($isExpired) {
                                $ticketBg = 'bg-rose-50/20 border-rose-100 opacity-60';
                                $leftBg = 'bg-rose-100 text-rose-500';
                                $badgeColor = 'bg-rose-100 text-rose-700';
                            } elseif ($isFreeship) {
                                $ticketBg = 'bg-white border-emerald-300 hover:border-emerald-500 hover:shadow-md';
                                $leftBg = 'bg-emerald-600 text-white';
                                $badgeColor = 'bg-emerald-100 text-emerald-800';
                            } else {
                                $ticketBg = 'bg-white border-amber-300 hover:border-amber-500 hover:shadow-md';
                                $leftBg = 'bg-gradient-to-br from-amber-500 to-rose-500 text-white';
                                $badgeColor = 'bg-amber-100 text-amber-800';
                            }
                        ?>
                            <!-- Thẻ Voucher Kiểu Vé Shopee 2 Khối -->
                            <div class="relative rounded-2xl border shadow-sm transition-all overflow-hidden flex <?= $ticketBg ?>">
                                <!-- Khối trái: Logo / Badge loại voucher -->
                                <div class="w-28 sm:w-32 <?= $leftBg ?> p-3.5 flex flex-col items-center justify-center text-center shrink-0 relative">
                                    <span class="material-symbols-outlined text-[30px] mb-1">
                                        <?= $isFreeship ? 'local_shipping' : 'loyalty' ?>
                                    </span>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider block leading-tight">
                                        <?= $isFreeship ? 'Freeship Xtra' : 'Voucher Thư Quán' ?>
                                    </span>
                                    <span class="text-[11px] font-black mt-1 block">
                                        <?= htmlspecialchars($c['text_giam'] ?? '') ?>
                                    </span>

                                    <!-- Vết khuyết vé (Perforated ticket cutouts) -->
                                    <div class="absolute -right-2 top-0 w-4 h-4 rounded-full bg-slate-100"></div>
                                    <div class="absolute -right-2 bottom-0 w-4 h-4 rounded-full bg-slate-100"></div>
                                </div>

                                <!-- Đường ranh giới răng cưa -->
                                <div class="border-r border-dashed border-slate-300 relative"></div>

                                <!-- Khối phải: Thông tin chi tiết, điều kiện & Nút bấm -->
                                <div class="flex-1 p-3.5 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-start justify-between gap-1.5 mb-1">
                                            <span class="font-mono font-extrabold text-xs px-2 py-0.5 rounded bg-slate-100 text-primary border border-slate-200">
                                                <?= htmlspecialchars($c['ma']) ?>
                                            </span>
                                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full <?= $badgeColor ?>">
                                                <?= $isUsed ? 'Đã dùng' : ($isExpired ? 'Hết hạn' : ($isFreeship ? 'Vận chuyển' : 'Giảm tiền hàng')) ?>
                                            </span>
                                        </div>

                                        <h4 class="text-xs font-bold text-primary line-clamp-1 mt-0.5">
                                            <?= htmlspecialchars($c['tieu_de'] ?? $c['text_giam']) ?>
                                        </h4>
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            Đơn tối thiểu: <strong class="text-primary"><?= format_currency($c['don_toi_thieu']) ?></strong>
                                        </p>

                                        <?php if ($isUsed && $orderId): ?>
                                            <!-- Chi tiết đơn hàng đã áp dụng -->
                                            <div class="p-2 rounded-lg bg-slate-100/80 border border-slate-200 text-[10px] space-y-0.5 mt-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-slate-600">Đơn hàng:</span>
                                                    <span class="font-bold text-primary">#BK-<?= $orderId ?></span>
                                                </div>
                                                <?php if (!empty($c['so_tien_da_giam']) && (float)$c['so_tien_da_giam'] > 0): ?>
                                                    <div class="flex items-center justify-between text-secondary">
                                                        <span>Đã giảm:</span>
                                                        <span class="font-bold">-<?= format_currency($c['so_tien_da_giam']) ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($c['ngay_dung'])): ?>
                                                    <div class="flex items-center justify-between text-slate-400">
                                                        <span>Ngày dùng:</span>
                                                        <span><?= date('d/m/Y H:i', strtotime($c['ngay_dung'])) ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-2">
                                                <span>HSD: <?= date('d/m/Y', strtotime($c['ket_thuc'])) ?></span>
                                                <?php if (!empty($c['remain_uses'])): ?>
                                                    <span class="text-rose-600 font-bold">Còn <?= $c['remain_uses'] ?> lượt</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                                        <?php if ($isAvailable): ?>
                                            <button type="button" onclick="copyVoucherCode('<?= htmlspecialchars($c['ma']) ?>')" class="text-[11px] text-slate-400 hover:text-secondary flex items-center gap-0.5 font-semibold" title="Sao chép mã">
                                                <span class="material-symbols-outlined text-[14px]">content_copy</span>
                                                <span>Sao chép</span>
                                            </button>
                                            <a href="<?= base_url('gio-hang') ?>" class="px-3.5 py-1.5 rounded-lg <?= $isFreeship ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-primary hover:bg-secondary' ?> text-white text-[11px] font-bold shadow-2xs transition-all flex items-center gap-1 active:scale-95">
                                                <span class="material-symbols-outlined text-[13px]">shopping_cart</span>
                                                <span>Dùng Ngay</span>
                                            </a>
                                        <?php elseif ($isUsed && $orderId): ?>
                                            <a href="<?= base_url('tai-khoan/don-hang/' . $orderId) ?>" 
                                               class="w-full text-center px-3 py-1.5 rounded-lg bg-primary hover:bg-secondary text-white text-[11px] font-bold shadow-2xs transition-all flex items-center justify-center gap-1 active:scale-95">
                                                <span class="material-symbols-outlined text-[13px]">visibility</span>
                                                <span>Xem Đơn Hàng #BK-<?= $orderId ?></span>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-[11px] text-slate-400 italic">Mã không còn khả dụng</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<script>
function copyVoucherCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        alert(`Đã sao chép mã voucher [ ${code} ]! Bạn có thể dán vào ô giảm giá khi thanh toán.`);
    }).catch(() => {
        alert(`Mã voucher: ${code}`);
    });
}
</script>
