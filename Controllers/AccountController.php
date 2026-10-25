<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\User;
use Models\Address;

class AccountController extends Controller {
    protected User $userModel;
    protected Address $addressModel;

    public function __construct() {
        Auth::requireLogin();
        $this->userModel = new User();
        $this->addressModel = new Address();
    }

    /**
     * Xem và cập nhật Hồ sơ cá nhân
     */
    public function profile(): void {
        $userId = Auth::id();
        $user = $this->userModel->findById($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hoTen = trim($_POST['ho_ten'] ?? '');
            $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');

            if (empty($hoTen)) {
                flash('error', 'Họ tên không được để trống.');
                redirect('tai-khoan');
            }

            $this->userModel->updateProfile($userId, [
                'ho_ten'        => $hoTen,
                'so_dien_thoai' => $soDienThoai
            ]);

            // Cập nhật lại session
            $_SESSION['user']['ho_ten'] = $hoTen;
            $_SESSION['user']['so_dien_thoai'] = $soDienThoai;

            flash('success', 'Cập nhật thông tin hồ sơ thành công.');
            redirect('tai-khoan');
        }

        $this->view('client.account.profile', [
            'title' => 'Hồ Sơ Cá Nhân | ' . APP_NAME,
            'user'  => $user
        ]);
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = Auth::id();
            $user = $this->userModel->findById($userId);

            $oldPassword = $_POST['mat_khau_cu'] ?? '';
            $newPassword = $_POST['mat_khau_moi'] ?? '';
            $confirmPassword = $_POST['mat_khau_xac_nhan'] ?? '';

            if (empty($oldPassword) || empty($newPassword)) {
                flash('error', 'Vui lòng nhập đầy đủ mật khẩu hiện tại và mật khẩu mới.');
                redirect('tai-khoan');
            }

            if (!password_verify($oldPassword, $user['mat_khau_hash'])) {
                flash('error', 'Mật khẩu hiện tại không chính xác.');
                redirect('tai-khoan');
            }

            if (strlen($newPassword) < 6) {
                flash('error', 'Mật khẩu mới phải có tối thiểu 6 ký tự.');
                redirect('tai-khoan');
            }

            if ($newPassword !== $confirmPassword) {
                flash('error', 'Xác nhận mật khẩu mới không trùng khớp.');
                redirect('tai-khoan');
            }

            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
            $this->userModel->updatePassword($userId, $newHash);

            flash('success', 'Đổi mật khẩu thành công! Vui lòng ghi nhớ mật khẩu mới.');
            redirect('tai-khoan');
        }

        redirect('tai-khoan');
    }

    /**
     * Sổ địa chỉ nhận hàng
     */
    public function addresses(): void {
        $userId = Auth::id();
        $addresses = $this->addressModel->getByUserId($userId);

        $this->view('client.account.addresses', [
            'title'     => 'Sổ Địa Chỉ Nhận Hàng | ' . APP_NAME,
            'addresses' => $addresses
        ]);
    }

    /**
     * Thêm địa chỉ mới
     */
    public function addAddress(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = Auth::id();
            $nguoiNhan = trim($_POST['nguoi_nhan'] ?? '');
            $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
            $diaChi = trim($_POST['dia_chi'] ?? '');
            $macDinh = !empty($_POST['mac_dinh']) ? 1 : 0;

            if (empty($nguoiNhan) || empty($soDienThoai) || empty($diaChi)) {
                flash('error', 'Vui lòng điền đầy đủ người nhận, số điện thoại và địa chỉ chi tiết.');
                redirect('tai-khoan/so-dia-chi');
            }

            $this->addressModel->create([
                'nguoi_dung_id' => $userId,
                'nguoi_nhan'    => $nguoiNhan,
                'so_dien_thoai' => $soDienThoai,
                'dia_chi'       => $diaChi,
                'mac_dinh'      => $macDinh
            ]);

            flash('success', 'Đã thêm địa chỉ nhận hàng mới thành công.');
            redirect('tai-khoan/so-dia-chi');
        }

        redirect('tai-khoan/so-dia-chi');
    }

    /**
     * Chỉnh sửa địa chỉ
     */
    public function editAddress(string $id): void {
        $userId = Auth::id();
        $addressId = (int)$id;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nguoiNhan = trim($_POST['nguoi_nhan'] ?? '');
            $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
            $diaChi = trim($_POST['dia_chi'] ?? '');
            $macDinh = !empty($_POST['mac_dinh']) ? 1 : 0;

            if (empty($nguoiNhan) || empty($soDienThoai) || empty($diaChi)) {
                flash('error', 'Vui lòng điền đầy đủ người nhận, số điện thoại và địa chỉ chi tiết.');
                redirect('tai-khoan/so-dia-chi');
            }

            $this->addressModel->update($addressId, $userId, [
                'nguoi_nhan'    => $nguoiNhan,
                'so_dien_thoai' => $soDienThoai,
                'dia_chi'       => $diaChi,
                'mac_dinh'      => $macDinh
            ]);

            flash('success', 'Đã cập nhật địa chỉ nhận hàng thành công.');
            redirect('tai-khoan/so-dia-chi');
        }

        redirect('tai-khoan/so-dia-chi');
    }

    /**
     * Xóa địa chỉ
     */
    public function deleteAddress(string $id): void {
        $userId = Auth::id();
        $addressId = (int)$id;

        $res = $this->addressModel->delete($addressId, $userId);

        if ($res) {
            flash('success', 'Đã xóa địa chỉ nhận hàng thành công.');
        } else {
            flash('error', 'Không tìm thấy địa chỉ hoặc bạn không có quyền xóa.');
        }

        redirect('tai-khoan/so-dia-chi');
    }

    /**
     * Đặt làm địa chỉ mặc định
     */
    public function setDefaultAddress(string $id): void {
        $userId = Auth::id();
        $addressId = (int)$id;

        $this->addressModel->setDefault($addressId, $userId);
        flash('success', 'Đã thiết lập địa chỉ mặc định thành công.');
        redirect('tai-khoan/so-dia-chi');
    }

    /**
     * Quản lý đơn hàng của tôi (Sẵn sàng phục vụ Giai đoạn 3)
     */
    public function orders(): void {
        // Tạm chuyển tiếp tới trang đơn hàng hoặc thông báo
        redirect('tai-khoan');
    }
}
