<?php
// Views/client/account/profile.php
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Cột trái: Sidebar độc giả -->
        <?php require __DIR__ . '/_sidebar.php'; ?>

        <!-- Cột phải: Khu vực cập nhật hồ sơ -->
        <section class="lg:col-span-9 space-y-6">
            
            <!-- Khối 1: Thông tin tài khoản -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-slate-100 space-y-6">
                <div>
                    <h1 class="text-xl font-bold text-primary tracking-tight">Hồ Sơ Độc Giả</h1>
                    <p class="text-xs text-slate-500 mt-1">Quản lý thông tin cá nhân và cập nhật thông tin liên hệ khi nhận sách.</p>
                </div>

                <form action="<?= base_url('tai-khoan') ?>" method="POST" class="space-y-5">
                    <?= csrf_field() ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Họ và tên -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Họ Và Tên *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">person</span>
                                <input type="text" name="ho_ten" value="<?= htmlspecialchars($user['ho_ten'] ?? '') ?>" required
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"/>
                            </div>
                        </div>

                        <!-- Email (Read-only) -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Địa Chỉ Email (Không thay đổi)</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">mail</span>
                                <input type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-100 text-slate-500 border border-slate-200 text-sm cursor-not-allowed"/>
                            </div>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Số Điện Thoại</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">call</span>
                                <input type="tel" name="so_dien_thoai" value="<?= htmlspecialchars($user['so_dien_thoai'] ?? '') ?>"
                                       placeholder="0912 345 678"
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"/>
                            </div>
                        </div>

                        <!-- Ngày tham gia -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Ngày Tham Gia</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">calendar_today</span>
                                <input type="text" value="<?= date('d/m/Y - H:i', strtotime($user['ngay_tao'] ?? 'now')) ?>" disabled
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-100 text-slate-500 border border-slate-200 text-sm cursor-not-allowed"/>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-secondary transition-all shadow flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            <span>Lưu Thay Đổi</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Khối 2: Đổi mật khẩu -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-slate-100 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-primary tracking-tight">Đổi Mật Khẩu Bảo Mật</h2>
                    <p class="text-xs text-slate-500 mt-1">Để tăng cường bảo mật cho tài khoản, bạn nên sử dụng mật khẩu mạnh kết hợp chữ và số.</p>
                </div>

                <form action="<?= base_url('tai-khoan/doi-mat-khau') ?>" method="POST" class="space-y-5">
                    <?= csrf_field() ?>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Mật khẩu cũ -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Mật Khẩu Hiện Tại *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">lock</span>
                                <input type="password" name="mat_khau_cu" required placeholder="••••••••"
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"/>
                            </div>
                        </div>

                        <!-- Mật khẩu mới -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Mật Khẩu Mới *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">key</span>
                                <input type="password" name="mat_khau_moi" required placeholder="Tối thiểu 6 ký tự"
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"/>
                            </div>
                        </div>

                        <!-- Xác nhận mật khẩu mới -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nhập Lại Mật Khẩu Mới *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">lock_reset</span>
                                <input type="password" name="mat_khau_xac_nhan" required placeholder="••••••••"
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"/>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-secondary text-white rounded-xl text-xs font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-all shadow flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">lock_open</span>
                            <span>Cập Nhật Mật Khẩu</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
