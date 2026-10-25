<?php
// Views/layout/main.php
use Core\Auth;
use Models\Cart;
use Models\Category;

$currentUser = Auth::user();
$cartItemCount = (new Cart())->getItemCount(Auth::id());
$categories = (new Category())->getAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= htmlspecialchars($title ?? APP_NAME . ' - ' . APP_SLOGAN) ?></title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    
    <!-- Google Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
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
                    "surface-container-highest": "#E0E3E5",
                    "on-surface": "#191C1E",
                    "on-surface-variant": "#43474D",
                    "outline": "#74777E",
                    "outline-variant": "#C3C6CE",
                    "tertiary-fixed": "#FFDDB8",
                    "on-tertiary-fixed": "#2A1700",
                    "error": "#BA1A1A",
                    "error-container": "#FFDAD6",
                    "on-error-container": "#93000A"
                },
                fontFamily: {
                    sans: ["Plus Jakarta Sans", "sans-serif"]
                },
                borderRadius: {
                    DEFAULT: "0.25rem",
                    lg: "0.5rem",
                    xl: "0.75rem",
                    "2xl": "1rem",
                    full: "9999px"
                },
                spacing: {
                    gutter: "1.5rem",
                    margin: "2rem",
                    "space-xs": "0.25rem",
                    "space-sm": "0.5rem",
                    "space-md": "1rem",
                    "space-lg": "1.5rem",
                    "space-xl": "2.5rem"
                }
            }
        }
    };
    </script>
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>"/>
</head>
<body class="bg-background font-sans text-on-surface antialiased flex flex-col min-h-screen selection:bg-secondary-fixed selection:text-primary">

    <!-- ================= TOAST NOTIFICATION CONTAINER ================= -->
    <div id="toast-container" class="fixed top-20 right-5 z-[9999] flex flex-col gap-3 pointer-events-none">
        <?php if ($flashSuccess = flash('success')): ?>
            <div class="pointer-events-auto flex items-center gap-3 bg-emerald-700 text-white px-5 py-3.5 rounded-xl shadow-xl transition-all duration-300 animate-slide-in">
                <span class="material-symbols-outlined text-[22px]">check_circle</span>
                <span class="text-sm font-medium"><?= htmlspecialchars($flashSuccess) ?></span>
                <button onclick="this.parentElement.remove()" class="ml-2 hover:opacity-75"><span class="material-symbols-outlined text-[18px]">close</span></button>
            </div>
        <?php endif; ?>

        <?php if ($flashError = flash('error')): ?>
            <div class="pointer-events-auto flex items-center gap-3 bg-rose-700 text-white px-5 py-3.5 rounded-xl shadow-xl transition-all duration-300 animate-slide-in">
                <span class="material-symbols-outlined text-[22px]">error</span>
                <span class="text-sm font-medium"><?= htmlspecialchars($flashError) ?></span>
                <button onclick="this.parentElement.remove()" class="ml-2 hover:opacity-75"><span class="material-symbols-outlined text-[18px]">close</span></button>
            </div>
        <?php endif; ?>

        <?php if ($flashInfo = flash('info')): ?>
            <div class="pointer-events-auto flex items-center gap-3 bg-sky-700 text-white px-5 py-3.5 rounded-xl shadow-xl transition-all duration-300 animate-slide-in">
                <span class="material-symbols-outlined text-[22px]">info</span>
                <span class="text-sm font-medium"><?= htmlspecialchars($flashInfo) ?></span>
                <button onclick="this.parentElement.remove()" class="ml-2 hover:opacity-75"><span class="material-symbols-outlined text-[18px]">close</span></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- ================= GLOBAL HEADER (3 TẦNG) ================= -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-surface-container-lowest/95 backdrop-blur-xl shadow-[0_1px_8px_rgba(15,41,66,0.06)]">
        <!-- TẦNG 1: TOP BAR -->
        <div class="bg-primary-container text-on-primary-container text-[12px] font-medium border-b border-primary/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-9">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px] text-secondary-fixed">call</span>
                        <span>Hotline: <strong class="text-white"><?= HOTLINE ?></strong> (8:00 - 21:30)</span>
                    </div>
                    <div class="hidden md:flex items-center gap-1.5 text-on-primary-container/90">
                        <span class="material-symbols-outlined text-[15px] text-secondary-fixed">local_shipping</span>
                        <span>Miễn phí vận chuyển cho đơn hàng từ <strong>250.000đ</strong></span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-1.5 text-secondary-fixed">
                        <span class="material-symbols-outlined text-[15px]">campaign</span>
                        <span>Ưu đãi Hội Sách Mùa Thu giảm đến 40%</span>
                    </div>
                    <div class="flex items-center gap-2 bg-primary/60 px-3 py-1 rounded-full text-[11px]">
                        <a class="text-secondary-fixed font-bold hover:underline" href="<?= base_url() ?>">Khách hàng</a>
                        <span class="text-white/40">|</span>
                        <a class="text-white/80 hover:text-white transition-colors" href="<?= base_url('admin') ?>">Quản trị</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- TẦNG 2: MAIN HEADER -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-6">
            <!-- Logo & Brand -->
            <a href="<?= base_url() ?>" class="flex items-center gap-3 flex-shrink-0 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary to-secondary flex items-center justify-center text-white shadow-md group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[24px]">auto_stories</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-xl text-primary tracking-tight leading-none">DQP Book Store</span>
                    <span class="text-[11px] text-on-surface-variant font-semibold tracking-wider uppercase mt-1">Thư Viện Tinh Hoa</span>
                </div>
            </a>

            <!-- Search Bar -->
            <div class="flex-1 max-w-2xl hidden lg:block">
                <form action="<?= base_url('tim-kiem') ?>" method="GET" class="relative flex items-center">
                    <div class="absolute left-3.5 text-on-surface-variant flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </div>
                    <input name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" 
                           class="w-full pl-10 pr-28 py-2.5 bg-surface-container-low text-on-surface rounded-full text-sm placeholder:text-on-surface-variant/70 focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all shadow-inner" 
                           placeholder="Tìm theo tựa sách, tác giả, nhà xuất bản, mã ISBN..." type="text"/>
                    <button type="submit" class="absolute right-1.5 px-5 py-1.5 bg-primary text-white rounded-full text-xs font-semibold hover:bg-secondary transition-colors shadow">
                        Tìm kiếm
                    </button>
                </form>
            </div>

            <!-- Actions: Tra cứu đơn, Giỏ hàng, Tài khoản -->
            <div class="flex items-center gap-4 flex-shrink-0">
                <a class="hidden xl:flex items-center gap-1.5 text-on-surface-variant hover:text-primary transition-colors text-xs font-semibold" href="<?= base_url('tai-khoan/don-hang') ?>">
                    <span class="material-symbols-outlined text-[20px]">local_shipping</span>
                    <span>Tra cứu đơn</span>
                </a>

                <!-- Giỏ Hàng -->
                <a class="relative flex items-center gap-2 px-3 py-2 rounded-xl text-primary hover:bg-surface-container-low transition-colors" href="<?= base_url('gio-hang') ?>">
                    <div class="relative">
                        <span class="material-symbols-outlined text-[26px]">shopping_bag</span>
                        <span id="header-cart-badge" class="absolute -top-1.5 -right-2 bg-secondary text-white text-[11px] font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-md">
                            <?= $cartItemCount ?>
                        </span>
                    </div>
                    <span class="hidden sm:inline text-xs font-semibold">Giỏ hàng</span>
                </a>

                <!-- Tài Khoản Menu Dropdown -->
                <?php if ($currentUser): ?>
                    <div class="relative group flex items-center gap-2 pl-2 cursor-pointer py-2">
                        <div class="flex items-center gap-2">
                            <div class="w-9 h-9 rounded-full bg-secondary/10 border-2 border-secondary/40 text-secondary flex items-center justify-center font-bold text-sm">
                                <?= strtoupper(mb_substr($currentUser['ho_ten'], 0, 1, 'UTF-8')) ?>
                            </div>
                            <div class="hidden md:flex flex-col text-left">
                                <span class="text-xs font-bold text-primary truncate max-w-[120px]"><?= htmlspecialchars($currentUser['ho_ten']) ?></span>
                                <span class="text-[10px] text-secondary font-semibold">
                                    <?= $currentUser['vai_tro'] === 'quan_tri' ? 'Quản Trị Viên' : 'DQP Hội Viên' ?>
                                </span>
                            </div>
                            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">expand_more</span>
                        </div>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 top-full pt-2 hidden group-hover:block w-60 z-50 animate-fade-in">
                            <div class="bg-white rounded-2xl p-2 shadow-2xl border border-slate-100">
                                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                                    <p class="text-xs font-bold text-primary truncate"><?= htmlspecialchars($currentUser['ho_ten']) ?></p>
                                    <p class="text-[11px] text-slate-500 truncate"><?= htmlspecialchars($currentUser['email']) ?></p>
                                </div>
                                
                                <?php if ($currentUser['vai_tro'] === 'quan_tri'): ?>
                                    <a class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-secondary bg-secondary/10 rounded-xl mb-1 hover:bg-secondary/20 transition-colors" href="<?= base_url('admin') ?>">
                                        <span class="material-symbols-outlined text-[18px]">dashboard</span>
                                        Trang Quản Trị Admin
                                    </a>
                                <?php endif; ?>

                                <a class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-surface-container-low hover:text-primary rounded-xl transition-colors" href="<?= base_url('tai-khoan') ?>">
                                    <span class="material-symbols-outlined text-[18px]">person</span>
                                    Hồ sơ độc giả
                                </a>
                                <a class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-surface-container-low hover:text-primary rounded-xl transition-colors" href="<?= base_url('tai-khoan/so-dia-chi') ?>">
                                    <span class="material-symbols-outlined text-[18px]">location_on</span>
                                    Sổ địa chỉ nhận
                                </a>
                                <a class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-surface-container-low hover:text-primary rounded-xl transition-colors" href="<?= base_url('tai-khoan/don-hang') ?>">
                                    <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                                    Đơn hàng của tôi
                                </a>
                                <div class="my-1 border-t border-slate-100"></div>
                                <a class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-xl transition-colors" href="<?= base_url('dang-xuat') ?>">
                                    <span class="material-symbols-outlined text-[18px]">logout</span>
                                    Đăng xuất
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-2">
                        <a href="<?= base_url('dang-nhap') ?>" class="px-4 py-2 text-xs font-bold text-primary hover:text-secondary transition-colors">
                            Đăng nhập
                        </a>
                        <a href="<?= base_url('dang-ky') ?>" class="px-4 py-2 text-xs font-bold bg-primary text-white rounded-full hover:bg-secondary transition-colors shadow-sm">
                            Đăng ký
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- TẦNG 3: NAVIGATION BAR & MEGA MENU -->
        <div class="bg-surface-container-low/80 border-t border-slate-200/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-11 flex items-center justify-between">
                <!-- Mega Menu Danh Mục Sách -->
                <div class="relative group flex items-center">
                    <button class="flex items-center gap-2 text-primary font-bold text-xs py-1.5 px-3 rounded-lg hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined text-[18px] text-secondary">menu_book</span>
                        <span>Danh Mục Sách</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_drop_down</span>
                    </button>

                    <!-- Dropdown Mega Menu -->
                    <div class="absolute left-0 top-full pt-1.5 hidden group-hover:block w-[720px] z-50 animate-fade-in">
                        <div class="bg-white rounded-2xl p-6 shadow-2xl border border-slate-100 grid grid-cols-3 gap-6">
                            <?php foreach ($categories as $cat): ?>
                                <div class="space-y-1">
                                    <a href="<?= base_url('tim-kiem?the_loai=' . $cat['id']) ?>" class="text-xs font-bold text-primary flex items-center gap-2 hover:text-secondary transition-colors">
                                        <span class="material-symbols-outlined text-[16px] text-secondary">auto_stories</span>
                                        <?= htmlspecialchars($cat['ten']) ?>
                                    </a>
                                    <p class="text-[11px] text-slate-500 line-clamp-2 pl-6 leading-relaxed">
                                        <?= htmlspecialchars($cat['mo_ta'] ?? 'Khám phá các tựa sách hay nhất.') ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-7 text-xs font-semibold text-slate-600">
                    <a class="hover:text-secondary transition-colors" href="<?= base_url() ?>">Trang Chủ</a>
                    <a class="hover:text-secondary transition-colors" href="<?= base_url('sach-moi-ve') ?>">Sách Mới</a>
                    <a class="hover:text-secondary transition-colors" href="<?= base_url('sach-ban-chay') ?>">Bán Chạy</a>
                    <a class="hover:text-secondary transition-colors" href="<?= base_url('khuyen-mai-hot') ?>">Khuyến Mãi Hot</a>
                    <a class="hover:text-secondary transition-colors" href="<?= base_url('tu-sach-tinh-hoa') ?>">Tủ Sách Tinh Hoa</a>
                    <a class="hover:text-secondary transition-colors" href="<?= base_url('tim-kiem') ?>">Tất Cả Sách</a>
                </nav>

                <!-- Right Trust Badge -->
                <div class="flex items-center gap-1.5 text-xs font-semibold text-secondary">
                    <span class="material-symbols-outlined text-[16px]">verified</span>
                    <span>100% Sách Thật Chuẩn NXB</span>
                </div>
            </div>
        </div>
    </header>

    <!-- ================= MAIN CONTENT CONTAINER ================= -->
    <main class="w-full pt-[156px] flex-1">
        <?= $content ?>
    </main>

    <!-- ================= GLOBAL FOOTER (5 CỘT) ================= -->
    <footer class="bg-primary text-white pt-14 pb-8 border-t border-primary-container mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 pb-12 border-b border-white/10">
                <!-- Cột 1: Thông tin thương hiệu -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-secondary flex items-center justify-center text-white shadow">
                            <span class="material-symbols-outlined text-[22px]">auto_stories</span>
                        </div>
                        <span class="text-lg font-bold tracking-tight"><?= APP_NAME ?></span>
                    </div>
                    <p class="text-xs text-on-primary-container leading-relaxed pr-6">
                        Hệ sinh thái phân phối sách bản quyền tinh hoa, tôn vinh giá trị tri thức và trải nghiệm thưởng thức tác phẩm văn học, học thuật đương đại.
                    </p>
                    <div class="space-y-1.5 text-xs text-on-primary-container">
                        <p class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-secondary-fixed">location_on</span>161B Lý Chính Thắng, Phường Võ Thị Sáu, Quận 3, TP.HCM</p>
                        <p class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-secondary-fixed">call</span>Hotline: <?= HOTLINE ?> (Hỗ trợ 24/7)</p>
                        <p class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-secondary-fixed">mail</span>Email: support@bookstore.vn</p>
                    </div>
                </div>

                <!-- Cột 2: Danh mục tuyển chọn -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-secondary-fixed">Danh Mục Sách</h4>
                    <ul class="space-y-2 text-xs text-on-primary-container">
                        <?php foreach (array_slice($categories, 0, 5) as $cat): ?>
                            <li><a href="<?= base_url('tim-kiem?the_loai=' . $cat['id']) ?>" class="hover:text-white transition-colors"><?= htmlspecialchars($cat['ten']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Cột 3: Hỗ trợ khách hàng -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-secondary-fixed">Chính Sách & Hỗ Trợ</h4>
                    <ul class="space-y-2 text-xs text-on-primary-container">
                        <li><a href="#" class="hover:text-white transition-colors">Điều khoản sử dụng</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Chính sách bảo mật</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Chính sách đổi trả 7 ngày</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Quy trình vận chuyển</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Hướng dẫn thanh toán</a></li>
                    </ul>
                </div>

                <!-- Cột 4: Kết nối & Bản tin -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-secondary-fixed">Nhận Voucher 50K</h4>
                    <p class="text-xs text-on-primary-container">Đăng ký email để nhận mã giảm giá và thông tin các tựa sách mới nhất.</p>
                    <form onsubmit="event.preventDefault(); alert('Cảm ơn bạn đã đăng ký nhận tin!');" class="space-y-2">
                        <input type="email" placeholder="Nhập email của bạn..." required class="w-full px-3 py-2 rounded-lg bg-primary-container text-white text-xs placeholder:text-on-primary-container/60 border border-white/10 focus:outline-none focus:border-secondary"/>
                        <button type="submit" class="w-full py-2 bg-secondary text-white rounded-lg text-xs font-bold hover:bg-secondary-fixed hover:text-primary transition-colors">
                            Đăng Ký Ngay
                        </button>
                    </form>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-on-primary-container gap-4">
                <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. Bản quyền thuộc về Nhã Thư Vân Hoa.</p>
                <div class="flex items-center gap-4 text-secondary-fixed">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">verified_user</span>Thanh toán bảo mật SSL</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">local_shipping</span>Giao hàng toàn quốc</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
