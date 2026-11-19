<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Core\Database;

class AdminDashboardController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(): void {
        $db = Database::getInstance()->getConnection();

        // 1. Thống kê KPI cốt lõi
        $totalRevenue = (float)$db->query("SELECT COALESCE(SUM(tong_thanh_toan), 0) FROM `don_hang` WHERE `trang_thai` = 'hoan_thanh'")->fetchColumn();
        $totalAllRevenue = (float)$db->query("SELECT COALESCE(SUM(tong_thanh_toan), 0) FROM `don_hang` WHERE `trang_thai` != 'da_huy'")->fetchColumn();
        
        $totalOrders = (int)$db->query("SELECT COUNT(*) FROM `don_hang`")->fetchColumn();
        $pendingOrdersCount = (int)$db->query("SELECT COUNT(*) FROM `don_hang` WHERE `trang_thai` = 'cho_xac_nhan'")->fetchColumn();
        $shippingOrdersCount = (int)$db->query("SELECT COUNT(*) FROM `don_hang` WHERE `trang_thai` = 'dang_giao'")->fetchColumn();
        $completedOrdersCount = (int)$db->query("SELECT COUNT(*) FROM `don_hang` WHERE `trang_thai` = 'hoan_thanh'")->fetchColumn();
        $cancelledOrdersCount = (int)$db->query("SELECT COUNT(*) FROM `don_hang` WHERE `trang_thai` = 'da_huy'")->fetchColumn();

        $totalBooksSold = (int)$db->query("SELECT COALESCE(SUM(ctdh.so_luong), 0) FROM `chi_tiet_don_hang` ctdh JOIN `don_hang` dh ON ctdh.don_hang_id = dh.id WHERE dh.trang_thai != 'da_huy'")->fetchColumn();
        $totalStock = (int)$db->query("SELECT COALESCE(SUM(so_luong_ton), 0) FROM `sach` WHERE `dang_ban` = 1")->fetchColumn();
        $totalBooks = (int)$db->query("SELECT COUNT(*) FROM `sach` WHERE `dang_ban` = 1")->fetchColumn();
        $totalUsers = (int)$db->query("SELECT COUNT(*) FROM `nguoi_dung` WHERE `vai_tro` = 'khach_hang'")->fetchColumn();
        
        // Cảnh báo tồn kho thấp (sách tồn kho <= 30)
        $lowStockCount = (int)$db->query("SELECT COUNT(*) FROM `sach` WHERE `so_luong_ton` <= 30 AND `dang_ban` = 1")->fetchColumn();
        
        // 2. Dữ liệu Biểu đồ Doanh thu & Biến động đơn hàng (Hỗ trợ chọn mốc thời gian cụ thể)
        $selectedRange = $_GET['range'] ?? '7days';
        $startDate = !empty($_GET['start_date']) ? trim($_GET['start_date']) : null;
        $endDate = !empty($_GET['end_date']) ? trim($_GET['end_date']) : null;
        $chartData = $this->getChartDataForRange($selectedRange, $startDate, $endDate);
        $chartPoints = $chartData['points'];

        // 3. Dữ liệu Phân bổ Doanh thu theo Thể loại (Donut Chart)
        $sqlCatBreakdown = "SELECT tl.ten, 
                                   COUNT(DISTINCT s.id) as tong_sach,
                                   COALESCE(SUM(ctdh.so_luong * ctdh.don_gia), 0) as doanh_so
                            FROM `the_loai` tl
                            LEFT JOIN `sach` s ON tl.id = s.the_loai_id AND s.dang_ban = 1
                            LEFT JOIN `chi_tiet_don_hang` ctdh ON s.id = ctdh.sach_id
                            GROUP BY tl.id
                            ORDER BY doanh_so DESC, tong_sach DESC";
        $categoryBreakdown = $db->query($sqlCatBreakdown)->fetchAll(\PDO::FETCH_ASSOC);

        // Tính tỷ lệ % cho từng thể loại
        $totalCatRevenue = 0;
        foreach ($categoryBreakdown as $c) {
            $totalCatRevenue += (float)$c['doanh_so'];
        }
        if ($totalCatRevenue <= 0) $totalCatRevenue = 1; // tránh chia cho 0

        $catPalette = [
            ['stroke' => '#001428', 'bg' => 'bg-primary'],
            ['stroke' => '#006A61', 'bg' => 'bg-secondary'],
            ['stroke' => '#86F2E4', 'bg' => 'bg-secondary-container'],
            ['stroke' => '#FFB95F', 'bg' => 'bg-amber-400'],
            ['stroke' => '#A0AEC0', 'bg' => 'bg-slate-400']
        ];

        $donutCircumference = 238.7; // 2 * PI * r (r = 38)
        $currentOffset = 0;
        foreach ($categoryBreakdown as $idx => &$cat) {
            $pct = round(((float)$cat['doanh_so'] / $totalCatRevenue) * 100);
            if ($pct === 0 && (int)$cat['tong_sach'] > 0) $pct = 5; // Cho hiển thị nhỏ nếu có sách
            $dashLength = ($pct / 100) * $donutCircumference;
            $cat['percent'] = $pct;
            $cat['dasharray'] = sprintf("%.1f %.1f", $dashLength, $donutCircumference);
            $cat['dashoffset'] = sprintf("%.1f", -$currentOffset);
            $cat['color'] = $catPalette[$idx % count($catPalette)];
            $currentOffset += $dashLength;
        }
        unset($cat);

        // 4. Danh sách Đơn hàng mới cần xử lý (5 đơn mới nhất)
        $sqlRecentOrders = "SELECT dh.*, nd.ho_ten, nd.so_dien_thoai as user_phone, tt.phuong_thuc as payment_method
                            FROM `don_hang` dh
                            JOIN `nguoi_dung` nd ON dh.nguoi_dung_id = nd.id
                            LEFT JOIN `thanh_toan` tt ON dh.id = tt.don_hang_id
                            ORDER BY (dh.trang_thai = 'cho_xac_nhan') DESC, dh.id DESC
                            LIMIT 5";
        $recentOrders = $db->query($sqlRecentOrders)->fetchAll(\PDO::FETCH_ASSOC);

        // 5. Top 5 Tựa sách bán chạy nhất
        $sqlTopBooks = "SELECT s.id, s.tieu_de, s.anh_bia, s.so_luong_ton, s.gia,
                               tl.ten as the_loai_ten,
                               GROUP_CONCAT(DISTINCT tg.ten SEPARATOR ', ') as tac_gia_ten,
                               COALESCE(SUM(ctdh.so_luong), 0) as da_ban,
                               COALESCE(SUM(ctdh.so_luong * ctdh.don_gia), 0) as doanh_thu
                        FROM `sach` s
                        LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                        LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                        LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                        LEFT JOIN `chi_tiet_don_hang` ctdh ON s.id = ctdh.sach_id
                        GROUP BY s.id
                        ORDER BY da_ban DESC, s.id ASC
                        LIMIT 5";
        $topBooks = $db->query($sqlTopBooks)->fetchAll(\PDO::FETCH_ASSOC);

        // 6. Đánh giá khách hàng mới nhất
        $sqlPendingReviews = "SELECT dg.*, nd.ho_ten, s.tieu_de as book_title
                              FROM `danh_gia` dg
                              JOIN `nguoi_dung` nd ON dg.nguoi_dung_id = nd.id
                              JOIN `sach` s ON dg.sach_id = s.id
                              ORDER BY dg.id DESC
                              LIMIT 3";
        $pendingReviews = $db->query($sqlPendingReviews)->fetchAll(\PDO::FETCH_ASSOC);

        // 7. Sách sắp hết hàng (tồn kho thấp nhất)
        $sqlLowStock = "SELECT s.id, s.tieu_de, s.so_luong_ton, s.anh_bia, s.gia, tl.ten as the_loai_ten
                        FROM `sach` s
                        LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                        WHERE s.dang_ban = 1
                        ORDER BY s.so_luong_ton ASC
                        LIMIT 3";
        $lowStockBooks = $db->query($sqlLowStock)->fetchAll(\PDO::FETCH_ASSOC);

        $this->view('admin.dashboard', [
            'title'                => 'Tổng Quan Số Liệu Quản Trị - ' . APP_NAME,
            'totalRevenue'         => $totalRevenue,
            'totalAllRevenue'      => $totalAllRevenue,
            'totalOrders'          => $totalOrders,
            'pendingOrdersCount'   => $pendingOrdersCount,
            'shippingOrdersCount'  => $shippingOrdersCount,
            'completedOrdersCount' => $completedOrdersCount,
            'cancelledOrdersCount' => $cancelledOrdersCount,
            'totalBooksSold'       => $totalBooksSold,
            'totalStock'           => $totalStock,
            'totalBooks'           => $totalBooks,
            'totalUsers'           => $totalUsers,
            'lowStockCount'        => $lowStockCount,
            'chartPoints'          => $chartPoints,
            'chartData'            => $chartData,
            'selectedRange'        => $selectedRange,
            'categoryBreakdown'    => $categoryBreakdown,
            'recentOrders'         => $recentOrders,
            'topBooks'             => $topBooks,
            'pendingReviews'       => $pendingReviews,
            'lowStockBooks'        => $lowStockBooks
        ], 'admin');
    }

    /**
     * Helper tạo dữ liệu biểu đồ theo khoảng thời gian linh hoạt (hỗ trợ custom mốc ngày)
     */
    public function getChartDataForRange(string $range = '7days', ?string $startDate = null, ?string $endDate = null): array {
        $db = Database::getInstance()->getConnection();
        $today = date('Y-m-d');

        // Xác định ngày bắt đầu và kết thúc
        if ($range === 'today') {
            $fromDate = $today;
            $toDate   = $today;
        } elseif ($range === 'yesterday') {
            $fromDate = date('Y-m-d', strtotime('-1 day'));
            $toDate   = $fromDate;
        } elseif ($range === '14days') {
            $fromDate = date('Y-m-d', strtotime('-13 days'));
            $toDate   = $today;
        } elseif ($range === '30days') {
            $fromDate = date('Y-m-d', strtotime('-29 days'));
            $toDate   = $today;
        } elseif ($range === 'this_month') {
            $fromDate = date('Y-m-01');
            $toDate   = $today;
        } elseif ($range === 'last_month') {
            $fromDate = date('Y-m-d', strtotime('first day of last month'));
            $toDate   = date('Y-m-d', strtotime('last day of last month'));
        } elseif ($range === 'custom' && !empty($startDate) && !empty($endDate)) {
            // Validate định dạng YYYY-MM-DD
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
                $tsStart = strtotime($startDate);
                $tsEnd = strtotime($endDate);
                if ($tsStart > $tsEnd) {
                    $tmp = $startDate;
                    $startDate = $endDate;
                    $endDate = $tmp;
                    $tsStart = strtotime($startDate);
                    $tsEnd = strtotime($endDate);
                }
                // Giới hạn tối đa 180 ngày để tránh quá tải
                $diffDays = ($tsEnd - $tsStart) / 86400;
                if ($diffDays > 180) {
                    $startDate = date('Y-m-d', strtotime("-180 days", $tsEnd));
                }
                $fromDate = $startDate;
                $toDate   = $endDate;
            } else {
                $fromDate = date('Y-m-d', strtotime('-6 days'));
                $toDate   = $today;
                $range    = '7days';
            }
        } else {
            // Mặc định: 7 ngày gần nhất
            $fromDate = date('Y-m-d', strtotime('-6 days'));
            $toDate   = $today;
            $range    = '7days';
        }

        // Truy vấn doanh thu và số lượng đơn hàng theo ngày trong khoảng [fromDate, toDate]
        $sqlChart = "SELECT DATE(ngay_dat) as order_date,
                            COUNT(*) as total_orders,
                            COALESCE(SUM(CASE WHEN trang_thai = 'hoan_thanh' THEN tong_thanh_toan ELSE 0 END), 0) as completed_rev,
                            COALESCE(SUM(CASE WHEN trang_thai != 'da_huy' THEN tong_thanh_toan ELSE 0 END), 0) as valid_rev,
                            COUNT(CASE WHEN trang_thai != 'da_huy' THEN 1 END) as valid_orders,
                            COUNT(CASE WHEN trang_thai = 'da_huy' THEN 1 END) as cancelled_orders
                     FROM `don_hang`
                     WHERE DATE(ngay_dat) >= :from_date AND DATE(ngay_dat) <= :to_date
                     GROUP BY DATE(ngay_dat)
                     ORDER BY order_date ASC";
        
        $stmt = $db->prepare($sqlChart);
        $stmt->execute([':from_date' => $fromDate, ':to_date' => $toDate]);
        $dbRows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $byDate = [];
        foreach ($dbRows as $row) {
            $byDate[$row['order_date']] = $row;
        }

        // Tạo chuỗi ngày liên tục không bị ngắt quãng giữa fromDate và toDate
        $chartRaw = [];
        $currTs = strtotime($fromDate);
        $endTs = strtotime($toDate);
        while ($currTs <= $endTs) {
            $d = date('Y-m-d', $currTs);
            if (isset($byDate[$d])) {
                $chartRaw[] = $byDate[$d];
            } else {
                $chartRaw[] = [
                    'order_date'       => $d,
                    'total_orders'     => 0,
                    'valid_orders'     => 0,
                    'cancelled_orders' => 0,
                    'completed_rev'    => 0.0,
                    'valid_rev'        => 0.0
                ];
            }
            $currTs = strtotime('+1 day', $currTs);
        }

        $maxRev = 0;
        $totalRangeRev = 0;
        $totalRangeOrders = 0;
        $totalRangeAllOrders = 0;
        $peakDate = null;
        $peakRev = 0;
        $peakOrders = 0;

        foreach ($chartRaw as $r) {
            $rev = (float)$r['valid_rev'];
            $orders = (int)($r['valid_orders'] ?? $r['total_orders']);
            if ($rev > $maxRev) $maxRev = $rev;
            $totalRangeRev += $rev;
            $totalRangeOrders += $orders;
            $totalRangeAllOrders += (int)$r['total_orders'];

            if ($rev > $peakRev || $peakDate === null) {
                $peakRev = $rev;
                $peakDate = $r['order_date'];
                $peakOrders = $orders;
            }
        }
        if ($maxRev <= 0) $maxRev = 1000000;

        $chartPoints = [];
        $countPoints = count($chartRaw);
        $svgWidth = 720;
        $padding = 40;
        $usableWidth = $svgWidth - ($padding * 2);
        $step = $countPoints > 1 ? ($usableWidth / ($countPoints - 1)) : $usableWidth;

        $vietnameseDays = [
            'Mon' => 'Thứ Hai',
            'Tue' => 'Thứ Ba',
            'Wed' => 'Thứ Tư',
            'Thu' => 'Thứ Năm',
            'Fri' => 'Thứ Sáu',
            'Sat' => 'Thứ Bảy',
            'Sun' => 'Chủ Nhật'
        ];

        foreach ($chartRaw as $idx => $r) {
            $dateTimestamp = strtotime($r['order_date']);
            $isToday = ($r['order_date'] === $today);
            $dateLabel = date('d/m', $dateTimestamp);
            $fullDateLabel = date('d/m/Y', $dateTimestamp);
            $dayName = $vietnameseDays[date('D', $dateTimestamp)] ?? '';
            
            if ($isToday) {
                $dateLabel .= ' (Hôm nay)';
            }

            $rev = (float)$r['valid_rev'];
            $orders = (int)($r['valid_orders'] ?? $r['total_orders']);

            $barH = $rev > 0 ? max(20, min(150, (int)round(($rev / $maxRev) * 135) + 15)) : ($orders > 0 ? 25 : 10);
            $x = round($padding + ($idx * $step), 1);
            $y = 190 - $barH;

            $isMilestone = ($isToday || ($peakRev > 0 && $r['order_date'] === $peakDate));

            $chartPoints[] = [
                'order_date'       => $r['order_date'],
                'label'            => $dateLabel,
                'short_label'      => date('d/m', $dateTimestamp),
                'full_date'        => $fullDateLabel,
                'day_name'         => $dayName,
                'rev'              => $rev,
                'rev_formatted'    => format_currency($rev),
                'completed_rev'    => (float)$r['completed_rev'],
                'orders'           => $orders,
                'total_orders'     => (int)$r['total_orders'],
                'cancelled_orders' => (int)($r['cancelled_orders'] ?? 0),
                'bar_h'            => $barH,
                'x'                => $x,
                'y'                => $y,
                'milestone'        => $isMilestone
            ];
        }

        $daysCount = count($chartRaw);
        $avgDailyRev = $daysCount > 0 ? round($totalRangeRev / $daysCount) : 0;
        $avgOrderVal = $totalRangeOrders > 0 ? round($totalRangeRev / $totalRangeOrders) : 0;

        return [
            'range'                 => $range,
            'start_date'            => $fromDate,
            'end_date'              => $toDate,
            'range_label'           => date('d/m/Y', strtotime($fromDate)) . ' - ' . date('d/m/Y', strtotime($toDate)),
            'days_count'            => $daysCount,
            'points'                => $chartPoints,
            'max_rev'               => $maxRev,
            'total_range_rev'       => $totalRangeRev,
            'total_range_rev_f'     => format_currency($totalRangeRev),
            'total_range_orders'    => $totalRangeOrders,
            'total_range_all_orders'=> $totalRangeAllOrders,
            'avg_daily_rev'         => $avgDailyRev,
            'avg_daily_rev_f'       => format_currency($avgDailyRev),
            'avg_order_value'       => $avgOrderVal,
            'avg_order_value_f'     => format_currency($avgOrderVal),
            'peak_day'              => [
                'date'              => $peakDate ?? $today,
                'full_date'         => $peakDate ? date('d/m/Y', strtotime($peakDate)) : date('d/m/Y'),
                'rev'               => $peakRev,
                'rev_formatted'     => format_currency($peakRev),
                'orders'            => $peakOrders
            ]
        ];
    }

    /**
     * AJAX endpoint: lấy dữ liệu biểu đồ khi người dùng đổi mốc thời gian (hoặc chọn mốc ngày cụ thể)
     * Route: admin/dashboard/chart-data
     */
    public function chartData(): void {
        header('Content-Type: application/json; charset=utf-8');
        $range = $_GET['range'] ?? '7days';
        $startDate = !empty($_GET['start_date']) ? trim($_GET['start_date']) : null;
        $endDate = !empty($_GET['end_date']) ? trim($_GET['end_date']) : null;

        $allowed = ['today', 'yesterday', '7days', '14days', '30days', 'this_month', 'last_month', 'custom'];
        if (!in_array($range, $allowed, true)) {
            $range = '7days';
        }

        $data = $this->getChartDataForRange($range, $startDate, $endDate);
        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }

    /**
     * AJAX endpoint: Lấy danh sách các đơn hàng theo mốc ngày khi click vào sơ đồ
     * Route: admin/dashboard/orders-by-date
     */
    public function ordersByDate(): void {
        header('Content-Type: application/json; charset=utf-8');
        $date = trim($_GET['date'] ?? '');
        if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $date = date('Y-m-d');
        }

        $db = Database::getInstance()->getConnection();
        $sql = "SELECT dh.*, nd.ho_ten, nd.so_dien_thoai, tt.phuong_thuc as payment_method,
                       (SELECT COUNT(*) FROM chi_tiet_don_hang WHERE don_hang_id = dh.id) as item_count
                FROM `don_hang` dh
                JOIN `nguoi_dung` nd ON dh.nguoi_dung_id = nd.id
                LEFT JOIN `thanh_toan` tt ON dh.id = tt.don_hang_id
                WHERE DATE(dh.ngay_dat) = :date
                ORDER BY dh.id DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':date' => $date]);
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $statusLabels = [
            'cho_xac_nhan' => ['label' => 'Chờ xác nhận', 'color' => 'bg-amber-100 text-amber-800 border-amber-300'],
            'da_xac_nhan'  => ['label' => 'Đã xác nhận',  'color' => 'bg-blue-100 text-blue-800 border-blue-300'],
            'dang_giao'    => ['label' => 'Đang giao hàng', 'color' => 'bg-sky-100 text-sky-800 border-sky-300'],
            'hoan_thanh'   => ['label' => 'Hoàn thành', 'color' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
            'da_huy'       => ['label' => 'Đã hủy', 'color' => 'bg-rose-100 text-rose-800 border-rose-300']
        ];

        $totalRev = 0.0;
        foreach ($orders as &$ord) {
            $st = $ord['trang_thai'] ?? 'cho_xac_nhan';
            $ord['status_info'] = $statusLabels[$st] ?? ['label' => $st, 'color' => 'bg-slate-100 text-slate-800 border-slate-300'];
            $ord['tong_thanh_toan_formatted'] = format_currency((float)$ord['tong_thanh_toan']);
            $ord['ngay_dat_formatted'] = date('H:i d/m/Y', strtotime($ord['ngay_dat']));
            $ord['detail_url'] = base_url('admin/don-hang/chi-tiet/' . $ord['id']);
            if ($st !== 'da_huy') {
                $totalRev += (float)$ord['tong_thanh_toan'];
            }
        }
        unset($ord);

        echo json_encode([
            'success'              => true,
            'date'                 => $date,
            'date_formatted'       => date('d/m/Y', strtotime($date)),
            'total_orders'         => count($orders),
            'total_rev'            => $totalRev,
            'total_rev_formatted'  => format_currency($totalRev),
            'orders'               => $orders
        ]);
        exit;
    }
}
