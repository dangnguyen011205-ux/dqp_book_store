<?php
// Views/admin/dashboard.php
?>
<div class="space-y-6">
    <!-- Top Greeting -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary tracking-tight">Bảng Điều Khiển Quản Trị</h1>
            <p class="text-xs text-slate-500 mt-1">Chào mừng bạn quay lại hệ thống điều hành nhà sách trực tuyến DQP Book Store.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-secondary/10 text-secondary text-xs font-bold flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">domain</span> Kho Trung Tâm TP.HCM
            </span>
        </div>
    </div>

    <!-- 4 Bento KPI Cards (Theo chuẩn DESIGN.md) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Tổng đầu sách -->
        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tổng Đầu Sách</span>
                <div class="w-9 h-9 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-[20px]">auto_stories</span>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-primary tracking-tight"><?= number_format($totalBooks) ?> <span class="text-xs font-normal text-slate-400">tựa</span></div>
                <p class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1 mt-1">
                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Đang lưu hành
                </p>
            </div>
        </div>

        <!-- Card 2: Tổng tồn kho -->
        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tồn Kho Hệ Thống</span>
                <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-700 flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-primary tracking-tight"><?= number_format($totalStock) ?> <span class="text-xs font-normal text-slate-400">cuốn</span></div>
                <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-1">
                    <span class="material-symbols-outlined text-[14px]">warehouse</span> Phân bố tại Kho Tổng
                </p>
            </div>
        </div>

        <!-- Card 3: Đơn hàng -->
        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tổng Đơn Hàng</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-primary tracking-tight"><?= number_format($totalOrders) ?> <span class="text-xs font-normal text-slate-400">đơn</span></div>
                <p class="text-[11px] text-amber-600 font-semibold flex items-center gap-1 mt-1">
                    <span class="material-symbols-outlined text-[14px]">sync</span> Đồng bộ từ sàn
                </p>
            </div>
        </div>

        <!-- Card 4: Hội viên độc giả -->
        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Độc Giả & Hội Viên</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-primary tracking-tight"><?= number_format($totalUsers) ?> <span class="text-xs font-normal text-slate-400">tài khoản</span></div>
                <p class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1 mt-1">
                    <span class="material-symbols-outlined text-[14px]">verified</span> Hội viên hoạt động
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Box Giai đoạn -->
    <div class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-2">
        <h3 class="text-sm font-bold text-primary flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary">task_alt</span>
            Trạng Thái Module Quản Trị
        </h3>
        <p class="text-xs text-slate-600 leading-relaxed">
            Layout Quản trị, Sidebar điều hướng, Topbar và hệ thống xác thực phân quyền Admin đã sẵn sàng hoạt động hoàn hảo. Toàn bộ các module quản lý chuyên sâu (CRUD Sách, Kho vận & Lập phiếu kho, Xử lý Đơn hàng, Duyệt Đánh giá, Quản lý Mã giảm giá & Người dùng) sẽ được hoàn thiện tại <strong>Giai đoạn 4</strong> theo đúng kế hoạch.
        </p>
    </div>
</div>
