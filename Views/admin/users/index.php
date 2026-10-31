<?php
// Views/admin/users/index.php
// Quản lý Người Dùng & Phân Quyền - Athenaeum Modern Design System v2.4
?>

<div class="px-6 py-5 max-w-[1440px] w-full mx-auto flex flex-col gap-5">
    <!-- 1. Header & Breadcrumb -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="flex flex-col gap-1">
            <nav class="flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant">
                <a class="hover:text-primary transition-colors flex items-center gap-1" href="<?= base_url('admin') ?>">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    <span>Bảng điều khiển</span>
                </a>
                <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
                <span class="text-primary font-bold">Người Dùng & Phân Quyền</span>
                <span class="ml-1.5 px-2 py-0.5 rounded-full bg-slate-100 text-[10px] font-bold text-slate-500">CSDL: nguoi_dung</span>
            </nav>
            <div class="flex items-baseline gap-2 mt-0.5">
                <h1 class="text-2xl lg:text-3xl font-extrabold text-primary tracking-tight">Quản Lý Người Dùng</h1>
                <span class="hidden sm:inline-block px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[11px] font-bold">
                    Phân Quyền & Bảo Mật • v2.4
                </span>
            </div>
            <p class="text-xs text-on-surface-variant">
                Quản lý danh sách thành viên, phân quyền Quản Trị Viên / Khách Hàng và kiểm soát trạng thái khóa tài khoản thời gian thực.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openAddUserModal()" class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm transition-all active:scale-95" type="button">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                <span>Thêm Người Dùng</span>
            </button>
            <a href="<?= base_url('admin/nguoi-dung') ?>" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-primary text-xs font-semibold shadow-sm border border-slate-200 transition-all active:scale-95" title="Làm mới">
                <span class="material-symbols-outlined text-[18px] text-amber-600">sync</span>
                <span>Làm Mới</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI Bento Cards (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Tổng người dùng -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Tổng Tài Khoản</span>
                <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[18px]">groups</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-primary"><?= number_format($statistics['total'] ?? 0) ?></span>
                <span class="text-xs text-on-surface-variant font-bold">người dùng</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Trạng thái hệ thống:</span>
                <span class="font-bold text-emerald-700"><?= number_format($statistics['hoat_dong'] ?? 0) ?> đang hoạt động</span>
            </div>
        </div>

        <!-- Card 2: Khách hàng -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-secondary uppercase tracking-wider">Khách Hàng Độc Giả</span>
                <span class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[18px]">person</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-secondary"><?= number_format($statistics['khach_hang'] ?? 0) ?></span>
                <span class="text-xs text-teal-600 font-bold">thành viên</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Tỷ trọng thành viên:</span>
                <span class="font-bold text-secondary">
                    <?= !empty($statistics['total']) ? round(($statistics['khach_hang'] / $statistics['total']) * 100) : 0 ?>% tổng tài khoản
                </span>
            </div>
        </div>

        <!-- Card 3: Quản trị viên -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-primary-container uppercase tracking-wider">Ban Quản Trị</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-primary-container">
                    <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-primary-container"><?= number_format($statistics['quan_tri'] ?? 0) ?></span>
                <span class="text-xs text-blue-600 font-bold">quản trị viên</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Quyền hệ thống:</span>
                <span class="font-bold text-blue-700">Toàn quyền Admin</span>
            </div>
        </div>

        <!-- Card 4: Bị khóa -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Đang Bị Khóa</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-700">
                    <span class="material-symbols-outlined text-[18px]">lock_person</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-rose-700"><?= number_format($statistics['khoa'] ?? 0) ?></span>
                <span class="text-xs text-rose-600 font-bold">tài khoản</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Cảnh báo bảo mật:</span>
                <span class="font-bold <?= ($statistics['khoa'] ?? 0) > 0 ? 'text-rose-700' : 'text-slate-400' ?>">
                    <?= ($statistics['khoa'] ?? 0) > 0 ? 'Tạm ngưng truy cập' : 'Không có vi phạm' ?>
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Bộ lọc và Tìm kiếm -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="<?= base_url('admin/nguoi-dung') ?>" method="GET" class="flex flex-wrap items-center gap-3 w-full">
            <!-- Ô tìm kiếm -->
            <div class="relative flex-1 min-w-[240px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-400">search</span>
                <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Tìm họ tên, email hoặc số điện thoại..." class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:outline-none transition-all placeholder:text-slate-400"/>
            </div>

            <!-- Lọc Vai trò -->
            <div class="min-w-[150px]">
                <select name="role" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:outline-none transition-all font-medium text-slate-700">
                    <option value="tat_ca" <?= $role === 'tat_ca' ? 'selected' : '' ?>>Tất cả vai trò</option>
                    <option value="khach_hang" <?= $role === 'khach_hang' ? 'selected' : '' ?>>Khách Hàng</option>
                    <option value="quan_tri" <?= $role === 'quan_tri' ? 'selected' : '' ?>>Quản Trị Viên</option>
                </select>
            </div>

            <!-- Lọc Trạng thái -->
            <div class="min-w-[150px]">
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:outline-none transition-all font-medium text-slate-700">
                    <option value="tat_ca" <?= $status === 'tat_ca' ? 'selected' : '' ?>>Tất cả trạng thái</option>
                    <option value="hoat_dong" <?= $status === 'hoat_dong' ? 'selected' : '' ?>>Đang hoạt động</option>
                    <option value="khoa" <?= $status === 'khoa' ? 'selected' : '' ?>>Đã bị khóa</option>
                </select>
            </div>

            <!-- Nút Lọc và Đặt lại -->
            <button type="submit" class="px-4 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold transition-all shadow-sm active:scale-95 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                <span>Lọc</span>
            </button>
            <?php if (!empty($search) || $role !== 'tat_ca' || $status !== 'tat_ca'): ?>
                <a href="<?= base_url('admin/nguoi-dung') ?>" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                    <span>Xóa lọc</span>
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- 4. Bảng Dữ Liệu Người Dùng -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Danh Sách Thành Viên</span>
                <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-700 text-[11px] font-black">
                    <?= count($users) ?> tài khoản
                </span>
            </div>
            <div class="flex items-center gap-3 text-xs text-slate-500">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Hoạt động</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span> Đã khóa</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-blue-600 inline-block"></span> Quản trị</span>
            </div>
        </div>

        <?php if (empty($users)): ?>
            <div class="py-16 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 mb-3">
                    <span class="material-symbols-outlined text-[36px]">person_off</span>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Không tìm thấy người dùng phù hợp</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-sm">
                    Hãy thử thay đổi từ khóa tìm kiếm hoặc bỏ bớt các tiêu chí lọc đang chọn.
                </p>
                <a href="<?= base_url('admin/nguoi-dung') ?>" class="mt-4 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                    Xem tất cả người dùng
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/40 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4 w-12 text-center">STT</th>
                            <th class="py-3 px-4">Người Dùng</th>
                            <th class="py-3 px-4">Liên Hệ</th>
                            <th class="py-3 px-4">Đơn Hàng & Chi Tiêu</th>
                            <th class="py-3 px-4 text-center">Vai Trò</th>
                            <th class="py-3 px-4 text-center">Trạng Thái</th>
                            <th class="py-3 px-4">Ngày Tạo</th>
                            <th class="py-3 px-4 text-right">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <?php foreach ($users as $idx => $user): ?>
                            <?php 
                                $isSelf = ($user['id'] === $currentUserId);
                                $initial = mb_strtoupper(mb_substr($user['ho_ten'] ?? 'U', 0, 1, 'UTF-8'), 'UTF-8');
                                $roleBadge = $user['vai_tro'] === 'quan_tri' 
                                    ? ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'text' => 'Quản Trị Viên', 'icon' => 'admin_panel_settings']
                                    : ['bg' => 'bg-teal-50 text-teal-700 border-teal-200', 'text' => 'Khách Hàng', 'icon' => 'person'];
                                $statusBadge = $user['trang_thai'] === 'hoat_dong'
                                    ? ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'text' => 'Hoạt Động', 'icon' => 'check_circle']
                                    : ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'text' => 'Đã Khóa', 'icon' => 'lock'];
                            ?>
                            <tr class="hover:bg-slate-50/60 transition-colors <?= $isSelf ? 'bg-amber-50/20' : '' ?>" id="user-row-<?= $user['id'] ?>">
                                <!-- STT -->
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                    <?= $idx + 1 ?>
                                </td>

                                <!-- Người Dùng -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-extrabold text-sm shadow-sm <?= $user['vai_tro'] === 'quan_tri' ? 'bg-primary text-white' : 'bg-slate-100 text-slate-700' ?>">
                                            <?= $initial ?>
                                        </div>
                                        <div class="flex flex-col">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-slate-800 text-xs"><?= htmlspecialchars($user['ho_ten']) ?></span>
                                                <?php if ($isSelf): ?>
                                                    <span class="px-1.5 py-0.2 rounded bg-amber-100 text-amber-800 text-[10px] font-black border border-amber-200" title="Tài khoản đang đăng nhập của bạn">
                                                        BẠN
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="text-[11px] text-slate-400">ID: #<?= $user['id'] ?></span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Liên Hệ -->
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="flex items-center gap-1.5 text-slate-700 font-medium">
                                            <span class="material-symbols-outlined text-[14px] text-slate-400">mail</span>
                                            <?= htmlspecialchars($user['email']) ?>
                                        </span>
                                        <?php if (!empty($user['so_dien_thoai'])): ?>
                                            <span class="flex items-center gap-1.5 text-slate-500 text-[11px]">
                                                <span class="material-symbols-outlined text-[14px] text-slate-400">call</span>
                                                <?= htmlspecialchars($user['so_dien_thoai']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-300 text-[11px] italic">Chưa cập nhật SĐT</span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Đơn Hàng & Chi Tiêu -->
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700"><?= (int)($user['so_don_hang'] ?? 0) ?> đơn hàng</span>
                                        <span class="text-[11px] font-semibold text-secondary">
                                            <?= format_currency((float)($user['tong_chi_tieu'] ?? 0)) ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Vai Trò -->
                                <td class="py-3.5 px-4 text-center">
                                    <span id="role-badge-<?= $user['id'] ?>" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border <?= $roleBadge['bg'] ?>">
                                        <span class="material-symbols-outlined text-[14px]"><?= $roleBadge['icon'] ?></span>
                                        <span><?= $roleBadge['text'] ?></span>
                                    </span>
                                </td>

                                <!-- Trạng Thái -->
                                <td class="py-3.5 px-4 text-center">
                                    <span id="status-badge-<?= $user['id'] ?>" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border <?= $statusBadge['bg'] ?>">
                                        <span class="material-symbols-outlined text-[14px]"><?= $statusBadge['icon'] ?></span>
                                        <span><?= $statusBadge['text'] ?></span>
                                    </span>
                                </td>

                                <!-- Ngày Tạo -->
                                <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                                    <?= date('d/m/Y H:i', strtotime($user['ngay_tao'])) ?>
                                </td>

                                <!-- Thao Tác -->
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <!-- Xem Chi Tiết -->
                                        <button type="button" onclick="viewUserDetail(<?= $user['id'] ?>)" class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" title="Xem chi tiết & lịch sử">
                                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        </button>

                                        <!-- Chỉnh sửa nhanh -->
                                        <button type="button" onclick="openEditUserModal(<?= htmlspecialchars(json_encode([
                                            'id' => $user['id'],
                                            'ho_ten' => $user['ho_ten'],
                                            'email' => $user['email'],
                                            'so_dien_thoai' => $user['so_dien_thoai'] ?? '',
                                            'vai_tro' => $user['vai_tro'],
                                            'trang_thai' => $user['trang_thai']
                                        ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>)" class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" title="Sửa thông tin">
                                            <span class="material-symbols-outlined text-[16px]">edit</span>
                                        </button>

                                        <!-- Đổi vai trò (Phân quyền) -->
                                        <?php if ($isSelf): ?>
                                            <button disabled class="p-1.5 rounded-lg bg-slate-100 text-slate-300 cursor-not-allowed" title="Không thể tự đổi vai trò của chính mình">
                                                <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" onclick="toggleRole(<?= $user['id'] ?>, '<?= $user['vai_tro'] ?>', '<?= htmlspecialchars(addslashes($user['ho_ten'])) ?>')" class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 transition-colors" title="Chuyển đổi vai trò Quản trị / Khách hàng">
                                                <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                                            </button>
                                        <?php endif; ?>

                                        <!-- Khóa / Mở Khóa -->
                                        <?php if ($isSelf): ?>
                                            <button disabled class="p-1.5 rounded-lg bg-slate-100 text-slate-300 cursor-not-allowed" title="Không thể tự khóa tài khoản của chính mình">
                                                <span class="material-symbols-outlined text-[16px]">lock</span>
                                            </button>
                                        <?php else: ?>
                                            <?php if ($user['trang_thai'] === 'hoat_dong'): ?>
                                                <button type="button" onclick="toggleStatus(<?= $user['id'] ?>, 'hoat_dong', '<?= htmlspecialchars(addslashes($user['ho_ten'])) ?>')" class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 transition-colors" title="Khóa tài khoản này">
                                                    <span class="material-symbols-outlined text-[16px]">lock</span>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" onclick="toggleStatus(<?= $user['id'] ?>, 'khoa', '<?= htmlspecialchars(addslashes($user['ho_ten'])) ?>')" class="p-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 transition-colors" title="Mở khóa tài khoản này">
                                                    <span class="material-symbols-outlined text-[16px]">lock_open</span>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ================= MODAL THÊM NGƯỜI DÙNG MỚI ================= -->
<div id="addUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
        <form id="addUserForm" action="" method="POST" onsubmit="handleAddUserSubmit(event)">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-primary text-white">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-secondary-fixed">person_add</span>
                    <h3 class="text-sm font-extrabold tracking-tight">Thêm Mới Tài Khoản Người Dùng</h3>
                </div>
                <button type="button" onclick="closeAddUserModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-4">
                <!-- Họ và tên -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Họ Và Tên <span class="text-rose-500">*</span></label>
                    <input type="text" id="addHoTen" name="ho_ten" required placeholder="Ví dụ: Nguyễn Văn A" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:outline-none transition-all font-semibold text-slate-800"/>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Địa Chỉ Email <span class="text-rose-500">*</span></label>
                    <input type="email" id="addEmail" name="email" required placeholder="vidu@bookstore.vn" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:outline-none transition-all font-semibold text-slate-800"/>
                    <p class="text-[10px] text-slate-400 mt-1">Email được dùng làm tên đăng nhập và phải là duy nhất.</p>
                </div>

                <!-- Mật khẩu -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mật Khẩu Đăng Nhập <span class="text-rose-500">*</span></label>
                    <input type="password" id="addPassword" name="mat_khau" required minlength="6" placeholder="Tối thiểu 6 ký tự..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:outline-none transition-all text-slate-800"/>
                    <p class="text-[10px] text-slate-400 mt-1">Mật khẩu sẽ được mã hóa an toàn bằng chuẩn Bcrypt.</p>
                </div>

                <!-- Số điện thoại -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Số Điện Thoại</label>
                    <input type="text" id="addPhone" name="so_dien_thoai" placeholder="Ví dụ: 0912345678" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:outline-none transition-all text-slate-800"/>
                </div>

                <!-- Phân quyền Vai trò -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Vai Trò Người Dùng <span class="text-rose-500">*</span></label>
                    <select id="addVaiTro" name="vai_tro" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:outline-none transition-all font-semibold text-slate-800">
                        <option value="khach_hang" selected>Khách Hàng (Độc giả thông thường)</option>
                        <option value="quan_tri">Quản Trị Viên (Ban quản trị Admin)</option>
                    </select>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition-all">
                    Hủy bỏ
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">check</span>
                    <span>Tạo Tài Khoản</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL XEM CHI TIẾT NGƯỜI DÙNG ================= -->
<div id="userDetailModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100 transition-all">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-primary text-white">
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[22px] text-secondary-fixed">badge</span>
                <h3 class="text-base font-bold tracking-tight">Hồ Sơ Chi Tiết Người Dùng</h3>
            </div>
            <button type="button" onclick="closeUserDetailModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <div id="userDetailContent" class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
            <div class="flex items-center justify-center py-10">
                <span class="material-symbols-outlined animate-spin text-[32px] text-primary">progress_activity</span>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="closeUserDetailModal()" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition-all">
                Đóng
            </button>
        </div>
    </div>
</div>

<!-- ================= MODAL CHỈNH SỬA THÔNG TIN ================= -->
<div id="userEditModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
        <form id="userEditForm" action="" method="POST" onsubmit="handleEditSubmit(event)">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-primary">edit_note</span>
                    <h3 class="text-sm font-extrabold text-slate-800">Cập Nhật Thông Tin Tài Khoản</h3>
                </div>
                <button type="button" onclick="closeUserEditModal()" class="w-7 h-7 rounded-full bg-slate-200 hover:bg-slate-300 flex items-center justify-center text-slate-600">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-4">
                <input type="hidden" id="editUserId" name="id" value=""/>

                <!-- Họ và tên -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Họ Và Tên <span class="text-rose-500">*</span></label>
                    <input type="text" id="editHoTen" name="ho_ten" required class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:outline-none transition-all font-semibold text-slate-800"/>
                </div>

                <!-- Email (Read-only) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Địa Chỉ Email</label>
                    <input type="email" id="editEmail" disabled class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-100 border border-slate-200 text-slate-500 cursor-not-allowed"/>
                    <p class="text-[10px] text-slate-400 mt-1">Email là tên định danh duy nhất không được phép thay đổi trực tiếp.</p>
                </div>

                <!-- Số điện thoại -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Số Điện Thoại</label>
                    <input type="text" id="editPhone" name="so_dien_thoai" placeholder="Ví dụ: 0912345678" class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:outline-none transition-all text-slate-800"/>
                </div>

                <!-- Đặt lại mật khẩu mới -->
                <div class="pt-2 border-t border-slate-100">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mật Khẩu Mới (Nếu muốn đổi)</label>
                    <input type="password" id="editPassword" name="mat_khau_moi" placeholder="Để trống nếu không muốn thay đổi mật khẩu..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:outline-none transition-all text-slate-800"/>
                    <p class="text-[10px] text-slate-400 mt-1">Hệ thống sẽ tự động băm mật khẩu theo chuẩn mã hóa an toàn Bcrypt.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeUserEditModal()" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition-all">
                    Hủy bỏ
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold transition-all shadow-sm">
                    Lưu Thay Đổi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= TOAST NOTIFICATION TỨC THÌ ================= -->
<div id="statusToast" class="fixed bottom-6 right-6 z-[9999] hidden flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl text-white text-xs font-semibold transition-all duration-300">
    <span id="toastIcon" class="material-symbols-outlined text-[20px]"></span>
    <span id="toastMessage"></span>
</div>

<!-- ================= JAVASCRIPT XỬ LÝ AJAX & UI ================= -->
<script>
// QUY TẮC BẮT BUỘC: baseUrl có trailing slash
const baseUrl = '<?= base_url() ?>/';

function showToast(message, isSuccess = true) {
    const toast = document.getElementById('statusToast');
    const icon = document.getElementById('toastIcon');
    const msg = document.getElementById('toastMessage');

    toast.className = `fixed bottom-6 right-6 z-[9999] flex items-center gap-3 px-5 py-3 rounded-2xl shadow-2xl text-white text-xs font-bold transition-all duration-300 ${
        isSuccess ? 'bg-emerald-700' : 'bg-rose-700'
    }`;
    icon.textContent = isSuccess ? 'check_circle' : 'error';
    msg.textContent = message;

    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3500);
}

// 1. Khóa / Mở Khóa Tài Khoản
async function toggleStatus(userId, currentStatus, userName) {
    const isLocking = (currentStatus === 'hoat_dong');
    const promptText = isLocking 
        ? `Bạn có chắc chắn muốn KHÓA tài khoản của [${userName}]?\nNgười dùng này sẽ bị thu hồi phiên đăng nhập ngay lập tức.`
        : `Bạn có muốn MỞ KHÓA tài khoản của [${userName}] không?`;

    if (!confirm(promptText)) return;

    try {
        const response = await fetch(`${baseUrl}admin/nguoi-dung/khoa/${userId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        if (data.success) {
            showToast(data.message, true);
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            showToast(data.message || 'Không thể thực hiện thao tác', false);
        }
    } catch (err) {
        showToast('Lỗi mạng hoặc máy chủ không phản hồi', false);
    }
}

// 2. Chuyển Đổi Vai Trò (Phân Quyền)
async function toggleRole(userId, currentRole, userName) {
    const targetRole = currentRole === 'quan_tri' ? 'khach_hang' : 'quan_tri';
    const roleText = targetRole === 'quan_tri' ? 'QUẢN TRỊ VIÊN' : 'KHÁCH HÀNG THƯỜNG';

    if (!confirm(`Xác nhận chuyển đổi vai trò của [${userName}] thành "${roleText}"?`)) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('vai_tro', targetRole);

        const response = await fetch(`${baseUrl}admin/nguoi-dung/vai-tro/${userId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();
        if (data.success) {
            showToast(data.message, true);
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            showToast(data.message || 'Không thể đổi quyền', false);
        }
    } catch (err) {
        showToast('Lỗi kết nối máy chủ', false);
    }
}

// 3. Xem Chi Tiết Người Dùng
async function viewUserDetail(userId) {
    const modal = document.getElementById('userDetailModal');
    const content = document.getElementById('userDetailContent');
    modal.classList.remove('hidden');
    content.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <span class="material-symbols-outlined animate-spin text-[36px] text-primary">progress_activity</span>
        </div>
    `;

    try {
        const res = await fetch(`${baseUrl}admin/nguoi-dung/chi-tiet/${userId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        const json = await res.json();

        if (!json.success || !json.data) {
            content.innerHTML = `<p class="text-center text-rose-600 font-bold py-6">${json.message || 'Không tìm thấy dữ liệu'}</p>`;
            return;
        }

        const u = json.data;
        const initial = (u.ho_ten || 'U').charAt(0).toUpperCase();

        let addressesHtml = '';
        if (u.dia_chi_list && u.dia_chi_list.length > 0) {
            addressesHtml = u.dia_chi_list.map(a => `
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-800">${a.nguoi_nhan} (${a.so_dien_thoai})</span>
                        ${Number(a.mac_dinh) === 1 ? '<span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Mặc định</span>' : ''}
                    </div>
                    <span class="text-slate-600">${a.dia_chi}</span>
                </div>
            `).join('');
        } else {
            addressesHtml = '<p class="text-xs text-slate-400 italic">Chưa lưu địa chỉ nào trong sổ địa chỉ.</p>';
        }

        let ordersHtml = '';
        if (u.don_hang_list && u.don_hang_list.length > 0) {
            ordersHtml = `
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-[10px] text-slate-400 uppercase font-bold border-b border-slate-100">
                                <th class="py-2">Mã Đơn</th>
                                <th class="py-2">Ngày Đặt</th>
                                <th class="py-2">Tổng Tiền</th>
                                <th class="py-2 text-right">Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            ${u.don_hang_list.map(o => `
                                <tr>
                                    <td class="py-2 font-bold text-primary">#${o.ma_don_hang}</td>
                                    <td class="py-2 text-slate-500">${o.ngay_tao}</td>
                                    <td class="py-2 font-bold text-secondary">${Number(o.tong_tien).toLocaleString('vi-VN')} đ</td>
                                    <td class="py-2 text-right">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${
                                            o.trang_thai === 'hoan_thanh' ? 'bg-emerald-100 text-emerald-800' :
                                            (o.trang_thai === 'da_huy' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800')
                                        }">${o.trang_thai}</span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        } else {
            ordersHtml = '<p class="text-xs text-slate-400 italic">Chưa phát sinh đơn hàng nào.</p>';
        }

        content.innerHTML = `
            <!-- Profile Overview Card -->
            <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="w-14 h-14 rounded-2xl ${u.vai_tro === 'quan_tri' ? 'bg-primary text-white' : 'bg-secondary text-white'} flex items-center justify-center font-black text-2xl shadow-sm">
                    ${initial}
                </div>
                <div class="flex-1 flex flex-col">
                    <div class="flex items-center gap-2">
                        <h4 class="text-base font-extrabold text-slate-900">${u.ho_ten}</h4>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black ${
                            u.vai_tro === 'quan_tri' ? 'bg-blue-100 text-blue-800' : 'bg-teal-100 text-teal-800'
                        }">${u.vai_tro === 'quan_tri' ? 'QUẢN TRỊ VIÊN' : 'KHÁCH HÀNG'}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black ${
                            u.trang_thai === 'hoat_dong' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'
                        }">${u.trang_thai === 'hoat_dong' ? 'ĐANG HOẠT ĐỘNG' : 'ĐÃ BỊ KHÓA'}</span>
                    </div>
                    <span class="text-xs text-slate-500 mt-0.5">${u.email} • ${u.so_dien_thoai || 'Chưa có SĐT'}</span>
                    <span class="text-[11px] text-slate-400 mt-1">Đăng ký ngày: ${u.ngay_tao}</span>
                </div>
            </div>

            <!-- Bento stats metrics -->
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] font-bold uppercase text-slate-400">Tổng Đơn Đã Đặt</span>
                    <div class="text-xl font-black text-primary mt-1">${u.tong_don || 0} đơn</div>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] font-bold uppercase text-slate-400">Tổng Chi Tiêu Hoàn Tất</span>
                    <div class="text-xl font-black text-secondary mt-1">${Number(u.tong_chi_tieu || 0).toLocaleString('vi-VN')} đ</div>
                </div>
            </div>

            <!-- Addresses List -->
            <div>
                <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px] text-slate-500">home_pin</span>
                    <span>Sổ Địa Chỉ Giao Hàng (${u.dia_chi_list ? u.dia_chi_list.length : 0})</span>
                </h5>
                <div class="space-y-2">
                    ${addressesHtml}
                </div>
            </div>

            <!-- Recent Orders -->
            <div>
                <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px] text-slate-500">shopping_bag</span>
                    <span>Đơn Hàng Gần Đây</span>
                </h5>
                ${ordersHtml}
            </div>
        `;
    } catch (err) {
        content.innerHTML = '<p class="text-center text-rose-600 font-bold py-6">Lỗi tải dữ liệu người dùng.</p>';
    }
}

function closeUserDetailModal() {
    document.getElementById('userDetailModal').classList.add('hidden');
}

// 4. Chỉnh Sửa Thông Tin Người Dùng
function openEditUserModal(user) {
    document.getElementById('editUserId').value = user.id;
    document.getElementById('editHoTen').value = user.ho_ten || '';
    document.getElementById('editEmail').value = user.email || '';
    document.getElementById('editPhone').value = user.so_dien_thoai || '';
    document.getElementById('editPassword').value = '';

    const form = document.getElementById('userEditForm');
    form.action = `${baseUrl}admin/nguoi-dung/sua/${user.id}`;

    document.getElementById('userEditModal').classList.remove('hidden');
}

function closeUserEditModal() {
    document.getElementById('userEditModal').classList.add('hidden');
}

async function handleEditSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const userId = document.getElementById('editUserId').value;
    const formData = new FormData(form);

    try {
        const res = await fetch(`${baseUrl}admin/nguoi-dung/sua/${userId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await res.json();
        if (data.success) {
            closeUserEditModal();
            showToast(data.message, true);
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            showToast(data.message || 'Lỗi cập nhật', false);
        }
    } catch (err) {
        showToast('Lỗi mạng khi lưu dữ liệu', false);
    }
}

// 5. Thêm Mới Người Dùng
function openAddUserModal() {
    document.getElementById('addHoTen').value = '';
    document.getElementById('addEmail').value = '';
    document.getElementById('addPassword').value = '';
    document.getElementById('addPhone').value = '';
    document.getElementById('addVaiTro').value = 'khach_hang';
    document.getElementById('addUserModal').classList.remove('hidden');
}

function closeAddUserModal() {
    document.getElementById('addUserModal').classList.add('hidden');
}

async function handleAddUserSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const res = await fetch(`${baseUrl}admin/nguoi-dung/them`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await res.json();
        if (data.success) {
            closeAddUserModal();
            showToast(data.message, true);
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            showToast(data.message || 'Lỗi thêm người dùng', false);
        }
    } catch (err) {
        showToast('Lỗi mạng khi lưu tài khoản', false);
    }
}
</script>

