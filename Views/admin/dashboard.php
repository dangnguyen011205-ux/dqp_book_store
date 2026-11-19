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
            <div class="flex flex-col gap-4 mb-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-bold text-primary flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px]">query_stats</span>
                            </span>
                            <span>Doanh Thu &amp; Biến Động Đơn Hàng</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Biểu đồ tiến trình doanh thu và số lượng đơn hàng ghi nhận tự động theo thực tế hệ thống</p>
                    </div>

                    <!-- Preset Time Range Buttons -->
                    <div class="flex flex-wrap items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-bold text-slate-600 self-start sm:self-auto" id="chart-range-selector">
                        <button type="button" onclick="changeChartRange('7days')" id="range-btn-7days" 
                                class="range-btn px-3 py-1.5 rounded-lg transition-all <?= ($selectedRange === '7days') ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:text-primary' ?>">
                            7 Ngày
                        </button>
                        <button type="button" onclick="changeChartRange('14days')" id="range-btn-14days" 
                                class="range-btn px-3 py-1.5 rounded-lg transition-all <?= ($selectedRange === '14days') ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:text-primary' ?>">
                            14 Ngày
                        </button>
                        <button type="button" onclick="changeChartRange('30days')" id="range-btn-30days" 
                                class="range-btn px-3 py-1.5 rounded-lg transition-all <?= ($selectedRange === '30days') ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:text-primary' ?>">
                            30 Ngày
                        </button>
                        <button type="button" onclick="changeChartRange('this_month')" id="range-btn-this_month" 
                                class="range-btn px-3 py-1.5 rounded-lg transition-all <?= ($selectedRange === 'this_month') ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:text-primary' ?>">
                            Tháng Này
                        </button>
                        <button type="button" onclick="changeChartRange('last_month')" id="range-btn-last_month" 
                                class="range-btn px-3 py-1.5 rounded-lg transition-all <?= ($selectedRange === 'last_month') ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:text-primary' ?>">
                            Tháng Trước
                        </button>
                        <button type="button" onclick="toggleCustomDatePicker()" id="range-btn-custom" 
                                class="range-btn inline-flex items-center gap-1 px-3 py-1.5 rounded-lg transition-all <?= ($selectedRange === 'custom') ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:text-primary' ?>">
                            <span class="material-symbols-outlined text-[15px]">date_range</span>
                            <span>Tùy chọn</span>
                        </button>
                    </div>
                </div>

                <!-- Custom Date Range Picker Toolbar (Có thể chọn mốc thời gian cụ thể) -->
                <div id="custom-date-picker-box" class="<?= ($selectedRange === 'custom') ? '' : 'hidden' ?> p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-3 text-xs transition-all duration-300">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="font-bold text-slate-700 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-secondary">calendar_today</span>
                            <span>Chọn khoảng ngày:</span>
                        </span>
                        <div class="flex items-center gap-2">
                            <label for="custom-start-date" class="text-slate-500 text-[11px] font-semibold">Từ ngày</label>
                            <input type="date" id="custom-start-date" value="<?= htmlspecialchars($chartData['start_date'] ?? date('Y-m-d', strtotime('-6 days'))) ?>" max="<?= date('Y-m-d') ?>" class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 text-xs font-semibold focus:outline-none focus:border-secondary shadow-2xs cursor-pointer">
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="custom-end-date" class="text-slate-500 text-[11px] font-semibold">Đến ngày</label>
                            <input type="date" id="custom-end-date" value="<?= htmlspecialchars($chartData['end_date'] ?? date('Y-m-d')) ?>" max="<?= date('Y-m-d') ?>" class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 text-xs font-semibold focus:outline-none focus:border-secondary shadow-2xs cursor-pointer">
                        </div>
                        <button type="button" onclick="applyCustomDateRange()" class="px-3.5 py-1.5 rounded-lg bg-secondary hover:bg-secondary/90 text-white font-bold text-xs shadow-xs transition-all flex items-center gap-1 active:scale-95">
                            <span class="material-symbols-outlined text-[15px]">filter_alt</span>
                            <span>Lọc Ngay</span>
                        </button>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] text-slate-400">
                        <span id="custom-range-indicator" class="font-bold text-secondary bg-secondary/10 px-2 py-0.5 rounded-md"><?= htmlspecialchars($chartData['range_label'] ?? '') ?> (<?= $chartData['days_count'] ?? 7 ?> ngày)</span>
                        <button type="button" onclick="toggleCustomDatePicker(false)" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-200/60 transition-colors" title="Đóng thanh chọn mốc">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </button>
                    </div>
                </div>

                <!-- 4 KPI Metrics Micro-Cards cho khoảng thời gian đang chọn -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100 flex flex-col justify-between hover:bg-white hover:shadow-xs transition-all">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center justify-between">
                            <span>Tổng Doanh Thu</span>
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">payments</span>
                        </span>
                        <span id="metric-total-rev" class="text-base sm:text-lg font-black text-emerald-700 mt-1">
                            <?= $chartData['total_range_rev_f'] ?? '0đ' ?>
                        </span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100 flex flex-col justify-between hover:bg-white hover:shadow-xs transition-all">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center justify-between">
                            <span>Đơn Hàng Hợp Lệ</span>
                            <span class="material-symbols-outlined text-[16px] text-indigo-600">receipt_long</span>
                        </span>
                        <span id="metric-total-orders" class="text-base sm:text-lg font-black text-indigo-700 mt-1">
                            <?= number_format($chartData['total_range_orders'] ?? 0) ?> đơn
                        </span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100 flex flex-col justify-between hover:bg-white hover:shadow-xs transition-all">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center justify-between">
                            <span>Doanh Thu TB / Ngày</span>
                            <span class="material-symbols-outlined text-[16px] text-amber-600">trending_up</span>
                        </span>
                        <span id="metric-avg-daily" class="text-base sm:text-lg font-black text-amber-700 mt-1">
                            <?= $chartData['avg_daily_rev_f'] ?? '0đ' ?>
                        </span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100 flex flex-col justify-between hover:bg-white hover:shadow-xs transition-all">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center justify-between">
                            <span>Mốc Đỉnh Doanh Thu</span>
                            <span class="material-symbols-outlined text-[16px] text-teal-600">military_tech</span>
                        </span>
                        <span id="metric-peak-day" class="text-xs sm:text-sm font-black text-primary mt-1 truncate" title="<?= $chartData['peak_day']['full_date'] ?? '' ?>: <?= $chartData['peak_day']['rev_formatted'] ?? '' ?>">
                            <?= $chartData['peak_day']['rev_formatted'] ?? '0đ' ?>
                        </span>
                    </div>
                </div>

                <!-- View Switcher & Action Hint Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs py-2 px-3 bg-secondary/5 rounded-xl border border-secondary/10">
                    <span class="text-secondary font-medium flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-secondary">touch_app</span>
                        <span><strong>Tương tác sơ đồ:</strong> Nhấn trực tiếp vào cột hoặc điểm bất kỳ trên đồ thị để xem danh sách đơn hàng tương ứng.</span>
                    </span>

                    <div class="flex items-center gap-1 self-end sm:self-auto shrink-0">
                        <span class="text-[11px] text-slate-400 mr-1 font-semibold">Hiển thị:</span>
                        <button type="button" onclick="setChartViewMode('combo')" id="view-mode-combo" 
                                class="px-2 py-0.5 rounded text-[11px] font-bold bg-primary text-white transition-all shadow-2xs">
                            Cả hai
                        </button>
                        <button type="button" onclick="setChartViewMode('revenue')" id="view-mode-revenue" 
                                class="px-2 py-0.5 rounded text-[11px] font-bold bg-white text-slate-600 hover:text-primary transition-all border border-slate-200">
                            Doanh thu
                        </button>
                        <button type="button" onclick="setChartViewMode('orders')" id="view-mode-orders" 
                                class="px-2 py-0.5 rounded text-[11px] font-bold bg-white text-slate-600 hover:text-primary transition-all border border-slate-200">
                            Số đơn
                        </button>
                    </div>
                </div>
            </div>

            <!-- Canvas Chart.js Container (Chuẩn retina, mượt mà và tương tác cao) -->
            <div class="relative w-full h-64 md:h-72 my-1" id="chartjs-container">
                <canvas id="dashboardRevenueChart"></canvas>
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

    <!-- SECTION 2.5: INTERACTIVE MILESTONE ORDERS EXPLORER (CHI TIẾT ĐƠN HÀNG THEO MỐC THỜI GIAN) -->
    <div id="milestone-orders-section" class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-4 transition-all">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[22px]">calendar_month</span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-bold text-primary">Chi Tiết Đơn Hàng Mốc:</h2>
                        <span id="milestone-date-label" class="text-secondary font-black bg-secondary/10 px-2.5 py-0.5 rounded-lg text-xs">--/--/----</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5" id="milestone-summary-label">Đang nạp dữ liệu đơn hàng theo mốc đã chọn...</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span id="milestone-badge-count" class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">0 đơn hàng</span>
                <span id="milestone-badge-rev" class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">0đ</span>
                <a href="<?= base_url('admin/don-hang') ?>" class="text-xs font-bold text-secondary hover:underline flex items-center gap-1 ml-2">
                    Xem tất cả đơn <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto min-h-[140px] relative">
            <div id="milestone-loading" class="hidden absolute inset-0 bg-white/80 backdrop-blur-xs flex items-center justify-center z-10">
                <span class="material-symbols-outlined text-[32px] text-secondary animate-spin">progress_activity</span>
            </div>

            <table class="w-full text-left border-collapse" id="milestone-orders-table">
                <thead>
                    <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50 rounded-xl">
                        <th class="py-3 px-4 rounded-l-xl">Mã Đơn</th>
                        <th class="py-3 px-4">Khách Hàng</th>
                        <th class="py-3 px-4">Thời Gian Đặt</th>
                        <th class="py-3 px-4 text-center">Số Cuốn</th>
                        <th class="py-3 px-4 text-right">Tổng Tiền</th>
                        <th class="py-3 px-4">Thanh Toán</th>
                        <th class="py-3 px-4">Trạng Thái</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Thao Tác</th>
                    </tr>
                </thead>
                <tbody id="milestone-orders-tbody" class="text-xs divide-y divide-slate-100">
                    <!-- Populated via JavaScript -->
                </tbody>
            </table>

            <!-- Empty State -->
            <div id="milestone-empty-state" class="hidden py-8 text-center flex flex-col items-center justify-center">
                <span class="material-symbols-outlined text-[36px] text-slate-300 mb-2">event_busy</span>
                <p class="text-xs font-bold text-slate-600" id="milestone-empty-msg">Không có đơn hàng nào được ghi nhận trong ngày này.</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Bạn có thể chọn mốc ngày khác trên biểu đồ phía trên để xem các đơn tương ứng.</p>
            </div>
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
                        <h3 class="text-sm font-bold text-primary">Đánh Giá Mới Nhất</h3>
                    </div>
                    <a href="<?= base_url('admin/danh-gia') ?>" class="text-[11px] font-bold text-secondary hover:underline">
                        Quản lý
                    </a>
                </div>
                <p class="text-xs text-slate-400">Phản hồi từ độc giả gửi lên hệ thống</p>

                <div class="space-y-3">
                    <?php if (empty($pendingReviews)): ?>
                        <div class="p-4 rounded-xl bg-slate-50 text-center text-xs text-slate-400">
                            Chưa có đánh giá nào gần đây.
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
let currentSelectedDate = '<?= $chartData['peak_day']['date'] ?? date('Y-m-d') ?>';

// Hàm chọn và xem các đơn hàng của mốc ngày trên sơ đồ
function selectMilestoneDate(dateStr, formattedDate, revFormatted, orderCount, clickedElement) {
    currentSelectedDate = dateStr;

    // Cập nhật giao diện mốc đang chọn trên Floating Callout
    const calloutDate = document.getElementById('callout-date');
    const calloutVal = document.getElementById('callout-val');
    if (calloutDate) calloutDate.textContent = formattedDate;
    if (calloutVal) calloutVal.textContent = `${revFormatted} / ${orderCount} đơn`;

    // Cập nhật Header của Bảng Chi Tiết Đơn Hàng Mốc
    const dateLabel = document.getElementById('milestone-date-label');
    const summaryLabel = document.getElementById('milestone-summary-label');
    const badgeCount = document.getElementById('milestone-badge-count');
    const badgeRev = document.getElementById('milestone-badge-rev');

    if (dateLabel) dateLabel.textContent = formattedDate;
    if (summaryLabel) summaryLabel.textContent = `Doanh số ghi nhận thực tế trong ngày: ${revFormatted} (${orderCount} đơn hàng)`;
    if (badgeCount) badgeCount.textContent = `${orderCount} đơn hàng`;
    if (badgeRev) badgeRev.textContent = revFormatted;

    // Highlight các thanh bar và circle tương ứng
    document.querySelectorAll('.chart-bar').forEach(b => {
        if (b.dataset.date === dateStr) {
            b.setAttribute('fill', '#FFB95F');
            b.setAttribute('opacity', '1');
            b.setAttribute('stroke', '#001428');
            b.setAttribute('stroke-width', '1.5');
        } else {
            b.setAttribute('fill', '#E2E8F0');
            b.setAttribute('opacity', '0.85');
            b.removeAttribute('stroke');
        }
    });

    document.querySelectorAll('.chart-dot').forEach(d => {
        if (d.dataset.date === dateStr) {
            d.setAttribute('fill', '#FFB95F');
            d.setAttribute('r', '8');
            d.setAttribute('stroke', '#001428');
        } else {
            d.setAttribute('fill', '#86F2E4');
            d.setAttribute('r', '4.5');
            d.setAttribute('stroke', '#ffffff');
        }
    });

    // Tải danh sách đơn hàng của ngày này qua AJAX
    const loading = document.getElementById('milestone-loading');
    const tbody = document.getElementById('milestone-orders-tbody');
    const table = document.getElementById('milestone-orders-table');
    const emptyState = document.getElementById('milestone-empty-state');
    const emptyMsg = document.getElementById('milestone-empty-msg');

    if (loading) loading.classList.remove('hidden');

    fetch('<?= base_url('admin/dashboard/orders-by-date') ?>?date=' + encodeURIComponent(dateStr))
        .then(res => res.json())
        .then(res => {
            if (loading) loading.classList.add('hidden');
            if (!res.success) return;

            const orders = res.orders || [];
            if (orders.length === 0) {
                if (table) table.classList.add('hidden');
                if (emptyState) {
                    emptyState.classList.remove('hidden');
                    if (emptyMsg) emptyMsg.textContent = `Không có đơn hàng nào được ghi nhận trong ngày ${res.date_formatted}.`;
                }
                return;
            }

            if (emptyState) emptyState.classList.add('hidden');
            if (table) table.classList.remove('hidden');

            let html = '';
            orders.forEach(ord => {
                const badge = ord.status_info || { label: ord.trang_thai, color: 'bg-slate-100 text-slate-800' };
                html += `
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3 px-4 font-mono font-bold text-primary">
                            #BK-${ord.id}
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-bold text-slate-800 block">${escapeHtml(ord.ho_ten || 'Khách vãng lai')}</span>
                            <span class="text-[11px] text-slate-400 font-mono">${escapeHtml(ord.so_dien_thoai || '')}</span>
                        </td>
                        <td class="py-3 px-4 text-slate-500 font-medium">
                            ${escapeHtml(ord.ngay_dat_formatted)}
                        </td>
                        <td class="py-3 px-4 text-center font-bold text-slate-700">
                            ${ord.item_count || 1}
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-secondary text-sm">
                            ${ord.tong_thanh_toan_formatted}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                ${escapeHtml(ord.payment_method || 'COD')}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border ${badge.color}">
                                ${escapeHtml(badge.label)}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="${ord.detail_url}" target="_blank" 
                               class="inline-flex items-center gap-1 px-3 py-1 bg-primary hover:bg-secondary text-white text-[11px] font-bold rounded-lg shadow-2xs transition-colors">
                                <span>Chi tiết</span>
                                <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                            </a>
                        </td>
                    </tr>
                `;
            });
            if (tbody) tbody.innerHTML = html;
        })
        .catch(err => {
            if (loading) loading.classList.add('hidden');
            console.error('Lỗi tải đơn hàng theo mốc:', err);
        });
}

let currentChartInstance = null;
let currentViewMode = 'combo';
let currentChartData = <?= json_encode($chartData, JSON_UNESCAPED_UNICODE) ?>;
let currentChartPoints = <?= json_encode($chartPoints, JSON_UNESCAPED_UNICODE) ?>;

// Định dạng tiền tệ VND rút gọn cho trục Y biểu đồ (ví dụ: 1.5Tr, 500k)
function formatShortVND(val) {
    if (val >= 1000000) {
        return (val / 1000000).toFixed(1).replace('.0', '') + 'Tr ₫';
    } else if (val >= 1000) {
        return Math.round(val / 1000) + 'k ₫';
    }
    return val + ' ₫';
}

function formatFullVND(val) {
    return new Intl.NumberFormat('vi-VN').format(val) + ' ₫';
}

// Khởi tạo hoặc cập nhật Chart.js biểu đồ Doanh thu & Đơn hàng
function initOrUpdateChartJs(points, chartData, viewMode = 'combo') {
    const canvas = document.getElementById('dashboardRevenueChart');
    if (!canvas || !window.Chart) return;
    const ctx = canvas.getContext('2d');

    // Tạo gradient màu ngọc bích chuẩn Athenaeum
    const gradient = ctx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, 'rgba(0, 106, 97, 0.40)');
    gradient.addColorStop(0.65, 'rgba(0, 106, 97, 0.08)');
    gradient.addColorStop(1, 'rgba(0, 106, 97, 0.00)');

    const labels = points.map(p => p.short_label || p.label);
    const revenueValues = points.map(p => p.rev);
    const orderValues = points.map(p => p.orders);

    const isRevenueHidden = (viewMode === 'orders');
    const isOrdersHidden = (viewMode === 'revenue');

    if (currentChartInstance) {
        currentChartInstance.destroy();
    }

    currentChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'line',
                    label: 'Doanh thu (₫)',
                    data: revenueValues,
                    yAxisID: 'y',
                    tension: 0.38,
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#006A61',
                    borderWidth: 3,
                    pointBackgroundColor: '#FFFFFF',
                    pointBorderColor: '#006A61',
                    pointBorderWidth: 2.2,
                    pointRadius: points.length > 25 ? 2.5 : 4.5,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#006A61',
                    pointHoverBorderColor: '#FFFFFF',
                    pointHoverBorderWidth: 2.5,
                    order: 1,
                    hidden: isRevenueHidden
                },
                {
                    type: 'bar',
                    label: 'Số đơn hàng',
                    data: orderValues,
                    yAxisID: 'y1',
                    backgroundColor: 'rgba(134, 242, 228, 0.65)',
                    hoverBackgroundColor: 'rgba(0, 106, 97, 0.85)',
                    borderRadius: 6,
                    maxBarThickness: points.length > 20 ? 18 : 28,
                    order: 2,
                    hidden: isOrdersHidden
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
            onClick: (evt, elements) => {
                if (elements && elements.length > 0) {
                    const idx = elements[0].index;
                    const pt = points[idx];
                    if (pt) {
                        selectMilestoneDate(pt.order_date, pt.full_date, pt.rev_formatted, pt.orders, null);
                        // Cuộn mượt đến bảng đơn hàng
                        const tableSection = document.getElementById('milestone-orders-section');
                        if (tableSection) {
                            tableSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#001428',
                    titleColor: '#86F2E4',
                    titleFont: { size: 12, weight: 'bold' },
                    bodyColor: '#FFFFFF',
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 12,
                    displayColors: true,
                    borderColor: 'rgba(255,255,255,0.12)',
                    borderWidth: 1,
                    callbacks: {
                        title: function(items) {
                            if (!items.length) return '';
                            const idx = items[0].dataIndex;
                            const pt = points[idx];
                            return (pt.day_name ? pt.day_name + ', ' : '') + pt.full_date;
                        },
                        label: function(ctx) {
                            if (ctx.datasetIndex === 0) {
                                return ' Doanh thu: ' + formatFullVND(ctx.raw);
                            } else {
                                return ' Đơn hàng: ' + ctx.raw + ' đơn';
                            }
                        },
                        afterBody: function() {
                            return ['\n👉 Nhấn để xem chi tiết các đơn ngày này'];
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748B',
                        font: { size: 11, weight: '600' },
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 14
                    }
                },
                y: {
                    type: 'linear',
                    display: !isRevenueHidden,
                    position: 'left',
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(226, 232, 240, 0.7)'
                    },
                    ticks: {
                        color: '#006A61',
                        font: { size: 11, weight: 'bold' },
                        callback: function(val) {
                            return formatShortVND(val);
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: !isOrdersHidden,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        color: '#64748B',
                        stepSize: 1,
                        precision: 0,
                        font: { size: 11, weight: '600' },
                        callback: function(val) {
                            return val + ' đơn';
                        }
                    }
                }
            }
        }
    });
}

// Bật / tắt thanh chọn mốc ngày cụ thể
function toggleCustomDatePicker(forceOpen = null) {
    const box = document.getElementById('custom-date-picker-box');
    if (!box) return;
    const shouldOpen = (forceOpen !== null) ? forceOpen : box.classList.contains('hidden');
    if (shouldOpen) {
        box.classList.remove('hidden');
        document.querySelectorAll('#chart-range-selector .range-btn').forEach(btn => {
            btn.className = "range-btn px-3 py-1.5 rounded-lg text-slate-600 hover:text-primary transition-all";
        });
        const customBtn = document.getElementById('range-btn-custom');
        if (customBtn) customBtn.className = "range-btn inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary text-white shadow-xs transition-all";
    } else {
        box.classList.add('hidden');
    }
}

// Áp dụng mốc ngày cụ thể do người dùng chọn
function applyCustomDateRange() {
    const startInput = document.getElementById('custom-start-date');
    const endInput = document.getElementById('custom-end-date');
    if (!startInput || !endInput) return;

    let start = startInput.value.trim();
    let end = endInput.value.trim();

    if (!start || !end) {
        alert('Vui lòng chọn đầy đủ ngày bắt đầu và ngày kết thúc.');
        return;
    }

    if (start > end) {
        const tmp = start;
        start = end;
        end = tmp;
        startInput.value = start;
        endInput.value = end;
    }

    changeChartRange('custom', start, end);
}

// Thay đổi chế độ hiển thị biểu đồ: Cả hai / Doanh thu / Số đơn
function setChartViewMode(mode) {
    currentViewMode = mode;
    const modes = ['combo', 'revenue', 'orders'];
    modes.forEach(m => {
        const btn = document.getElementById('view-mode-' + m);
        if (btn) {
            if (m === mode) {
                btn.className = "px-2.5 py-1 rounded-lg text-[11px] font-bold bg-primary text-white transition-all shadow-2xs";
            } else {
                btn.className = "px-2.5 py-1 rounded-lg text-[11px] font-bold bg-white text-slate-600 hover:text-primary transition-all border border-slate-200";
            }
        }
    });

    if (currentChartInstance) {
        initOrUpdateChartJs(currentChartPoints, currentChartData, currentViewMode);
    }
}

// Cập nhật 4 thẻ KPI tóm tắt khoảng thời gian
function updateMetricsKpi(data) {
    if (!data) return;
    const revEl = document.getElementById('metric-total-rev');
    const ordersEl = document.getElementById('metric-total-orders');
    const avgDailyEl = document.getElementById('metric-avg-daily');
    const peakDayEl = document.getElementById('metric-peak-day');
    const indicatorEl = document.getElementById('custom-range-indicator');

    if (revEl) revEl.textContent = data.total_range_rev_f || '0đ';
    if (ordersEl) ordersEl.textContent = (data.total_range_orders || 0) + ' đơn';
    if (avgDailyEl) avgDailyEl.textContent = data.avg_daily_rev_f || '0đ';
    if (peakDayEl && data.peak_day) {
        peakDayEl.textContent = data.peak_day.rev_formatted || '0đ';
        peakDayEl.title = (data.peak_day.full_date || '') + ': ' + (data.peak_day.rev_formatted || '') + ' (' + (data.peak_day.orders || 0) + ' đơn)';
    }
    if (indicatorEl) {
        indicatorEl.textContent = (data.range_label || '') + ' (' + (data.days_count || 0) + ' ngày)';
    }
}

// Hàm đổi mốc thời gian xem biểu đồ (7 ngày, 14 ngày, 30 ngày, tháng này, tháng trước, custom)
function changeChartRange(range, customStart = null, customEnd = null) {
    document.querySelectorAll('#chart-range-selector .range-btn').forEach(btn => {
        btn.className = "range-btn px-3 py-1.5 rounded-lg text-slate-600 hover:text-primary transition-all";
    });
    const activeBtn = document.getElementById('range-btn-' + range);
    if (activeBtn) {
        activeBtn.className = "range-btn px-3 py-1.5 rounded-lg bg-primary text-white shadow-xs transition-all";
    }

    if (range === 'custom') {
        toggleCustomDatePicker(true);
    }

    let url = '<?= base_url('admin/dashboard/chart-data') ?>?range=' + encodeURIComponent(range);
    if (range === 'custom' && customStart && customEnd) {
        url += '&start_date=' + encodeURIComponent(customStart) + '&end_date=' + encodeURIComponent(customEnd);
    }

    fetch(url)
        .then(res => res.json())
        .then(res => {
            if (!res.success || !res.data) return;
            const data = res.data;
            currentChartData = data;
            currentChartPoints = data.points || [];

            // Cập nhật 4 thẻ KPI
            updateMetricsKpi(data);

            // Cập nhật biểu đồ Chart.js
            initOrUpdateChartJs(currentChartPoints, data, currentViewMode);

            // Tự động load mốc đỉnh hoặc mốc gần nhất vào bảng chi tiết
            if (currentChartPoints.length > 0) {
                let peak = currentChartPoints[currentChartPoints.length - 1];
                for (let p of currentChartPoints) {
                    if (p.milestone) { peak = p; break; }
                }
                selectMilestoneDate(peak.order_date, peak.full_date, peak.rev_formatted, peak.orders, null);
            }
        })
        .catch(err => console.error('Lỗi cập nhật biểu đồ:', err));
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

// Khởi chạy mặc định khi trang sẵn sàng
function startDashboardCharts() {
    if (typeof Chart === 'undefined') {
        setTimeout(startDashboardCharts, 50);
        return;
    }
    // Khởi tạo biểu đồ Chart.js
    initOrUpdateChartJs(currentChartPoints, currentChartData, currentViewMode);

    // Tự động tải danh sách đơn hàng cho mốc đỉnh khởi đầu
    const initDate = '<?= $chartData['peak_day']['date'] ?? date('Y-m-d') ?>';
    const initFormatted = '<?= $chartData['peak_day']['full_date'] ?? date('d/m/Y') ?>';
    const initRevF = '<?= $chartData['peak_day']['rev_formatted'] ?? '0đ' ?>';
    const initOrders = <?= (int)($chartData['peak_day']['orders'] ?? 0) ?>;

    selectMilestoneDate(initDate, initFormatted, initRevF, initOrders, null);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startDashboardCharts);
} else {
    startDashboardCharts();
}
</script>
