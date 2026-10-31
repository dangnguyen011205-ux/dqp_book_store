<?php
namespace Core;

class App
{
    protected string $controller = 'Controllers\\HomeController';
    protected string $action = 'index';
    protected array $params = [];

    // Bảng định nghĩa Friendly Routes
    protected array $routes = [
        // Khách hàng - Auth & Account
        '' => ['HomeController', 'index'],
        'trang-chu' => ['HomeController', 'index'],
        'dang-nhap' => ['AuthController', 'login'],
        'dang-ky' => ['AuthController', 'register'],
        'dang-xuat' => ['AuthController', 'logout'],
        'tai-khoan' => ['AccountController', 'profile'],
        'tai-khoan/ho-so' => ['AccountController', 'profile'],
        'ho-so-ca-nhan' => ['AccountController', 'profile'],
        'tai-khoan/doi-mat-khau' => ['AccountController', 'changePassword'],
        'tai-khoan/so-dia-chi' => ['AccountController', 'addresses'],
        'so-dia-chi' => ['AccountController', 'addresses'],
        'tai-khoan/so-dia-chi/them' => ['AccountController', 'addAddress'],
        'tai-khoan/so-dia-chi/sua/(\d+)' => ['AccountController', 'editAddress'],
        'tai-khoan/so-dia-chi/xoa/(\d+)' => ['AccountController', 'deleteAddress'],
        'tai-khoan/so-dia-chi/mac-dinh/(\d+)' => ['AccountController', 'setDefaultAddress'],
        'tai-khoan/don-hang' => ['AccountController', 'orders'],
        'lich-su-don-hang' => ['AccountController', 'orders'],
        'tai-khoan/don-hang/huy/(\d+)' => ['AccountController', 'cancelOrder'],

        // Khách hàng - Sách & Tìm kiếm
        'tim-kiem' => ['BookController', 'index'],
        'sach' => ['BookController', 'index'],
        'sach-moi-ve' => ['BookController', 'index'],
        'sach-ban-chay' => ['BookController', 'index'],
        'khuyen-mai-hot' => ['BookController', 'index'],
        'tu-sach-tinh-hoa' => ['BookController', 'index'],
        'chi-tiet/(\d+)' => ['BookController', 'detail'],
        'sach/(\d+)' => ['BookController', 'detail'],
        'danh-gia/gui' => ['BookController', 'addReview'],

        // Khách hàng - Giỏ hàng & Thanh toán
        'gio-hang' => ['CartController', 'index'],
        'gio-hang/them' => ['CartController', 'add'],
        'gio-hang/cap-nhat' => ['CartController', 'update'],
        'gio-hang/xoa/(\d+)' => ['CartController', 'remove'],
        'gio-hang/xoa-tat-ca' => ['CartController', 'clear'],
        'gio-hang/ap-dung-voucher' => ['CartController', 'applyCoupon'],
        'gio-hang/go-voucher' => ['CartController', 'removeCoupon'],
        'thanh-toan' => ['CheckoutController', 'index'],
        'thanh-toan/dat-hang' => ['CheckoutController', 'process'],
        'thanh-toan/thanh-cong/(\d+)' => ['CheckoutController', 'success'],
        'tra-cuu-don-hang' => ['AccountController', 'orders'],
        'tai-khoan/don-hang/chi-tiet/(\d+)' => ['AccountController', 'orderDetail'],
        'tai-khoan/don-hang/(\d+)' => ['AccountController', 'orderDetail'],

        // Admin Dashboard & Quản lý
        'admin' => ['AdminDashboardController', 'index'],
        'tong-quan-quan-tri' => ['AdminDashboardController', 'index'],
        'admin/sach' => ['AdminBookController', 'index'],
        'quan-ly-kho-sach' => ['AdminBookController', 'index'],
        'admin/sach/them' => ['AdminBookController', 'create'],
        'admin/sach/sua/(\d+)' => ['AdminBookController', 'edit'],
        'admin/sach/xoa/(\d+)' => ['AdminBookController', 'delete'],
        'admin/sach/toggle-status/(\d+)' => ['AdminBookController', 'toggleStatus'],

        'admin/kho' => ['AdminInventoryController', 'index'],
        'admin/kho/tao-phieu' => ['AdminInventoryController', 'createSlip'],

        'admin/don-hang' => ['AdminOrderController', 'index'],
        'quan-ly-don-hang' => ['AdminOrderController', 'index'],
        'admin/don-hang/chi-tiet/(\d+)' => ['AdminOrderController', 'detail'],
        'admin/don-hang/cap-nhat-trang-thai' => ['AdminOrderController', 'updateStatus'],

        'admin/danh-gia' => ['AdminReviewController', 'index'],
        'duyet-danh-gia' => ['AdminReviewController', 'index'],
        'admin/danh-gia/duyet/(\d+)' => ['AdminReviewController', 'approve'],
        'admin/danh-gia/an/(\d+)' => ['AdminReviewController', 'hide'],
        'admin/danh-gia/xoa/(\d+)' => ['AdminReviewController', 'delete'],
        'admin/danh-gia/cap-nhat-trang-thai' => ['AdminReviewController', 'updateStatus'],
        'admin/danh-gia/bulk' => ['AdminReviewController', 'bulk'],

        'admin/the-loai' => ['AdminCategoryController', 'index'],
        'quan-ly-danh-muc' => ['AdminCategoryController', 'index'],
        'admin/the-loai/them' => ['AdminCategoryController', 'create'],
        'admin/the-loai/chi-tiet/(\d+)' => ['AdminCategoryController', 'detail'],
        'admin/the-loai/sua/(\d+)' => ['AdminCategoryController', 'edit'],
        'admin/the-loai/xoa/(\d+)' => ['AdminCategoryController', 'delete'],

        'admin/tac-gia' => ['AdminAuthorController', 'index'],
        'admin/tac-gia/them' => ['AdminAuthorController', 'create'],
        'admin/tac-gia/chi-tiet/(\d+)' => ['AdminAuthorController', 'detail'],
        'admin/tac-gia/sua/(\d+)' => ['AdminAuthorController', 'edit'],
        'admin/tac-gia/xoa/(\d+)' => ['AdminAuthorController', 'delete'],

        'admin/nha-xuat-ban' => ['AdminPublisherController', 'index'],
        'admin/nha-xuat-ban/them' => ['AdminPublisherController', 'create'],
        'admin/nha-xuat-ban/chi-tiet/(\d+)' => ['AdminPublisherController', 'detail'],
        'admin/nha-xuat-ban/sua/(\d+)' => ['AdminPublisherController', 'edit'],
        'admin/nha-xuat-ban/xoa/(\d+)' => ['AdminPublisherController', 'delete'],

        'admin/ma-giam-gia' => ['AdminCouponController', 'index'],
        'quan-ly-chuong-trinh-khuyen-mai' => ['AdminCouponController', 'index'],
        'admin/ma-giam-gia/them' => ['AdminCouponController', 'create'],
        'admin/ma-giam-gia/chi-tiet/(\d+)' => ['AdminCouponController', 'detail'],
        'admin/ma-giam-gia/sua/(\d+)' => ['AdminCouponController', 'edit'],
        'admin/ma-giam-gia/xoa/(\d+)' => ['AdminCouponController', 'delete'],

        'admin/nguoi-dung' => ['AdminUserController', 'index'],
        'quan-ly-khach-hang' => ['AdminUserController', 'index'],
        'admin/nguoi-dung/them' => ['AdminUserController', 'create'],
        'admin/nguoi-dung/khoa/(\d+)' => ['AdminUserController', 'toggleStatus'],
        'admin/nguoi-dung/chi-tiet/(\d+)' => ['AdminUserController', 'detail'],
        'admin/nguoi-dung/sua/(\d+)' => ['AdminUserController', 'update'],
        'admin/nguoi-dung/vai-tro/(\d+)' => ['AdminUserController', 'changeRole'],
    ];

    public function __construct()
    {
        $url = $this->parseUrl();

        // 1. Kiểm tra Friendly Routes trước
        $matched = false;
        foreach ($this->routes as $pattern => $target) {
            $regex = '#^' . $pattern . '$#u';
            if (preg_match($regex, $url, $matches)) {
                $this->controller = 'Controllers\\' . $target[0];
                $this->action = $target[1];
                array_shift($matches); // Bỏ full match
                $this->params = $matches;
                $matched = true;
                break;
            }
        }

        // 2. Nếu không match friendly route, phân tách theo mô hình MVC Controller/Action/Params
        if (!$matched && !empty($url)) {
            $segments = explode('/', $url);

            // Kiểm tra controller
            $potentialController = 'Controllers\\' . ucfirst($segments[0]) . 'Controller';
            if (class_exists($potentialController)) {
                $this->controller = $potentialController;
                unset($segments[0]);

                if (isset($segments[1])) {
                    $this->action = $segments[1];
                    unset($segments[1]);
                }
                $this->params = array_values($segments);
            } else {
                $this->render404("Đường dẫn '$url' không tồn tại.");
            }
        }

        // 3. Khởi tạo Controller và gọi Action
        if (class_exists($this->controller)) {
            $controllerInstance = new $this->controller();

            if (method_exists($controllerInstance, $this->action)) {
                call_user_func_array([$controllerInstance, $this->action], $this->params);
            } else {
                $this->render404("Không tìm thấy hành động '{$this->action}' trong {$this->controller}.");
            }
        } else {
            $this->render404("Không tìm thấy Controller: {$this->controller}.");
        }
    }

    protected function parseUrl(): string
    {
        if (isset($_GET['url'])) {
            $url = rawurldecode($_GET['url']);
            $url = filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL);
            return trim($url, '/');
        }
        return '';
    }

    protected function render404(string $message = 'Trang bạn yêu cầu không tồn tại hoặc đã được di chuyển.'): void
    {
        http_response_code(404);
        $title = "404 - Không Tìm Thấy Trang | " . APP_NAME;
        require ROOT_PATH . '/Views/client/404.php';
        exit;
    }
}
