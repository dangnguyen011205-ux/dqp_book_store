<?php
// Views/client/auth/register.php
$oldData = $_SESSION['old'] ?? [];
?>
<div class="max-w-5xl mx-auto px-4 py-12">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-12 border border-slate-100">
        
        <!-- Cột trái: Lợi ích thành viên -->
        <div class="md:col-span-5 bg-gradient-to-br from-primary-container via-[#133352] to-primary p-8 md:p-10 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-secondary/20 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-secondary-fixed text-xs font-semibold backdrop-blur-md">
                    <span class="material-symbols-outlined text-[15px]">workspace_premium</span>
                    <span>ĐỘC QUYỀN HỘI VIÊN</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-white leading-snug">
                        Gia Nhập Cộng Đồng <br/>
                        <span class="text-secondary-fixed">Độc Giả Tinh Hoa</span>
                    </h2>
                    <p class="text-xs text-on-primary-container mt-2 leading-relaxed">
                        Khám phá kho tàng tri thức bất tận và tận hưởng các đặc quyền vượt trội dành riêng cho bạn.
                    </p>
                </div>

                <!-- Danh sách đặc quyền -->
                <div class="space-y-3 pt-2">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-secondary-fixed text-[20px] shrink-0 mt-0.5">redeem</span>
                        <div class="text-xs">
                            <strong class="text-white block font-semibold">Tặng Voucher 50.000đ</strong>
                            <span class="text-on-primary-container">Áp dụng ngay cho đơn hàng sách đầu tiên.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-secondary-fixed text-[20px] shrink-0 mt-0.5">local_shipping</span>
                        <div class="text-xs">
                            <strong class="text-white block font-semibold">Miễn Phí Vận Chuyển</strong>
                            <span class="text-on-primary-container">Ưu đãi freeship toàn quốc cho các đơn từ 250k.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-secondary-fixed text-[20px] shrink-0 mt-0.5">menu_book</span>
                        <div class="text-xs">
                            <strong class="text-white block font-semibold">Sổ Địa Chỉ & Lịch Sử Đơn</strong>
                            <span class="text-on-primary-container">Lưu nhiều địa chỉ nhận hàng và theo dõi vận đơn 24/7.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 pt-6 border-t border-white/10 mt-6">
                <p class="text-[11px] text-on-primary-container">
                    Cam kết bảo mật 100% dữ liệu cá nhân theo tiêu chuẩn quốc tế.
                </p>
            </div>
        </div>

        <!-- Cột phải: Form Đăng Ký -->
        <div class="md:col-span-7 p-8 md:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-md mx-auto w-full space-y-6">
                <div>
                    <h1 class="text-2xl font-bold text-primary tracking-tight">Tạo Tài Khoản Mới</h1>
                    <p class="text-xs text-slate-500 mt-1">Chỉ mất chưa đầy 1 phút để trở thành hội viên.</p>
                </div>

                <form action="<?= base_url('dang-ky') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <!-- Họ và tên -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Họ Và Tên *</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">person</span>
                            <input type="text" name="ho_ten" value="<?= htmlspecialchars($oldData['ho_ten'] ?? '') ?>" required 
                                   placeholder="Nguyễn Văn A"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"/>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Địa Chỉ Email *</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">mail</span>
                            <input type="email" name="email" value="<?= htmlspecialchars($oldData['email'] ?? '') ?>" required 
                                   placeholder="name@example.com"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"/>
                        </div>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Số Điện Thoại</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">call</span>
                            <input type="tel" name="so_dien_thoai" value="<?= htmlspecialchars($oldData['so_dien_thoai'] ?? '') ?>" 
                                   placeholder="0912 345 678"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"/>
                        </div>
                    </div>

                    <!-- Mật khẩu -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Mật Khẩu (Ít nhất 6 ký tự) *</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">lock</span>
                            <input type="password" name="mat_khau" required 
                                   placeholder="••••••••"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"/>
                        </div>
                    </div>

                    <!-- Xác nhận mật khẩu -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Xác Nhận Mật Khẩu *</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">lock_reset</span>
                            <input type="password" name="mat_khau_xac_nhan" required 
                                   placeholder="••••••••"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"/>
                        </div>
                    </div>

                    <!-- Nút Đăng Ký -->
                    <button type="submit" class="w-full py-3 bg-secondary text-white rounded-xl text-sm font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 mt-2">
                        <span>Hoàn Tất Đăng Ký</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </button>
                </form>

                <!-- Footer link -->
                <p class="text-center text-xs text-slate-500">
                    Bạn đã có tài khoản rồi? 
                    <a href="<?= base_url('dang-nhap') ?>" class="text-primary font-bold hover:underline ml-1">Đăng nhập tại đây</a>
                </p>
            </div>
        </div>
    </div>
</div>
