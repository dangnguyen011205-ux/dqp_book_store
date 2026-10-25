<?php
// Views/layout/admin.php
use Core\Auth;

Auth::requireAdmin();
$currentUser = Auth::user();
$currentUrl = trim($_GET['url'] ?? '', '/');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= htmlspecialchars($title ?? 'Hệ Thống Quản Trị - ' . APP_NAME) ?></title>
    
    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#001428",
                    "primary-container": "#0F2942",
                    "on-primary": "#FFFFFF",
                    "on-primary-container": "#7991AF",
                    "primary-fixed": "#D1E4FF",
                    "on-primary-fixed": "#011D35",
                    "secondary": "#006A61",
                    "secondary-container": "#86F2E4",
                    "on-secondary-container": "#006F66",
                    "secondary-fixed": "#89F5E7",
                    "background": "#F7F9FB",
                    "surface": "#F7F9FB",
                    "surface-container-lowest": "#FFFFFF",
                    "surface-container-low": "#F2F4F6",
                    "surface-container": "#ECEEF0",
                    "surface-container-high": "#E6E8EA",
                    "on-surface": "#191C1E",
                    "on-surface-variant": "#43474D",
                    "outline-variant": "#C3C6CE",
                    "tertiary-fixed": "#FFDDB8",
                    "error": "#BA1A1A",
                    "error-container": "#FFDAD6"
                },
                fontFamily: {
                    sans: ["Plus Jakarta Sans", "sans-serif"]
                }
            }
        }
    };
    </script>
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>"/>
</head>
<body class="bg-background font-sans text-on-surface antialiased">

    <!-- Toast container -->
    <div id="toast-container" class="fixed top-20 right-5 z-[9999] flex flex-col gap-3 pointer-events-none">
        <?php if ($flashSuccess = flash('success')): ?>
            <div class="pointer-events-auto flex items-center gap-3 bg-emerald-700 text-white px-5 py-3.5 rounded-xl shadow-xl transition-all duration-300">
                <span class="material-symbols-outlined text-[22px]">check_circle</span>
                <span class="text-sm font-medium"><?= htmlspecialchars($flashSuccess) ?></span>
                <button onclick="this.parentElement.remove()" class="ml-2 hover:opacity-75"><span class="material-symbols-outlined text-[18px]">close</span></button>
            </div>
        <?php endif; ?>

        <?php if ($flashError = flash('error')): ?>
            <div class="pointer-events-auto flex items-center gap-3 bg-rose-700 text-white px-5 py-3.5 rounded-xl shadow-xl transition-all duration-300">
                <span class="material-symbols-outlined text-[22px]">error</span>
                <span class="text-sm font-medium"><?= htmlspecialchars($flashError) ?></span>
                <button onclick="this.parentElement.remove()" class="ml-2 hover:opacity-75"><span class="material-symbols-outlined text-[18px]">close</span></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- SIDEBAR QUẢN TRỊ -->
    <aside class="fixed left-0 top-0 h-full w-64 bg-primary text-white z-50 flex flex-col justify-between shadow-2xl overflow-y-auto">
        <div class="flex flex-col">
            <!-- Admin Brand Header -->
            <div class="h-16 px-5 flex items-center gap-3 bg-primary-container border-b border-white/5">
                <div class="w-8 h-8 rounded-lg bg-secondary flex items-center justify-center text-white font-bold">
                    <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-bold tracking-tight"><?= APP_NAME ?></span>
                    <span class="text-[10px] text-secondary-fixed uppercase font-semibold">Cổng Quản Trị</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-on-primary-container">Menu Nghiệp Vụ</p>
            </div>

            <nav class="px-3 space-y-1 text-xs font-semibold">
                <!-- Dashboard -->
                <a href="<?= base_url('admin') ?>" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors <?= ($currentUrl === 'admin' || $currentUrl === 'tong-quan-quan-tri') ? 'bg-secondary text-white shadow-sm' : 'text-on-primary-container hover:bg-primary-container hover:text-white' ?>">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    <span>Tổng quan Dashboard</span>
                </a>

                <!-- Quản lý Sách -->
                <a href="<?= base_url('admin/sach') ?>" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors <?= str_starts_with($currentUrl, 'admin/sach') ? 'bg-secondary text-white shadow-sm' : 'text-on-primary-container hover:bg-primary-container hover:text-white' ?>">
                    <span class="material-symbols-outlined text-[20px]">auto_stories</span>
                    <span>Quản lý Tựa Sách</span>
                </a>

                <!-- Quản lý Kho -->
                <a href="<?= base_url('admin/kho') ?>" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors <?= str_starts_with($currentUrl, 'admin/kho') ? 'bg-secondary text-white shadow-sm' : 'text-on-primary-container hover:bg-primary-container hover:text-white' ?>">
                    <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                    <span>Tồn Kho & Phiếu Kho</span>
                </a>

                <!-- Quản lý Đơn Hàng -->
                <a href="<?= base_url('admin/don-hang') ?>" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors <?= str_starts_with($currentUrl, 'admin/don-hang') ? 'bg-secondary text-white shadow-sm' : 'text-on-primary-container hover:bg-primary-container hover:text-white' ?>">
                    <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                    <span>Quản lý Đơn Hàng</span>
                </a>

                <!-- Thể loại -->
                <a href="<?= base_url('admin/the-loai') ?>" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors <?= str_starts_with($currentUrl, 'admin/the-loai') ? 'bg-secondary text-white shadow-sm' : 'text-on-primary-container hover:bg-primary-container hover:text-white' ?>">
                    <span class="material-symbols-outlined text-[20px]">category</span>
                    <span>Thể Loại Sách</span>
                </a>

                <!-- Tác giả -->
                <a href="<?= base_url('admin/tac-gia') ?>" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors <?= str_starts_with($currentUrl, 'admin/tac-gia') ? 'bg-secondary text-white shadow-sm' : 'text-on-primary-container hover:bg-primary-container hover:text-white' ?>">
                    <span class="material-symbols-outlined text-[20px]">person_edit</span>
                    <span>Tác Giả</span>
                </a>

                <!-- Nhà Xuất Bản -->
                <a href="<?= base_url('admin/nha-xuat-ban') ?>" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors <?= str_starts_with($currentUrl, 'admin/nha-xuat-ban') ? 'bg-secondary text-white shadow-sm' : 'text-on-primary-container hover:bg-primary-container hover:text-white' ?>">
                    <span class="material-symbols-outlined text-[20px]">business</span>
                    <span>Nhà Xuất Bản</span>
                </a>

                <!-- Mã Giảm Giá -->
                <a href="<?= base_url('admin/ma-giam-gia') ?>" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors <?= str_starts_with($currentUrl, 'admin/ma-giam-gia') ? 'bg-secondary text-white shadow-sm' : 'text-on-primary-container hover:bg-primary-container hover:text-white' ?>">
                    <span class="material-symbols-outlined text-[20px]">confirmation_number</span>
                    <span>Mã Giảm Giá</span>
                </a>

                <!-- Duyệt Đánh Giá -->
                <a href="<?= base_url('admin/danh-gia') ?>" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors <?= str_starts_with($currentUrl, 'admin/danh-gia') ? 'bg-secondary text-white shadow-sm' : 'text-on-primary-container hover:bg-primary-container hover:text-white' ?>">
                    <span class="material-symbols-outlined text-[20px]">star_half</span>
                    <span>Duyệt Đánh Giá</span>
                </a>

                <!-- Người Dùng -->
                <a href="<?= base_url('admin/nguoi-dung') ?>" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors <?= str_starts_with($currentUrl, 'admin/nguoi-dung') ? 'bg-secondary text-white shadow-sm' : 'text-on-primary-container hover:bg-primary-container hover:text-white' ?>">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                    <span>Người Dùng & Phân Quyền</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer Action -->
        <div class="p-4 bg-primary-container/80 space-y-2 border-t border-white/5">
            <a href="<?= base_url() ?>" class="flex items-center justify-center gap-2 w-full py-2 bg-secondary text-white rounded-xl text-xs font-bold hover:bg-secondary-fixed hover:text-primary transition-colors shadow">
                <span class="material-symbols-outlined text-[18px]">storefront</span>
                <span>Về Shop Khách Hàng</span>
            </a>

            <div class="flex items-center justify-between pt-2">
                <div class="flex items-center gap-2 truncate">
                    <div class="w-7 h-7 rounded-full bg-secondary/20 text-secondary-fixed flex items-center justify-center font-bold text-xs">
                        <?= strtoupper(mb_substr($currentUser['ho_ten'] ?? 'A', 0, 1, 'UTF-8')) ?>
                    </div>
                    <div class="truncate text-[11px]">
                        <p class="font-bold text-white truncate"><?= htmlspecialchars($currentUser['ho_ten'] ?? 'Admin') ?></p>
                        <p class="text-on-primary-container truncate"><?= htmlspecialchars($currentUser['email'] ?? '') ?></p>
                    </div>
                </div>
                <a href="<?= base_url('dang-xuat') ?>" title="Đăng xuất" class="p-1.5 text-rose-400 hover:text-rose-300 rounded-lg hover:bg-white/5 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- CONTENT WRAPPER -->
    <div class="pl-64">
        <!-- Topbar Quản trị -->
        <header class="fixed top-0 left-64 right-0 h-16 bg-white/95 backdrop-blur-xl shadow-sm z-40 flex items-center justify-between px-6 border-b border-slate-200/60">
            <div class="flex items-center gap-3 w-96">
                <div class="relative w-full">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-[18px] text-slate-400">search</span>
                    <input class="w-full pl-9 pr-4 py-2 bg-slate-100 rounded-lg text-xs placeholder:text-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary transition-all" 
                           placeholder="Tìm kiếm nhanh đơn hàng, sách, khách hàng..." type="text"/>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Hệ Thống Trực Tuyến</span>
                </div>
                <div class="h-4 w-px bg-slate-200"></div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                    <span><?= htmlspecialchars($currentUser['ho_ten'] ?? 'Quản Trị') ?></span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="w-full pt-16 min-h-screen p-6">
            <?= $content ?>
        </main>
    </div>

    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
