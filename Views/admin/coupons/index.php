<?php
// Views/admin/coupons/index.php
// Quản lý Mã Giảm Giá & Voucher Khuyến Mãi - Athenaeum Modern v2.4
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
                <span class="text-primary font-bold">Mã Giảm Giá</span>
                <span class="ml-1.5 px-2 py-0.5 rounded-full bg-slate-100 text-[10px] font-bold text-slate-500">CSDL: ma_giam_gia</span>
            </nav>
            <div class="flex items-baseline gap-2 mt-0.5">
                <h1 class="text-2xl lg:text-3xl font-extrabold text-primary tracking-tight">Quản Lý Mã Giảm Giá</h1>
                <span class="hidden sm:inline-block px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[11px] font-bold">
                    Khuyến Mãi & Voucher • v2.4
                </span>
            </div>
            <p class="text-xs text-on-surface-variant">
                Thiết lập các mã voucher ưu đãi, tỷ lệ giảm phần trăm / tiền mặt, đơn tối thiểu và giới hạn lượt áp dụng.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openAddCouponModal()" class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm transition-all active:scale-95" type="button">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>Tạo Mã Mới</span>
            </button>
            <a href="<?= base_url('admin/ma-giam-gia') ?>" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-primary text-xs font-semibold shadow-sm border border-slate-200 transition-all active:scale-95" title="Làm mới">
                <span class="material-symbols-outlined text-[18px] text-amber-600">sync</span>
                <span>Làm Mới</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI Bento Cards (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Tổng số mã -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Tổng Số Voucher</span>
                <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[18px]">confirmation_number</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-primary"><?= number_format($statistics['total'] ?? 0) ?></span>
                <span class="text-xs text-on-surface-variant font-bold">mã đã tạo</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Chương trình ưu đãi:</span>
                <span class="font-bold text-primary">Toàn hệ thống</span>
            </div>
        </div>

        <!-- Card 2: Đang hiệu lực -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Đang Hiệu Lực</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-800">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-emerald-700"><?= number_format($statistics['active'] ?? 0) ?></span>
                <span class="text-xs text-emerald-600 font-bold">mã sẵn sàng</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Tỷ lệ khả dụng:</span>
                <span class="font-bold text-emerald-700">
                    <?= !empty($statistics['total']) ? round(($statistics['active'] / $statistics['total']) * 100) : 0 ?>% tổng mã
                </span>
            </div>
        </div>

        <!-- Card 3: Hết hạn & Hết lượt -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Hết Hạn / Hết Lượt</span>
                <span class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center text-rose-800">
                    <span class="material-symbols-outlined text-[18px]">event_busy</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-rose-700"><?= number_format(($statistics['expired'] ?? 0) + ($statistics['max_used'] ?? 0)) ?></span>
                <span class="text-xs text-rose-600 font-bold">mã vô hiệu</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Hết hạn: <b class="text-rose-700"><?= $statistics['expired'] ?? 0 ?></b></span>
                <span>Hết lượt: <b class="text-amber-700"><?= $statistics['max_used'] ?? 0 ?></b></span>
            </div>
        </div>

        <!-- Card 4: Tổng lượt dùng & Tiết kiệm -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Tổng Lượt Đã Áp Dụng</span>
                <span class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-800">
                    <span class="material-symbols-outlined text-[18px]">savings</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-amber-700"><?= number_format($statistics['total_used'] ?? 0) ?></span>
                <span class="text-xs text-amber-600 font-bold">lượt dùng</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Đã tiết kiệm cho khách:</span>
                <span class="font-bold text-emerald-700"><?= format_currency($statistics['total_discount'] ?? 0) ?></span>
            </div>
        </div>
    </div>

    <!-- 3. Bộ lọc & Tìm kiếm -->
    <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-100">
        <form method="GET" action="<?= base_url('admin/ma-giam-gia') ?>" class="flex flex-wrap items-center gap-3">
            <!-- Ô tìm kiếm mã -->
            <div class="relative flex-1 min-w-[240px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                <input type="text" 
                       name="q" 
                       value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Tìm theo mã voucher (VD: WELCOME, GIAM30K)..." 
                       class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-on-surface focus:outline-none focus:border-secondary focus:bg-white transition-all uppercase"/>
            </div>

            <!-- Lọc Loại giảm giá -->
            <div class="w-full sm:w-auto">
                <select name="type" class="w-full sm:w-auto px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-on-surface focus:outline-none focus:border-secondary">
                    <option value="tat_ca" <?= $type === 'tat_ca' ? 'selected' : '' ?>>Tất cả loại giảm</option>
                    <option value="phan_tram" <?= $type === 'phan_tram' ? 'selected' : '' ?>>Giảm theo phần trăm (%)</option>
                    <option value="tien_mat" <?= $type === 'tien_mat' ? 'selected' : '' ?>>Giảm tiền mặt (đ)</option>
                </select>
            </div>

            <!-- Lọc Trạng thái hiệu lực -->
            <div class="w-full sm:w-auto">
                <select name="status" class="w-full sm:w-auto px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-on-surface focus:outline-none focus:border-secondary">
                    <option value="tat_ca" <?= $status === 'tat_ca' ? 'selected' : '' ?>>Tất cả trạng thái</option>
                    <option value="con_han" <?= $status === 'con_han' ? 'selected' : '' ?>>Đang hiệu lực (Sẵn sàng)</option>
                    <option value="chua_bat_dau" <?= $status === 'chua_bat_dau' ? 'selected' : '' ?>>Chưa bắt đầu</option>
                    <option value="het_han" <?= $status === 'het_han' ? 'selected' : '' ?>>Đã hết hạn</option>
                    <option value="het_luot" <?= $status === 'het_luot' ? 'selected' : '' ?>>Đã hết lượt sử dụng</option>
                </select>
            </div>

            <!-- Nút submit lọc -->
            <button type="submit" class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-primary text-xs font-bold transition-colors">
                <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                <span>Lọc Dữ Liệu</span>
            </button>

            <?php if (!empty($search) || $status !== 'tat_ca' || $type !== 'tat_ca'): ?>
                <a href="<?= base_url('admin/ma-giam-gia') ?>" class="text-xs font-semibold text-rose-600 hover:underline flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                    <span>Xóa lọc</span>
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- 4. Bảng Dữ Liệu Mã Giảm Giá -->
    <div class="rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-on-surface-variant font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4 text-center w-12">#</th>
                        <th class="py-3.5 px-4">Mã Voucher</th>
                        <th class="py-3.5 px-4">Loại & Mức Giảm</th>
                        <th class="py-3.5 px-4">Đơn Tối Thiểu</th>
                        <th class="py-3.5 px-4">Thời Gian Hiệu Lực</th>
                        <th class="py-3.5 px-4">Lượt Đã Dùng</th>
                        <th class="py-3.5 px-4 text-center">Trạng Thái</th>
                        <th class="py-3.5 px-4 text-center w-28">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($coupons)): ?>
                        <tr>
                            <td colspan="8" class="py-12 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-slate-300">confirmation_number</span>
                                    <p class="font-medium">Không tìm thấy mã giảm giá nào phù hợp.</p>
                                    <button onclick="openAddCouponModal()" class="mt-2 text-secondary font-bold hover:underline">
                                        + Tạo mã giảm giá mới
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($coupons as $idx => $c): ?>
                            <?php 
                                $now = date('Y-m-d H:i:s');
                                $isExpired = $c['ket_thuc'] < $now;
                                $isNotStarted = $c['bat_dau'] > $now;
                                $isMaxUsed = ($c['so_luot_toi_da'] > 0 && $c['da_dung'] >= $c['so_luot_toi_da']);
                                $isActive = (!$isExpired && !$isNotStarted && !$isMaxUsed);

                                $percentUsed = ($c['so_luot_toi_da'] > 0) ? min(100, round(($c['da_dung'] / $c['so_luot_toi_da']) * 100)) : 0;
                            ?>
                            <tr id="coupon-row-<?= $c['id'] ?>" class="hover:bg-slate-50/60 transition-colors">
                                <!-- STT -->
                                <td class="py-3 px-4 text-center font-semibold text-slate-400">
                                    <?= $idx + 1 ?>
                                </td>

                                <!-- Mã Code -->
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="px-2.5 py-1 rounded-lg border border-dashed border-secondary/40 bg-secondary/5 font-mono font-bold text-xs text-secondary tracking-wider flex items-center gap-1.5 shadow-2xs">
                                            <span class="material-symbols-outlined text-[14px]">local_activity</span>
                                            <span><?= htmlspecialchars($c['ma']) ?></span>
                                        </div>
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-1">
                                        Đã dùng trong <?= number_format($c['so_don_hang'] ?? 0) ?> đơn hàng
                                    </div>
                                </td>

                                <!-- Loại & Mức Giảm -->
                                <td class="py-3 px-4">
                                    <?php if ($c['loai'] === 'phan_tram'): ?>
                                        <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-sky-100 text-sky-800 font-bold text-xs">
                                            <span class="material-symbols-outlined text-[14px]">percent</span>
                                            <span>Giảm <?= number_format($c['gia_tri']) ?>%</span>
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">Giảm theo % đơn hàng</div>
                                    <?php else: ?>
                                        <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs">
                                            <span class="material-symbols-outlined text-[14px]">payments</span>
                                            <span>Giảm <?= format_currency($c['gia_tri']) ?></span>
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">Trừ trực tiếp tiền mặt</div>
                                    <?php endif; ?>
                                </td>

                                <!-- Đơn Tối Thiểu -->
                                <td class="py-3 px-4 font-semibold text-primary">
                                    <?= (float)$c['don_toi_thieu'] > 0 ? format_currency($c['don_toi_thieu']) : '<span class="text-slate-400 font-normal">Không yêu cầu</span>' ?>
                                </td>

                                <!-- Thời Gian Hiệu Lực -->
                                <td class="py-3 px-4 text-on-surface-variant">
                                    <div class="flex items-center gap-1 text-[11px] font-medium">
                                        <span class="text-slate-400">Từ:</span>
                                        <span><?= date('d/m/Y H:i', strtotime($c['bat_dau'])) ?></span>
                                    </div>
                                    <div class="flex items-center gap-1 text-[11px] font-medium mt-0.5">
                                        <span class="text-slate-400">Đến:</span>
                                        <span class="<?= $isExpired ? 'text-rose-600 font-bold line-through' : 'text-slate-700' ?>">
                                            <?= date('d/m/Y H:i', strtotime($c['ket_thuc'])) ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Lượt Đã Dùng -->
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-between text-[11px] font-bold mb-1">
                                        <span class="text-primary"><?= number_format($c['da_dung']) ?></span>
                                        <span class="text-slate-400 font-normal">
                                            / <?= $c['so_luot_toi_da'] > 0 ? number_format($c['so_luot_toi_da']) : '∞' ?>
                                        </span>
                                    </div>
                                    <?php if ($c['so_luot_toi_da'] > 0): ?>
                                        <div class="w-24 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full rounded-full <?= $percentUsed >= 100 ? 'bg-rose-500' : ($percentUsed >= 80 ? 'bg-amber-500' : 'bg-secondary') ?>" style="width: <?= $percentUsed ?>%"></div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-[10px] text-emerald-600 font-medium">Không giới hạn</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Trạng Thái Badge -->
                                <td class="py-3 px-4 text-center">
                                    <?php if ($isActive): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                            <span>Hoạt động</span>
                                        </span>
                                    <?php elseif ($isNotStarted): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-sky-100 text-sky-800 text-[11px] font-bold">
                                            <span class="material-symbols-outlined text-[13px]">schedule</span>
                                            <span>Chưa bắt đầu</span>
                                        </span>
                                    <?php elseif ($isMaxUsed): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold">
                                            <span class="material-symbols-outlined text-[13px]">lock</span>
                                            <span>Hết lượt</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 text-[11px] font-bold">
                                            <span class="material-symbols-outlined text-[13px]">cancel</span>
                                            <span>Hết hạn</span>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Thao Tác -->
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button onclick="openEditCouponModal(<?= $c['id'] ?>)" 
                                                class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-secondary hover:text-white text-slate-600 flex items-center justify-center transition-all" 
                                                title="Chỉnh sửa voucher">
                                            <span class="material-symbols-outlined text-[16px]">edit</span>
                                        </button>
                                        <button onclick="deleteCoupon(<?= $c['id'] ?>, '<?= htmlspecialchars($c['ma']) ?>', <?= (int)($c['so_don_hang'] ?? 0) ?>)" 
                                                class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-rose-600 hover:text-white text-slate-600 flex items-center justify-center transition-all" 
                                                title="Xóa voucher">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL THÊM / CHỈNH SỬA MÃ GIẢM GIÁ         -->
<!-- ========================================== -->
<div id="couponModalBackdrop" class="fixed inset-0 bg-primary/40 backdrop-blur-xs z-50 transition-opacity duration-300 opacity-0 pointer-events-none"></div>

<div id="couponModal" class="fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-3xl shadow-2xl z-50 p-6 transition-all duration-300 opacity-0 pointer-events-none scale-95 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div class="flex items-center gap-2.5">
            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined text-[22px]">confirmation_number</span>
            </div>
            <div>
                <h3 id="modalTitle" class="text-base font-bold text-primary">Tạo Mã Giảm Giá Mới</h3>
                <p class="text-xs text-on-surface-variant">Cấu hình thông số voucher áp dụng cho toàn sàn</p>
            </div>
        </div>
        <button onclick="closeCouponModal()" class="w-8 h-8 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>

    <form id="couponForm" method="POST" action="<?= base_url('admin/ma-giam-gia/them') ?>" class="mt-4 flex flex-col gap-4">
        <input type="hidden" id="couponId" name="id" value=""/>

        <!-- Mã Voucher -->
        <div>
            <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-1.5" for="couponCode">
                Mã Giảm Giá (Code) <span class="text-rose-600">*</span>
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">local_activity</span>
                <input type="text" 
                       id="couponCode" 
                       name="ma" 
                       required 
                       placeholder="VD: WELCOME2026, SUMMER50" 
                       class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono font-bold text-primary uppercase focus:outline-none focus:border-secondary focus:bg-white tracking-wider"/>
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Chỉ gồm chữ in hoa, số và gạch nối (không chứa khoảng trắng).</p>
        </div>

        <!-- Loại giảm giá & Giá trị -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-1.5" for="couponType">
                    Loại Giảm Giá <span class="text-rose-600">*</span>
                </label>
                <select id="couponType" name="loai" onchange="handleTypeChange()" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-primary focus:outline-none focus:border-secondary">
                    <option value="phan_tram">Phần trăm (%)</option>
                    <option value="tien_mat">Tiền mặt (VNĐ)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-1.5" for="couponValue">
                    Mức Giảm <span class="text-rose-600">*</span> <span id="valueUnit" class="text-secondary">(%)</span>
                </label>
                <input type="number" 
                       id="couponValue" 
                       name="gia_tri" 
                       required 
                       min="1" 
                       step="any"
                       placeholder="VD: 10" 
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-primary focus:outline-none focus:border-secondary focus:bg-white"/>
            </div>
        </div>

        <!-- Đơn tối thiểu & Giới hạn số lượt -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-1.5" for="couponMinOrder">
                    Đơn Tối Thiểu (VNĐ)
                </label>
                <input type="number" 
                       id="couponMinOrder" 
                       name="don_toi_thieu" 
                       min="0" 
                       step="1000"
                       value="0" 
                       placeholder="VD: 150000" 
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-primary focus:outline-none focus:border-secondary focus:bg-white"/>
                <p class="text-[10px] text-slate-400 mt-1">Để 0 nếu áp dụng cho mọi giá trị đơn.</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-1.5" for="couponMaxUsage">
                    Số Lượt Tối Đa
                </label>
                <input type="number" 
                       id="couponMaxUsage" 
                       name="so_luot_toi_da" 
                       min="0" 
                       value="0" 
                       placeholder="VD: 100" 
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-primary focus:outline-none focus:border-secondary focus:bg-white"/>
                <p class="text-[10px] text-slate-400 mt-1">Để 0 nếu không giới hạn lượt dùng.</p>
            </div>
        </div>

        <!-- Thời gian bắt đầu & kết thúc -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-1.5" for="couponStart">
                    Bắt Đầu Áp Dụng <span class="text-rose-600">*</span>
                </label>
                <input type="datetime-local" 
                       id="couponStart" 
                       name="bat_dau" 
                       required 
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-primary focus:outline-none focus:border-secondary"/>
            </div>
            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-1.5" for="couponEnd">
                    Kết Thúc Hiệu Lực <span class="text-rose-600">*</span>
                </label>
                <input type="datetime-local" 
                       id="couponEnd" 
                       name="ket_thuc" 
                       required 
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-primary focus:outline-none focus:border-secondary"/>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
            <button type="button" onclick="closeCouponModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                Hủy Bỏ
            </button>
            <button type="submit" id="modalSubmitBtn" class="px-5 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm transition-all active:scale-95">
                Tạo Mã Mới
            </button>
        </div>
    </form>
</div>

<!-- ========================================== -->
<!-- TOAST NOTIFICATION CONTAINER               -->
<!-- ========================================== -->
<div id="statusToast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-primary text-white px-5 py-3.5 rounded-2xl shadow-xl transition-all duration-300 transform translate-y-20 opacity-0 pointer-events-none">
    <span id="toastIcon" class="material-symbols-outlined text-[20px] text-emerald-400">check_circle</span>
    <div class="flex flex-col">
        <span id="toastTitle" class="text-xs font-bold">Thao tác thành công</span>
        <span id="toastMessage" class="text-[11px] text-slate-300">Dữ liệu mã giảm giá đã được cập nhật.</span>
    </div>
</div>

<!-- JAVASCRIPT XỬ LÝ TƯƠNG TÁC -->
<script>
    const baseUrl = '<?= base_url() ?>/';
    let toastTimer = null;

    function showToast(title, message, isError = false) {
        const toast = document.getElementById('statusToast');
        const toastTitle = document.getElementById('toastTitle');
        const toastMsg = document.getElementById('toastMessage');
        const toastIcon = document.getElementById('toastIcon');

        toastTitle.textContent = title;
        toastMsg.textContent = message;

        if (isError) {
            toast.classList.remove('bg-primary');
            toast.classList.add('bg-rose-700');
            toastIcon.className = "material-symbols-outlined text-[20px] text-white";
            toastIcon.textContent = "error";
        } else {
            toast.classList.remove('bg-rose-700');
            toast.classList.add('bg-primary');
            toastIcon.className = "material-symbols-outlined text-[20px] text-emerald-400";
            toastIcon.textContent = "check_circle";
        }

        toast.classList.remove('translate-y-20', 'opacity-0');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3500);
    }

    function handleTypeChange() {
        const type = document.getElementById('couponType').value;
        const unit = document.getElementById('valueUnit');
        const valInput = document.getElementById('couponValue');

        if (type === 'phan_tram') {
            unit.textContent = '(%)';
            valInput.max = '100';
            valInput.placeholder = 'VD: 15';
        } else {
            unit.textContent = '(VNĐ)';
            valInput.removeAttribute('max');
            valInput.placeholder = 'VD: 30000';
        }
    }

    function openAddCouponModal() {
        document.getElementById('modalTitle').textContent = 'Tạo Mã Giảm Giá Mới';
        document.getElementById('couponForm').action = baseUrl + 'admin/ma-giam-gia/them';
        document.getElementById('couponId').value = '';
        document.getElementById('couponCode').value = '';
        document.getElementById('couponType').value = 'phan_tram';
        handleTypeChange();
        document.getElementById('couponValue').value = '';
        document.getElementById('couponMinOrder').value = '0';
        document.getElementById('couponMaxUsage').value = '0';

        // Mặc định thời gian: Bắt đầu từ hiện tại, kết thúc sau 30 ngày
        const now = new Date();
        const startStr = now.toISOString().slice(0, 16);
        now.setDate(now.getDate() + 30);
        const endStr = now.toISOString().slice(0, 16);

        document.getElementById('couponStart').value = startStr;
        document.getElementById('couponEnd').value = endStr;
        document.getElementById('modalSubmitBtn').textContent = 'Tạo Mã Mới';

        document.getElementById('couponModalBackdrop').classList.remove('opacity-0', 'pointer-events-none');
        const modal = document.getElementById('couponModal');
        modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
        modal.classList.add('scale-100');
    }

    function openEditCouponModal(id) {
        fetch(baseUrl + 'admin/ma-giam-gia/chi-tiet/' + id)
            .then(res => res.json())
            .then(res => {
                if (!res.success || !res.data) {
                    alert(res.message || 'Không thể tải thông tin mã giảm giá.');
                    return;
                }
                const c = res.data;
                document.getElementById('modalTitle').textContent = 'Chỉnh Sửa Mã Giảm Giá #' + c.id;
                document.getElementById('couponForm').action = baseUrl + 'admin/ma-giam-gia/sua/' + c.id;
                document.getElementById('couponId').value = c.id;
                document.getElementById('couponCode').value = c.ma;
                document.getElementById('couponType').value = c.loai;
                handleTypeChange();
                document.getElementById('couponValue').value = parseFloat(c.gia_tri);
                document.getElementById('couponMinOrder').value = parseFloat(c.don_toi_thieu);
                document.getElementById('couponMaxUsage').value = parseInt(c.so_luot_toi_da);
                document.getElementById('couponStart').value = c.bat_dau_input;
                document.getElementById('couponEnd').value = c.ket_thuc_input;
                document.getElementById('modalSubmitBtn').textContent = 'Cập Nhật Voucher';

                document.getElementById('couponModalBackdrop').classList.remove('opacity-0', 'pointer-events-none');
                const modal = document.getElementById('couponModal');
                modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
                modal.classList.add('scale-100');
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi khi tải dữ liệu voucher: ' + err);
            });
    }

    function closeCouponModal() {
        document.getElementById('couponModalBackdrop').classList.add('opacity-0', 'pointer-events-none');
        const modal = document.getElementById('couponModal');
        modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
        modal.classList.remove('scale-100');
    }

    function deleteCoupon(id, code, orderCount) {
        let confirmMsg = `Bạn có chắc chắn muốn xóa mã giảm giá '${code}'?`;
        if (orderCount > 0) {
            confirmMsg += `\nLưu ý: Đã có ${orderCount} đơn hàng cũ từng áp dụng mã này (lịch sử tiền giảm trên đơn vẫn được bảo toàn).`;
        }

        if (!confirm(confirmMsg)) {
            return;
        }

        fetch(baseUrl + 'admin/ma-giam-gia/xoa/' + id, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(`Voucher ${code}`, data.message);
                const row = document.getElementById('coupon-row-' + id);
                if (row) {
                    row.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => row.remove(), 300);
                }
            } else {
                showToast('Lỗi', data.message, true);
                alert(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Lỗi', 'Không thể xóa voucher: ' + err, true);
        });
    }

    // Tự động đóng modal khi nhấn ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCouponModal();
        }
    });
</script>

