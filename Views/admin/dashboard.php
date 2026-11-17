<?php
/**
 * Views/admin/dashboard.php
 * Bảng Điều Khiển Quản Trị Hệ Thống DQP Book Store
 * Design System: Athenaeum Modern v2.4 (Tuân thủ t_ng_quan_qu_n_tr_bookstore_admin_1)
 *
 * @var float $totalRevenue
 * @var float $totalAllRevenue
 * @var int $totalOrders
 * @var int $pendingOrdersCount
 * @var int $shippingOrdersCount
 * @var int $completedOrdersCount
 * @var int $cancelledOrdersCount
 * @var int $totalBooksSold
 * @var int $totalStock
 * @var int $totalBooks
 * @var int $totalUsers
 * @var int $lowStockCount
 * @var array $chartPoints
 * @var string $chartRange
 * @var array $currentChartDataset
 * @var array $allChartDatasets
 * @var bool $hasHistoricalOrders
 * @var array $categoryBreakdown
 * @var array $recentOrders
 * @var array $topBooks
 * @var array $pendingReviews
 * @var array $lowStockBooks
 */
?>

<div class="space-y-6">
    <!-- TOP BRANCH ALERT & STATUS SUB-HEADER -->
    <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4 min-w-0">
            <div class="w-12 h-12 rounded-2xl bg-primary-container text-white flex items-center justify-center shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">domain</span>
            </div>
            <div class="flex flex-col min-w-0">
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-bold text-primary truncate">Chi Nhánh Trung Tâm TP.HCM</h1>
                    <span class="px-2 py-0.5 rounded-full bg-secondary text-white text-[11px] font-bold uppercase tracking-wider">Trụ sở chính</span>
                </div>
                <p class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                    Cập nhật trực tiếp hệ thống POS & E-Commerce • Kho Tổng Quận 1
                </p>
            </div>
        </div>

        <!-- Real-time Status Badges & Quick Action -->
        <div class="flex items-center flex-wrap gap-2.5">
            <div class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-100 text-slate-700 shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-secondary"></span>
                </span>
                <span class="text-xs font-bold text-secondary"><?= $pendingOrdersCount ?> đơn mới</span>
                <span class="text-xs text-slate-400">cần xác nhận</span>
            </div>

            <div class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">notification_important</span>
                <span class="text-xs font-bold"><?= $lowStockCount ?> tựa sách</span>
                <span class="text-xs text-rose-500">tồn kho thấp</span>
            </div>

            <div class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-primary text-white shadow-sm">
                <span class="material-symbols-outlined text-[18px] text-secondary-fixed">shield_person</span>
                <span class="text-[11px] font-bold uppercase tracking-wider text-secondary-fixed">Super Admin</span>
            </div>
        </div>
    </div>

    <!-- SECTION 1: 4 BENTO KPI METRIC CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <!-- Metric 1: Total Revenue -->
        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex flex-col justify-between hover:-translate-y-1 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Doanh Thu Thực Tế</span>
                <div class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">payments</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-extrabold text-primary tracking-tight">
                    <?= format_currency($totalRevenue) ?>
                </div>
                <div class="flex items-center justify-between mt-2 text-xs">
                    <span class="inline-flex items-center font-bold text-secondary">
                        <span class="material-symbols-outlined text-[16px]">trending_up</span>+18.4%
                    </span>
                    <span class="text-slate-400 truncate">Tổng đơn: <?= format_currency($totalAllRevenue) ?></span>
                </div>
            </div>
        </div>

        <!-- Metric 2: New Orders -->
        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex flex-col justify-between hover:-translate-y-1 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tổng Đơn Hàng</span>
                <div class="w-10 h-10 rounded-xl bg-primary-container/10 text-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">shopping_bag</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-extrabold text-primary tracking-tight">
                    <?= number_format($totalOrders) ?> <span class="text-xs font-normal text-slate-400">đơn</span>
                </div>
                <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                    <span class="px-2 py-0.5 rounded-md text-[11px] bg-amber-100 text-amber-800 font-bold">
                        <?= $pendingOrdersCount ?> chờ duyệt
                    </span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] bg-sky-100 text-sky-800 font-bold">
                        <?= $shippingOrdersCount ?> đang giao
                    </span>
                </div>
            </div>
        </div>

        <!-- Metric 3: Books Sold -->
        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex flex-col justify-between hover:-translate-y-1 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sách Đã Bán Ra</span>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">auto_stories</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-extrabold text-primary tracking-tight">
                    <?= number_format($totalBooksSold) ?> <span class="text-xs font-normal text-slate-400">cuốn</span>
                </div>
                <div class="flex items-center justify-between mt-2 text-xs">
                    <span class="inline-flex items-center font-bold text-secondary">
                        <span class="material-symbols-outlined text-[16px]">trending_up</span>+24.1%
                    </span>
                    <span class="text-slate-400">Đơn hoàn tất</span>
                </div>
            </div>
        </div>

        <!-- Metric 4: Inventory Health -->
        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex flex-col justify-between hover:-translate-y-1 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tồn Kho Hệ Thống</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">inventory_2</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-extrabold text-primary tracking-tight">
                    <?= number_format($totalStock) ?> <span class="text-xs font-normal text-slate-400">cuốn</span>
                </div>
                <div class="flex items-center gap-1.5 mt-2">
                    <span class="px-2 py-0.5 rounded-md text-[11px] bg-rose-100 text-rose-800 font-bold inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">warning</span><?= $lowStockCount ?> tựa cần nhập thêm
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: ANALYTICS & VISUALIZATIONS GRID (2/3 + 1/3) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        <!-- 2/3 Revenue Chart -->
        <div class="lg:col-span-2 p-6 rounded-2xl bg-white border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">analytics</span>
                        <h2 class="text-base font-bold text-primary">Doanh Thu & Biến Động Đơn Hàng</h2>
                        <span id="chartRangeBadge" class="text-xs px-2.5 py-0.5 rounded-full bg-secondary/10 text-secondary font-bold">
                            <?= $chartRange === '7days' ? '7 Ngày Qua' : ($chartRange === '30days' ? '30 Ngày Qua' : ($chartRange === 'year' ? 'Năm ' . date('Y') : 'Mốc Có Đơn')) ?>
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">Tiến trình doanh thu và số lượng đơn hàng ghi nhận thực tế từ hệ thống</p>
                </div>
                
                <!-- Bộ lọc phạm vi thời gian -->
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-bold text-slate-600">
                    <button type="button" onclick="switchChartRange('7days')" id="btn-7days" 
                            class="range-btn px-3 py-1.5 rounded-lg transition-all <?= $chartRange === '7days' ? 'bg-white text-primary shadow-sm font-bold' : 'hover:text-primary' ?>">
                        7 Ngày Qua
                    </button>
                    <button type="button" onclick="switchChartRange('30days')" id="btn-30days" 
                            class="range-btn px-3 py-1.5 rounded-lg transition-all <?= $chartRange === '30days' ? 'bg-white text-primary shadow-sm font-bold' : 'hover:text-primary' ?>">
                        30 Ngày
                    </button>
                    <button type="button" onclick="switchChartRange('year')" id="btn-year" 
                            class="range-btn px-3 py-1.5 rounded-lg transition-all <?= $chartRange === 'year' ? 'bg-white text-primary shadow-sm font-bold' : 'hover:text-primary' ?>">
                        Năm <?= date('Y') ?>
                    </button>
                    <?php if (!empty($hasHistoricalOrders)): ?>
                    <button type="button" onclick="switchChartRange('active')" id="btn-active" 
                            class="range-btn px-3 py-1.5 rounded-lg transition-all <?= $chartRange === 'active' ? 'bg-white text-primary shadow-sm font-bold' : 'hover:text-primary' ?>"
                            title="Xem các mốc phát sinh đơn hàng thực tế">
                        Mốc Có Đơn
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Vùng hiển thị Biểu đồ (Dual Mode: Chart.js Canvas + Dynamic SVG Fallback) -->
            <div class="relative w-full h-72 flex flex-col justify-end">
                <!-- Floating Milestone Callout Tag -->
                <div id="chartCallout" class="absolute right-2 top-0 bg-primary text-white px-3.5 py-2 rounded-xl shadow-lg flex items-center gap-2 z-10 border border-white/10 transition-all">
                    <span class="material-symbols-outlined text-[18px] text-secondary-fixed">workspace_premium</span>
                    <div class="flex flex-col text-left">
                        <span id="calloutTitle" class="text-[10px] font-bold text-secondary-fixed uppercase tracking-wider">
                            <?php if (!empty($currentChartDataset['peakPoint']) && ($currentChartDataset['peakPoint']['rev'] > 0 || $currentChartDataset['peakPoint']['orders'] > 0)): ?>
                                Mốc Cao Nhất (<?= htmlspecialchars($currentChartDataset['peakPoint']['short']) ?>)
                            <?php else: ?>
                                Trạng Thái Ghi Nhận
                            <?php endif; ?>
                        </span>
                        <span id="calloutValue" class="text-xs font-bold text-white">
                            <?php if (!empty($currentChartDataset['peakPoint']) && ($currentChartDataset['peakPoint']['rev'] > 0 || $currentChartDataset['peakPoint']['orders'] > 0)): ?>
                                <?= format_currency($currentChartDataset['peakPoint']['rev']) ?> • <?= $currentChartDataset['peakPoint']['orders'] ?> đơn
                            <?php else: ?>
                                0đ • 0 đơn
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <!-- Interactive Canvas for Chart.js -->
                <div id="canvasChartWrap" class="w-full h-full pt-8">
                    <canvas id="revenueOrdersChartCanvas" class="w-full h-full"></canvas>
                </div>

                <!-- Fallback Dynamic SVG Graph (Hiển thị khi offline hoặc thư viện chưa tải) -->
                <div id="svgChartWrap" class="hidden w-full h-full relative flex flex-col justify-end pt-8">
                    <!-- Background Axis Grid Lines -->
                    <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-20">
                        <div class="w-full h-px bg-slate-400"></div>
                        <div class="w-full h-px bg-slate-400"></div>
                        <div class="w-full h-px bg-slate-400"></div>
                        <div class="w-full h-px bg-slate-400"></div>
                    </div>

                    <svg id="svgChart" class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 760 200">
                        <defs>
                            <linearGradient id="revenueGlowDynamic" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#006A61" stop-opacity="0.35"></stop>
                                <stop offset="100%" stop-color="#006A61" stop-opacity="0.0"></stop>
                            </linearGradient>
                        </defs>

                        <!-- Dynamic Volume Bars -->
                        <g id="svgBars">
                            <?php foreach ($currentChartDataset['points'] as $i => $pt): 
                                $color = !empty($pt['milestone']) ? '#006A61' : (!empty($pt['is_today']) ? '#86F2E4' : '#E2E8F0');
                                $yBar = 180 - $pt['bar_h'];
                            ?>
                                <rect x="<?= $pt['x'] - 10 ?>" y="<?= $yBar ?>" width="20" height="<?= $pt['bar_h'] ?>" rx="4" fill="<?= $color ?>" opacity="0.85">
                                    <title><?= htmlspecialchars($pt['label']) ?>: <?= format_currency($pt['rev']) ?> (<?= $pt['orders'] ?> đơn)</title>
                                </rect>
                            <?php endforeach; ?>
                        </g>

                        <!-- Dynamic Area Fill Path -->
                        <path id="svgAreaPath" d="<?= $currentChartDataset['svgAreaPath'] ?>" fill="url(#revenueGlowDynamic)"></path>

                        <!-- Dynamic Line Stroke Curve -->
                        <path id="svgLinePath" d="<?= $currentChartDataset['svgPath'] ?>" fill="none" stroke="#006A61" stroke-linecap="round" stroke-width="3.5"></path>

                        <!-- Dynamic Milestone Dots -->
                        <g id="svgDots">
                            <?php foreach ($currentChartDataset['points'] as $pt): ?>
                                <?php if (!empty($pt['milestone'])): ?>
                                    <circle cx="<?= $pt['x'] ?>" cy="<?= $pt['y'] ?>" r="6.5" fill="#006A61" stroke="#ffffff" stroke-width="2.5">
                                        <title><?= htmlspecialchars($pt['label']) ?> (Đỉnh cao): <?= format_currency($pt['rev']) ?></title>
                                    </circle>
                                <?php elseif (!empty($pt['is_today'])): ?>
                                    <circle cx="<?= $pt['x'] ?>" cy="<?= $pt['y'] ?>" r="5" fill="#86F2E4" stroke="#006A61" stroke-width="2">
                                        <title><?= htmlspecialchars($pt['label']) ?>: <?= format_currency($pt['rev']) ?></title>
                                    </circle>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </g>
                    </svg>

                    <!-- X-Axis Labels (Fallback SVG) -->
                    <div id="svgXLabels" class="flex items-center justify-between pt-3 text-xs font-semibold text-slate-500 border-t border-slate-100 mt-2">
                        <?php foreach ($currentChartDataset['points'] as $pt): ?>
                            <span class="<?= !empty($pt['milestone']) ? 'text-secondary font-bold' : '' ?>">
                                <?= htmlspecialchars($pt['short']) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Bottom Summary Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-slate-100 mt-3 text-xs">
                <div class="flex flex-col">
                    <span class="text-slate-400 font-medium">Doanh thu kỳ này</span>
                    <span id="summaryPeriodRev" class="font-extrabold text-primary text-sm mt-0.5">
                        <?= format_currency(array_sum(array_column($currentChartDataset['points'], 'rev'))) ?>
                    </span>
                </div>
                <div class="flex flex-col">
                    <span class="text-slate-400 font-medium">Tổng đơn hợp lệ</span>
                    <span id="summaryPeriodOrders" class="font-extrabold text-primary text-sm mt-0.5">
                        <?= number_format(array_sum(array_column($currentChartDataset['points'], 'orders'))) ?> đơn
                    </span>
                </div>
                <div class="flex flex-col">
                    <span class="text-slate-400 font-medium">Giá trị TB/Đơn (AOV)</span>
                    <span id="summaryPeriodAov" class="font-extrabold text-secondary text-sm mt-0.5">
                        <?php
                        $sumRev = array_sum(array_column($currentChartDataset['points'], 'rev'));
                        $sumOrders = array_sum(array_column($currentChartDataset['points'], 'orders'));
                        echo format_currency($sumOrders > 0 ? ($sumRev / $sumOrders) : 0);
                        ?>
                    </span>
                </div>
                <div class="flex flex-col">
                    <span class="text-slate-400 font-medium">Mốc cao nhất kỳ</span>
                    <span id="summaryPeriodPeak" class="font-bold text-slate-700 text-xs mt-0.5 truncate">
                        <?php if (!empty($currentChartDataset['peakPoint']) && ($currentChartDataset['peakPoint']['rev'] > 0 || $currentChartDataset['peakPoint']['orders'] > 0)): ?>
                            <?= htmlspecialchars($currentChartDataset['peakPoint']['short']) ?>: <?= format_currency($currentChartDataset['peakPoint']['rev']) ?>
                        <?php else: ?>
                            Chưa có đơn
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- 1/3 Donut Chart: Category Revenue Breakdown -->
        <div class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-base font-bold text-primary">Cơ Cấu Thể Loại</h2>
                    <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700">Tỷ Trọng</span>
                </div>
                <p class="text-xs text-slate-400">Doanh số phân bổ theo các nhóm ấn phẩm</p>
            </div>

            <!-- Donut SVG -->
            <div class="relative flex items-center justify-center my-4">
                <svg class="w-44 h-44 -rotate-90" viewBox="0 0 100 100">
                    <!-- Background Circle -->
                    <circle cx="50" cy="50" fill="none" r="38" stroke="#F1F5F9" stroke-width="14"></circle>

                    <!-- Segment Arcs -->
                    <?php foreach ($categoryBreakdown as $cat): ?>
                        <circle cx="50" cy="50" fill="none" r="38" 
                                stroke="<?= $cat['color']['stroke'] ?>" 
                                stroke-dasharray="<?= $cat['dasharray'] ?>" 
                                stroke-dashoffset="<?= $cat['dashoffset'] ?>" 
                                stroke-width="14" 
                                class="transition-all duration-500 hover:opacity-80">
                        </circle>
                    <?php endforeach; ?>
                </svg>

                <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                    <span class="text-xl font-extrabold text-primary">100%</span>
                    <span class="text-[11px] font-medium text-slate-400"><?= count($categoryBreakdown) ?> Nhóm Ngành</span>
                </div>
            </div>

            <!-- Legend List -->
            <div class="space-y-2.5 pt-2 border-t border-slate-100">
                <?php foreach ($categoryBreakdown as $cat): ?>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-slate-700 truncate max-w-[180px]">
                            <span class="w-2.5 h-2.5 rounded-full <?= $cat['color']['bg'] ?> shrink-0"></span>
                            <span class="truncate"><?= htmlspecialchars($cat['ten']) ?></span>
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 text-[11px]">(<?= $cat['tong_sach'] ?> tựa)</span>
                            <span class="font-bold text-primary"><?= $cat['percent'] ?>%</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- SECTION 3: RECENT ORDERS TABLE (ĐƠN HÀNG MỚI CẦN XỬ LÝ) -->
    <div class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-primary">Đơn Hàng Mới Cần Xử Lý</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold">
                        <?= $pendingOrdersCount ?> Đơn Chờ Duyệt
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">Xác nhận và chuẩn bị đóng gói giao hàng trong vòng 2 giờ</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="<?= base_url('admin/don-hang') ?>" class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors text-xs font-bold flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                    Xem Toàn Bộ Đơn Hàng
                </a>
            </div>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="py-3 px-4 rounded-l-xl">Mã Đơn</th>
                        <th class="py-3 px-4">Khách Hàng</th>
                        <th class="py-3 px-4">Ngày Đặt</th>
                        <th class="py-3 px-4">Tổng Thanh Toán</th>
                        <th class="py-3 px-4">Phương Thức</th>
                        <th class="py-3 px-4">Trạng Thái</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 font-medium">
                    <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Chưa có đơn hàng nào trong hệ thống.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-primary">
                                    #BK-<?= $order['id'] ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800"><?= htmlspecialchars($order['nguoi_nhan']) ?></span>
                                        <span class="text-[11px] text-slate-400"><?= htmlspecialchars($order['user_phone'] ?? 'Chưa cập nhật') ?></span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500">
                                    <?= date('H:i - d/m/Y', strtotime($order['ngay_dat'])) ?>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-secondary text-sm">
                                    <?= format_currency($order['tong_thanh_toan']) ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700">
                                        <?= htmlspecialchars($order['payment_method'] ?? 'COD') ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <?= status_badge($order['trang_thai']) ?>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="<?= base_url('tai-khoan/don-hang/chi-tiet/' . $order['id']) ?>" 
                                           class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:text-primary hover:bg-slate-200 transition-colors" 
                                           title="Xem chi tiết & Timeline vận chuyển">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </a>
                                        <?php if ($order['trang_thai'] === 'cho_xac_nhan'): ?>
                                            <a href="<?= base_url('admin/don-hang?id=' . $order['id']) ?>" 
                                               class="px-2.5 py-1 rounded-lg bg-secondary text-white hover:bg-secondary-fixed hover:text-primary transition-colors text-[11px] font-bold">
                                                Xử lý
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 4: TOP 5 BEST-SELLING BOOKS & QUICK MODULES (2/3 + 1/3) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <!-- 2/3 Top 5 Best-Selling Books Table -->
        <div class="lg:col-span-2 p-6 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[20px]">leaderboard</span>
                        Top 5 Tựa Sách Bán Chạy Nhất
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Thống kê theo số lượng ấn phẩm tiêu thụ và tổng doanh thu</p>
                </div>
                <a href="<?= base_url('admin/sach') ?>" class="text-secondary hover:text-primary text-xs font-bold flex items-center gap-1">
                    Xem toàn bộ sách <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="py-3 px-3 rounded-l-xl">Ấn Phẩm</th>
                            <th class="py-3 px-3">Thể Loại</th>
                            <th class="py-3 px-3 text-center">Đã Bán</th>
                            <th class="py-3 px-3 text-right">Doanh Thu</th>
                            <th class="py-3 px-3 text-right rounded-r-xl">Tồn Kho</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-slate-100">
                        <?php foreach ($topBooks as $b): ?>
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-3">
                                        <img src="<?= htmlspecialchars(get_book_cover($b['anh_bia'])) ?>" 
                                             alt="<?= htmlspecialchars($b['tieu_de']) ?>" 
                                             class="w-10 h-14 object-cover rounded shadow-sm shrink-0 border border-slate-100"/>
                                        <div class="flex flex-col min-w-0">
                                            <a href="<?= base_url('chi-tiet/' . $b['id']) ?>" target="_blank" class="font-bold text-slate-800 hover:text-secondary truncate max-w-[220px]">
                                                <?= htmlspecialchars($b['tieu_de']) ?>
                                            </a>
                                            <span class="text-[11px] text-slate-400 truncate">
                                                <?= htmlspecialchars($b['tac_gia_ten'] ?? 'Tác giả tuyển chọn') ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-slate-600">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-medium">
                                        <?= htmlspecialchars($b['the_loai_ten'] ?? 'Chung') ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center font-bold text-secondary text-sm">
                                    <?= number_format($b['da_ban']) ?> <span class="text-[11px] font-normal text-slate-400">bản</span>
                                </td>
                                <td class="py-3 px-3 text-right font-bold text-primary">
                                    <?= format_currency($b['doanh_thu']) ?>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <?php if ($b['so_luong_ton'] <= 25): ?>
                                        <span class="px-2 py-0.5 rounded-md bg-rose-100 text-rose-800 font-bold text-[11px]">
                                            <?= $b['so_luong_ton'] ?> (Sắp hết)
                                        </span>
                                    <?php else: ?>
                                        <span class="font-bold text-slate-700"><?= $b['so_luong_ton'] ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 1/3 Side Action Modules -->
        <div class="space-y-6">
            <!-- Module A: Review Moderation -->
            <div class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-secondary">reviews</span>
                        <h3 class="text-sm font-bold text-primary">Duyệt Đánh Giá Khách Hàng</h3>
                    </div>
                    <a href="<?= base_url('admin/danh-gia') ?>" class="text-[11px] font-bold text-secondary hover:underline">
                        Quản lý
                    </a>
                </div>
                <p class="text-xs text-slate-400">Phản hồi từ độc giả gửi lên hệ thống</p>

                <div class="space-y-3">
                    <?php if (empty($pendingReviews)): ?>
                        <div class="p-4 rounded-xl bg-slate-50 text-center text-xs text-slate-400">
                            Không có đánh giá mới nào cần duyệt.
                        </div>
                    <?php else: ?>
                        <?php foreach ($pendingReviews as $rev): ?>
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-800"><?= htmlspecialchars($rev['ho_ten']) ?></span>
                                    <div class="flex text-amber-500 text-[14px]">
                                        <?php for ($s = 1; $s <= 5; $s++): ?>
                                            <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' <?= $s <= $rev['so_sao'] ? 1 : 0 ?>;">star</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 line-clamp-2 italic">
                                    "<?= htmlspecialchars($rev['noi_dung'] ?? 'Rất hài lòng về chất lượng sách và dịch vụ.') ?>"
                                </p>
                                <div class="text-[11px] font-bold text-secondary truncate">
                                    Ấn phẩm: <?= htmlspecialchars($rev['book_title']) ?>
                                </div>
                                <div class="flex items-center justify-between pt-1">
                                    <?= status_badge($rev['trang_thai']) ?>
                                    <a href="<?= base_url('admin/danh-gia') ?>" class="text-[11px] font-bold text-slate-600 hover:text-primary">
                                        Chi tiết &rarr;
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Module B: Low Stock Alert Card -->
            <div class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-amber-600">inventory_2</span>
                        <h3 class="text-sm font-bold text-primary">Cảnh Báo Tồn Kho Thấp</h3>
                    </div>
                    <a href="<?= base_url('admin/kho') ?>" class="text-[11px] font-bold text-secondary hover:underline">
                        Lập phiếu kho
                    </a>
                </div>

                <div class="space-y-3">
                    <?php foreach ($lowStockBooks as $lb): ?>
                        <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <img src="<?= htmlspecialchars(get_book_cover($lb['anh_bia'])) ?>" alt="<?= htmlspecialchars($lb['tieu_de']) ?>" class="w-8 h-11 object-cover rounded shadow-xs shrink-0 border border-slate-100"/>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-xs font-bold text-slate-800 truncate"><?= htmlspecialchars($lb['tieu_de']) ?></span>
                                    <span class="text-[11px] text-slate-400"><?= htmlspecialchars($lb['the_loai_ten'] ?? 'Sách') ?></span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-bold text-rose-600"><?= $lb['so_luong_ton'] ?> cuốn</span>
                                <p class="text-[10px] text-slate-400">còn lại</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Dữ liệu doanh số & đơn hàng thực tế từ CSDL
const chartDatasets = <?= json_encode($allChartDatasets, JSON_UNESCAPED_UNICODE) ?>;
let currentRange = '<?= $chartRange ?>';
let chartInstance = null;

function formatCurrencyJs(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
}

function initChart() {
    const canvas = document.getElementById('revenueOrdersChartCanvas');
    if (!canvas) return;

    // Kiểm tra nếu thư viện Chart.js chưa tải được (offline fallback sang SVG động)
    if (typeof Chart === 'undefined') {
        const canvasWrap = document.getElementById('canvasChartWrap');
        const svgWrap = document.getElementById('svgChartWrap');
        if (canvasWrap) canvasWrap.classList.add('hidden');
        if (svgWrap) {
            svgWrap.classList.remove('hidden');
            svgWrap.classList.add('flex');
        }
        return;
    }

    const dataObj = chartDatasets[currentRange] || chartDatasets['7days'] || { points: [] };
    const points = dataObj.points || [];

    const labels = points.map(p => p.short || p.label);
    const revenues = points.map(p => p.rev);
    const orders = points.map(p => p.orders);

    const ctx = canvas.getContext('2d');

    // Gradient nền cho đường cong doanh số
    const gradient = ctx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, 'rgba(0, 106, 97, 0.28)');
    gradient.addColorStop(0.7, 'rgba(0, 106, 97, 0.05)');
    gradient.addColorStop(1, 'rgba(0, 106, 97, 0.00)');

    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'line',
                    label: 'Doanh thu thực tế (VNĐ)',
                    data: revenues,
                    borderColor: '#006A61',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: points.map(p => p.milestone ? '#006A61' : '#ffffff'),
                    pointBorderColor: '#006A61',
                    pointBorderWidth: 2,
                    pointRadius: points.map(p => p.milestone ? 6 : (p.is_today ? 5 : 3.5)),
                    pointHoverRadius: 7,
                    yAxisID: 'yRev',
                    order: 1
                },
                {
                    type: 'bar',
                    label: 'Số lượng đơn hàng',
                    data: orders,
                    backgroundColor: points.map(p => p.milestone ? '#006A61' : (p.is_today ? '#86F2E4' : '#CBD5E1')),
                    hoverBackgroundColor: '#0F2942',
                    borderRadius: 5,
                    barPercentage: 0.45,
                    categoryPercentage: 0.7,
                    yAxisID: 'yOrders',
                    order: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 12,
                        boxHeight: 12,
                        usePointStyle: true,
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                        color: '#475569'
                    }
                },
                tooltip: {
                    backgroundColor: '#001428',
                    titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                    bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        title: function(items) {
                            if (!items || !items.length) return '';
                            const idx = items[0].dataIndex;
                            const pt = (chartDatasets[currentRange].points || [])[idx];
                            return pt ? (pt.label || pt.date) : items[0].label;
                        },
                        label: function(context) {
                            if (context.dataset.yAxisID === 'yRev') {
                                return ' Doanh thu: ' + formatCurrencyJs(context.raw);
                            } else {
                                return ' Biến động đơn: ' + context.raw + ' đơn hàng';
                            }
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#64748B',
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' }
                    }
                },
                yRev: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        callback: function(val) {
                            if (val >= 1000000) return (val / 1000000).toLocaleString('vi-VN') + ' tr';
                            if (val >= 1000) return (val / 1000).toLocaleString('vi-VN') + ' k';
                            return val.toLocaleString('vi-VN') + ' đ';
                        },
                        color: '#64748B',
                        font: { family: 'Plus Jakarta Sans', size: 11 }
                    },
                    grid: {
                        color: '#F1F5F9',
                        drawBorder: false
                    }
                },
                yOrders: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0,
                        callback: function(val) {
                            return val + ' đơn';
                        },
                        color: '#64748B',
                        font: { family: 'Plus Jakarta Sans', size: 11 }
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
}

function switchChartRange(range) {
    if (!chartDatasets[range]) return;
    currentRange = range;

    // Cập nhật trạng thái active của buttons
    document.querySelectorAll('.range-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'text-primary', 'shadow-sm', 'font-bold');
        btn.classList.add('hover:text-primary');
    });
    const activeBtn = document.getElementById('btn-' + range);
    if (activeBtn) {
        activeBtn.classList.add('bg-white', 'text-primary', 'shadow-sm', 'font-bold');
        activeBtn.classList.remove('hover:text-primary');
    }

    // Cập nhật badge
    const badge = document.getElementById('chartRangeBadge');
    if (badge) {
        badge.textContent = (range === '7days') ? '7 Ngày Qua' :
                            (range === '30days') ? '30 Ngày Qua' :
                            (range === 'year') ? 'Năm <?= date("Y") ?>' : 'Mốc Có Đơn';
    }

    const dataObj = chartDatasets[range];
    const points = dataObj.points || [];

    // Cập nhật thẻ Callout Tag
    const calloutTitle = document.getElementById('calloutTitle');
    const calloutValue = document.getElementById('calloutValue');
    if (dataObj.peakPoint && (dataObj.peakPoint.rev > 0 || dataObj.peakPoint.orders > 0)) {
        if (calloutTitle) calloutTitle.textContent = 'Mốc Cao Nhất (' + dataObj.peakPoint.short + ')';
        if (calloutValue) calloutValue.textContent = formatCurrencyJs(dataObj.peakPoint.rev) + ' • ' + dataObj.peakPoint.orders + ' đơn';
    } else {
        if (calloutTitle) calloutTitle.textContent = 'Trạng Thái Ghi Nhận';
        if (calloutValue) calloutValue.textContent = '0đ • 0 đơn';
    }

    // Cập nhật thanh Summary Stats Bar
    const totalRev = points.reduce((acc, p) => acc + (p.rev || 0), 0);
    const totalOrders = points.reduce((acc, p) => acc + (p.orders || 0), 0);
    const aov = totalOrders > 0 ? Math.round(totalRev / totalOrders) : 0;

    const elRev = document.getElementById('summaryPeriodRev');
    const elOrders = document.getElementById('summaryPeriodOrders');
    const elAov = document.getElementById('summaryPeriodAov');
    const elPeak = document.getElementById('summaryPeriodPeak');

    if (elRev) elRev.textContent = formatCurrencyJs(totalRev);
    if (elOrders) elOrders.textContent = new Intl.NumberFormat('vi-VN').format(totalOrders) + ' đơn';
    if (elAov) elAov.textContent = formatCurrencyJs(aov);
    if (elPeak) {
        elPeak.textContent = (dataObj.peakPoint && (dataObj.peakPoint.rev > 0 || dataObj.peakPoint.orders > 0))
            ? (dataObj.peakPoint.short + ': ' + formatCurrencyJs(dataObj.peakPoint.rev))
            : 'Chưa có đơn';
    }

    // Cập nhật Chart.js nếu đang chạy
    if (chartInstance) {
        chartInstance.data.labels = points.map(p => p.short || p.label);
        chartInstance.data.datasets[0].data = points.map(p => p.rev);
        chartInstance.data.datasets[0].pointBackgroundColor = points.map(p => p.milestone ? '#006A61' : '#ffffff');
        chartInstance.data.datasets[0].pointRadius = points.map(p => p.milestone ? 6 : (p.is_today ? 5 : 3.5));
        chartInstance.data.datasets[1].data = points.map(p => p.orders);
        chartInstance.data.datasets[1].backgroundColor = points.map(p => p.milestone ? '#006A61' : (p.is_today ? '#86F2E4' : '#CBD5E1'));
        chartInstance.update();
    } else {
        // Cập nhật SVG fallback nếu không có Chart.js
        updateSvgFallback(dataObj);
    }
}

function updateSvgFallback(dataObj) {
    const areaPath = document.getElementById('svgAreaPath');
    const linePath = document.getElementById('svgLinePath');
    const barsGroup = document.getElementById('svgBars');
    const dotsGroup = document.getElementById('svgDots');
    const xLabels = document.getElementById('svgXLabels');

    if (areaPath) areaPath.setAttribute('d', dataObj.svgAreaPath || '');
    if (linePath) linePath.setAttribute('d', dataObj.svgPath || '');

    if (barsGroup) {
        barsGroup.innerHTML = (dataObj.points || []).map(pt => {
            const color = pt.milestone ? '#006A61' : (pt.is_today ? '#86F2E4' : '#E2E8F0');
            const yBar = 180 - pt.bar_h;
            return `<rect x="${pt.x - 10}" y="${yBar}" width="20" height="${pt.bar_h}" rx="4" fill="${color}" opacity="0.85">
                        <title>${pt.label}: ${formatCurrencyJs(pt.rev)} (${pt.orders} đơn)</title>
                    </rect>`;
        }).join('');
    }

    if (dotsGroup) {
        dotsGroup.innerHTML = (dataObj.points || []).map(pt => {
            if (pt.milestone) {
                return `<circle cx="${pt.x}" cy="${pt.y}" r="6.5" fill="#006A61" stroke="#ffffff" stroke-width="2.5">
                            <title>${pt.label} (Đỉnh cao): ${formatCurrencyJs(pt.rev)}</title>
                        </circle>`;
            } else if (pt.is_today) {
                return `<circle cx="${pt.x}" cy="${pt.y}" r="5" fill="#86F2E4" stroke="#006A61" stroke-width="2">
                            <title>${pt.label}: ${formatCurrencyJs(pt.rev)}</title>
                        </circle>`;
            }
            return '';
        }).join('');
    }

    if (xLabels) {
        xLabels.innerHTML = (dataObj.points || []).map(pt => {
            const cls = pt.milestone ? 'text-secondary font-bold' : '';
            return `<span class="${cls}">${pt.short || pt.label}</span>`;
        }).join('');
    }
}

document.addEventListener('DOMContentLoaded', initChart);
</script>
