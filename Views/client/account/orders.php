<?php
/**
 * Giao diện Quản Lý Lịch Sử Đơn Hàng - DQP Book Store
 * Phong cách: Athenaeum Modern
 */
function getOrderBookCover(?string $fileName, string $title): string {
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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-6">
        <a href="<?= base_url('trang-chu') ?>" class="hover:text-primary transition-colors">Trang chủ</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <a href="<?= base_url('tai-khoan') ?>" class="hover:text-primary transition-colors">Tài khoản độc giả</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-semibold">Lịch sử đơn hàng</span>
    </div>

    <!-- Layout 2 Cột: Sidebar & Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Sidebar Trái -->
        <?php require __DIR__ . '/_sidebar.php'; ?>

        <!-- Khu vực Nội dung Quản lý Đơn hàng (9 Cột) -->
        <main class="lg:col-span-9 space-y-6">
            <!-- Header Banner -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-surface-container flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-primary">Quản Lý & Lịch Sử Đơn Hàng</h1>
                    <p class="text-xs text-on-surface-variant mt-1">Theo dõi hành trình kiện sách, thông tin thanh toán và đánh giá tác phẩm.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs bg-surface-container-low px-3 py-1.5 rounded-full text-secondary font-semibold flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">support_agent</span>
                        <span>Hotline: 1900 6868</span>
                    </span>
                </div>
            </div>

            <!-- Thanh Tabs Trạng Thái -->
            <div class="overflow-x-auto pb-1 scrollbar-none">
                <div class="flex items-center gap-1.5 bg-surface-container-low p-1.5 rounded-xl w-max min-w-full border border-surface-container">
                    <?php 
                    $tabList = [
                        'tat_ca'       => ['label' => 'Tất cả', 'badgeClass' => 'bg-secondary-fixed text-primary'],
                        'cho_xac_nhan' => ['label' => 'Chờ xác nhận', 'badgeClass' => 'bg-amber-100 text-amber-800'],
                        'da_xac_nhan'  => ['label' => 'Đã xác nhận', 'badgeClass' => 'bg-slate-200 text-slate-800'],
                        'dang_giao'    => ['label' => 'Đang giao', 'badgeClass' => 'bg-sky-100 text-sky-800'],
                        'hoan_thanh'   => ['label' => 'Hoàn thành', 'badgeClass' => 'bg-emerald-100 text-emerald-800'],
                        'da_huy'       => ['label' => 'Đã hủy', 'badgeClass' => 'bg-rose-100 text-rose-800']
                    ];
                    foreach ($tabList as $key => $tab): 
                        $isActive = ($currentStatus === $key);
                        $countVal = $counts[$key] ?? 0;
                    ?>
                        <a href="<?= base_url('tai-khoan/don-hang?status=' . $key) ?>" 
                           class="px-4 py-2 rounded-lg text-xs font-bold whitespace-nowrap flex items-center gap-2 transition-all <?= $isActive ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-lowest hover:text-primary' ?>">
                            <span><?= $tab['label'] ?></span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold <?= $isActive ? 'bg-white/20 text-on-primary' : $tab['badgeClass'] ?>">
                                <?= $countVal ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Danh Sách Đơn Hàng -->
            <?php if (empty($orders)): ?>
                <div class="bg-surface-container-lowest rounded-2xl p-12 text-center shadow-sm border border-surface-container">
                    <div class="w-20 h-20 mx-auto rounded-full bg-surface-container-low flex items-center justify-center text-on-surface-variant mb-4">
                        <span class="material-symbols-outlined text-[40px] text-secondary">receipt_long</span>
                    </div>
                    <h3 class="text-base font-bold text-primary mb-1">Không có đơn hàng nào</h3>
                    <p class="text-xs text-on-surface-variant max-w-sm mx-auto mb-6">
                        Bạn chưa có đơn hàng nào thuộc trạng thái này. Hãy tìm kiếm những tác phẩm tinh hoa để bổ sung vào tủ sách của bạn.
                    </p>
                    <a href="<?= base_url('tim-kiem') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-on-primary rounded-xl font-bold text-xs hover:bg-secondary transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">menu_book</span>
                        <span>Khám Phá Sách Ngay</span>
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($orders as $ord): ?>
                        <article class="bg-surface-container-lowest rounded-2xl shadow-sm border border-surface-container overflow-hidden hover:shadow-md transition-shadow">
                            <!-- Header Thẻ Đơn Hàng -->
                            <div class="p-4 sm:p-5 bg-surface-container-low/40 border-b border-surface-container flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-secondary text-[22px]">local_shipping</span>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-bold text-sm text-primary font-mono tracking-wider">#BK-<?= $ord['id'] ?></span>
                                            <span class="text-xs text-on-surface-variant">| Đặt lúc <?= date('H:i - d/m/Y', strtotime($ord['ngay_dat'])) ?></span>
                                        </div>
                                        <p class="text-[11px] text-on-surface-variant mt-0.5">
                                            Người nhận: <strong><?= htmlspecialchars($ord['nguoi_nhan']) ?></strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <?= status_badge($ord['trang_thai']) ?>
                                    <?= payment_status_badge($ord['phuong_thuc'], $ord['trang_thai_thanh_toan']) ?>
                                </div>
                            </div>

                            <!-- Thân Thẻ: Danh sách các cuốn sách trong đơn -->
                            <div class="p-4 sm:p-5 divide-y divide-surface-container-low">
                                <?php foreach ($ord['items'] as $item): 
                                    $coverUrl = getOrderBookCover($item['anh_bia'] ?? null, $item['tieu_de']);
                                ?>
                                    <div class="py-3 first:pt-0 last:pb-0 flex items-center justify-between gap-4 text-xs">
                                        <div class="flex items-center gap-3 min-w-0 flex-1">
                                            <a href="<?= base_url('chi-tiet/' . $item['sach_id']) ?>" class="w-12 h-16 object-cover rounded shadow-2xs flex-shrink-0 overflow-hidden bg-surface-container">
                                                <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($item['tieu_de']) ?>" class="w-full h-full object-cover">
                                            </a>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-bold text-primary text-sm truncate hover:text-secondary transition-colors">
                                                    <a href="<?= base_url('chi-tiet/' . $item['sach_id']) ?>">
                                                        <?= htmlspecialchars($item['tieu_de']) ?>
                                                    </a>
                                                </h4>
                                                <div class="flex items-center gap-2 mt-1 text-[11px] text-on-surface-variant">
                                                    <span>Số lượng: <strong>x<?= $item['so_luong'] ?></strong></span>
                                                    <span>•</span>
                                                    <span>Đơn giá: <?= format_currency($item['don_gia']) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <span class="font-bold text-primary text-sm block">
                                                <?= format_currency($item['don_gia'] * $item['so_luong']) ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Footer Thẻ Đơn Hàng -->
                            <div class="p-4 sm:p-5 bg-surface-container-low/20 border-t border-surface-container flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-xs text-on-surface-variant">Tổng thanh toán:</span>
                                    <span class="text-lg font-bold text-secondary">
                                        <?= format_currency($ord['tong_thanh_toan']) ?>
                                    </span>
                                    <?php if ((float)$ord['tien_giam'] > 0): ?>
                                        <span class="text-[11px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-semibold">
                                            Tiết kiệm <?= format_currency($ord['tien_giam']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- Nút Hủy đơn nếu còn chờ xác nhận -->
                                    <?php if ($ord['trang_thai'] === 'cho_xac_nhan'): ?>
                                        <button type="button" 
                                                onclick="openCancelModal(<?= $ord['id'] ?>)"
                                                class="px-4 py-2 rounded-xl text-xs font-bold text-error bg-error-container/20 hover:bg-error-container/40 transition-colors">
                                            Hủy Đơn Hàng
                                        </button>
                                    <?php endif; ?>

                                    <!-- Nút Đánh giá nếu hoàn thành -->
                                    <?php if ($ord['trang_thai'] === 'hoan_thanh' && !empty($ord['items'][0]['sach_id'])): ?>
                                        <a href="<?= base_url('chi-tiet/' . $ord['items'][0]['sach_id'] . '#reviews-section') ?>" 
                                           class="px-4 py-2 rounded-xl text-xs font-bold text-secondary bg-secondary-container/40 hover:bg-secondary-container transition-colors flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">rate_review</span>
                                            <span>Viết Cảm Nhận</span>
                                        </a>
                                    <?php endif; ?>

                                    <!-- Nút Chi Tiết & Theo Dõi -->
                                    <a href="<?= base_url('tai-khoan/don-hang/chi-tiet/' . $ord['id']) ?>" 
                                       class="px-5 py-2 rounded-xl text-xs font-bold text-on-primary bg-primary hover:bg-secondary transition-colors shadow-xs flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">timeline</span>
                                        <span>Theo Dõi Vận Đơn</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Modal Hủy Đơn Hàng -->
<div id="cancel-order-modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-6 shadow-xl border border-surface-container space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-surface-container">
            <h3 class="font-bold text-base text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-error text-[20px]">warning</span>
                <span>Xác Nhận Hủy Đơn Hàng</span>
            </h3>
            <button onclick="closeCancelModal()" class="text-on-surface-variant hover:text-primary">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="cancel-form" action="" method="POST" class="space-y-4">
            <p class="text-xs text-on-surface-variant leading-relaxed">
                Bạn có chắc chắn muốn hủy đơn hàng này không? Sau khi hủy, số lượng sách sẽ được hoàn lại kho và mã giảm giá sẽ được hoàn lại lượt sử dụng.
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
function openCancelModal(orderId) {
    const modal = document.getElementById('cancel-order-modal');
    const form = document.getElementById('cancel-form');
    form.action = '<?= base_url('tai-khoan/don-hang/huy/') ?>' + orderId;
    modal.classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancel-order-modal').classList.add('hidden');
}
</script>
