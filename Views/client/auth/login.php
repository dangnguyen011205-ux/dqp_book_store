<?php
// Views/client/auth/login.php
?>
<div class="max-w-5xl mx-auto px-4 py-12">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-12 border border-slate-100">
        
        <!-- Cột trái: Brand Banner & Triết lý đọc sách -->
        <div class="md:col-span-5 bg-gradient-to-br from-primary-container via-[#133352] to-primary p-8 md:p-10 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-secondary/20 blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-secondary-fixed text-xs font-semibold backdrop-blur-md">
                    <span class="material-symbols-outlined text-[15px]">auto_stories</span>
                    <span>DQP BOOK STORE</span>
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-white leading-snug">
                    Cánh Cửa Mở Ra <br/>
                    <span class="text-secondary-fixed">Vương Quốc Tri Thức</span>
                </h2>
                <p class="text-xs text-on-primary-container leading-relaxed">
                    Đăng nhập để theo dõi lộ trình đơn hàng, quản lý tủ sách tâm đắc và nhận các ưu đãi độc quyền dành riêng cho hội viên.
                </p>
            </div>

            <!-- Trích dẫn triết lý -->
            <div class="relative z-10 pt-8 border-t border-white/10 mt-8">
                <blockquote class="italic text-xs text-white/90 leading-relaxed">
                    “Một cuốn sách thực sự tốt là cuốn sách mà sau khi đọc xong, bạn cảm thấy mình thông thái và thấu hiểu cuộc đời hơn một chút.”
                </blockquote>
                <p class="text-[11px] text-secondary-fixed font-semibold mt-2">— Lời ngỏ từ DQP Book</p>
            </div>
        </div>

        <!-- Cột phải: Form Đăng Nhập -->
        <div class="md:col-span-7 p-8 md:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-md mx-auto w-full space-y-6">
                <div>
                    <h1 class="text-2xl font-bold text-primary tracking-tight">Đăng Nhập Tài Khoản</h1>
                    <p class="text-xs text-slate-500 mt-1">Chào mừng bạn quay lại với không gian sách tinh hoa.</p>
                </div>

                <form action="<?= base_url('dang-nhap') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Đăng Ký</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">mail</span>
                            <input type="email" id="login-email" name="email" value="<?= htmlspecialchars(old('email', '')) ?>" required 
                                   placeholder="name@example.com"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"/>
                        </div>
                    </div>

                    <!-- Mật khẩu -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Mật Khẩu</label>
                            <a href="#" onclick="alert('Vui lòng liên hệ ban quản trị hoặc hotline 1900 6868 để cấp lại mật khẩu.'); return false;" class="text-xs text-secondary font-semibold hover:underline">
                                Quên mật khẩu?
                            </a>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">lock</span>
                            <input type="password" id="login-password" name="password" required 
                                   placeholder="••••••••"
                                   class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary focus:border-transparent transition-all"/>
                            <button type="button" onclick="togglePasswordVisibility('login-password')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Nút Đăng Nhập -->
                    <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl text-sm font-bold hover:bg-secondary transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <span>Đăng Nhập</span>
                        <span class="material-symbols-outlined text-[18px]">login</span>
                    </button>
                </form>

                <!-- Box Tài Khoản Demo Mẫu -->
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-600 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-secondary">vpn_key</span>
                            Tài Khoản Mẫu Thử Nghiệm (Click để điền)
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <button type="button" onclick="fillDemoAccount('admin@bookstore.vn', '123456')" class="p-2 rounded-lg bg-white border border-slate-200 text-left hover:border-secondary transition-colors">
                            <p class="font-bold text-primary">Quản Trị Viên</p>
                            <p class="text-[11px] text-slate-500 truncate">admin@bookstore.vn</p>
                            <p class="text-[10px] text-slate-400">Pass: 123456</p>
                        </button>
                        <button type="button" onclick="fillDemoAccount('nguyenvanan@gmail.com', '123456')" class="p-2 rounded-lg bg-white border border-slate-200 text-left hover:border-secondary transition-colors">
                            <p class="font-bold text-secondary">Khách Hàng</p>
                            <p class="text-[11px] text-slate-500 truncate">nguyenvanan@gmail.com</p>
                            <p class="text-[10px] text-slate-400">Pass: 123456</p>
                        </button>
                    </div>
                </div>

                <!-- Footer link -->
                <p class="text-center text-xs text-slate-500">
                    Bạn chưa có tài khoản tại DQP Book? 
                    <a href="<?= base_url('dang-ky') ?>" class="text-secondary font-bold hover:underline ml-1">Đăng ký thành viên ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}

function fillDemoAccount(email, password) {
    document.getElementById('login-email').value = email;
    document.getElementById('login-password').value = password;
}
</script>
