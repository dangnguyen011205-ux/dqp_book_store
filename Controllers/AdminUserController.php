<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\User;

class AdminUserController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Màn hình danh sách người dùng, bộ lọc và thống kê KPI
     */
    public function index(): void {
        $search = trim($_GET['q'] ?? $_GET['search'] ?? '');
        $role = trim($_GET['role'] ?? 'tat_ca');
        $status = trim($_GET['status'] ?? 'tat_ca');

        $userModel = new User();
        $users = $userModel->getAll(
            $search,
            $role === 'tat_ca' ? '' : $role,
            $status === 'tat_ca' ? '' : $status
        );
        $statistics = $userModel->getStatistics();
        $currentUserId = (int)Auth::id();

        $this->view('admin/users/index', [
            'users'         => $users,
            'statistics'    => $statistics,
            'search'        => $search,
            'role'          => $role,
            'status'        => $status,
            'currentUserId' => $currentUserId
        ], 'admin');
    }

    /**
     * Thêm mới tài khoản người dùng từ trang quản trị
     */
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/nguoi-dung');
        }

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        $hoTen = trim($_POST['ho_ten'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $matKhau = trim($_POST['mat_khau'] ?? '');
        $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
        $vaiTro = trim($_POST['vai_tro'] ?? 'khach_hang');

        // Validation
        if (empty($hoTen)) {
            $msg = 'Họ và tên không được để trống.';
            if ($isAjax) {
                http_response_code(400);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
            redirect('admin/nguoi-dung');
            return;
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = 'Địa chỉ email không đúng định dạng.';
            if ($isAjax) {
                http_response_code(400);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
            redirect('admin/nguoi-dung');
            return;
        }

        $userModel = new User();
        $existing = $userModel->findByEmail($email);
        if ($existing) {
            $msg = "Địa chỉ email [{$email}] đã được sử dụng bởi một tài khoản khác trong hệ thống.";
            if ($isAjax) {
                http_response_code(400);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
            redirect('admin/nguoi-dung');
            return;
        }

        if (empty($matKhau) || mb_strlen($matKhau) < 6) {
            $msg = 'Mật khẩu phải có độ dài tối thiểu 6 ký tự.';
            if ($isAjax) {
                http_response_code(400);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
            redirect('admin/nguoi-dung');
            return;
        }

        if (!in_array($vaiTro, ['khach_hang', 'quan_tri'], true)) {
            $vaiTro = 'khach_hang';
        }

        $matKhauHash = password_hash($matKhau, PASSWORD_BCRYPT);
        $newId = $userModel->create([
            'ho_ten'        => $hoTen,
            'email'         => $email,
            'mat_khau_hash' => $matKhauHash,
            'so_dien_thoai' => !empty($soDienThoai) ? $soDienThoai : null,
            'vai_tro'       => $vaiTro,
            'trang_thai'    => 'hoat_dong'
        ]);

        if ($newId > 0) {
            $roleLabel = ($vaiTro === 'quan_tri') ? 'Quản Trị Viên' : 'Khách Hàng';
            $msg = "Tạo tài khoản [{$hoTen}] ({$roleLabel}) thành công!";
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success'  => true,
                    'message'  => $msg,
                    'user_id'  => $newId
                ]);
                exit;
            }
            flash('success', $msg);
        } else {
            $msg = 'Có lỗi xảy ra khi lưu tài khoản vào cơ sở dữ liệu.';
            if ($isAjax) {
                http_response_code(500);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
        }

        redirect('admin/nguoi-dung');
    }

    /**
     * Khóa hoặc mở khóa tài khoản người dùng
     * RÀNG BUỘC BẢO MẬT: Chặn Admin tự khóa tài khoản của chính mình
     */
    public function toggleStatus(int $id): void {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
        $currentUserId = (int)Auth::id();

        if ($id === $currentUserId) {
            $msg = 'Bảo vệ an toàn: Bạn không thể tự khóa tài khoản quản trị của chính mình!';
            if ($isAjax) {
                http_response_code(400);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
            redirect('admin/nguoi-dung');
            return;
        }

        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            $msg = 'Không tìm thấy người dùng trong hệ thống.';
            if ($isAjax) {
                http_response_code(404);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
            redirect('admin/nguoi-dung');
            return;
        }

        $newStatus = ($user['trang_thai'] === 'hoat_dong') ? 'khoa' : 'hoat_dong';
        $success = $userModel->updateStatus($id, $newStatus);

        if ($success) {
            $actionText = ($newStatus === 'khoa') ? 'Đã khóa tài khoản' : 'Đã mở khóa tài khoản';
            $msg = "{$actionText} [{$user['ho_ten']}] thành công.";
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success'    => true,
                    'message'    => $msg,
                    'new_status' => $newStatus,
                    'user_id'    => $id
                ]);
                exit;
            }
            flash('success', $msg);
        } else {
            $msg = 'Có lỗi xảy ra khi cập nhật trạng thái tài khoản.';
            if ($isAjax) {
                http_response_code(500);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
        }

        redirect('admin/nguoi-dung');
    }

    /**
     * Thay đổi vai trò người dùng (Phân quyền: 'khach_hang' <-> 'quan_tri')
     * RÀNG BUỘC BẢO MẬT: Chặn Admin tự hạ quyền quản trị của chính mình
     */
    public function changeRole(int $id): void {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
        $currentUserId = (int)Auth::id();

        if ($id === $currentUserId) {
            $msg = 'Bảo vệ an toàn: Bạn không thể tự thay đổi vai trò quản trị của chính mình!';
            if ($isAjax) {
                http_response_code(400);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
            redirect('admin/nguoi-dung');
            return;
        }

        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            $msg = 'Không tìm thấy người dùng trong hệ thống.';
            if ($isAjax) {
                http_response_code(404);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
            redirect('admin/nguoi-dung');
            return;
        }

        // Nếu có POST vai_tro thì dùng, nếu không thì tự đảo vai trò
        $targetRole = trim($_POST['vai_tro'] ?? '');
        if (!in_array($targetRole, ['khach_hang', 'quan_tri'], true)) {
            $targetRole = ($user['vai_tro'] === 'quan_tri') ? 'khach_hang' : 'quan_tri';
        }

        $success = $userModel->changeRole($id, $targetRole);

        if ($success) {
            $roleLabel = ($targetRole === 'quan_tri') ? 'Quản Trị Viên' : 'Khách Hàng';
            $msg = "Đã cập nhật vai trò của [{$user['ho_ten']}] thành \"{$roleLabel}\".";
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success'     => true,
                    'message'     => $msg,
                    'new_role'    => $targetRole,
                    'role_label'  => $roleLabel,
                    'user_id'     => $id
                ]);
                exit;
            }
            flash('success', $msg);
        } else {
            $msg = 'Có lỗi xảy ra khi cập nhật phân quyền người dùng.';
            if ($isAjax) {
                http_response_code(500);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
        }

        redirect('admin/nguoi-dung');
    }

    /**
     * Xem chi tiết thông tin, địa chỉ và lịch sử đơn hàng của người dùng (AJAX Modal)
     */
    public function detail(int $id): void {
        $userModel = new User();
        $user = $userModel->getUserDetailWithOrders($id);

        header('Content-Type: application/json; charset=utf-8');
        if (!$user) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin người dùng.']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'data'    => $user
        ]);
        exit;
    }

    /**
     * Cập nhật thông tin cơ bản người dùng (Modal chỉnh sửa / Đặt lại mật khẩu)
     */
    public function update(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/nguoi-dung');
        }

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            $msg = 'Không tìm thấy người dùng.';
            if ($isAjax) {
                http_response_code(404);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
            redirect('admin/nguoi-dung');
            return;
        }

        $hoTen = trim($_POST['ho_ten'] ?? '');
        $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
        $newPassword = trim($_POST['mat_khau_moi'] ?? '');

        if (empty($hoTen)) {
            $msg = 'Họ và tên không được để trống.';
            if ($isAjax) {
                http_response_code(400);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            flash('error', $msg);
            redirect('admin/nguoi-dung');
            return;
        }

        $userModel->updateProfile($id, [
            'ho_ten'        => $hoTen,
            'so_dien_thoai' => $soDienThoai
        ]);

        if (!empty($newPassword)) {
            $hash = password_hash($newPassword, PASSWORD_BCRYPT);
            $userModel->updatePassword($id, $hash);
        }

        $msg = "Đã cập nhật thông tin tài khoản [{$hoTen}] thành công.";
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true, 'message' => $msg]);
            exit;
        }

        flash('success', $msg);
        redirect('admin/nguoi-dung');
    }
}

