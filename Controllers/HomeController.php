<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Book;
use Models\Category;
use Models\Author;
use Models\Review;
use Models\Advertisement;
use Models\FlashSale;

class HomeController extends Controller {
    protected Book $bookModel;
    protected Category $categoryModel;
    protected Author $authorModel;
    protected Review $reviewModel;
    protected Advertisement $adModel;
    protected FlashSale $flashSaleModel;

    public function __construct() {
        $this->bookModel = new Book();
        $this->categoryModel = new Category();
        $this->authorModel = new Author();
        $this->reviewModel = new Review();
        $this->adModel = new Advertisement();
        $this->flashSaleModel = new FlashSale();
    }

    public function index(): void {
        $categories = $this->categoryModel->getAll();
        $bestsellers = $this->bookModel->getBestsellers(6);
        $authors = $this->authorModel->getFeatured(6);
        $featuredReviews = $this->reviewModel->getFeaturedReviews(3);
        $advertisements = $this->adModel->getActiveAds();

        // Flash Sale: lấy đang active + sắp bắt đầu
        $activeFlashSales   = $this->flashSaleModel->getActive(8);
        $upcomingFlashSales = $this->flashSaleModel->getUpcoming(4);
        $flashItems         = array_merge($activeFlashSales, $upcomingFlashSales);

        $userId = Auth::check() ? Auth::id() : null;
        $savedVoucherIds = [];
        if ($userId) {
            $savedVouchers = $this->adModel->getUserSavedVouchers($userId);
            $savedVoucherIds = array_column($savedVouchers, 'ma_giam_gia_id');
        }

        $this->view('client.home', [
            'title'           => APP_NAME . ' - ' . APP_SLOGAN . ' | Thư Viện Tinh Hoa',
            'categories'      => $categories,
            'flashItems'      => $flashItems,
            'bestsellers'     => $bestsellers,
            'authors'         => $authors,
            'featuredReviews' => $featuredReviews,
            'advertisements'  => $advertisements,
            'savedVoucherIds' => $savedVoucherIds
        ]);
    }

    /**
     * AJAX endpoint: trả về trạng thái flash sale hiện tại (polling mỗi 30 giây)
     * Route: flash-sale/status
     */
    public function flashSaleStatus(): void {
        header('Content-Type: application/json; charset=utf-8');

        $activeRaw   = $this->flashSaleModel->getActive(8);
        $upcomingRaw = $this->flashSaleModel->getUpcoming(4);

        $mapItem = function(array $item, bool $isActive): array {
            return [
                'id'        => (int)$item['id'],
                'sach_id'   => (int)$item['sach_id'],
                'tieu_de'   => $item['tieu_de'],
                'anh_bia'   => $item['anh_bia'],
                'gia'       => (float)$item['gia'],
                'gia_sale'  => (float)$item['gia_sale'],
                'bat_dau'   => strtotime($item['bat_dau']),
                'ket_thuc'  => strtotime($item['ket_thuc']),
                'is_active' => $isActive,
            ];
        };

        $active   = array_map(fn($i) => $mapItem($i, true),  $activeRaw);
        $upcoming = array_map(fn($i) => $mapItem($i, false), $upcomingRaw);

        echo json_encode([
            'active'      => $active,
            'upcoming'    => $upcoming,
            'server_time' => time(),
        ]);
        exit;
    }

    /**
     * Khách hàng ấn Lưu Voucher từ Banner quảng cáo trên trang chủ (AJAX)
     */
    public function saveVoucher(?string $paramId = null): void {
        header('Content-Type: application/json; charset=utf-8');

        $couponId = (int)($paramId ?? $_POST['coupon_id'] ?? 0);
        if ($couponId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Mã voucher không hợp lệ.']);
            exit;
        }

        if (!Auth::check()) {
            echo json_encode([
                'success'       => false,
                'require_login' => true,
                'message'       => 'Vui lòng đăng nhập tài khoản để lưu voucher vào ví ưu đãi cá nhân.'
            ]);
            exit;
        }

        $userId = Auth::id();
        $result = $this->adModel->saveUserVoucher($userId, $couponId);
        echo json_encode($result);
        exit;
    }
}
