<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Cart;
use Models\Coupon;
use Models\Book;

class CartController extends Controller {
    protected Cart $cartModel;
    protected Coupon $couponModel;
    protected Book $bookModel;

    public function __construct() {
        $this->cartModel = new Cart();
        $this->couponModel = new Coupon();
        $this->bookModel = new Book();
    }

    /**
     * Màn hình Giỏ hàng tương tác
     */
    public function index(): void {
        $userId = Auth::id();
        $items = $this->cartModel->getItems($userId);

        $subtotal = 0.0;
        $totalItems = 0;
        foreach ($items as $item) {
            $subtotal += (float)$item['thanh_tien'];
            $totalItems += (int)$item['so_luong'];
        }

        // Quy tắc tính phí vận chuyển chuẩn: Đơn từ 250k miễn phí giao hàng (0đ), dưới 250k là 25.000đ
        $standardShipping = ($subtotal >= 250000 || $subtotal == 0) ? 0.0 : 25000.0;
        $shippingFee = $standardShipping;

        // Xử lý mã giảm giá trong Session nếu đã áp dụng
        $appliedCoupon = null;
        $discount = 0.0;
        if (!empty($_SESSION['applied_coupon']) && $subtotal > 0) {
            $couponCode = $_SESSION['applied_coupon']['code'];
            $valResult = $this->couponModel->validateCoupon($couponCode, $subtotal);

            if ($valResult['valid']) {
                $appliedCoupon = $valResult['coupon'];
                $discount = (float)$valResult['discount'];
                $_SESSION['applied_coupon']['discount'] = $discount;
                $_SESSION['applied_coupon']['coupon'] = $appliedCoupon;
            } else {
                // Nếu giỏ hàng thay đổi khiến voucher không còn hợp lệ
                flash('warning', "Mã ưu đãi '{$couponCode}' đã tự động gỡ: " . $valResult['message']);
                unset($_SESSION['applied_coupon']);
            }
        }

        $grandTotal = max(0.0, $subtotal - $discount + $shippingFee);
        $activeCoupons = $this->couponModel->getActiveCoupons();

        $this->view('client.cart', [
            'title'          => 'Giỏ Hàng Của Bạn | ' . APP_NAME,
            'items'          => $items,
            'subtotal'       => $subtotal,
            'totalItems'     => $totalItems,
            'shippingFee'    => $shippingFee,
            'discount'       => $discount,
            'grandTotal'     => $grandTotal,
            'appliedCoupon'  => $appliedCoupon,
            'activeCoupons'  => $activeCoupons
        ]);
    }

    /**
     * Thêm sách vào giỏ hàng (hỗ trợ Mua Ngay và Thêm Giỏ)
     */
    public function add(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = Auth::id();
            $bookId = (int)($_POST['sach_id'] ?? 0);
            $quantity = max(1, (int)($_POST['so_luong'] ?? 1));
            $action = $_POST['action'] ?? 'add_to_cart';

            $result = $this->cartModel->addItem($userId, $bookId, $quantity);

            if ($result['success']) {
                flash('success', $result['message']);
                if ($action === 'buy_now') {
                    redirect('thanh-toan');
                }
            } else {
                flash('error', $result['message']);
            }

            // Chuyển hướng về trang trước hoặc trang giỏ hàng
            $referrer = $_SERVER['HTTP_REFERER'] ?? '';
            if (!empty($referrer) && strpos($referrer, base_url()) !== false) {
                header("Location: $referrer");
                exit;
            }
            redirect('gio-hang');
        }

        redirect('gio-hang');
    }

    /**
     * Cập nhật số lượng sách trong giỏ
     */
    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = Auth::id();
            $bookId = (int)($_POST['sach_id'] ?? 0);
            $quantity = (int)($_POST['so_luong'] ?? 1);

            $result = $this->cartModel->updateItem($userId, $bookId, $quantity);
            if ($result['success']) {
                flash('success', $result['message']);
            } else {
                flash('error', $result['message']);
            }
        }

        redirect('gio-hang');
    }

    /**
     * Xóa một cuốn sách khỏi giỏ hàng
     */
    public function remove(string $id): void {
        $userId = Auth::id();
        $bookId = (int)$id;

        $result = $this->cartModel->removeItem($userId, $bookId);
        flash('success', $result['message']);
        redirect('gio-hang');
    }

    /**
     * Xóa toàn bộ sản phẩm trong giỏ
     */
    public function clear(): void {
        $userId = Auth::id();
        $this->cartModel->clearCart($userId);
        unset($_SESSION['applied_coupon']);

        flash('success', 'Đã dọn sạch giỏ hàng.');
        redirect('gio-hang');
    }

    /**
     * Áp dụng mã giảm giá
     */
    public function applyCoupon(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['coupon_code'] ?? '');
            if (empty($code)) {
                flash('error', 'Vui lòng nhập mã giảm giá.');
                redirect('gio-hang');
            }

            $userId = Auth::id();
            $items = $this->cartModel->getItems($userId);
            if (empty($items)) {
                flash('error', 'Giỏ hàng của bạn đang trống, không thể áp dụng mã ưu đãi.');
                redirect('gio-hang');
            }

            $subtotal = 0.0;
            foreach ($items as $item) {
                $subtotal += (float)$item['thanh_tien'];
            }

            $result = $this->couponModel->validateCoupon($code, $subtotal);
            if ($result['valid']) {
                $_SESSION['applied_coupon'] = [
                    'code'     => $result['coupon']['ma'],
                    'discount' => $result['discount'],
                    'coupon'   => $result['coupon']
                ];
                flash('success', $result['message']);
            } else {
                flash('error', $result['message']);
            }
        }

        redirect('gio-hang');
    }

    /**
     * Gỡ bỏ mã giảm giá đang áp dụng
     */
    public function removeCoupon(): void {
        unset($_SESSION['applied_coupon']);
        flash('info', 'Đã gỡ bỏ mã giảm giá.');
        redirect('gio-hang');
    }
}
