<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Cart;
use Models\Coupon;
use Models\Order;
use Models\Address;
use Core\Vnpay;

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

        // Kiểm tra chế độ MUA NGAY (chỉ mua đúng 1 sản phẩm được chọn, không bao gồm toàn bộ giỏ hàng)
        $isBuyNow = !empty($_SESSION['buy_now_item']);
        if ($isBuyNow) {
            $bn = $_SESSION['buy_now_item'];
            $single = $this->cartModel->getSingleItemForCheckout((int)$bn['sach_id'], (int)($bn['so_luong'] ?? 1));
            if (!$single) {
                unset($_SESSION['buy_now_item']);
                flash('warning', 'Sản phẩm mua nhanh không tồn tại hoặc đã hết hàng.');
                redirect('trang-chu');
            }
            $items = [$single];
        } else {
            $items = $this->cartModel->getItems($userId);
            if (empty($items)) {
                flash('warning', 'Giỏ hàng của bạn đang trống, vui lòng chọn sách trước khi thanh toán.');
                redirect('tim-kiem');
            }
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

        // Xử lý voucher chuẩn Shopee (Hỗ trợ áp dụng đồng thời Voucher Giảm Giá + Mã Freeship)
        $appliedDiscountCoupon = null;
        $appliedFreeshipCoupon = null;
        $discount = 0.0;
        $shippingDiscount = 0.0;

        // 1. Voucher giảm giá đơn hàng
        $discountCode = $_SESSION['applied_discount_coupon']['code'] ?? $_SESSION['applied_coupon']['code'] ?? null;
        if (!empty($discountCode)) {
            $valResult = $this->couponModel->validateCoupon($discountCode, $subtotal, $userId, $shippingFee);
            if ($valResult['valid'] && $valResult['loai_voucher'] !== 'mien_phi_van_chuyen') {
                $appliedDiscountCoupon = $valResult['coupon'];
                $discount = (float)$valResult['discount'];
                $_SESSION['applied_discount_coupon'] = ['code' => $discountCode, 'discount' => $discount, 'coupon' => $appliedDiscountCoupon];
                $_SESSION['applied_coupon'] = $_SESSION['applied_discount_coupon'];
            } else {
                unset($_SESSION['applied_discount_coupon'], $_SESSION['applied_coupon']);
            }
        }

        // 2. Mã Freeship
        $freeshipCode = $_SESSION['applied_freeship_coupon']['code'] ?? null;
        if (empty($freeshipCode) && !empty($_SESSION['applied_coupon']['coupon']) && ($_SESSION['applied_coupon']['coupon']['loai_voucher'] ?? '') === 'mien_phi_van_chuyen') {
            $freeshipCode = $_SESSION['applied_coupon']['code'];
        }
        if (!empty($freeshipCode)) {
            $valShip = $this->couponModel->validateCoupon($freeshipCode, $subtotal, $userId, $shippingFee);
            if ($valShip['valid'] && $valShip['loai_voucher'] === 'mien_phi_van_chuyen') {
                $appliedFreeshipCoupon = $valShip['coupon'];
                $shippingDiscount = (float)$valShip['discount'];
                $_SESSION['applied_freeship_coupon'] = ['code' => $freeshipCode, 'discount' => $shippingDiscount, 'coupon' => $appliedFreeshipCoupon];
            } else {
                unset($_SESSION['applied_freeship_coupon']);
            }
        }

        $finalShippingFee = max(0.0, $shippingFee - $shippingDiscount);
        $grandTotal = max(0.0, $subtotal - $discount + $finalShippingFee);

        // Lấy danh sách voucher trong kho riêng của user, phân loại Shopee
        $walletCoupons = $this->couponModel->getUserWalletCoupons($userId, 'available');
        $freeshipCoupons = array_values(array_filter($walletCoupons, fn($c) => ($c['loai_voucher'] ?? 'giam_gia') === 'mien_phi_van_chuyen'));
        $discountCoupons = array_values(array_filter($walletCoupons, fn($c) => ($c['loai_voucher'] ?? 'giam_gia') !== 'mien_phi_van_chuyen'));

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
            'title'                 => 'Thanh Toán & Xác Nhận Đơn Hàng | ' . APP_NAME,
            'items'                 => $items,
            'subtotal'              => $subtotal,
            'totalQuantity'         => $totalQuantity,
            'shippingFee'           => $shippingFee,
            'discount'              => $discount,
            'shippingDiscount'      => $shippingDiscount,
            'finalShippingFee'      => $finalShippingFee,
            'grandTotal'            => $grandTotal,
            'appliedCoupon'         => $appliedDiscountCoupon ?? $appliedFreeshipCoupon,
            'appliedDiscountCoupon' => $appliedDiscountCoupon,
            'appliedFreeshipCoupon' => $appliedFreeshipCoupon,
            'addresses'             => $addresses,
            'defaultAddress'        => $defaultAddress,
            'coupons'               => $walletCoupons,
            'freeshipCoupons'       => $freeshipCoupons,
            'discountCoupons'       => $discountCoupons,
            'isBuyNow'              => $isBuyNow
        ]);
    }

    /**
     * Xử lý tạo đơn hàng với Database Transaction
     */
    public function process(): void {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = Auth::id();

            // Kiểm tra chế độ MUA NGAY: chỉ xử lý đúng sản phẩm đó, không trừ hay xóa giỏ hàng
            $isBuyNow = !empty($_SESSION['buy_now_item']);
            if ($isBuyNow) {
                $bn = $_SESSION['buy_now_item'];
                $single = $this->cartModel->getSingleItemForCheckout((int)$bn['sach_id'], (int)($bn['so_luong'] ?? 1));
                if (!$single) {
                    unset($_SESSION['buy_now_item']);
                    flash('error', 'Sản phẩm mua nhanh không còn khả dụng trong kho.');
                    redirect('trang-chu');
                }
                $items = [$single];
            } else {
                $items = $this->cartModel->getItems($userId);
                if (empty($items)) {
                    flash('error', 'Giỏ hàng của bạn đang trống.');
                    redirect('gio-hang');
                }
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

            // 3. Kiểm tra mã giảm giá & mã freeship (Chuẩn Shopee)
            $appliedDiscountCoupon = null;
            $appliedFreeshipCoupon = null;
            $discount = 0.0;
            $shippingDiscount = 0.0;

            // Mã giảm giá đơn hàng
            $discountCode = $_SESSION['applied_discount_coupon']['code'] ?? $_SESSION['applied_coupon']['code'] ?? null;
            if (!empty($discountCode)) {
                $valResult = $this->couponModel->validateCoupon($discountCode, $subtotal, $userId, $shippingFee);
                if ($valResult['valid'] && $valResult['loai_voucher'] !== 'mien_phi_van_chuyen') {
                    $appliedDiscountCoupon = $valResult['coupon'];
                    $discount = (float)$valResult['discount'];
                } else {
                    unset($_SESSION['applied_discount_coupon'], $_SESSION['applied_coupon']);
                }
            }

            // Mã freeship
            $freeshipCode = $_SESSION['applied_freeship_coupon']['code'] ?? null;
            if (empty($freeshipCode) && !empty($_SESSION['applied_coupon']['coupon']) && ($_SESSION['applied_coupon']['coupon']['loai_voucher'] ?? '') === 'mien_phi_van_chuyen') {
                $freeshipCode = $_SESSION['applied_coupon']['code'];
            }
            if (!empty($freeshipCode)) {
                $valShip = $this->couponModel->validateCoupon($freeshipCode, $subtotal, $userId, $shippingFee);
                if ($valShip['valid'] && $valShip['loai_voucher'] === 'mien_phi_van_chuyen') {
                    $appliedFreeshipCoupon = $valShip['coupon'];
                    $shippingDiscount = (float)$valShip['discount'];
                } else {
                    unset($_SESSION['applied_freeship_coupon']);
                }
            }

            $finalShippingFee = max(0.0, $shippingFee - $shippingDiscount);
            $grandTotal = max(0.0, $subtotal - $discount + $finalShippingFee);
            $paymentMethod = $_POST['payment_method'] ?? 'COD';
            $orderNotes = trim($_POST['order_notes'] ?? '');

            // 4. Tạo đơn hàng bằng Database Transaction
            $orderData = [
                'nguoi_dung_id'  => $userId,
                'nguoi_nhan'     => $nguoiNhan . " (" . $soDienThoai . ")",
                'dia_chi_giao'   => $diaChiGiao,
                'tong_tien_hang' => $subtotal,
                'tien_giam'      => $discount,
                'tien_giam_ship' => $shippingDiscount,
                'phi_van_chuyen' => $shippingFee,
                'tong_thanh_toan'=> $grandTotal,
                'phuong_thuc'    => $paymentMethod,
                'ghi_chu_khach'  => $orderNotes
            ];

            $result = $this->orderModel->createOrder($orderData, $items, $appliedDiscountCoupon, $isBuyNow, $appliedFreeshipCoupon);

            if ($result['success']) {
                unset($_SESSION['applied_coupon'], $_SESSION['applied_discount_coupon'], $_SESSION['applied_freeship_coupon']);
                if ($isBuyNow) {
                    unset($_SESSION['buy_now_item']);
                }
                flash('success', 'Đặt hàng thành công! Cảm ơn bạn đã lựa chọn DQP Book Store.');
                $orderId = (int)$result['order_id'];
                if ($paymentMethod === 'VNPAY') {
                    $orderInfo = "Thanh toan don hang BK{$orderId} tai DQP Book Store";
                    $vnpayUrl = Vnpay::createPaymentUrl($orderId, $grandTotal, $orderInfo);
                    header('Location: ' . $vnpayUrl);
                    exit;
                }
                redirect('thanh-toan/thanh-cong/' . $orderId);
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

    /**
     * Xử lý kết quả trả về từ Cổng thanh toán VNPAY (VNPAY Return URL)
     */
    public function vnpayReturn(): void {
        $queryParams = $_GET;
        $verify = Vnpay::verifyResponse($queryParams);

        $orderId = (int)$verify['order_id'];
        $order = null;
        if ($orderId > 0) {
            $order = $this->orderModel->getOrderDetail($orderId);
        }

        $isValid = $verify['is_valid'];
        $isSuccess = false;
        $message = '';

        if (!$isValid) {
            $message = 'Chữ ký số không hợp lệ hoặc dữ liệu phản hồi đã bị thay đổi.';
            if ($orderId > 0) {
                $this->orderModel->updatePaymentStatus(
                    $orderId, 
                    'that_bai', 
                    $verify['transaction_no'] ?: null, 
                    'Xác thực chữ ký số VNPAY Return thất bại'
                );
            }
        } else {
            $responseCode = $verify['response_code'];
            $message = Vnpay::getResponseMessage($responseCode);

            if ($responseCode === '00') {
                $isSuccess = true;
                if ($orderId > 0) {
                    $this->orderModel->updatePaymentStatus(
                        $orderId, 
                        'thanh_cong', 
                        $verify['transaction_no'] ?: null, 
                        'Thanh toán trực tuyến thành công qua VNPAY (Ngân hàng: ' . ($verify['bank_code'] ?? 'N/A') . ')'
                    );
                }
            } else {
                $isSuccess = false;
                if ($orderId > 0) {
                    $this->orderModel->updatePaymentStatus(
                        $orderId, 
                        'that_bai', 
                        $verify['transaction_no'] ?: null, 
                        'Giao dịch VNPAY không thành công: ' . $message
                    );
                }
            }
        }

        // Tải lại chi tiết đơn hàng sau khi cập nhật trạng thái thanh toán
        if ($orderId > 0) {
            $order = $this->orderModel->getOrderDetail($orderId);
        }

        $this->view('client.vnpay_result', [
            'title'     => ($isSuccess ? 'Thanh Toán Thành Công' : 'Thanh Toán Không Thành Công') . ' | ' . APP_NAME,
            'isSuccess' => $isSuccess,
            'message'   => $message,
            'verify'    => $verify,
            'order'     => $order
        ]);
    }

    /**
     * Xử lý Instant Payment Notification (IPN ngầm giữa server VNPAY và Bookstore)
     */
    public function vnpayIpn(): void {
        header('Content-Type: application/json; charset=utf-8');

        $queryParams = $_GET;
        $verify = Vnpay::verifyResponse($queryParams);

        if (!$verify['is_valid']) {
            echo json_encode([
                'RspCode' => '97',
                'Message' => 'Invalid signature'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $orderId = (int)$verify['order_id'];
        $order = $this->orderModel->getOrderDetail($orderId);

        if (!$order) {
            echo json_encode([
                'RspCode' => '01',
                'Message' => 'Order not found'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Kiểm tra số tiền có khớp không (cho phép chênh lệch làm tròn <= 1 đồng)
        $expectedAmount = (float)$order['tong_thanh_toan'];
        $receivedAmount = (float)$verify['amount'];
        if (abs($expectedAmount - $receivedAmount) > 1.0) {
            echo json_encode([
                'RspCode' => '04',
                'Message' => 'Invalid amount'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Kiểm tra xem đơn hàng đã được xác nhận thanh toán trước đó chưa
        $payment = $this->orderModel->getPaymentByOrderId($orderId);
        if ($payment && $payment['trang_thai'] === 'thanh_cong') {
            echo json_encode([
                'RspCode' => '02',
                'Message' => 'Order already confirmed'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Cập nhật trạng thái thanh toán theo mã phản hồi
        if ($verify['response_code'] === '00') {
            $this->orderModel->updatePaymentStatus(
                $orderId,
                'thanh_cong',
                $verify['transaction_no'] ?: null,
                'VNPAY IPN: Xác nhận thanh toán thành công qua ngân hàng ' . ($verify['bank_code'] ?? 'N/A')
            );
        } else {
            $this->orderModel->updatePaymentStatus(
                $orderId,
                'that_bai',
                $verify['transaction_no'] ?: null,
                'VNPAY IPN: Giao dịch thất bại (Code: ' . $verify['response_code'] . ')'
            );
        }

        echo json_encode([
            'RspCode' => '00',
            'Message' => 'Confirm Success'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Cho phép khách hàng thanh toán lại đơn hàng VNPAY chưa thanh toán hoặc thất bại
     */
    public function vnpayRetry(string $id): void {
        Auth::requireLogin();
        $userId = Auth::id();
        $orderId = (int)$id;

        $order = $this->orderModel->getOrderDetail($orderId, $userId);
        if (!$order) {
            flash('error', 'Không tìm thấy thông tin đơn hàng để thanh toán lại.');
            redirect('tai-khoan/don-hang');
        }

        // Đơn hàng chỉ được thanh toán lại nếu đang ở trạng thái 'cho_xac_nhan' và thanh toán chưa thành công
        if ($order['trang_thai'] !== 'cho_xac_nhan' || $order['trang_thai_thanh_toan'] === 'thanh_cong') {
            flash('warning', 'Đơn hàng này không thể thanh toán lại (đã xử lý hoặc đã hoàn tất thanh toán).');
            redirect('tai-khoan/don-hang/' . $orderId);
        }

        $grandTotal = (float)$order['tong_thanh_toan'];
        $orderInfo = "Thanh toan lai don hang BK{$orderId} tai DQP Book Store";
        $vnpayUrl = Vnpay::createPaymentUrl($orderId, $grandTotal, $orderInfo);

        header('Location: ' . $vnpayUrl);
        exit;
    }
}
