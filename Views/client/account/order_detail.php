<?php
/**
 * Giao diện Chi Tiết Đơn Hàng & Timeline Stepper 4 Bước - DQP Book Store
 * Phong cách: Athenaeum Modern
 */
function getOrderDetailBookCover(?string $fileName, string $title): string {
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

// Xây dựng tiến trình Timeline Stepper 4 bước
$steps = [
    'cho_xac_nhan' => [
        'step'  => 1,
        'title' => 'Đã Đặt Đơn',
        'icon'  => 'receipt_long',
        'desc'  => 'Khởi tạo thành công qua website'
    ],
    'da_xac_nhan' => [
        'step'  => 2,
        'title' => 'Xác Nhận & Đóng Gói',
        'icon'  => 'inventory',
        'desc'  => 'Kho Thư Quán kiểm tra & đóng gói'
    ],
    'dang_giao' => [
        'step'  => 3,
        'title' => 'Đang Vận Chuyển',
        'icon'  => 'local_shipping',
        'desc'  => 'Bàn giao cho đơn vị chuyển phát'
    ],
    'hoan_thanh' => [
        'step'  => 4,
        'title' => 'Giao Thành Công',
        'icon'  => 'task_alt',
        'desc'  => 'Kiện sách đã giao tận tay độc giả'
    ]
];

$statusOrder = ['cho_xac_nhan' => 1, 'da_xac_nhan' => 2, 'dang_giao' => 3, 'hoan_thanh' => 4, 'da_huy' => 0];
$currentStepNum = $statusOrder[$order['trang_thai']] ?? 1;

// Gom dữ liệu lịch sử theo trạng thái
$historyMap = [];
if (!empty($order['history'])) {
    foreach ($order['history'] as $h) {
        $historyMap[$h['trang_thai']] = $h;
    }
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-6">
        <a href="<?= base_url('trang-chu') ?>" class="hover:text-primary transition-colors">Trang chủ</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <a href="<?= base_url('tai-khoan') ?>" class="hover:text-primary transition-colors">Tài khoản độc giả</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <a href="<?= base_url('tai-khoan/don-hang') ?>" class="hover:text-primary transition-colors">Lịch sử đơn hàng</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">Chi tiết #BK-<?= $order['id'] ?></span>
    </div>

    <!-- Layout 2 Cột: Sidebar & Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Sidebar Trái -->
        <?php require __DIR__ . '/_sidebar.php'; ?>

        <!-- Khu vực Nội dung Chi tiết Đơn hàng (9 Cột) -->
        <main class="lg:col-span-9 space-y-6">
            <!-- Header Card Đơn Hàng -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-surface-container">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-surface-container">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[24px]">receipt_long</span>
                        </span>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h1 class="text-lg font-bold text-primary font-mono tracking-wider">Đơn Hàng #BK-<?= $order['id'] ?></h1>
                                <span>•</span>
                                <span class="text-xs text-on-surface-variant">Ngày đặt: <?= date('H:i - d/m/Y', strtotime($order['ngay_dat'])) ?></span>
                            </div>
                            <p class="text-xs text-on-surface-variant mt-0.5">Mã tra cứu vận đơn thư viện DQP Book Store</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <?= status_badge($order['trang_thai']) ?>
                        <?= payment_status_badge($order['phuong_thuc'], $order['trang_thai_thanh_toan']) ?>
                    </div>
                </div>

                <!-- Cảnh báo nếu đơn đã hủy -->
                <?php if ($order['trang_thai'] === 'da_huy'): ?>
                    <div class="mt-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-3">
                        <span class="material-symbols-outlined text-rose-600 text-[20px] shrink-0">cancel</span>
                        <div>
                            <strong class="font-bold block text-sm mb-0.5">Đơn hàng này đã bị hủy</strong>
                            <p>
                                <?= htmlspecialchars($historyMap['da_huy']['ghi_chu'] ?? 'Đơn hàng đã được hủy và hoàn trả tồn kho vào hệ thống.') ?>
                            </p>
                            <?php if (!empty($historyMap['da_huy']['thoi_gian'])): ?>
                                <span class="text-[11px] text-rose-600 mt-1 block">Thời gian hủy: <?= date('H:i - d/m/Y', strtotime($historyMap['da_huy']['thoi_gian'])) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- TIMELINE STEPPER 4 BƯỚC THỜI GIAN THỰC -->
                    <div class="pt-6">
                        <h3 class="text-xs font-bold text-primary mb-4 flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="material-symbols-outlined text-secondary text-[16px]">timeline</span>
                            <span>Hành Trình Vận Đơn Trực Tiếp</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 relative">
                            <?php 
                            $stepIndex = 1;
                            foreach ($steps as $stKey => $stData): 
                                $isCompleted = ($currentStepNum > $stData['step']) || ($order['trang_thai'] === 'hoan_thanh');
                                $isActive = ($currentStepNum === $stData['step']);
                                $histItem = $historyMap[$stKey] ?? null;
                            ?>
                                <div class="flex sm:flex-col items-start gap-3 relative p-3 rounded-xl transition-all <?= $isActive ? 'bg-secondary-container/20 border border-secondary/30 ring-1 ring-secondary/20' : 'bg-surface-container-low/40' ?>">
                                    <!-- Icon Step Circle -->
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs shrink-0 shadow-xs <?= $isCompleted ? 'bg-secondary text-on-secondary' : ($isActive ? 'bg-primary text-on-primary ring-4 ring-secondary-container' : 'bg-surface-container-high text-on-surface-variant') ?>">
                                        <?php if ($isCompleted): ?>
                                            <span class="material-symbols-outlined text-[18px]">check</span>
                                        <?php else: ?>
                                            <span class="material-symbols-outlined text-[18px]"><?= $stData['icon'] ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Step Text -->
                                    <div class="min-w-0">
                                        <span class="text-[10px] font-bold uppercase tracking-wider block <?= $isCompleted ? 'text-secondary' : ($isActive ? 'text-primary' : 'text-on-surface-variant') ?>">
                                            Bước <?= $stData['step'] ?> <?= $isActive ? '(Hiện tại)' : '' ?>
                                        </span>
                                        <h4 class="font-bold text-xs text-primary truncate mt-0.5">
                                            <?= $stData['title'] ?>
                                        </h4>
                                        <p class="text-[11px] text-on-surface-variant mt-0.5 line-clamp-2">
                                            <?= htmlspecialchars($histItem['ghi_chu'] ?? $stData['desc']) ?>
                                        </p>
                                        <?php if (!empty($histItem['thoi_gian'])): ?>
                                            <span class="text-[10px] font-semibold text-secondary mt-1 block">
                                                <?= date('H:i - d/m', strtotime($histItem['thoi_gian'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php $stepIndex++; endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Bento 2 Cột: Thông Tin Giao Nhận & Thanh Toán -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Thông tin người nhận -->
                <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-surface-container text-xs space-y-3">
                    <h3 class="font-bold text-sm text-primary flex items-center gap-2 pb-2 border-b border-surface-container">
                        <span class="material-symbols-outlined text-[18px] text-secondary">person_pin</span>
                        <span>Thông Tin Giao Nhận</span>
                    </h3>
                    <p><strong class="text-primary">Người nhận:</strong> <?= htmlspecialchars($order['nguoi_nhan']) ?></p>
                    <p><strong class="text-primary">Địa chỉ giao:</strong> <?= htmlspecialchars($order['dia_chi_giao']) ?></p>
                    <p><strong class="text-primary">Hình thức vận chuyển:</strong> Giao hàng tiêu chuẩn chống va đập</p>
                </div>

                <!-- Thông tin thanh toán -->
                <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-surface-container text-xs space-y-3">
                    <h3 class="font-bold text-sm text-primary flex items-center gap-2 pb-2 border-b border-surface-container">
                        <span class="material-symbols-outlined text-[18px] text-secondary">account_balance_wallet</span>
                        <span>Thông Tin Thanh Toán</span>
                    </h3>
                    <p><strong class="text-primary">Phương thức:</strong> <?= ($order['phuong_thuc'] === 'VNPAY') ? 'Cổng thanh toán điện tử VNPAY (QR/Thẻ)' : 'Thanh toán tiền mặt khi nhận hàng (COD)' ?></p>
                    <p><strong class="text-primary">Trạng thái:</strong> <?= payment_status_badge($order['phuong_thuc'], $order['trang_thai_thanh_toan']) ?></p>
                    <?php if (!empty($order['ma_giao_dich'])): ?>
                        <p><strong class="text-primary">Mã GD VNPAY:</strong> <span class="font-mono text-secondary"><?= htmlspecialchars($order['ma_giao_dich']) ?></span></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Danh Sách Sách Trong Đơn Hàng -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-surface-container">
                <h3 class="font-bold text-sm text-primary mb-4 flex items-center justify-between pb-3 border-b border-surface-container">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-secondary">auto_stories</span>
                        <span>Kiện Sách Đã Đặt (<?= count($order['items']) ?> tác phẩm)</span>
                    </span>
                    <span class="text-xs text-on-surface-variant font-normal">Đóng gói chuẩn hộp thư quán</span>
                </h3>

                <div class="divide-y divide-surface-container-low">
                    <?php foreach ($order['items'] as $item): 
                        $coverUrl = getOrderDetailBookCover($item['anh_bia'] ?? null, $item['tieu_de']);
                    ?>
                        <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4 text-xs">
                            <div class="flex items-center gap-4 min-w-0 flex-1">
                                <a href="<?= base_url('chi-tiet/' . $item['sach_id']) ?>" class="w-14 h-20 rounded-lg overflow-hidden shadow-xs bg-surface-container flex-shrink-0 group">
                                    <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($item['tieu_de']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </a>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] font-bold text-secondary uppercase tracking-wider block">
                                        <?= htmlspecialchars($item['the_loai_ten'] ?? 'Sách Hay') ?>
                                    </span>
                                    <h4 class="font-bold text-sm text-primary hover:text-secondary transition-colors line-clamp-1 mt-0.5">
                                        <a href="<?= base_url('chi-tiet/' . $item['sach_id']) ?>">
                                            <?= htmlspecialchars($item['tieu_de']) ?>
                                        </a>
                                    </h4>
                                    <p class="text-xs text-on-surface-variant mt-0.5">
                                        Tác giả: <?= htmlspecialchars($item['tac_gia_ten'] ?? 'Nhiều tác giả') ?>
                                    </p>
                                    <div class="flex items-center gap-3 mt-1.5">
                                        <span class="text-[11px] text-on-surface-variant">Số lượng: <strong>x<?= $item['so_luong'] ?> cuốn</strong></span>
                                        <?php if ($order['trang_thai'] === 'hoan_thanh'): ?>
                                            <a href="<?= base_url('chi-tiet/' . $item['sach_id'] . '#reviews-section') ?>" class="text-[11px] font-bold text-secondary hover:underline flex items-center gap-0.5">
                                                <span class="material-symbols-outlined text-[13px]">rate_review</span>
                                                <span>Viết đánh giá</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right flex-shrink-0">
                                <span class="font-bold text-sm text-primary block">
                                    <?= format_currency($item['don_gia'] * $item['so_luong']) ?>
                                </span>
                                <span class="text-[11px] text-on-surface-variant"><?= format_currency($item['don_gia']) ?> / cuốn</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Bảng Tổng Chi Phí -->
                <div class="mt-6 p-4 rounded-xl bg-surface-container-low/60 space-y-2 text-xs">
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Tổng tiền hàng:</span>
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

                    <div class="pt-2 border-t border-surface-container flex justify-between text-base font-bold text-primary">
                        <span>Tổng Thanh Toán:</span>
                        <span class="text-lg text-secondary"><?= format_currency($order['tong_thanh_toan']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Nút Hành Động Cuối Trang -->
            <div class="flex flex-wrap items-center justify-between gap-4 pt-2">
                <a href="<?= base_url('tai-khoan/don-hang') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-primary hover:text-secondary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>Quay Lại Danh Sách Đơn Hàng</span>
                </a>

                <div class="flex items-center gap-3">
                    <?php if ($order['trang_thai'] === 'cho_xac_nhan'): ?>
                        <button type="button" 
                                onclick="openCancelModal(<?= $order['id'] ?>)"
                                class="px-5 py-2.5 rounded-xl text-xs font-bold text-error bg-error-container/20 hover:bg-error-container/40 transition-colors">
                            Hủy Đơn Hàng Này
                        </button>
                    <?php endif; ?>

                    <a href="<?= base_url('tim-kiem') ?>" class="px-5 py-2.5 rounded-xl text-xs font-bold text-on-primary bg-primary hover:bg-secondary transition-colors shadow-xs">
                        Tiếp Tục Chọn Sách
                    </a>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal Hủy Đơn Hàng -->
<div id="cancel-order-modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-6 shadow-xl border border-surface-container space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-surface-container">
            <h3 class="font-bold text-base text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-error text-[20px]">warning</span>
                <span>Xác Nhận Hủy Đơn Hàng #BK-<?= $order['id'] ?></span>
            </h3>
            <button onclick="closeCancelModal()" class="text-on-surface-variant hover:text-primary">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="<?= base_url('tai-khoan/don-hang/huy/' . $order['id']) ?>" method="POST" class="space-y-4">
            <p class="text-xs text-on-surface-variant leading-relaxed">
                Sau khi hủy, số lượng sách sẽ được hoàn lại kho và mã giảm giá sẽ được hoàn lại lượt sử dụng.
            </p>

            <div>
                <label class="block text-xs font-bold text-primary mb-1">Lý do hủy đơn *</label>
                <select name="ly_do_huy" required class="w-full px-3 py-2 bg-surface-container-low rounded-lg text-xs text-primary border border-surface-container focus:outline-none focus:ring-2 focus:ring-secondary">
                    <option value="Tôi muốn thay đổi địa chỉ giao hàng">Tôi muốn thay đổi địa chỉ giao hàng</option>
                    <option value="Tôi muốn đổi phương thức thanh toán">Tôi muốn đổi phương thức thanh toán</option>
                    <option value="Tôi tìm thấy giá tốt hơn ở nơi khác">Tôi tìm thấy giá tốt hơn ở nơi khác</option>
                    <option value="Tôi đổi ý, không có nhu cầu mua nữa">Tôi đổi ý, không có nhu cầu mua nữa</option>
                    <option value="Khác">Lý do khác...</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeCancelModal()" class="px-4 py-2 rounded-xl bg-surface-container-low text-primary text-xs font-bold hover:bg-surface-container-high transition-colors">
                    Đóng
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-error text-white text-xs font-bold hover:bg-rose-700 transition-colors shadow-sm">
                    Xác Nhận Hủy
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCancelModal() {
    document.getElementById('cancel-order-modal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancel-order-modal').classList.add('hidden');
}
</script>
