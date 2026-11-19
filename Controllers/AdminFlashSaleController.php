<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Book;
use Models\FlashSale;

class AdminFlashSaleController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    // ─── INDEX ───────────────────────────────────────────────────────────────

    public function index(): void
    {
        $filter = trim($_GET['filter'] ?? 'all');
        $fsModel = new FlashSale();
        $bookModel = new Book();

        switch ($filter) {
            case 'active':
                $flashSales = $fsModel->getActive(100);
                break;
            case 'upcoming':
                $flashSales = $fsModel->getUpcoming(100);
                break;
            case 'expired':
                $flashSales = $fsModel->getExpired(100);
                break;
            default:
                $flashSales = $fsModel->getAll(100);
        }

        $stats  = $fsModel->countByStatus();
        $books  = $bookModel->getAllForAdmin(); // dropdown

        $this->view('admin/flash-sale/index', [
            'title'      => 'Quản Lý Flash Sale | ' . APP_NAME,
            'flashSales' => $flashSales,
            'stats'      => $stats,
            'books'      => $books,
            'filter'     => $filter,
        ], 'admin');
    }

    // ─── CREATE ──────────────────────────────────────────────────────────────

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/flash-sale');
            return;
        }

        $sachId   = (int)($_POST['sach_id']   ?? 0);
        $giaSale  = (float)($_POST['gia_sale'] ?? 0);
        $batDau   = trim($_POST['bat_dau']     ?? '');
        $ketThuc  = trim($_POST['ket_thuc']    ?? '');

        if (!$sachId || !$giaSale || !$batDau || !$ketThuc) {
            flash('error', 'Vui lòng điền đầy đủ thông tin flash sale.');
            redirect('admin/flash-sale');
            return;
        }

        // Chuyển datetime-local sang MySQL DATETIME
        $batDau  = date('Y-m-d H:i:s', strtotime($batDau));
        $ketThuc = date('Y-m-d H:i:s', strtotime($ketThuc));

        $user = Auth::user();
        $fsModel = new FlashSale();
        $fsModel->create([
            'sach_id'    => $sachId,
            'gia_sale'   => $giaSale,
            'bat_dau'    => $batDau,
            'ket_thuc'   => $ketThuc,
            'created_by' => $user['id'] ?? null,
        ]);

        // Đồng bộ ngược sang bảng sach
        $db = \Core\Database::getInstance()->getConnection();
        $db->prepare("UPDATE `sach` SET `is_flash_sale` = 1, `gia_flash_sale` = ?, `flash_sale_end` = ? WHERE `id` = ?")
           ->execute([$giaSale, $ketThuc, $sachId]);

        flash('success', 'Tạo flash sale thành công!');
        redirect('admin/flash-sale');
    }

    // ─── EDIT ────────────────────────────────────────────────────────────────

    public function edit(int $id): void
    {
        $fsModel = new FlashSale();
        $fs = $fsModel->getById($id);

        if (!$fs) {
            flash('error', 'Không tìm thấy flash sale.');
            redirect('admin/flash-sale');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sachId  = (int)($_POST['sach_id']   ?? $fs['sach_id']);
            $giaSale = (float)($_POST['gia_sale'] ?? 0);
            $batDau  = trim($_POST['bat_dau']     ?? '');
            $ketThuc = trim($_POST['ket_thuc']    ?? '');

            if (!$giaSale || !$batDau || !$ketThuc) {
                flash('error', 'Vui lòng điền đầy đủ thông tin.');
                redirect('admin/flash-sale');
                return;
            }

            $batDau  = date('Y-m-d H:i:s', strtotime($batDau));
            $ketThuc = date('Y-m-d H:i:s', strtotime($ketThuc));

            $fsModel->update($id, [
                'sach_id'  => $sachId,
                'gia_sale' => $giaSale,
                'bat_dau'  => $batDau,
                'ket_thuc' => $ketThuc,
            ]);

            // Đồng bộ sang bảng sach
            $db = \Core\Database::getInstance()->getConnection();
            $db->prepare("UPDATE `sach` SET `is_flash_sale` = 1, `gia_flash_sale` = ?, `flash_sale_end` = ? WHERE `id` = ?")
               ->execute([$giaSale, $ketThuc, $sachId]);

            flash('success', 'Cập nhật flash sale thành công!');
            redirect('admin/flash-sale');
            return;
        }

        // GET – trả JSON cho modal
        $this->json(['success' => true, 'data' => $fs]);
    }

    // ─── DELETE ──────────────────────────────────────────────────────────────

    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/flash-sale');
            return;
        }

        $fsModel = new FlashSale();
        $fs = $fsModel->getById($id);
        if ($fsModel->delete($id)) {
            if ($fs && !empty($fs['sach_id'])) {
                $db = \Core\Database::getInstance()->getConnection();
                $rem = (int)$db->query("SELECT COUNT(*) FROM `flash_sale` WHERE `sach_id` = " . (int)$fs['sach_id'] . " AND `ket_thuc` >= NOW()")->fetchColumn();
                if ($rem === 0) {
                    $db->prepare("UPDATE `sach` SET `is_flash_sale` = 0, `gia_flash_sale` = NULL, `flash_sale_end` = NULL WHERE `id` = ?")
                       ->execute([$fs['sach_id']]);
                }
            }
            flash('success', 'Đã xóa flash sale.');
        } else {
            flash('error', 'Xóa flash sale thất bại.');
        }
        redirect('admin/flash-sale');
    }

    // ─── AJAX LIST ──────────────────────────────────────────────────────────

    /**
     * GET admin/flash-sale/ajax-list
     * Trả JSON danh sách flash sale đang active (cho client polling)
     */
    public function ajaxList(): void
    {
        $fsModel = new FlashSale();
        $active  = $fsModel->getActive(20);

        $result = array_map(function ($row) {
            return [
                'id'       => $row['id'],
                'sach_id'  => $row['sach_id'],
                'tieu_de'  => $row['tieu_de'],
                'anh_bia'  => $row['anh_bia'],
                'gia'      => $row['gia'],
                'gia_sale' => $row['gia_sale'],
                'bat_dau'  => $row['bat_dau'],
                'ket_thuc' => $row['ket_thuc'],
            ];
        }, $active);

        $this->json(['success' => true, 'data' => $result]);
    }
}
