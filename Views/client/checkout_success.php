<?php
/**
 * Giao diện Xác Nhận Đặt Hàng Thành Công - DQP Book Store
 * Phong cách: Athenaeum Modern
 */
function getSuccessBookCover(?string $fileName, string $title): string {
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

<!-- Tiến Trình Mua Sách: Bước 3 Hoàn Tất -->
<section class="w-full bg-surface-container-low py-6 shadow-sm border-b border-surface-container">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between max-w-3xl mx-auto relative">
            <div class="absolute left-8 right-8 top-1/2 -translate-y-1/2 h-1 bg-secondary z-0"></div>

            <!-- Bước 1 -->
            <div class="flex flex-col items-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-secondary text-on-secondary flex items-center justify-center font-bold text-sm shadow-md">
                    <span class="material-symbols-outlined text-[20px]">check</span>
                </div>
                <span class="mt-2 text-xs font-bold text-secondary">1. Giỏ Hàng</span>
            </div>

            <!-- Bước 2 -->
            <div class="flex flex-col items-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-secondary text-on-secondary flex items-center justify-center font-bold text-sm shadow-md">
                    <span class="material-symbols-outlined text-[20px]">check</span>
                </div>
                <span class="mt-2 text-xs font-bold text-secondary">2. Giao Nhận</span>
            </div>

            <!-- Bước 3 (Active Success) -->
            <div class="flex flex-col items-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm shadow-lg ring-4 ring-secondary-container">
                    <span class="material-symbols-outlined text-[20px]">task_alt</span>
                </div>
                <span class="mt-2 text-xs font-bold text-primary">3. Hoàn Tất</span>
            </div>
        </div>
    </div>
</section>

<!-- Khối Xác Nhận Đơn Hàng Thành Công -->
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <div class="bg-surface-container-lowest rounded-2xl p-6 sm:p-10 shadow-sm border border-surface-container">
        
        <!-- Header Chúc Mừng -->
        <div class="text-center pb-8 border-b border-surface-container">
            <div class="w-20 h-20 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 ring-8 ring-emerald-50/50">
                <span class="material-symbols-outlined text-[42px]">check_circle</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-primary mb-2">Đặt Hàng Thành Công!</h1>
            <p class="text-sm text-on-surface-variant max-w-lg mx-auto leading-relaxed">
                Cảm ơn bạn đã tin tưởng lựa chọn <strong>DQP Book Store</strong>. Đơn hàng của bạn đã được ghi nhận vào hệ thống và đang được chuẩn bị để đóng gói gửi đến bạn.
            </p>
            <div class="mt-4 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary-container/40 text-on-secondary-container text-xs font-bold">
                <span>Mã Đơn Hàng:</span>
                <span class="text-primary text-sm font-mono tracking-wider">#BK-<?= $order['id'] ?></span>
            </div>
        </div>

        <!-- Thông Tin Tóm Tắt Đơn Hàng (Bento Grid) -->
        <div class="py-6 border-b border-surface-container grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            <div class="p-3.5 rounded-xl bg-surface-container-low/70">
                <span class="text-on-surface-variant block mb-1">Thời gian đặt:</span>
                <strong class="text-primary text-xs font-semibold block">
                    <?= date('H:i - d/m/Y', strtotime($order['ngay_dat'])) ?>
                </strong>
            </div>

            <div class="p-3.5 rounded-xl bg-surface-container-low/70">
                <span class="text-on-surface-variant block mb-1">Trạng thái đơn:</span>
                <div><?= status_badge($order['trang_thai']) ?></div>
            </div>

            <div class="p-3.5 rounded-xl bg-surface-container-low/70">
                <span class="text-on-surface-variant block mb-1">Phương thức thanh toán:</span>
                <div><?= payment_status_badge($order['phuong_thuc'], $order['trang_thai_thanh_toan']) ?></div>
            </div>

            <div class="p-3.5 rounded-xl bg-surface-container-low/70">
                <span class="text-on-surface-variant block mb-1">Tổng thanh toán:</span>
                <strong class="text-secondary text-sm font-bold block">
                    <?= format_currency($order['tong_thanh_toan']) ?>
                </strong>
            </div>
        </div>

        <!-- Địa Chỉ Nhận Hàng -->
        <div class="py-6 border-b border-surface-container text-xs">
            <h3 class="font-bold text-sm text-primary mb-2 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-secondary">local_shipping</span>
                <span>Thông Tin Người Nhận & Địa Chỉ Giao Hàng</span>
            </h3>
            <div class="p-4 rounded-xl bg-surface-container-low/50 space-y-1">
                <p><strong class="text-primary">Người nhận:</strong> <?= htmlspecialchars($order['nguoi_nhan']) ?></p>
                <p><strong class="text-primary">Địa chỉ giao:</strong> <?= htmlspecialchars($order['dia_chi_giao']) ?></p>
                <?php if (!empty($order['ma_giao_dich'])): ?>
                    <p><strong class="text-primary">Mã giao dịch VNPAY:</strong> <span class="font-mono text-secondary"><?= htmlspecialchars($order['ma_giao_dich']) ?></span></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Danh Sách Sản Phẩm Đã Đặt -->
        <div class="py-6 border-b border-surface-container">
            <h3 class="font-bold text-sm text-primary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-secondary">auto_stories</span>
                <span>Kiện Sách Đã Đặt (<?= count($order['items']) ?> đầu sách)</span>
            </h3>

            <div class="divide-y divide-surface-container-low">
                <?php foreach ($order['items'] as $item): 
                    $coverUrl = getSuccessBookCover($item['anh_bia'] ?? null, $item['tieu_de']);
                ?>
                    <div class="py-3 flex items-center justify-between gap-4 text-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($item['tieu_de']) ?>" class="w-12 h-16 object-cover rounded shadow-xs flex-shrink-0">
                            <div class="min-w-0">
                                <h4 class="font-bold text-primary truncate text-sm"><?= htmlspecialchars($item['tieu_de']) ?></h4>
                                <span class="text-on-surface-variant text-xs">Số lượng: <?= $item['so_luong'] ?> cuốn</span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="font-bold text-primary block"><?= format_currency($item['don_gia'] * $item['so_luong']) ?></span>
                            <span class="text-[11px] text-on-surface-variant"><?= format_currency($item['don_gia']) ?> / cuốn</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Bảng Tổng Kết Chi Phí -->
            <div class="mt-4 p-4 rounded-xl bg-surface-container-low/50 space-y-2 text-xs">
                <div class="flex justify-between text-on-surface-variant">
                    <span>Tổng tiền sách:</span>
                    <span class="font-semibold text-primary"><?= format_currency($order['tong_tien_hang']) ?></span>
                </div>
                <?php if ((float)$order['tien_giam'] > 0): ?>
                    <div class="flex justify-between text-secondary font-semibold">
                        <span>Giảm giá khuyến mãi (<?= htmlspecialchars($order['ma_voucher'] ?? 'Voucher') ?>):</span>
                        <span>-<?= format_currency($order['tien_giam']) ?></span>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between text-on-surface-variant">
                    <span>Phí giao hàng:</span>
                    <span class="font-semibold text-primary">
                        <?= ((float)$order['phi_van_chuyen'] == 0) ? 'Miễn phí' : format_currency($order['phi_van_chuyen']) ?>
                    </span>
                </div>
                <div class="pt-2 border-t border-surface-container flex justify-between text-sm font-bold text-primary">
                    <span>Tổng thanh toán:</span>
                    <span class="text-base text-secondary"><?= format_currency($order['tong_thanh_toan']) ?></span>
                </div>
            </div>
        </div>

        <!-- Các Nút Hành Động -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?= base_url('tai-khoan/don-hang/chi-tiet/' . $order['id']) ?>" 
               class="w-full sm:w-auto px-6 py-3.5 bg-primary hover:bg-secondary text-on-primary rounded-xl font-bold text-xs flex items-center justify-center gap-2 shadow-md transition-colors">
                <span class="material-symbols-outlined text-[18px]">timeline</span>
                <span>Theo Dõi Hành Trình Vận Đơn</span>
            </a>

            <a href="<?= base_url('tai-khoan/don-hang') ?>" 
               class="w-full sm:w-auto px-6 py-3.5 bg-surface-container-low hover:bg-surface-container-high text-primary rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                <span>Xem Tất Cả Đơn Hàng</span>
            </a>

            <a href="<?= base_url('tim-kiem') ?>" 
               class="w-full sm:w-auto px-6 py-3.5 text-secondary hover:text-primary rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                <span>Tiếp Tục Mua Sách</span>
            </a>
        </div>
    </div>
</div>
