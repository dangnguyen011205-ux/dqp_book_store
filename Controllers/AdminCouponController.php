<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Coupon;

class AdminCouponController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Danh sách mã giảm giá & Bảng điều khiển KPI voucher
     */
    public function index(): void {
        $search = trim($_GET['q'] ?? $_GET['search'] ?? '');
        $status = trim($_GET['status'] ?? 'tat_ca');
        $type = trim($_GET['type'] ?? 'tat_ca');

        $couponModel = new Coupon();
        $coupons = $couponModel->getAll(
            $search, 
            $status === 'tat_ca' ? '' : $status, 
            $type === 'tat_ca' ? '' : $type
        );
        $statistics = $couponModel->getStatistics();

        $this->view('admin/coupons/index', [
            'coupons'    => $coupons,
            'statistics' => $statistics,
            'search'     => $search,
            'status'     => $status,
            'type'       => $type
        ], 'admin');
    }

    /**
     * Lấy thông tin chi tiết voucher (JSON phục vụ nạp Modal sửa)
     */
    public function detail(int $id): void {
        $couponModel = new Coupon();
        $coupon = $couponModel->findById($id);

        header('Content-Type: application/json; charset=utf-8');
        if (!$coupon) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy mã giảm giá trong hệ thống.']);
            exit;
        }

        // Định dạng datetime-local cho input HTML (YYYY-MM-DDTHH:mm)
        $coupon['bat_dau_input'] = date('Y-m-d\TH:i', strtotime($coupon['bat_dau']));
        $coupon['ket_thuc_input'] = date('Y-m-d\TH:i', strtotime($coupon['ket_thuc']));

        echo json_encode(['success' => true, 'data' => $coupon]);
        exit;
    }

    /**
     * Thêm mới mã giảm giá
     */
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/ma-giam-gia');
        }

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        $ma = strtoupper(trim($_POST['ma'] ?? ''));
        $loai = trim($_POST['loai'] ?? 'phan_tram');
        $giaTri = (float)str_replace(['.', ','], ['', '.'], $_POST['gia_tri'] ?? '0');
        $donToiThieu = (float)str_replace(['.', ','], ['', '.'], $_POST['don_toi_thieu'] ?? '0');
        $batDau = trim($_POST['bat_dau'] ?? '');
        $ketThuc = trim($_POST['ket_thuc'] ?? '');
        $soLuotToiDa = (int)($_POST['so_luot_toi_da'] ?? 0);

        // Validation
        $errors = [];
        if (empty($ma)) {
            $errors[] = 'Mã giảm giá không được để trống.';
        } elseif (!preg_match('/^[A-Z0-9_\-]+$/', $ma)) {
            $errors[] = 'Mã giảm giá chỉ được chứa chữ cái, số và dấu gạch nối (không dấu cách).';
        }

        $couponModel = new Coupon();
        if (empty($errors) && $couponModel->checkCodeExists($ma)) {
            $errors[] = "Mã giảm giá '{$ma}' đã tồn tại trong hệ thống. Vui lòng chọn mã khác.";
        }

        if (!in_array($loai, ['phan_tram', 'tien_mat'])) {
            $errors[] = 'Loại giảm giá không hợp lệ.';
        }

        if ($giaTri <= 0) {
            $errors[] = 'Giá trị giảm giá phải lớn hơn 0.';
        } elseif ($loai === 'phan_tram' && $giaTri > 100) {
            $errors[] = 'Mức giảm theo phần trăm không được vượt quá 100%.';
        }

        if ($donToiThieu < 0) {
            $errors[] = 'Đơn tối thiểu không được âm.';
        }

        if (empty($batDau) || empty($ketThuc)) {
            $errors[] = 'Vui lòng chọn thời gian bắt đầu và kết thúc áp dụng mã.';
        } else {
            $timeBatDau = strtotime($batDau);
            $timeKetThuc = strtotime($ketThuc);
            if ($timeKetThuc <= $timeBatDau) {
                $errors[] = 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu áp dụng.';
            }
        }

        if ($soLuotToiDa < 0) {
            $errors[] = 'Số lượt sử dụng tối đa không được âm.';
        }

        if (!empty($errors)) {
            $msg = implode(' ', $errors);
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/ma-giam-gia');
        }

        // Lưu vào CSDL
        $id = $couponModel->create([
            'ma'            => $ma,
            'loai'          => $loai,
            'gia_tri'       => $giaTri,
            'don_toi_thieu' => $donToiThieu,
            'bat_dau'       => date('Y-m-d H:i:s', strtotime($batDau)),
            'ket_thuc'      => date('Y-m-d H:i:s', strtotime($ketThuc)),
            'so_luot_toi_da'=> $soLuotToiDa
        ]);

        $msg = "Đã khởi tạo thành công mã giảm giá '{$ma}'!";
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true, 'message' => $msg, 'id' => $id]);
            exit;
        }

        set_flash('success', $msg);
        redirect('admin/ma-giam-gia');
    }

    /**
     * Cập nhật thông tin mã giảm giá
     */
    public function edit(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/ma-giam-gia');
        }

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        $couponModel = new Coupon();
        $coupon = $couponModel->findById($id);
        if (!$coupon) {
            $msg = 'Mã giảm giá không tồn tại trong hệ thống.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/ma-giam-gia');
        }

        $ma = strtoupper(trim($_POST['ma'] ?? ''));
        $loai = trim($_POST['loai'] ?? 'phan_tram');
        $giaTri = (float)str_replace(['.', ','], ['', '.'], $_POST['gia_tri'] ?? '0');
        $donToiThieu = (float)str_replace(['.', ','], ['', '.'], $_POST['don_toi_thieu'] ?? '0');
        $batDau = trim($_POST['bat_dau'] ?? '');
        $ketThuc = trim($_POST['ket_thuc'] ?? '');
        $soLuotToiDa = (int)($_POST['so_luot_toi_da'] ?? 0);

        // Validation
        $errors = [];
        if (empty($ma)) {
            $errors[] = 'Mã giảm giá không được để trống.';
        } elseif (!preg_match('/^[A-Z0-9_\-]+$/', $ma)) {
            $errors[] = 'Mã giảm giá chỉ được chứa chữ cái, số và dấu gạch nối.';
        }

        if (empty($errors) && $couponModel->checkCodeExists($ma, $id)) {
            $errors[] = "Mã giảm giá '{$ma}' đã được dùng cho voucher khác. Vui lòng chọn mã khác.";
        }

        if (!in_array($loai, ['phan_tram', 'tien_mat'])) {
            $errors[] = 'Loại giảm giá không hợp lệ.';
        }

        if ($giaTri <= 0) {
            $errors[] = 'Giá trị giảm giá phải lớn hơn 0.';
        } elseif ($loai === 'phan_tram' && $giaTri > 100) {
            $errors[] = 'Mức giảm theo phần trăm không được vượt quá 100%.';
        }

        if ($donToiThieu < 0) {
            $errors[] = 'Đơn tối thiểu không được âm.';
        }

        if (empty($batDau) || empty($ketThuc)) {
            $errors[] = 'Vui lòng chọn thời gian bắt đầu và kết thúc áp dụng mã.';
        } else {
            $timeBatDau = strtotime($batDau);
            $timeKetThuc = strtotime($ketThuc);
            if ($timeKetThuc <= $timeBatDau) {
                $errors[] = 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu áp dụng.';
            }
        }

        if ($soLuotToiDa < 0) {
            $errors[] = 'Số lượt sử dụng tối đa không được âm.';
        }

        if (!empty($errors)) {
            $msg = implode(' ', $errors);
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/ma-giam-gia');
        }

        $ok = $couponModel->update($id, [
            'ma'            => $ma,
            'loai'          => $loai,
            'gia_tri'       => $giaTri,
            'don_toi_thieu' => $donToiThieu,
            'bat_dau'       => date('Y-m-d H:i:s', strtotime($batDau)),
            'ket_thuc'      => date('Y-m-d H:i:s', strtotime($ketThuc)),
            'so_luot_toi_da'=> $soLuotToiDa
        ]);

        $msg = $ok ? "Đã cập nhật mã giảm giá '{$ma}' thành công!" : "Lỗi khi cập nhật mã giảm giá.";
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => $ok, 'message' => $msg]);
            exit;
        }

        if ($ok) {
            set_flash('success', $msg);
        } else {
            set_flash('error', $msg);
        }
        redirect('admin/ma-giam-gia');
    }

    /**
     * Xóa mã giảm giá
     */
    public function delete(int $id): void {
        $couponModel = new Coupon();
        $result = $couponModel->delete($id);

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
            exit;
        }

        if ($result['success']) {
            set_flash('success', $result['message']);
        } else {
            set_flash('error', $result['message']);
        }
        redirect('admin/ma-giam-gia');
    }
}

