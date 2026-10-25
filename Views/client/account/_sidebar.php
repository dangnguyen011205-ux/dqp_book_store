<?php
// Views/client/account/_sidebar.php
use Core\Auth;

$u = Auth::user();
$currentPath = trim($_GET['url'] ?? '', '/');
?>
<aside class="lg:col-span-3 space-y-4">
    <!-- User Profile Badge Card -->
    <div class="bg-surface-container-lowest rounded-2xl p-5 shadow-sm border border-slate-100 space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-secondary/10 border-2 border-secondary/40 text-secondary flex items-center justify-center font-bold text-lg shrink-0">
                <?= strtoupper(mb_substr($u['ho_ten'] ?? 'U', 0, 1, 'UTF-8')) ?>
            </div>
            <div class="min-w-0 flex-1">
                <h3 class="text-sm font-bold text-primary truncate"><?= htmlspecialchars($u['ho_ten'] ?? 'Độc Giả') ?></h3>
                <p class="text-xs text-slate-500 truncate"><?= htmlspecialchars($u['email'] ?? '') ?></p>
                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-secondary/10 text-secondary mt-1">
                    <?= ($u['vai_tro'] ?? '') === 'quan_tri' ? 'Quản Trị Viên' : 'DQP Hội Viên Thân Thiết' ?>
                </span>
            </div>
        </div>

        <!-- Điểm tích lũy hội viên -->
        <div class="p-3 bg-surface-container-low rounded-xl space-y-1.5 border border-slate-200/60">
            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-500">Điểm tri thức:</span>
                <span class="font-bold text-primary">1.450 / 2.000 điểm</span>
            </div>
            <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                <div class="h-full bg-secondary rounded-full" style="width: 72.5%;"></div>
            </div>
            <p class="text-[11px] text-slate-500 flex items-center gap-1 pt-0.5">
                <span class="material-symbols-outlined text-[14px] text-secondary">workspace_premium</span>
                Còn 550 điểm để nhận Voucher 200.000đ
            </p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="bg-surface-container-lowest rounded-2xl p-2 shadow-sm border border-slate-100 flex flex-col space-y-1 text-xs font-semibold">
        <!-- Hồ sơ cá nhân -->
        <a href="<?= base_url('tai-khoan') ?>" 
           class="flex items-center justify-between px-4 py-3 rounded-xl transition-colors <?= ($currentPath === 'tai-khoan' || $currentPath === 'ho-so-ca-nhan') ? 'bg-primary text-white shadow-sm' : 'text-slate-600 hover:bg-surface-container-low hover:text-primary' ?>">
            <span class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[18px]">account_circle</span>
                <span>Hồ sơ cá nhân</span>
            </span>
            <?php if ($currentPath === 'tai-khoan' || $currentPath === 'ho-so-ca-nhan'): ?>
                <span class="w-2 h-2 rounded-full bg-secondary-fixed"></span>
            <?php endif; ?>
        </a>

        <!-- Sổ địa chỉ -->
        <a href="<?= base_url('tai-khoan/so-dia-chi') ?>" 
           class="flex items-center justify-between px-4 py-3 rounded-xl transition-colors <?= (str_starts_with($currentPath, 'tai-khoan/so-dia-chi') || $currentPath === 'so-dia-chi') ? 'bg-primary text-white shadow-sm' : 'text-slate-600 hover:bg-surface-container-low hover:text-primary' ?>">
            <span class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[18px]">pin_drop</span>
                <span>Sổ địa chỉ nhận hàng</span>
            </span>
            <?php if (str_starts_with($currentPath, 'tai-khoan/so-dia-chi') || $currentPath === 'so-dia-chi'): ?>
                <span class="w-2 h-2 rounded-full bg-secondary-fixed"></span>
            <?php endif; ?>
        </a>

        <!-- Đơn hàng của tôi -->
        <a href="<?= base_url('tai-khoan/don-hang') ?>" 
           class="flex items-center justify-between px-4 py-3 rounded-xl transition-colors <?= (str_starts_with($currentPath, 'tai-khoan/don-hang') || $currentPath === 'lich-su-don-hang') ? 'bg-primary text-white shadow-sm' : 'text-slate-600 hover:bg-surface-container-low hover:text-primary' ?>">
            <span class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                <span>Lịch sử đơn hàng</span>
            </span>
            <?php if (str_starts_with($currentPath, 'tai-khoan/don-hang') || $currentPath === 'lich-su-don-hang'): ?>
                <span class="w-2 h-2 rounded-full bg-secondary-fixed"></span>
            <?php endif; ?>
        </a>

        <div class="my-1 border-t border-slate-100"></div>

        <a href="<?= base_url('dang-xuat') ?>" class="flex items-center gap-2.5 px-4 py-3 rounded-xl text-rose-600 hover:bg-rose-50 transition-colors">
            <span class="material-symbols-outlined text-[18px]">logout</span>
            <span>Đăng xuất tài khoản</span>
        </a>
    </nav>

    <!-- Reading Goal Mini Widget -->
    <div class="bg-gradient-to-br from-primary-container to-primary text-white rounded-2xl p-5 shadow-sm relative overflow-hidden">
        <div class="relative z-10 space-y-1.5">
            <span class="text-[10px] font-bold text-secondary-fixed flex items-center gap-1 uppercase tracking-wider">
                <span class="material-symbols-outlined text-[14px]">auto_stories</span> Thử thách đọc 2026
            </span>
            <h4 class="text-sm font-bold">Đã đọc 18 / 24 cuốn</h4>
            <p class="text-xs text-on-primary-container leading-relaxed">Bạn sắp hoàn thành mục tiêu tri thức của năm nay.</p>
        </div>
        <div class="absolute -right-3 -bottom-3 opacity-10">
            <span class="material-symbols-outlined text-[80px]">menu_book</span>
        </div>
    </div>
</aside>
