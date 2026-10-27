<?php
// Views/admin/orders/index.php
// Quản lý & Xử lý đơn hàng - Athenaeum Modern v2.4
?>

<div class="px-6 py-5 max-w-[1440px] w-full mx-auto flex flex-col gap-5">
    <!-- 1. Breadcrumb & Action Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="flex flex-col gap-1">
            <nav class="flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant">
                <a class="hover:text-primary transition-colors flex items-center gap-1" href="<?= base_url('admin') ?>">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    <span>Bảng điều khiển</span>
                </a>
                <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
                <span class="text-primary font-bold">Quản lý Đơn hàng</span>
            </nav>
            <div class="flex items-baseline gap-2 mt-0.5">
                <h1 class="text-2xl lg:text-3xl font-extrabold text-primary tracking-tight">Quản Lý &amp; Xử Lý Đơn Hàng</h1>
                <span class="hidden sm:inline-block px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[11px] font-bold">
                    Toàn Hệ Thống • v2.4
                </span>
            </div>
            <p class="text-xs text-on-surface-variant">
                Theo dõi chu kỳ thực thi đơn, luồng vận chuyển và lịch sử thay đổi trạng thái tự động ghi log vào CSDL theo thời gian thực.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button onclick="window.print()" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-primary text-xs font-semibold shadow-sm border border-slate-200 transition-all active:scale-95" type="button">
                <span class="material-symbols-outlined text-[18px] text-secondary">file_download</span>
                <span>Xuất / In Báo Cáo</span>
            </button>
            <a href="<?= base_url('admin/don-hang') ?>" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-primary text-xs font-semibold shadow-sm border border-slate-200 transition-all active:scale-95" title="Làm mới">
                <span class="material-symbols-outlined text-[18px] text-amber-600">sync</span>
                <span>Làm Mới</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI Cards (4 Bento Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Tổng đơn hôm nay -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Tổng đơn hôm nay</span>
                <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-primary"><?= number_format($stats['today_orders']) ?></span>
                <span class="text-xs text-secondary font-bold">Đơn mới</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Doanh thu hôm nay:</span>
                <span class="font-bold text-primary"><?= format_currency($stats['today_revenue']) ?></span>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-primary h-full rounded-full" style="width: <?= min(100, max(10, $stats['today_orders'] * 10)) ?>%"></div>
            </div>
        </div>

        <!-- Card 2: Đơn Chờ xác nhận -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Đơn Chờ xác nhận</span>
                <span class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-800">
                    <span class="material-symbols-outlined text-[18px]">pending_actions</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-amber-600"><?= number_format($stats['pending_orders']) ?></span>
                <?php if ($stats['pending_orders'] > 0): ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold animate-pulse">
                        Cần duyệt gấp
                    </span>
                <?php endif; ?>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Tồn đọng &gt; 2 giờ:</span>
                <span class="font-bold <?= $stats['pending_overdue_2h'] > 0 ? 'text-rose-600' : 'text-slate-600' ?>">
                    <?= number_format($stats['pending_overdue_2h']) ?> đơn
                </span>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full" style="width: <?= min(100, max(5, $stats['pending_orders'] * 15)) ?>%"></div>
            </div>
        </div>

        <!-- Card 3: Đang vận chuyển -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-sky-700 uppercase tracking-wider">Đang vận chuyển</span>
                <span class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center text-sky-800">
                    <span class="material-symbols-outlined text-[18px]">local_shipping</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-sky-700"><?= number_format($stats['shipping_orders']) ?></span>
                <span class="text-[11px] text-sky-600 font-semibold">NowShip / GHN</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Luồng bưu cục:</span>
                <span class="font-medium text-primary">Liên tỉnh &amp; Nội thành</span>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-sky-500 h-full rounded-full" style="width: <?= min(100, max(10, $stats['shipping_orders'] * 12)) ?>%"></div>
            </div>
        </div>

        <!-- Card 4: Tỷ lệ giao thành công -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Tỷ lệ giao thành công</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-800">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-emerald-700"><?= $stats['success_rate'] ?>%</span>
                <span class="text-[11px] text-emerald-600 font-bold">Chỉ số uy tín</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Đơn hoàn tất tháng này:</span>
                <span class="font-bold text-primary"><?= number_format($stats['month_completed']) ?> đơn</span>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-emerald-600 h-full rounded-full" style="width: <?= $stats['success_rate'] ?>%"></div>
            </div>
        </div>
    </div>

    <!-- 3. Filter Section & Status Tabs -->
    <div class="flex flex-col gap-3 bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <!-- Status Tabs Bar -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
            <?php
            $tabItems = [
                'tat_ca'       => ['label' => 'Tất cả',        'count' => $counts['tat_ca'],       'badgeBg' => 'bg-slate-200 text-slate-800'],
                'cho_xac_nhan' => ['label' => 'Chờ xác nhận',  'count' => $counts['cho_xac_nhan'], 'badgeBg' => 'bg-amber-100 text-amber-800'],
                'da_xac_nhan'  => ['label' => 'Đã xác nhận',   'count' => $counts['da_xac_nhan'],  'badgeBg' => 'bg-slate-200 text-slate-700'],
                'dang_giao'    => ['label' => 'Đang giao',     'count' => $counts['dang_giao'],    'badgeBg' => 'bg-sky-100 text-sky-800'],
                'hoan_thanh'   => ['label' => 'Hoàn thành',    'count' => $counts['hoan_thanh'],   'badgeBg' => 'bg-emerald-100 text-emerald-800'],
                'da_huy'       => ['label' => 'Đã hủy',        'count' => $counts['da_huy'],       'badgeBg' => 'bg-rose-100 text-rose-800']
            ];
            ?>
            <?php foreach ($tabItems as $tabKey => $tab): ?>
                <?php $isActive = ($currentStatus === $tabKey); ?>
                <a href="<?= base_url('admin/don-hang?status=' . $tabKey . '&q=' . urlencode($search) . '&tu_ngay=' . $dateFrom . '&den_ngay=' . $dateTo . '&phuong_thuc=' . $paymentMethod) ?>" 
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= $isActive ? 'bg-primary text-white shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200' ?>">
                    <span><?= $tab['label'] ?></span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold <?= $isActive ? 'bg-white/20 text-white' : $tab['badgeBg'] ?>">
                        <?= number_format($tab['count']) ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Advanced Filters Form -->
        <form method="GET" action="<?= base_url('admin/don-hang') ?>" class="grid grid-cols-1 md:grid-cols-12 gap-3 pt-2 border-t border-slate-100">
            <input type="hidden" name="status" value="<?= htmlspecialchars($currentStatus) ?>"/>

            <!-- Search field -->
            <div class="md:col-span-4 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                <input name="q" value="<?= htmlspecialchars($search) ?>" class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-on-surface placeholder:text-outline text-xs focus:outline-none focus:bg-white focus:border-secondary transition-all" placeholder="Tìm theo Mã đơn (#BK-...), Tên, SĐT, Địa chỉ..." type="text"/>
            </div>

            <!-- Date range -->
            <div class="md:col-span-4 flex items-center gap-2 bg-slate-50 border border-slate-200 px-3 py-1 rounded-xl">
                <span class="material-symbols-outlined text-[18px] text-slate-400">calendar_today</span>
                <div class="flex items-center gap-1.5 w-full text-xs">
                    <input name="tu_ngay" value="<?= htmlspecialchars($dateFrom) ?>" class="bg-transparent text-xs text-on-surface focus:outline-none w-full cursor-pointer" type="date" title="Từ ngày"/>
                    <span class="text-slate-400 text-[11px]">đến</span>
                    <input name="den_ngay" value="<?= htmlspecialchars($dateTo) ?>" class="bg-transparent text-xs text-on-surface focus:outline-none w-full cursor-pointer" type="date" title="Đến ngày"/>
                </div>
            </div>

            <!-- Payment method -->
            <div class="md:col-span-3 relative">
                <select name="phuong_thuc" onchange="this.form.submit()" class="w-full pl-3 pr-8 py-2 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs appearance-none focus:outline-none focus:bg-white focus:border-secondary cursor-pointer">
                    <option value="">Thanh toán: Tất cả</option>
                    <option value="COD" <?= $paymentMethod === 'COD' ? 'selected' : '' ?>>COD (Thanh toán nhận hàng)</option>
                    <option value="VNPAY" <?= $paymentMethod === 'VNPAY' ? 'selected' : '' ?>>VNPAY (Cổng trực tuyến)</option>
                </select>
                <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[16px] pointer-events-none">unfold_more</span>
            </div>

            <!-- Submit button / Reset -->
            <div class="md:col-span-1 flex items-center gap-1">
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold flex items-center justify-center gap-1 shadow-sm transition-all" title="Lọc kết quả">
                    <span class="material-symbols-outlined text-[16px]">filter_list</span>
                </button>
                <?php if (!empty($search) || !empty($dateFrom) || !empty($dateTo) || !empty($paymentMethod) || $currentStatus !== 'tat_ca'): ?>
                    <a href="<?= base_url('admin/don-hang') ?>" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold flex items-center justify-center transition-all" title="Bỏ lọc">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- 4. Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-on-surface-variant text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Mã đơn</th>
                        <th class="py-3 px-4">Khách hàng &amp; Địa chỉ</th>
                        <th class="py-3 px-3">Thời gian đặt</th>
                        <th class="py-3 px-4">Kiện sách</th>
                        <th class="py-3 px-4 text-right">Tổng tiền</th>
                        <th class="py-3 px-3 text-center">Thanh toán</th>
                        <th class="py-3 px-3 text-center">Trạng thái đơn</th>
                        <th class="py-3 px-4 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-on-surface">
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">receipt_long</span>
                                <p class="font-semibold text-sm">Không tìm thấy đơn hàng nào phù hợp với bộ lọc hiện tại.</p>
                                <a href="<?= base_url('admin/don-hang') ?>" class="inline-block mt-3 text-secondary text-xs font-bold hover:underline">Quay lại danh sách tất cả đơn</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $ord): ?>
                            <?php 
                                $firstItem = $ord['items'][0] ?? null;
                                $itemCount = count($ord['items']);
                                $isVnPay = ($ord['phuong_thuc'] === 'VNPAY');
                                $isPaid = ($ord['trang_thai_thanh_toan'] === 'thanh_cong');
                            ?>
                            <tr class="hover:bg-slate-50/60 transition-colors group" id="order-row-<?= $ord['id'] ?>">
                                <!-- Mã đơn -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <button onclick="openOrderDrawer(<?= $ord['id'] ?>)" class="text-left text-xs font-bold text-secondary hover:underline flex items-center gap-1" type="button">
                                        <span>#BK-<?= $ord['id'] ?></span>
                                        <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                                    </button>
                                    <span class="text-[11px] text-slate-400 block mt-0.5">
                                        <?= !empty($ord['ma_voucher']) ? 'Voucher: ' . htmlspecialchars($ord['ma_voucher']) : 'Trực tuyến' ?>
                                    </span>
                                </td>

                                <!-- Khách hàng & Địa chỉ -->
                                <td class="py-3.5 px-4 min-w-[200px]">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-primary"><?= htmlspecialchars($ord['nguoi_nhan']) ?></span>
                                        <span class="text-[11px] text-slate-500"><?= htmlspecialchars($ord['khach_hang_sdt'] ?? '') ?></span>
                                        <span class="text-[11px] text-slate-400 truncate max-w-[240px]" title="<?= htmlspecialchars($ord['dia_chi_giao']) ?>">
                                            <?= htmlspecialchars($ord['dia_chi_giao']) ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Thời gian đặt -->
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700"><?= date('H:i', strtotime($ord['ngay_dat'])) ?></span>
                                        <span class="text-[11px] text-slate-400"><?= date('d/m/Y', strtotime($ord['ngay_dat'])) ?></span>
                                    </div>
                                </td>

                                <!-- Kiện sách -->
                                <td class="py-3.5 px-4">
                                    <?php if ($firstItem): ?>
                                        <div class="flex items-center gap-2">
                                            <img class="w-8 h-11 object-cover rounded shadow-xs shrink-0 border border-slate-200" 
                                                 src="<?= htmlspecialchars(get_book_cover($firstItem['anh_bia'])) ?>" 
                                                 alt="<?= htmlspecialchars($firstItem['tieu_de']) ?>" loading="lazy"/>
                                            <div class="flex flex-col min-w-0">
                                                <span class="font-semibold text-primary truncate max-w-[180px]" title="<?= htmlspecialchars($firstItem['tieu_de']) ?>">
                                                    <?= htmlspecialchars($firstItem['tieu_de']) ?>
                                                </span>
                                                <span class="text-[11px] text-slate-400">
                                                    <?= (int)$firstItem['so_luong'] ?> cuốn
                                                    <?php if ($itemCount > 1): ?>
                                                        <span class="text-secondary font-bold">(+<?= $itemCount - 1 ?> sách khác)</span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-400">Không có sản phẩm</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Tổng tiền -->
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <span class="font-black text-primary text-sm"><?= format_currency($ord['tong_thanh_toan']) ?></span>
                                    <span class="block text-[10px] text-slate-400">
                                        <?= (float)$ord['phi_van_chuyen'] == 0 ? 'Freeship' : 'Ship: ' . format_currency($ord['phi_van_chuyen']) ?>
                                    </span>
                                </td>

                                <!-- Thanh toán -->
                                <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                    <div class="flex flex-col items-center gap-0.5">
                                        <?php if ($isPaid): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                                                <span class="material-symbols-outlined text-[12px]">check_circle</span> Đã thanh toán
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">
                                                <span class="material-symbols-outlined text-[12px]">schedule</span> Chờ thanh toán
                                            </span>
                                        <?php endif; ?>
                                        <span class="text-[10px] text-slate-500 font-semibold"><?= htmlspecialchars($ord['phuong_thuc']) ?></span>
                                    </div>
                                </td>

                                <!-- Trạng thái đơn -->
                                <td class="py-3.5 px-3 text-center whitespace-nowrap" id="status-badge-container-<?= $ord['id'] ?>">
                                    <?= status_badge($ord['trang_thai']) ?>
                                </td>

                                <!-- Hành động -->
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Quick Status Change Select -->
                                        <div class="relative group/tooltip inline-block">
                                            <select onchange="quickUpdateStatus(<?= $ord['id'] ?>, this.value)" 
                                                    class="bg-slate-100 hover:bg-slate-200 text-primary font-bold text-[11px] px-2.5 py-1.5 rounded-lg border-0 cursor-pointer focus:ring-2 focus:ring-secondary/40 transition-colors">
                                                <option value="cho_xac_nhan" <?= $ord['trang_thai'] === 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                                <option value="da_xac_nhan"  <?= $ord['trang_thai'] === 'da_xac_nhan' ? 'selected' : '' ?>>Đã xác nhận</option>
                                                <option value="dang_giao"    <?= $ord['trang_thai'] === 'dang_giao' ? 'selected' : '' ?>>Đang giao</option>
                                                <option value="hoan_thanh"   <?= $ord['trang_thai'] === 'hoan_thanh' ? 'selected' : '' ?>>Hoàn thành</option>
                                                <option value="da_huy"       <?= $ord['trang_thai'] === 'da_huy' ? 'selected' : '' ?>>Hủy đơn</option>
                                            </select>
                                        </div>

                                        <!-- Action View -->
                                        <button onclick="openOrderDrawer(<?= $ord['id'] ?>)" class="p-1.5 rounded-lg text-slate-500 hover:text-primary hover:bg-slate-100 transition-colors" title="Xem chi tiết đơn" type="button">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </button>

                                        <!-- Action Print -->
                                        <button onclick="printOrder(<?= $ord['id'] ?>)" class="p-1.5 rounded-lg text-slate-500 hover:text-secondary hover:bg-slate-100 transition-colors" title="In phiếu xuất kho / hóa đơn" type="button">
                                            <span class="material-symbols-outlined text-[18px]">print</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <?php if ($totalPages > 1): ?>
            <div class="px-4 py-3 bg-slate-50/60 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-on-surface-variant font-medium">
                <div>
                    <span>Đang hiển thị <strong class="text-primary font-bold"><?= ($page - 1) * $perPage + 1 ?> - <?= min($total, $page * $perPage) ?></strong> trong tổng số <strong class="text-primary font-bold"><?= number_format($total) ?></strong> đơn hàng</span>
                </div>
                <div class="flex items-center gap-1">
                    <?php if ($page > 1): ?>
                        <a href="<?= base_url('admin/don-hang?page=' . ($page - 1) . '&status=' . $currentStatus . '&q=' . urlencode($search) . '&tu_ngay=' . $dateFrom . '&den_ngay=' . $dateTo . '&phuong_thuc=' . $paymentMethod) ?>" 
                           class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-primary flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                        </a>
                    <?php endif; ?>

                    <?php for ($p = max(1, $page - 2); $p <= min($totalPages, $page + 2); $p++): ?>
                        <a href="<?= base_url('admin/don-hang?page=' . $p . '&status=' . $currentStatus . '&q=' . urlencode($search) . '&tu_ngay=' . $dateFrom . '&den_ngay=' . $dateTo . '&phuong_thuc=' . $paymentMethod) ?>" 
                           class="w-8 h-8 rounded-lg <?= $p === $page ? 'bg-primary text-white font-bold shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' ?> flex items-center justify-center transition-colors">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?= base_url('admin/don-hang?page=' . ($page + 1) . '&status=' . $currentStatus . '&q=' . urlencode($search) . '&tu_ngay=' . $dateFrom . '&den_ngay=' . $dateTo . '&phuong_thuc=' . $paymentMethod) ?>" 
                           class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-primary flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 5. DRAWER SLIDE-OVER CHI TIẾT ĐƠN HÀNG NHANH                              -->
<!-- ========================================================================= -->
<div class="fixed inset-0 bg-primary/40 backdrop-blur-[2px] z-50 opacity-0 pointer-events-none transition-opacity duration-300" id="orderDrawerBackdrop" onclick="closeOrderDrawer()"></div>

<aside class="fixed top-0 right-0 h-full w-full max-w-xl bg-white shadow-2xl z-50 translate-x-full transition-transform duration-300 flex flex-col justify-between overflow-hidden" id="orderDrawer">
    <!-- Drawer Header -->
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
        <div class="flex flex-col">
            <div class="flex items-center gap-2">
                <span class="text-lg font-black text-primary" id="drawerOrderId">#BK-...</span>
                <span id="drawerStatusBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold">Chờ xác nhận</span>
            </div>
            <span class="text-xs text-on-surface-variant">Chi tiết kiện hàng và dòng thời gian xử lý đơn hàng</span>
        </div>
        <button class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 hover:text-primary transition-colors" onclick="closeOrderDrawer()" type="button">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>

    <!-- Drawer Content Scrollable -->
    <div class="flex-1 overflow-y-auto p-6 flex flex-col gap-5 text-xs text-on-surface" id="drawerBody">
        <!-- Khung nạp Skeleton / Spinner khi đang load -->
        <div id="drawerLoading" class="py-12 flex flex-col items-center justify-center gap-2 text-slate-400">
            <span class="material-symbols-outlined animate-spin text-3xl text-secondary">progress_activity</span>
            <span>Đang tải thông tin đơn hàng...</span>
        </div>

        <!-- Khung nội dung chi tiết -->
        <div id="drawerDetailContent" class="hidden flex-col gap-5">
            <!-- Thông tin khách hàng & Giao vận -->
            <div class="grid grid-cols-2 gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                <div class="flex flex-col gap-1">
                    <span class="text-[11px] font-bold text-on-surface-variant uppercase">Người nhận hàng</span>
                    <span class="text-sm font-bold text-primary" id="drawerReceiverName">---</span>
                    <span class="text-slate-600" id="drawerReceiverPhone">---</span>
                    <span class="text-slate-400" id="drawerReceiverEmail">---</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-[11px] font-bold text-on-surface-variant uppercase">Địa chỉ &amp; Giao nhận</span>
                    <span class="text-slate-700" id="drawerReceiverAddress">---</span>
                    <div class="mt-1 flex items-center gap-1.5 text-secondary font-bold">
                        <span class="material-symbols-outlined text-[16px]">local_shipping</span>
                        <span>NowShip / GHN Tiêu chuẩn</span>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm trong đơn -->
            <div class="flex flex-col gap-2">
                <span class="text-xs font-bold text-primary uppercase tracking-wide">Danh sách sách trong kiện</span>
                <div class="flex flex-col gap-2 rounded-xl bg-slate-50 p-3 border border-slate-100" id="drawerItemsList">
                    <!-- Dynamic Items injected via JS -->
                </div>
            </div>

            <!-- Tóm tắt thanh toán -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex flex-col gap-2">
                <div class="flex justify-between text-slate-600">
                    <span>Tạm tính tiền hàng:</span>
                    <span class="font-bold text-primary" id="drawerSubtotal">0đ</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Phí vận chuyển:</span>
                    <span class="text-secondary font-semibold" id="drawerShipping">0đ</span>
                </div>
                <div class="flex justify-between text-slate-600" id="drawerDiscountRow">
                    <span>Giảm giá khuyến mãi:</span>
                    <span class="text-rose-600 font-bold" id="drawerDiscount">-0đ</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Phương thức thanh toán:</span>
                    <span class="font-bold text-primary" id="drawerPaymentMethod">COD</span>
                </div>
                <div class="pt-2 border-t border-slate-200 flex justify-between items-baseline">
                    <span class="text-sm font-bold text-primary">Tổng thanh toán:</span>
                    <span class="text-xl font-black text-primary" id="drawerTotal">0đ</span>
                </div>
            </div>

            <!-- Timeline Log (lich_su_don_hang) -->
            <div class="flex flex-col gap-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-primary uppercase tracking-wide">Nhật Ký Xử Lý (lich_su_don_hang)</span>
                    <span class="text-[11px] text-slate-400">Ghi log CSDL tự động</span>
                </div>
                <div class="relative pl-6 flex flex-col gap-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200" id="drawerTimeline">
                    <!-- Dynamic Timeline items injected via JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Drawer Footer Actions -->
    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-2" id="drawerFooter">
        <button onclick="printOrder(currentDrawerOrderId)" class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-primary text-xs font-semibold transition-colors" type="button">
            <span class="material-symbols-outlined text-[18px]">receipt</span>
            <span>In Phiếu Thu</span>
        </button>
        <div class="flex items-center gap-2" id="drawerActionButtons">
            <!-- Dynamic Buttons injected via JS (Xác nhận, Giao hàng, Hủy đơn) -->
        </div>
    </div>
</aside>

<!-- ========================================================================= -->
<!-- 6. TOAST NOTIFICATION THÔNG BÁO TỨC THÌ                                    -->
<!-- ========================================================================= -->
<div class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white shadow-2xl translate-y-20 opacity-0 transition-all duration-300 pointer-events-none" id="statusToast">
    <span class="material-symbols-outlined text-secondary text-[22px]" id="toastIcon">check_circle</span>
    <div class="flex flex-col">
        <span class="text-xs font-bold" id="toastTitle">Đã cập nhật đơn hàng!</span>
        <span class="text-[11px] text-slate-300" id="toastDesc">Hệ thống đã tự động ghi log vào bảng lich_su_don_hang.</span>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 7. JAVASCRIPT XỬ LÝ DRAWER, QUICK UPDATE STATUS & AJAX                    -->
<!-- ========================================================================= -->
<script>
    let currentDrawerOrderId = null;
    let toastTimer = null;

    // Toast hiển thị thông báo
    function showToast(title, desc, isError = false) {
        const toast = document.getElementById('statusToast');
        const toastTitle = document.getElementById('toastTitle');
        const toastDesc = document.getElementById('toastDesc');
        const toastIcon = document.getElementById('toastIcon');

        if (!toast) return;
        toastTitle.textContent = title;
        toastDesc.textContent = desc;

        if (isError) {
            toast.className = "fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-700 text-white shadow-2xl transition-all duration-300 pointer-events-none";
            toastIcon.textContent = "error";
        } else {
            toast.className = "fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white shadow-2xl transition-all duration-300 pointer-events-none";
            toastIcon.textContent = "check_circle";
        }

        toast.classList.remove('translate-y-20', 'opacity-0');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3500);
    }

    // Đổi trạng thái nhanh từ dropdown bảng
    function quickUpdateStatus(orderId, newStatus) {
        fetch('<?= base_url('admin/don-hang/cap-nhat-trang-thai') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                'order_id': orderId,
                'trang_thai': newStatus,
                'ghi_chu': 'Cập nhật trực tiếp qua dropdown quản trị viên'
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(`Đơn hàng #BK-${orderId}`, data.message);
                // Cập nhật lại badge trạng thái trong bảng
                updateRowBadge(orderId, newStatus);
            } else {
                showToast(`Lỗi #BK-${orderId}`, data.message, true);
                setTimeout(() => window.location.reload(), 1500);
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Lỗi kết nối', 'Không thể gửi yêu cầu cập nhật: ' + err, true);
        });
    }

    // Cập nhật hiển thị badge tại chỗ
    function updateRowBadge(orderId, status) {
        const container = document.getElementById('status-badge-container-' + orderId);
        if (!container) return;

        const map = {
            'cho_xac_nhan': { label: 'Chờ xác nhận', bg: 'bg-amber-100', text: 'text-amber-800', icon: 'pending_actions' },
            'da_xac_nhan':  { label: 'Đã xác nhận',  bg: 'bg-slate-200', text: 'text-slate-800', icon: 'inventory' },
            'dang_giao':    { label: 'Đang giao hàng', bg: 'bg-sky-100', text: 'text-sky-800', icon: 'local_shipping' },
            'hoan_thanh':   { label: 'Hoàn thành',   bg: 'bg-emerald-100', text: 'text-emerald-800', icon: 'verified' },
            'da_huy':       { label: 'Đã hủy',       bg: 'bg-rose-100', text: 'text-rose-800', icon: 'cancel' }
        };

        const info = map[status] || { label: status, bg: 'bg-gray-100', text: 'text-gray-800', icon: 'info' };
        container.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[12px] font-semibold ${info.bg} ${info.text} border border-transparent"><span class="material-symbols-outlined text-[14px]">${info.icon}</span>${info.label}</span>`;
    }

    // Mở Slide-over Drawer xem chi tiết đơn
    function openOrderDrawer(orderId) {
        currentDrawerOrderId = orderId;
        const drawer = document.getElementById('orderDrawer');
        const backdrop = document.getElementById('orderDrawerBackdrop');
        const loading = document.getElementById('drawerLoading');
        const content = document.getElementById('drawerDetailContent');
        const orderIdEl = document.getElementById('drawerOrderId');

        if (!drawer || !backdrop) return;
        orderIdEl.textContent = '#BK-' + orderId;
        loading.classList.remove('hidden');
        content.classList.add('hidden');
        content.classList.remove('flex');

        backdrop.classList.remove('opacity-0', 'pointer-events-none');
        drawer.classList.remove('translate-x-full');

        // Nạp dữ liệu JSON từ API detail
        fetch(`<?= base_url('admin/don-hang/chi-tiet/') ?>${orderId}?json=1`)
            .then(res => res.json())
            .then(resData => {
                if (!resData.success || !resData.data) {
                    alert(resData.message || 'Không thể tải chi tiết đơn hàng.');
                    closeOrderDrawer();
                    return;
                }
                renderDrawerData(resData.data);
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi nạp dữ liệu: ' + err);
                closeOrderDrawer();
            });
    }

    // Đổ dữ liệu vào Drawer
    function renderDrawerData(order) {
        const loading = document.getElementById('drawerLoading');
        const content = document.getElementById('drawerDetailContent');
        const badge = document.getElementById('drawerStatusBadge');

        // 1. Badge trạng thái
        const statusMap = {
            'cho_xac_nhan': { label: 'Chờ xác nhận', bg: 'bg-amber-100 text-amber-800' },
            'da_xac_nhan':  { label: 'Đã xác nhận', bg: 'bg-slate-200 text-slate-800' },
            'dang_giao':    { label: 'Đang vận chuyển', bg: 'bg-sky-100 text-sky-800' },
            'hoan_thanh':   { label: 'Giao thành công', bg: 'bg-emerald-100 text-emerald-800' },
            'da_huy':       { label: 'Đã hủy', bg: 'bg-rose-100 text-rose-800' }
        };
        const sInfo = statusMap[order.trang_thai] || { label: order.trang_thai, bg: 'bg-slate-100 text-slate-700' };
        badge.className = `px-2.5 py-0.5 rounded-full text-xs font-bold ${sInfo.bg}`;
        badge.textContent = sInfo.label;

        // 2. Khách hàng & Giao nhận
        document.getElementById('drawerReceiverName').textContent = order.nguoi_nhan || '---';
        document.getElementById('drawerReceiverPhone').textContent = order.khach_hang_sdt || '---';
        document.getElementById('drawerReceiverEmail').textContent = order.khach_hang_email || '---';
        document.getElementById('drawerReceiverAddress').textContent = order.dia_chi_giao || '---';

        // 3. Danh sách sản phẩm
        const itemsList = document.getElementById('drawerItemsList');
        itemsList.innerHTML = '';
        if (order.items && order.items.length > 0) {
            order.items.forEach(it => {
                const itemDiv = document.createElement('div');
                itemDiv.className = "flex items-center justify-between p-2 rounded-lg bg-white border border-slate-100";
                
                let coverUrl = it.anh_bia || '';
                if (!coverUrl.startsWith('http')) {
                    coverUrl = `<?= asset('uploads/books/') ?>` + it.anh_bia;
                }

                itemDiv.innerHTML = `
                    <div class="flex items-center gap-3">
                        <img class="w-10 h-14 object-cover rounded shadow-xs border border-slate-200" 
                             src="${coverUrl}" alt="${it.tieu_de}" onerror="this.src='https://lh3.googleusercontent.com/aida-public/AB6AXuAUz2h5NbRRpQxcVtF5NPJD_iS9y0RXn5uwAPV0D9DJcKseQpT7V-mhEMQrFOtRq9VGbSv5umrMiFHWzcQoyNiuSN0Q5GWTjaPPf7tDrnd0UM41X0iM8jiG6rSV-Qso0bjWxZZwKU3ZW-CMRmhOd2jEnf0IIVCt_vBGUR1D7PQcAHq6Wf1_lJqQj7u5F1ZhpKyX1w15CdFG0PsLVH263eNelWhvV99odJk1qBi7NxYEbClVt4p3Z0vQ'"/>
                        <div class="flex flex-col">
                            <span class="font-bold text-primary max-w-xs truncate">${it.tieu_de}</span>
                            <span class="text-slate-400 text-[11px]">${it.the_loai_ten || 'Sách'}</span>
                            <span class="text-secondary font-bold text-[11px]">Đơn giá: ${formatMoney(it.don_gia)} × ${it.so_luong}</span>
                        </div>
                    </div>
                    <span class="font-bold text-primary">${formatMoney(it.don_gia * it.so_luong)}</span>
                `;
                itemsList.appendChild(itemDiv);
            });
        }

        // 4. Tóm tắt chi phí
        document.getElementById('drawerSubtotal').textContent = formatMoney(order.tong_tien_hang);
        document.getElementById('drawerShipping').textContent = (parseFloat(order.phi_van_chuyen) === 0) ? 'Miễn phí' : formatMoney(order.phi_van_chuyen);
        
        const discountRow = document.getElementById('drawerDiscountRow');
        const discountVal = parseFloat(order.tien_giam || 0);
        if (discountVal > 0) {
            discountRow.classList.remove('hidden');
            discountRow.classList.add('flex');
            document.getElementById('drawerDiscount').textContent = '-' + formatMoney(discountVal);
        } else {
            discountRow.classList.add('hidden');
            discountRow.classList.remove('flex');
        }

        document.getElementById('drawerPaymentMethod').textContent = (order.phuong_thuc || 'COD') + (order.trang_thai_thanh_toan === 'thanh_cong' ? ' (Đã thanh toán)' : ' (Chờ thanh toán)');
        document.getElementById('drawerTotal').textContent = formatMoney(order.tong_thanh_toan);

        // 5. Nhật ký xử lý (lich_su_don_hang timeline)
        const timeline = document.getElementById('drawerTimeline');
        timeline.innerHTML = '';
        if (order.history && order.history.length > 0) {
            order.history.forEach((h, idx) => {
                const isLast = (idx === order.history.length - 1);
                const item = document.createElement('div');
                item.className = "relative flex flex-col gap-1";
                item.innerHTML = `
                    <span class="absolute -left-[19px] top-1 w-3 h-3 rounded-full ${isLast ? 'bg-secondary ring-4 ring-secondary-container' : 'bg-slate-300 ring-4 ring-white'}"></span>
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-primary">${h.trang_thai}</span>
                        <span class="text-[11px] text-slate-400">${h.thoi_gian}</span>
                    </div>
                    <p class="text-slate-600 text-[11px]">${h.ghi_chu || 'Cập nhật tiến trình đơn hàng'}</p>
                `;
                timeline.appendChild(item);
            });
        } else {
            timeline.innerHTML = '<span class="text-slate-400 text-xs">Chưa có nhật ký ghi nhận.</span>';
        }

        // 6. Action buttons in Drawer Footer
        const actionBtns = document.getElementById('drawerActionButtons');
        actionBtns.innerHTML = '';

        if (order.trang_thai !== 'da_huy' && order.trang_thai !== 'hoan_thanh') {
            // Nút Hủy
            const cancelBtn = document.createElement('button');
            cancelBtn.className = "px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition-colors";
            cancelBtn.textContent = "Hủy Đơn";
            cancelBtn.onclick = function() {
                if (confirm(`Bạn có chắc chắn muốn hủy đơn hàng #BK-${order.id}? Tồn kho sẽ tự động được hoàn trả.`)) {
                    quickUpdateStatus(order.id, 'da_huy');
                    closeOrderDrawer();
                }
            };
            actionBtns.appendChild(cancelBtn);
        }

        if (order.trang_thai === 'cho_xac_nhan') {
            const nextBtn = document.createElement('button');
            nextBtn.className = "px-4 py-2 rounded-xl bg-primary hover:bg-primary-container text-white font-bold text-xs transition-all shadow-sm flex items-center gap-1";
            nextBtn.innerHTML = `<span class="material-symbols-outlined text-[16px]">check</span><span>Xác Nhận & Đóng Gói</span>`;
            nextBtn.onclick = function() {
                quickUpdateStatus(order.id, 'da_xac_nhan');
                closeOrderDrawer();
            };
            actionBtns.appendChild(nextBtn);
        } else if (order.trang_thai === 'da_xac_nhan') {
            const shipBtn = document.createElement('button');
            shipBtn.className = "px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs transition-all shadow-sm flex items-center gap-1";
            shipBtn.innerHTML = `<span class="material-symbols-outlined text-[16px]">local_shipping</span><span>Giao Hàng Bưu Cục</span>`;
            shipBtn.onclick = function() {
                quickUpdateStatus(order.id, 'dang_giao');
                closeOrderDrawer();
            };
            actionBtns.appendChild(shipBtn);
        } else if (order.trang_thai === 'dang_giao') {
            const completeBtn = document.createElement('button');
            completeBtn.className = "px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all shadow-sm flex items-center gap-1";
            completeBtn.innerHTML = `<span class="material-symbols-outlined text-[16px]">verified</span><span>Hoàn Tất Giao Đơn</span>`;
            completeBtn.onclick = function() {
                quickUpdateStatus(order.id, 'hoan_thanh');
                closeOrderDrawer();
            };
            actionBtns.appendChild(completeBtn);
        }

        loading.classList.add('hidden');
        content.classList.remove('hidden');
        content.classList.add('flex');
    }

    // Đóng Drawer
    function closeOrderDrawer() {
        const drawer = document.getElementById('orderDrawer');
        const backdrop = document.getElementById('orderDrawerBackdrop');
        if (!drawer || !backdrop) return;
        backdrop.classList.add('opacity-0', 'pointer-events-none');
        drawer.classList.add('translate-x-full');
    }

    function formatMoney(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
    }

    function printOrder(orderId) {
        window.print();
    }
</script>
