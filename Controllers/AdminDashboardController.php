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
        
        // 2. Dữ liệu Biểu đồ Doanh thu & Biến động Đơn hàng (Truy vấn thực tế từ CSDL)
        $chartData = $this->getRevenueChartDatasets($db);
        $currentChartDataset = $chartData['datasets'][$chartData['defaultRange']];
        $chartPoints = $currentChartDataset['points'];

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

        // 6. Đánh giá khách hàng chờ duyệt
        $sqlPendingReviews = "SELECT dg.*, nd.ho_ten, s.tieu_de as book_title
                              FROM `danh_gia` dg
                              JOIN `nguoi_dung` nd ON dg.nguoi_dung_id = nd.id
                              JOIN `sach` s ON dg.sach_id = s.id
                              ORDER BY (dg.trang_thai = 'cho_duyet') DESC, dg.id DESC
                              LIMIT 2";
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
            'chartRange'           => $chartData['defaultRange'],
            'currentChartDataset'  => $currentChartDataset,
            'allChartDatasets'     => $chartData['datasets'],
            'hasHistoricalOrders'  => $chartData['hasHistoricalOrders'],
            'categoryBreakdown'    => $categoryBreakdown,
            'recentOrders'         => $recentOrders,
            'topBooks'             => $topBooks,
            'pendingReviews'       => $pendingReviews,
            'lowStockBooks'        => $lowStockBooks
        ], 'admin');
    }

    /**
     * Thu thập và chuẩn hóa dữ liệu doanh số & đơn hàng thực tế từ bảng don_hang
     */
    private function getRevenueChartDatasets(\PDO $db): array {
        // Truy vấn doanh thu và số đơn thực tế theo từng ngày (chỉ tính đơn hợp lệ: trang_thai != 'da_huy')
        $stmtDaily = $db->query("SELECT 
            DATE(ngay_dat) as order_date,
            COUNT(CASE WHEN trang_thai != 'da_huy' THEN 1 END) as total_orders,
            COALESCE(SUM(CASE WHEN trang_thai != 'da_huy' THEN tong_thanh_toan ELSE 0 END), 0) as total_revenue,
            COALESCE(SUM(CASE WHEN trang_thai = 'hoan_thanh' THEN tong_thanh_toan ELSE 0 END), 0) as completed_revenue
        FROM `don_hang`
        GROUP BY DATE(ngay_dat)
        ORDER BY order_date ASC");
        $dailyRows = $stmtDaily ? $stmtDaily->fetchAll(\PDO::FETCH_ASSOC) : [];

        $salesByDate = [];
        foreach ($dailyRows as $r) {
            $d = $r['order_date'];
            $salesByDate[$d] = [
                'orders'        => (int)$r['total_orders'],
                'rev'           => (float)$r['total_revenue'],
                'completed_rev' => (float)$r['completed_revenue']
            ];
        }

        $today = date('Y-m-d');

        // 1. DATASET: 7 NGÀY GẦN NHẤT (tính đến hôm nay)
        $ds7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $short = date('d/m', strtotime($d));
            $isToday = ($d === $today);
            $rev = $salesByDate[$d]['rev'] ?? 0;
            $orders = $salesByDate[$d]['orders'] ?? 0;
            $ds7Days[] = [
                'date'     => $d,
                'label'    => $isToday ? ($short . ' (Hôm nay)') : $short,
                'short'    => $short,
                'rev'      => $rev,
                'orders'   => $orders,
                'is_today' => $isToday
            ];
        }

        // 2. DATASET: 30 NGÀY GẦN NHẤT
        $ds30Days = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $short = date('d/m', strtotime($d));
            $isToday = ($d === $today);
            $rev = $salesByDate[$d]['rev'] ?? 0;
            $orders = $salesByDate[$d]['orders'] ?? 0;
            $ds30Days[] = [
                'date'     => $d,
                'label'    => $isToday ? ($short . ' (Hôm nay)') : $short,
                'short'    => $short,
                'rev'      => $rev,
                'orders'   => $orders,
                'is_today' => $isToday
            ];
        }

        // 3. DATASET: 12 THÁNG NĂM HIỆN TẠI
        $currentYear = (int)date('Y');
        $stmtMonthly = $db->prepare("SELECT 
            MONTH(ngay_dat) as m,
            COUNT(CASE WHEN trang_thai != 'da_huy' THEN 1 END) as total_orders,
            COALESCE(SUM(CASE WHEN trang_thai != 'da_huy' THEN tong_thanh_toan ELSE 0 END), 0) as total_revenue
        FROM `don_hang`
        WHERE YEAR(ngay_dat) = :year
        GROUP BY MONTH(ngay_dat)");
        $stmtMonthly->execute(['year' => $currentYear]);
        $monthlyRows = $stmtMonthly ? $stmtMonthly->fetchAll(\PDO::FETCH_ASSOC) : [];
        $salesByMonth = [];
        foreach ($monthlyRows as $r) {
            $salesByMonth[(int)$r['m']] = [
                'orders' => (int)$r['total_orders'],
                'rev'    => (float)$r['total_revenue']
            ];
        }

        $dsYear = [];
        $currentMonth = (int)date('n');
        for ($m = 1; $m <= 12; $m++) {
            $rev = $salesByMonth[$m]['rev'] ?? 0;
            $orders = $salesByMonth[$m]['orders'] ?? 0;
            $dsYear[] = [
                'date'       => sprintf('%04d-%02d', $currentYear, $m),
                'label'      => 'Tháng ' . $m,
                'short'      => 'Th' . $m,
                'rev'        => $rev,
                'orders'     => $orders,
                'is_current' => ($m === $currentMonth)
            ];
        }

        // 4. DATASET: CÁC MỐC CÓ ĐƠN HÀNG THỰC TẾ TRONG HỆ THỐNG
        $dsActive = [];
        if (!empty($dailyRows)) {
            foreach ($dailyRows as $r) {
                if ((int)$r['total_orders'] > 0 || (float)$r['total_revenue'] > 0) {
                    $d = $r['order_date'];
                    $short = date('d/m', strtotime($d));
                    $dsActive[] = [
                        'date'     => $d,
                        'label'    => $short . ' (' . date('Y', strtotime($d)) . ')',
                        'short'    => $short,
                        'rev'      => (float)$r['total_revenue'],
                        'orders'   => (int)$r['total_orders'],
                        'is_today' => ($d === $today)
                    ];
                }
            }
        }

        if (count($dsActive) > 12) {
            $dsActive = array_slice($dsActive, -12);
        }

        // Tự động nhận diện khoảng thời gian trực quan nhất
        $has7DaysData = array_sum(array_column($ds7Days, 'orders')) > 0;
        $has30DaysData = array_sum(array_column($ds30Days, 'orders')) > 0;
        
        $defaultRange = '7days';
        if (!$has7DaysData) {
            if ($has30DaysData) {
                $defaultRange = '30days';
            } elseif (!empty($dsActive)) {
                $defaultRange = 'active';
            }
        }

        $requestedRange = $_GET['range'] ?? null;
        if ($requestedRange && in_array($requestedRange, ['7days', '30days', 'year', 'active'], true)) {
            $defaultRange = $requestedRange;
        }

        $datasets = [
            '7days'  => $this->prepareChartPoints($ds7Days),
            '30days' => $this->prepareChartPoints($ds30Days),
            'year'   => $this->prepareChartPoints($dsYear),
            'active' => $this->prepareChartPoints($dsActive)
        ];

        return [
            'defaultRange'        => $defaultRange,
            'datasets'            => $datasets,
            'hasHistoricalOrders' => !empty($dsActive)
        ];
    }

    /**
     * Tính toán toạ độ SVG, chiều cao cột bar_h và điểm peak cho dataset
     */
    private function prepareChartPoints(array $points): array {
        if (empty($points)) {
            return [
                'points'      => [],
                'maxRev'      => 0,
                'maxOrders'   => 0,
                'peakPoint'   => null,
                'svgPath'     => '',
                'svgAreaPath' => ''
            ];
        }

        $maxRev = 0;
        $maxOrders = 0;
        $peakIndex = -1;

        foreach ($points as $idx => $pt) {
            if ($pt['rev'] > $maxRev) {
                $maxRev = $pt['rev'];
                $peakIndex = $idx;
            }
            if ($pt['orders'] > $maxOrders) {
                $maxOrders = $pt['orders'];
            }
        }

        if ($peakIndex === -1 && $maxOrders > 0) {
            foreach ($points as $idx => $pt) {
                if ($pt['orders'] === $maxOrders) {
                    $peakIndex = $idx;
                    break;
                }
            }
        }

        $count = count($points);
        $viewWidth = 760;
        $paddingX = 50;
        $availableW = $viewWidth - ($paddingX * 2);
        $stepX = $count > 1 ? ($availableW / ($count - 1)) : 0;

        $svgPoints = [];
        foreach ($points as $i => &$pt) {
            $x = $count > 1 ? round($paddingX + $i * $stepX) : 380;
            
            // Chiều cao cột bar (max 130px)
            if ($maxOrders > 0 && $pt['orders'] > 0) {
                $barH = max(18, (int)round(($pt['orders'] / $maxOrders) * 130));
            } else {
                $barH = 4;
            }

            // Tọa độ Y doanh thu (từ y=175 xuống y=35)
            if ($maxRev > 0 && $pt['rev'] > 0) {
                $y = round(175 - (($pt['rev'] / $maxRev) * 140));
            } else {
                $y = 175;
            }

            $pt['x'] = $x;
            $pt['y'] = $y;
            $pt['bar_h'] = $barH;
            $pt['milestone'] = ($i === $peakIndex && ($pt['rev'] > 0 || $pt['orders'] > 0));
            $svgPoints[] = ['x' => $x, 'y' => $y];
        }
        unset($pt);

        // Tạo đường cong SVG mượt mà
        $svgPath = '';
        $svgAreaPath = '';
        if (!empty($svgPoints)) {
            $first = $svgPoints[0];
            $last = end($svgPoints);

            $lineD = "M {$first['x']} {$first['y']}";
            for ($i = 0; $i < count($svgPoints) - 1; $i++) {
                $p0 = $svgPoints[$i];
                $p1 = $svgPoints[$i + 1];
                $midX = round(($p0['x'] + $p1['x']) / 2);
                $lineD .= " C {$midX} {$p0['y']}, {$midX} {$p1['y']}, {$p1['x']} {$p1['y']}";
            }

            $svgPath = $lineD;
            $svgAreaPath = "{$lineD} L {$last['x']} 180 L {$first['x']} 180 Z";
        }

        return [
            'points'      => $points,
            'maxRev'      => $maxRev,
            'maxOrders'   => $maxOrders,
            'peakPoint'   => ($peakIndex >= 0) ? ($points[$peakIndex] ?? null) : null,
            'svgPath'     => $svgPath,
            'svgAreaPath' => $svgAreaPath
        ];
    }
}
