<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Cart;
use Models\Coupon;
use Models\Order;
use Models\Address;

class CheckoutController extends Controller {
    protected Cart $cartModel;
    protected Coupon $couponModel;
    protected Order $orderModel;
    protected Address $addressModel;

    public function __construct() {
        $this->cartModel = new Cart();
        $this->couponModel = new Coupon();
        $this->orderModel = new Order();
        $this->addressModel = new Address();
    }

    /**
     * Màn hình Thanh toán & Đặt hàng (Checkout)
     */
    public function index(): void {
        Auth::requireLogin();
        $userId = Auth::id();

        $items = $this->cartModel->getItems($userId);
        if (empty($items)) {
            flash('warning', 'Giỏ hàng của bạn đang trống, vui lòng chọn sách trước khi thanh toán.');
            redirect('tim-kiem');
        }

        // Tính tổng tiền hàng
        $subtotal = 0.0;
        $totalQuantity = 0;
        foreach ($items as $item) {
            $subtotal += (float)$item['thanh_tien'];
            $totalQuantity += (int)$item['so_luong'];
        }

        // Phí vận chuyển mặc định: Freeship nếu >= 250k, ngược lại 22.000đ
        $standardShipping = ($subtotal >= 250000) ? 0.0 : 22000.0;
        $shippingFee = $standardShipping;

        // Xử lý mã giảm giá
        $appliedCoupon = null;
        $discount = 0.0;
        if (!empty($_SESSION['applied_coupon'])) {
            $couponCode = $_SESSION['applied_coupon']['code'];
            $valResult = $this->couponModel->validateCoupon($couponCode, $subtotal);
            if ($valResult['valid']) {
                $appliedCoupon = $valResult['coupon'];
                $discount = (float)$valResult['discount'];
            } else {
                unset($_SESSION['applied_coupon']);
            }
        }

        $grandTotal = max(0.0, $subtotal - $discount + $shippingFee);

        // Lấy danh sách địa chỉ của người dùng
        $addresses = $this->addressModel->getByUserId($userId);
        $defaultAddress = null;
        foreach ($addresses as $addr) {
            if ((int)$addr['mac_dinh'] === 1) {
                $defaultAddress = $addr;
                break;
            }
        }
        if (!$defaultAddress && !empty($addresses)) {
            $defaultAddress = $addresses[0];
        }

        $this->view('client.checkout', [
            'title'          => 'Thanh Toán & Xác Nhận Đơn Hàng | ' . APP_NAME,
            'items'          => $items,
            'subtotal'       => $subtotal,
            'totalQuantity'  => $totalQuantity,
            'shippingFee'    => $shippingFee,
            'discount'       => $discount,
            'grandTotal'     => $grandTotal,
            'appliedCoupon'  => $appliedCoupon,
            'addresses'      => $addresses,
            'defaultAddress' => $defaultAddress
        ]);
    }

    /**
     * Xử lý tạo đơn hàng với Database Transaction
     */
    public function process(): void {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = Auth::id();
            $items = $this->cartModel->getItems($userId);

            if (empty($items)) {
                flash('error', 'Giỏ hàng của bạn đang trống.');
                redirect('gio-hang');
            }

            // 1. Xác định địa chỉ giao hàng
            $addressChoice = $_POST['address_choice'] ?? 'existing';
            $nguoiNhan = '';
            $soDienThoai = '';
            $diaChiGiao = '';

            if ($addressChoice === 'existing') {
                $addressId = (int)($_POST['address_id'] ?? 0);
                $address = $this->addressModel->getById($addressId, $userId);

                if (!$address) {
                    flash('error', 'Vui lòng chọn địa chỉ nhận hàng hợp lệ.');
                    redirect('thanh-toan');
                }

                $nguoiNhan = $address['nguoi_nhan'];
                $soDienThoai = $address['so_dien_thoai'];
                $diaChiGiao = $address['dia_chi'];
            } else {
                // Nhập địa chỉ mới trực tiếp
                $nguoiNhan = trim($_POST['new_nguoi_nhan'] ?? '');
                $soDienThoai = trim($_POST['new_so_dien_thoai'] ?? '');
                $diaChiChiTiet = trim($_POST['new_dia_chi'] ?? '');

                if (empty($nguoiNhan) || empty($soDienThoai) || empty($diaChiChiTiet)) {
                    flash('error', 'Vui lòng điền đầy đủ họ tên, số điện thoại và địa chỉ nhận hàng mới.');
                    redirect('thanh-toan');
                }

                $diaChiGiao = $diaChiChiTiet;

                // Tự động lưu vào sổ địa chỉ nếu người dùng chọn lưu
                if (!empty($_POST['save_new_address'])) {
                    $this->addressModel->create([
                        'nguoi_dung_id' => $userId,
                        'nguoi_nhan'    => $nguoiNhan,
                        'so_dien_thoai' => $soDienThoai,
                        'dia_chi'       => $diaChiGiao,
                        'mac_dinh'      => 0
                    ]);
                }
            }

            // 2. Tính toán tiền hàng và phí vận chuyển
            $subtotal = 0.0;
            foreach ($items as $item) {
                $subtotal += (float)$item['thanh_tien'];
            }

            $shippingMethod = $_POST['shipping_method'] ?? 'standard';
            $shippingFee = ($subtotal >= 250000) ? 0.0 : 22000.0;
            if ($shippingMethod === 'express') {
                $shippingFee = 40000.0; // Hỏa tốc
            }

            // 3. Kiểm tra mã giảm giá
            $appliedCoupon = null;
            $discount = 0.0;
            if (!empty($_SESSION['applied_coupon'])) {
                $couponCode = $_SESSION['applied_coupon']['code'];
                $valResult = $this->couponModel->validateCoupon($couponCode, $subtotal);
                if ($valResult['valid']) {
                    $appliedCoupon = $valResult['coupon'];
                    $discount = (float)$valResult['discount'];
                } else {
                    unset($_SESSION['applied_coupon']);
                }
            }

            $grandTotal = max(0.0, $subtotal - $discount + $shippingFee);
            $paymentMethod = $_POST['payment_method'] ?? 'COD';
            $orderNotes = trim($_POST['order_notes'] ?? '');

            // 4. Tạo đơn hàng bằng Database Transaction
            $orderData = [
                'nguoi_dung_id'  => $userId,
                'nguoi_nhan'     => $nguoiNhan . " (" . $soDienThoai . ")",
                'dia_chi_giao'   => $diaChiGiao,
                'tong_tien_hang' => $subtotal,
                'tien_giam'      => $discount,
                'phi_van_chuyen' => $shippingFee,
                'tong_thanh_toan'=> $grandTotal,
                'phuong_thuc'    => $paymentMethod,
                'ghi_chu_khach'  => $orderNotes
            ];

            $result = $this->orderModel->createOrder($orderData, $items, $appliedCoupon);

            if ($result['success']) {
                unset($_SESSION['applied_coupon']);
                flash('success', 'Đặt hàng thành công! Cảm ơn bạn đã lựa chọn DQP Book Store.');
                redirect('thanh-toan/thanh-cong/' . $result['order_id']);
            } else {
                flash('error', $result['message']);
                redirect('thanh-toan');
            }
        }

        redirect('thanh-toan');
    }

    /**
     * Màn hình Xác nhận Đặt hàng thành công
     */
    public function success(string $id): void {
        Auth::requireLogin();
        $userId = Auth::id();
        $orderId = (int)$id;

        $order = $this->orderModel->getOrderDetail($orderId, $userId);
        if (!$order) {
            flash('error', 'Không tìm thấy thông tin đơn hàng.');
            redirect('tai-khoan/don-hang');
        }

        $this->view('client.checkout_success', [
            'title' => 'Đặt Hàng Thành Công | ' . APP_NAME,
            'order' => $order
        ]);
    }
}
