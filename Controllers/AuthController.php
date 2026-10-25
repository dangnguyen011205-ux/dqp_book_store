<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\User;
use Models\Cart;

class AuthController extends Controller {
    protected User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Đăng nhập tài khoản
     */
    public function login(): void {
        if (Auth::check()) {
            if (Auth::isAdmin()) {
                redirect('admin');
            }
            redirect('trang-chu');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $_SESSION['old']['email'] = $email;

            if (empty($email) || empty($password)) {
                flash('error', 'Vui lòng nhập đầy đủ Email và Mật khẩu.');
                redirect('dang-nhap');
            }

            $user = $this->userModel->findByEmail($email);

            if (!$user || !password_verify($password, $user['mat_khau_hash'])) {
                flash('error', 'Email hoặc mật khẩu không chính xác.');
                redirect('dang-nhap');
            }

            // Kiểm tra trạng thái khóa tài khoản
            if ($user['trang_thai'] === 'khoa') {
                flash('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
                redirect('dang-nhap');
            }

            // Đăng nhập thành công
            Auth::login($user);
            unset($_SESSION['old']);

            flash('success', 'Chào mừng ' . htmlspecialchars($user['ho_ten']) . ' đã quay trở lại!');

            // Chuyển hướng tới trang trước đó nếu có
            if (!empty($_SESSION['redirect_after_login'])) {
                $target = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                redirect($target);
            }

            if ($user['vai_tro'] === 'quan_tri') {
                redirect('admin');
            }

            redirect('trang-chu');
        }

        $this->view('client.auth.login', [
            'title' => 'Đăng Nhập Tài Khoản | ' . APP_NAME
        ]);
    }

    /**
     * Đăng ký tài khoản
     */
    public function register(): void {
        if (Auth::check()) {
            redirect('trang-chu');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hoTen = trim($_POST['ho_ten'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
            $password = $_POST['mat_khau'] ?? '';
            $passwordConfirm = $_POST['mat_khau_xac_nhan'] ?? '';

            $_SESSION['old'] = [
                'ho_ten' => $hoTen,
                'email' => $email,
                'so_dien_thoai' => $soDienThoai
            ];

            // Validation
            if (empty($hoTen) || empty($email) || empty($password)) {
                flash('error', 'Vui lòng điền đầy đủ các thông tin bắt buộc.');
                redirect('dang-ky');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                flash('error', 'Địa chỉ email không đúng định dạng.');
                redirect('dang-ky');
            }

            if (strlen($password) < 6) {
                flash('error', 'Mật khẩu phải chứa ít nhất 6 ký tự.');
                redirect('dang-ky');
            }

            if ($password !== $passwordConfirm) {
                flash('error', 'Mật khẩu xác nhận không khớp.');
                redirect('dang-ky');
            }

            // Kiểm tra trùng email
            if ($this->userModel->findByEmail($email)) {
                flash('error', 'Email này đã được sử dụng. Vui lòng chọn email khác hoặc đăng nhập.');
                redirect('dang-ky');
            }

            // Tạo người dùng mới
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $userId = $this->userModel->create([
                'ho_ten'        => $hoTen,
                'email'         => $email,
                'mat_khau_hash' => $hash,
                'so_dien_thoai' => $soDienThoai,
                'vai_tro'       => 'khach_hang',
                'trang_thai'    => 'hoat_dong'
            ]);

            // Khởi tạo giỏ hàng cho user
            (new Cart())->getOrCreateCartId($userId);

            unset($_SESSION['old']);

            // Tự động đăng nhập
            $newUser = $this->userModel->findById($userId);
            Auth::login($newUser);

            flash('success', 'Đăng ký tài khoản thành công! Chào mừng bạn gia nhập DQP Book Store.');
            redirect('trang-chu');
        }

        $this->view('client.auth.register', [
            'title' => 'Đăng Ký Thành Viên | ' . APP_NAME
        ]);
    }

    /**
     * Đăng xuất tài khoản
     */
    public function logout(): void {
        Auth::logout();
        flash('success', 'Bạn đã đăng xuất tài khoản thành công.');
        redirect('dang-nhap');
    }
}
