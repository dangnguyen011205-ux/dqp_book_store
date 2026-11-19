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
        // Khi người dùng xem giỏ hàng đầy đủ, xóa trạng thái mua nhanh (nếu có trước đó)
        unset($_SESSION['buy_now_item']);

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

        // Xử lý voucher (Mã giảm giá đơn hàng & Mã freeship chuẩn Shopee)
        $appliedDiscountCoupon = null;
        $appliedFreeshipCoupon = null;
        $discount = 0.0;
        $shippingDiscount = 0.0;

        // 1. Mã giảm giá đơn hàng
        $discountCode = $_SESSION['applied_discount_coupon']['code'] ?? $_SESSION['applied_coupon']['code'] ?? null;
        if (!empty($discountCode) && $subtotal > 0) {
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

        // 2. Mã freeship
        $freeshipCode = $_SESSION['applied_freeship_coupon']['code'] ?? null;
        if (empty($freeshipCode) && !empty($_SESSION['applied_coupon']['coupon']) && ($_SESSION['applied_coupon']['coupon']['loai_voucher'] ?? '') === 'mien_phi_van_chuyen') {
            $freeshipCode = $_SESSION['applied_coupon']['code'];
        }
        if (!empty($freeshipCode) && $subtotal > 0) {
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

        // Lấy danh sách voucher trong kho của user
        $walletCoupons = $this->couponModel->getUserWalletCoupons($userId, 'available');
        $freeshipCoupons = array_values(array_filter($walletCoupons, fn($c) => ($c['loai_voucher'] ?? 'giam_gia') === 'mien_phi_van_chuyen'));
        $discountCoupons = array_values(array_filter($walletCoupons, fn($c) => ($c['loai_voucher'] ?? 'giam_gia') !== 'mien_phi_van_chuyen'));

        $this->view('client.cart', [
            'title'                 => 'Giỏ Hàng Của Bạn | ' . APP_NAME,
            'items'                 => $items,
            'subtotal'              => $subtotal,
            'totalItems'            => $totalItems,
            'shippingFee'           => $shippingFee,
            'discount'              => $discount,
            'shippingDiscount'      => $shippingDiscount,
            'finalShippingFee'      => $finalShippingFee,
            'grandTotal'            => $grandTotal,
            'appliedCoupon'         => $appliedDiscountCoupon ?? $appliedFreeshipCoupon,
            'appliedDiscountCoupon' => $appliedDiscountCoupon,
            'appliedFreeshipCoupon' => $appliedFreeshipCoupon,
            'coupons'               => $walletCoupons,
            'freeshipCoupons'       => $freeshipCoupons,
            'discountCoupons'       => $discountCoupons
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

            // Xử lý riêng biệt khi MUA NGAY: chỉ mua đúng sản phẩm này, không gộp và không ảnh hưởng giỏ hàng
            if ($action === 'buy_now') {
                $singleItem = $this->cartModel->getSingleItemForCheckout($bookId, $quantity);
                if (!$singleItem) {
                    flash('error', 'Tác phẩm không tồn tại hoặc đã hết hàng trong kho.');
                    $referrer = $_SERVER['HTTP_REFERER'] ?? '';
                    if (!empty($referrer) && strpos($referrer, base_url()) !== false) {
                        header("Location: $referrer");
                        exit;
                    }
                    redirect('tim-kiem');
                }

                // Gán phiên mua ngay độc lập
                $_SESSION['buy_now_item'] = [
                    'sach_id'  => $bookId,
                    'so_luong' => $quantity
                ];

                // Tự động áp dụng voucher nếu có chọn từ form Mua Ngay (Shopee Style)
                $couponCode = trim($_POST['coupon_code'] ?? '');
                if (!empty($couponCode)) {
                    $subtotal = (float)$singleItem['thanh_tien'];
                    $shippingFee = ($subtotal >= 250000) ? 0.0 : 22000.0;
                    $cResult = $this->couponModel->validateCoupon($couponCode, $subtotal, $userId, $shippingFee);
                    if ($cResult['valid']) {
                        if ($cResult['loai_voucher'] === 'mien_phi_van_chuyen') {
                            $_SESSION['applied_freeship_coupon'] = [
                                'code'     => $cResult['coupon']['ma'],
                                'discount' => $cResult['discount'],
                                'coupon'   => $cResult['coupon']
                            ];
                        } else {
                            $_SESSION['applied_discount_coupon'] = [
                                'code'     => $cResult['coupon']['ma'],
                                'discount' => $cResult['discount'],
                                'coupon'   => $cResult['coupon']
                            ];
                            $_SESSION['applied_coupon'] = $_SESSION['applied_discount_coupon'];
                        }
                    }
                }

                redirect('thanh-toan');
            }

            // Thêm vào giỏ hàng thông thường (xóa cờ mua ngay nếu có)
            unset($_SESSION['buy_now_item']);
            $result = $this->cartModel->addItem($userId, $bookId, $quantity);

            if ($result['success']) {
                flash('success', $result['message']);
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
     * Áp dụng mã giảm giá (Chuẩn Shopee: Hỗ trợ áp dụng đồng thời Mã Giảm Giá Đơn Hàng + Mã Freeship)
     */
    public function applyCoupon(): void {
        $redirectTo = (!empty($_POST['redirect_to']) && $_POST['redirect_to'] === 'thanh-toan') ? 'thanh-toan' : 'gio-hang';
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xác định danh sách sản phẩm để tính subtotal (Mua Ngay hoặc Giỏ Hàng)
            $items = [];
            if ($redirectTo === 'thanh-toan' && !empty($_SESSION['buy_now_item'])) {
                $bn = $_SESSION['buy_now_item'];
                $single = $this->cartModel->getSingleItemForCheckout((int)$bn['sach_id'], (int)($bn['so_luong'] ?? 1));
                if ($single) {
                    $items = [$single];
                }
            } else {
                $userId = Auth::id();
                $items = $this->cartModel->getItems($userId);
            }

            if (empty($items)) {
                $msg = 'Không có sản phẩm nào để áp dụng mã ưu đãi.';
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['success' => false, 'message' => $msg]);
                    exit;
                }
                flash('error', $msg);
                redirect($redirectTo);
            }

            $subtotal = 0.0;
            foreach ($items as $item) {
                $subtotal += (float)$item['thanh_tien'];
            }

            $shippingMethod = $_POST['shipping_method'] ?? 'standard';
            $shippingFee = ($subtotal >= 250000) ? 0.0 : 22000.0;
            if ($shippingMethod === 'express') {
                $shippingFee = 40000.0;
            }

            $currentUserId = Auth::check() ? Auth::id() : null;

            // TRƯỜNG HỢP 1: Nhận từ Modal Shopee (gửi cùng lúc discount_code và/hoặc freeship_code)
            if (isset($_POST['shopee_modal']) || isset($_POST['discount_code']) || isset($_POST['freeship_code'])) {
                $discountCode = trim($_POST['discount_code'] ?? '');
                $freeshipCode = trim($_POST['freeship_code'] ?? '');

                $discountMessages = [];
                $successApplied = false;

                // Xử lý mã giảm giá
                if (!empty($discountCode)) {
                    $valResult = $this->couponModel->validateCoupon($discountCode, $subtotal, $currentUserId, $shippingFee);
                    if ($valResult['valid']) {
                        $_SESSION['applied_discount_coupon'] = [
                            'code'     => $valResult['coupon']['ma'],
                            'discount' => $valResult['discount'],
                            'coupon'   => $valResult['coupon']
                        ];
                        $_SESSION['applied_coupon'] = $_SESSION['applied_discount_coupon'];
                        $discountMessages[] = $valResult['message'];
                        $successApplied = true;
                    } else {
                        $discountMessages[] = $valResult['message'];
                    }
                } else {
                    // Người dùng chủ động bỏ chọn mã giảm giá
                    unset($_SESSION['applied_discount_coupon'], $_SESSION['applied_coupon']);
                }

                // Xử lý mã freeship
                if (!empty($freeshipCode)) {
                    $valShip = $this->couponModel->validateCoupon($freeshipCode, $subtotal, $currentUserId, $shippingFee);
                    if ($valShip['valid']) {
                        $_SESSION['applied_freeship_coupon'] = [
                            'code'     => $valShip['coupon']['ma'],
                            'discount' => $valShip['discount'],
                            'coupon'   => $valShip['coupon']
                        ];
                        $discountMessages[] = $valShip['message'];
                        $successApplied = true;
                    } else {
                        $discountMessages[] = $valShip['message'];
                    }
                } else {
                    // Người dùng chủ động bỏ chọn mã freeship
                    unset($_SESSION['applied_freeship_coupon']);
                }

                $msg = !empty($discountMessages) ? implode(' ', $discountMessages) : 'Đã cập nhật lựa chọn voucher.';

                if ($isAjax) {
                    $curDisc = (float)($_SESSION['applied_discount_coupon']['discount'] ?? 0.0);
                    $curShipDisc = (float)($_SESSION['applied_freeship_coupon']['discount'] ?? 0.0);
                    $finalShip = max(0.0, $shippingFee - $curShipDisc);
                    $grandTotal = max(0.0, $subtotal - $curDisc + $finalShip);

                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode([
                        'success'          => $successApplied || empty($discountCode) || empty($freeshipCode),
                        'message'          => $msg,
                        'subtotal'         => $subtotal,
                        'shippingFee'      => $shippingFee,
                        'discount'         => $curDisc,
                        'shippingDiscount' => $curShipDisc,
                        'finalShippingFee' => $finalShip,
                        'grandTotal'       => $grandTotal,
                        'appliedDiscount'  => $_SESSION['applied_discount_coupon'] ?? null,
                        'appliedFreeship'  => $_SESSION['applied_freeship_coupon'] ?? null
                    ]);
                    exit;
                }

                if ($successApplied) {
                    flash('success', $msg);
                } else {
                    flash('info', $msg);
                }
                redirect($redirectTo);
            }

            // TRƯỜNG HỢP 2: Người dùng nhập 1 mã đơn lẻ vào ô input
            $code = trim($_POST['coupon_code'] ?? '');
            if (empty($code)) {
                $msg = 'Vui lòng nhập mã giảm giá.';
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['success' => false, 'message' => $msg]);
                    exit;
                }
                flash('error', $msg);
                redirect($redirectTo);
            }

            $result = $this->couponModel->validateCoupon($code, $subtotal, $currentUserId, $shippingFee);
            if ($result['valid']) {
                if ($result['loai_voucher'] === 'mien_phi_van_chuyen') {
                    $_SESSION['applied_freeship_coupon'] = [
                        'code'     => $result['coupon']['ma'],
                        'discount' => $result['discount'],
                        'coupon'   => $result['coupon']
                    ];
                } else {
                    $_SESSION['applied_discount_coupon'] = [
                        'code'     => $result['coupon']['ma'],
                        'discount' => $result['discount'],
                        'coupon'   => $result['coupon']
                    ];
                    $_SESSION['applied_coupon'] = $_SESSION['applied_discount_coupon'];
                }

                if ($isAjax) {
                    $curDisc = (float)($_SESSION['applied_discount_coupon']['discount'] ?? 0.0);
                    $curShipDisc = (float)($_SESSION['applied_freeship_coupon']['discount'] ?? 0.0);
                    $finalShip = max(0.0, $shippingFee - $curShipDisc);
                    $grandTotal = max(0.0, $subtotal - $curDisc + $finalShip);

                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode([
                        'success'          => true,
                        'message'          => $result['message'],
                        'subtotal'         => $subtotal,
                        'shippingFee'      => $shippingFee,
                        'discount'         => $curDisc,
                        'shippingDiscount' => $curShipDisc,
                        'finalShippingFee' => $finalShip,
                        'grandTotal'       => $grandTotal,
                        'appliedDiscount'  => $_SESSION['applied_discount_coupon'] ?? null,
                        'appliedFreeship'  => $_SESSION['applied_freeship_coupon'] ?? null
                    ]);
                    exit;
                }

                flash('success', $result['message']);
            } else {
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['success' => false, 'message' => $result['message']]);
                    exit;
                }
                flash('error', $result['message']);
            }
        }

        redirect($redirectTo);
    }

    /**
     * Gỡ bỏ mã giảm giá đang áp dụng
     * Hỗ trợ gỡ riêng mã freeship (?type=freeship), mã giảm giá (?type=discount) hoặc toàn bộ
     */
    public function removeCoupon(): void {
        $type = $_GET['type'] ?? 'all';
        if ($type === 'freeship') {
            unset($_SESSION['applied_freeship_coupon']);
            flash('info', 'Đã gỡ bỏ mã miễn phí vận chuyển.');
        } elseif ($type === 'discount') {
            unset($_SESSION['applied_discount_coupon'], $_SESSION['applied_coupon']);
            flash('info', 'Đã gỡ bỏ mã giảm giá đơn hàng.');
        } else {
            unset($_SESSION['applied_discount_coupon'], $_SESSION['applied_freeship_coupon'], $_SESSION['applied_coupon']);
            flash('info', 'Đã gỡ bỏ toàn bộ voucher ưu đãi.');
        }

        $redirectTo = (!empty($_GET['redirect_to']) && $_GET['redirect_to'] === 'thanh-toan') ? 'thanh-toan' : 'gio-hang';
        redirect($redirectTo);
    }
}
