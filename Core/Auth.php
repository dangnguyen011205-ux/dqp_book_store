<?php
namespace Core;

use Models\User;

class Auth {
    public static function check(): bool {
        if (!isset($_SESSION['user']['id'])) {
            return false;
        }

        // Kiểm tra an toàn trạng thái tài khoản trong DB
        return self::verifyAccountStatus();
    }

    public static function user(): ?array {
        if (self::check()) {
            return $_SESSION['user'];
        }
        return null;
    }

    public static function id(): ?int {
        return $_SESSION['user']['id'] ?? null;
    }

    public static function role(): ?string {
        return $_SESSION['user']['vai_tro'] ?? null;
    }

    public static function isAdmin(): bool {
        return self::check() && (self::role() === 'quan_tri');
    }

    public static function login(array $user): void {
        // Không lưu mật khẩu hash trong session
        unset($user['mat_khau_hash']);
        $_SESSION['user'] = $user;
        if (!headers_sent()) {
            session_regenerate_id(true);
        }
    }

    public static function logout(): void {
        unset($_SESSION['user']);
        if (isset($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    }

    public static function requireLogin(): void {
        if (!self::check()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? base_url();
            flash('error', 'Vui lòng đăng nhập để tiếp tục.');
            redirect('dang-nhap');
        }
    }

    public static function requireAdmin(): void {
        self::requireLogin();
        if (!self::isAdmin()) {
            flash('error', 'Bạn không có quyền truy cập vào trang quản trị.');
            redirect('trang-chu');
        }
    }

    public static function verifyAccountStatus(): bool {
        if (!isset($_SESSION['user']['id'])) {
            return false;
        }

        $userId = (int)$_SESSION['user']['id'];
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, ho_ten, email, vai_tro, trang_thai FROM nguoi_dung WHERE id = ? LIMIT 1");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user || $user['trang_thai'] === 'khoa') {
            self::logout();
            flash('error', 'Tài khoản của bạn đã bị khóa hoặc không tồn tại. Vui lòng liên hệ hỗ trợ.');
            return false;
        }

        // Cập nhật lại thông tin mới nhất vào session nếu có thay đổi
        $_SESSION['user']['ho_ten'] = $user['ho_ten'];
        $_SESSION['user']['vai_tro'] = $user['vai_tro'];
        $_SESSION['user']['trang_thai'] = $user['trang_thai'];

        return true;
    }
}
