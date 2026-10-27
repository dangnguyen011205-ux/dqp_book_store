<?php
// Views/admin/reviews/index.php
// Quản lý & Duyệt Đánh Giá Khách Hàng - Athenaeum Modern v2.4
?>

<div class="px-6 py-5 max-w-[1440px] w-full mx-auto flex flex-col gap-5">
    <!-- 1. Top Breadcrumb & Executive Header Bar -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="flex flex-col gap-1">
            <nav class="flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant">
                <a class="hover:text-primary transition-colors flex items-center gap-1" href="<?= base_url('admin') ?>">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    <span>Bảng điều khiển</span>
                </a>
                <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
                <span class="text-primary font-bold">Duyệt Đánh Giá</span>
                <span class="ml-1.5 px-2 py-0.5 rounded-full bg-slate-100 text-[10px] font-bold text-slate-500">CSDL: danh_gia</span>
            </nav>
            <div class="flex items-baseline gap-2 mt-0.5">
                <h1 class="text-2xl lg:text-3xl font-extrabold text-primary tracking-tight">Quản Lý &amp; Duyệt Đánh Giá Khách Hàng</h1>
                <?php if ($stats['pending_reviews'] > 0): ?>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                        <?= $stats['pending_reviews'] ?> cần duyệt gấp
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Đã xử lý hết
                    </span>
                <?php endif; ?>
            </div>
            <p class="text-xs text-on-surface-variant">
                Kiểm duyệt bình luận, đánh giá sao của độc giả trước khi công khai trên sàn BookStore nhằm đảm bảo tính chân thực và văn minh.
            </p>
        </div>

        <!-- Right Header Actions -->
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="window.print()" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-primary text-xs font-semibold shadow-sm border border-slate-200 transition-all active:scale-95" type="button">
                <span class="material-symbols-outlined text-[18px] text-secondary">file_download</span>
                <span>Xuất Báo Cáo</span>
            </button>
            <a href="<?= base_url('admin/danh-gia') ?>" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-primary text-xs font-semibold shadow-sm border border-slate-200 transition-all active:scale-95" title="Làm mới">
                <span class="material-symbols-outlined text-[18px] text-amber-600">sync</span>
                <span>Làm Mới</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI Metric Cards (4 Bento Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- KPI 1: Đánh giá Chờ Duyệt -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Đánh giá Chờ Duyệt</span>
                <span class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-800">
                    <span class="material-symbols-outlined text-[18px]">pending_actions</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-amber-600"><?= number_format($stats['pending_reviews']) ?></span>
                <?php if ($stats['pending_reviews'] > 0): ?>
                    <span class="text-[11px] text-amber-700 font-bold">Cần xử lý sớm</span>
                <?php else: ?>
                    <span class="text-[11px] text-emerald-600 font-bold">Tồn đọng: 0</span>
                <?php endif; ?>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Ưu tiên kiểm duyệt:</span>
                <span class="font-bold text-primary"><?= $stats['pending_reviews'] > 0 ? 'Có bản ghi mới' : 'Đã sạch hàng đợi' ?></span>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: <?= min(100, max(10, $stats['pending_reviews'] * 20)) ?>%"></div>
            </div>
        </div>

        <!-- KPI 2: Đang Hiển Thị -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Đang Hiển Thị</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-800">
                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-emerald-700"><?= number_format($stats['approved_reviews']) ?></span>
                <span class="text-[11px] text-emerald-600 font-bold">Công khai</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Tỷ trọng công khai:</span>
                <span class="font-bold text-primary">
                    <?= $stats['total_reviews'] > 0 ? round(($stats['approved_reviews'] / $stats['total_reviews']) * 100, 1) : 100 ?>%
                </span>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-emerald-600 h-full rounded-full transition-all duration-500" style="width: <?= $stats['total_reviews'] > 0 ? round(($stats['approved_reviews'] / $stats['total_reviews']) * 100) : 100 ?>%"></div>
            </div>
        </div>

        <!-- KPI 3: Điểm Đánh Giá Toàn Sàn -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-secondary uppercase tracking-wider">Điểm Đánh Giá Toàn Sàn</span>
                <span class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                </span>
            </div>
            <div class="mt-2 flex items-center gap-2">
                <span class="text-3xl font-black text-primary"><?= number_format($stats['avg_rating'], 1) ?></span>
                <div class="flex flex-col">
                    <div class="flex items-center text-amber-500 text-xs">
                        <?php 
                        $fullStars = floor($stats['avg_rating']);
                        $hasHalf = ($stats['avg_rating'] - $fullStars) >= 0.5;
                        for ($i = 1; $i <= 5; $i++): 
                            if ($i <= $fullStars): ?>
                                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <?php elseif ($i == $fullStars + 1 && $hasHalf): ?>
                                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star_half</span>
                            <?php else: ?>
                                <span class="material-symbols-outlined text-[16px] text-slate-300" style="font-variation-settings: 'FILL' 0;">star</span>
                            <?php endif; 
                        endfor; ?>
                    </div>
                    <span class="text-[10px] text-slate-400 font-semibold">thang điểm 5.0</span>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Tỷ lệ hài lòng (4-5★):</span>
                <span class="font-bold text-emerald-700"><?= $stats['satisfaction_rate'] ?>%</span>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-secondary h-full rounded-full transition-all duration-500" style="width: <?= min(100, $stats['satisfaction_rate']) ?>%"></div>
            </div>
        </div>

        <!-- KPI 4: Đã Ẩn / Vi Phạm -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Đã Ẩn / Vi Phạm</span>
                <span class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center text-rose-800">
                    <span class="material-symbols-outlined text-[18px]">visibility_off</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-rose-600"><?= number_format($stats['hidden_reviews']) ?></span>
                <span class="text-[11px] text-slate-400">đánh giá</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Bị ẩn do spam / vi phạm:</span>
                <span class="font-bold text-primary"><?= number_format($stats['hidden_reviews']) ?> mục</span>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-rose-500 h-full rounded-full transition-all duration-500" style="width: <?= min(100, max(5, $stats['hidden_reviews'] * 15)) ?>%"></div>
            </div>
        </div>
    </div>

    <!-- 3. Filter Section & Tabs -->
    <div class="flex flex-col gap-3 bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <!-- Status Tabs Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                <?php
                $tabItems = [
                    'tat_ca'    => ['label' => 'Tất cả',           'count' => $counts['status']['tat_ca'],    'badgeBg' => 'bg-slate-200 text-slate-800'],
                    'cho_duyet' => ['label' => 'Chờ duyệt',        'count' => $counts['status']['cho_duyet'], 'badgeBg' => 'bg-amber-100 text-amber-800 font-bold'],
                    'hien_thi'  => ['label' => 'Đang hiển thị',    'count' => $counts['status']['hien_thi'],  'badgeBg' => 'bg-emerald-100 text-emerald-800'],
                    'an'        => ['label' => 'Đã ẩn / Báo cáo',  'count' => $counts['status']['an'],        'badgeBg' => 'bg-rose-100 text-rose-800']
                ];
                ?>
                <?php foreach ($tabItems as $tabKey => $tab): ?>
                    <?php $isActive = ($currentStatus === $tabKey); ?>
                    <a href="<?= base_url('admin/danh-gia?status=' . $tabKey . '&star=' . ($currentStar ?? '') . '&q=' . urlencode($search) . '&sort=' . $currentSort) ?>" 
                       class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap <?= $isActive ? 'bg-primary text-white shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200' ?>">
                        <?php if ($tabKey === 'cho_duyet' && $tab['count'] > 0): ?>
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        <?php endif; ?>
                        <span><?= $tab['label'] ?></span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] <?= $isActive ? 'bg-white/20 text-white' : $tab['badgeBg'] ?>">
                            <?= number_format($tab['count']) ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Quick Search Input -->
            <form method="GET" action="<?= base_url('admin/danh-gia') ?>" class="relative w-full md:w-80">
                <input type="hidden" name="status" value="<?= htmlspecialchars($currentStatus) ?>"/>
                <input type="hidden" name="star" value="<?= htmlspecialchars($currentStar ?? '') ?>"/>
                <input type="hidden" name="sort" value="<?= htmlspecialchars($currentSort) ?>"/>
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                <input name="q" value="<?= htmlspecialchars($search) ?>" class="w-full pl-9 pr-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface placeholder:text-outline text-xs focus:outline-none focus:bg-white focus:border-secondary transition-all" placeholder="Tìm theo độc giả, email, tên sách, nội dung..." type="text"/>
            </form>
        </div>

        <!-- Secondary Filters: Star Rating & Sorting -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
            <!-- Star Ratings Filter Buttons -->
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-[11px] font-bold text-on-surface-variant mr-1">Xếp hạng sao:</span>
                <a href="<?= base_url('admin/danh-gia?status=' . $currentStatus . '&q=' . urlencode($search) . '&sort=' . $currentSort) ?>" 
                   class="px-2.5 py-1 rounded-lg text-xs font-semibold transition-all <?= empty($currentStar) ? 'bg-primary text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' ?>">
                    Tất cả sao
                </a>
                <?php for ($s = 5; $s >= 1; $s--): ?>
                    <?php $isStarActive = ($currentStar === $s); ?>
                    <a href="<?= base_url('admin/danh-gia?status=' . $currentStatus . '&star=' . $s . '&q=' . urlencode($search) . '&sort=' . $currentSort) ?>" 
                       class="flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold transition-all <?= $isStarActive ? 'bg-amber-600 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' ?>">
                        <span><?= $s ?></span>
                        <span class="material-symbols-outlined text-[14px] text-amber-500" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="text-[10px] <?= $isStarActive ? 'text-white/80' : 'text-slate-400' ?>">(<?= $counts['stars'][$s] ?? 0 ?>)</span>
                    </a>
                <?php endfor; ?>
            </div>

            <!-- Sort By -->
            <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                <span class="font-bold">Sắp xếp:</span>
                <select onchange="location.href='<?= base_url('admin/danh-gia?status=' . $currentStatus . '&star=' . ($currentStar ?? '') . '&q=' . urlencode($search) . '&sort=') ?>' + this.value" class="bg-slate-50 border border-slate-200 text-on-surface text-xs px-2.5 py-1.5 rounded-xl focus:outline-none focus:bg-white focus:border-secondary cursor-pointer">
                    <option value="moi_nhat" <?= $currentSort === 'moi_nhat' ? 'selected' : '' ?>>Mới nhất trước</option>
                    <option value="cu_nhat" <?= $currentSort === 'cu_nhat' ? 'selected' : '' ?>>Cũ nhất trước</option>
                    <option value="sao_cao" <?= $currentSort === 'sao_cao' ? 'selected' : '' ?>>Số sao cao nhất</option>
                    <option value="sao_thap" <?= $currentSort === 'sao_thap' ? 'selected' : '' ?>>Số sao thấp nhất</option>
                </select>
                <?php if (!empty($search) || !empty($currentStar) || $currentStatus !== 'tat_ca' || $currentSort !== 'moi_nhat'): ?>
                    <a href="<?= base_url('admin/danh-gia') ?>" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold flex items-center gap-1 transition-colors" title="Bỏ lọc">
                        <span class="material-symbols-outlined text-[14px]">close</span>
                        <span>Bỏ lọc</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 4. Bulk Moderation Toolbar (Floating/Sticky when selected) -->
    <div id="bulkToolbar" class="hidden items-center justify-between px-4 py-2.5 rounded-xl bg-primary text-white shadow-md transition-all duration-300">
        <div class="flex items-center gap-3">
            <span class="text-xs font-semibold">Đã chọn <strong class="text-secondary font-black" id="selectedCount">0</strong> đánh giá</span>
            <span class="text-[11px] text-slate-300 hidden sm:inline">Áp dụng thao tác đồng loạt trên các mục đã đánh dấu:</span>
        </div>
        <div class="flex items-center gap-1.5">
            <button onclick="executeBulkAction('duyet')" class="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-secondary hover:bg-secondary/90 text-white font-bold text-xs transition-colors shadow-sm" type="button">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                <span>Duyệt hiển thị</span>
            </button>
            <button onclick="executeBulkAction('an')" class="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-semibold text-xs transition-colors" type="button">
                <span class="material-symbols-outlined text-[16px]">visibility_off</span>
                <span>Ẩn đánh giá</span>
            </button>
            <button onclick="executeBulkAction('xoa')" class="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-rose-700 hover:bg-rose-800 text-white font-semibold text-xs transition-colors" type="button">
                <span class="material-symbols-outlined text-[16px]">delete</span>
                <span>Xóa vĩnh viễn</span>
            </button>
            <button onclick="deselectAll()" class="p-1 rounded-lg hover:bg-white/10 text-slate-300 hover:text-white transition-colors" title="Hủy chọn" type="button">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>
    </div>

    <!-- 5. Main Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-on-surface-variant text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-3 w-10 text-center">
                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this.checked)" class="w-4 h-4 rounded text-secondary focus:ring-0 cursor-pointer accent-secondary"/>
                        </th>
                        <th class="py-3 px-4 min-w-[190px]">Người Đánh Giá</th>
                        <th class="py-3 px-4 min-w-[220px]">Tựa Sách &amp; Đơn Hàng</th>
                        <th class="py-3 px-3 w-28 text-center">Số Sao</th>
                        <th class="py-3 px-4 min-w-[320px]">Nhận Xét &amp; Nội Dung</th>
                        <th class="py-3 px-3 w-32">Thời Gian</th>
                        <th class="py-3 px-3 w-28 text-center">Trạng Thái</th>
                        <th class="py-3 px-4 text-right">Thao Tác Nhanh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-on-surface" id="reviewTableBody">
                    <?php if (empty($reviews)): ?>
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">rate_review</span>
                                <p class="font-semibold text-sm">Không tìm thấy đánh giá nào phù hợp với bộ lọc hiện tại.</p>
                                <a href="<?= base_url('admin/danh-gia') ?>" class="inline-block mt-3 text-secondary text-xs font-bold hover:underline">Quay lại tất cả đánh giá</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reviews as $rev): ?>
                            <?php 
                                $isPending = ($rev['trang_thai'] === 'cho_duyet');
                                $isApproved = ($rev['trang_thai'] === 'hien_thi');
                                $isHidden = ($rev['trang_thai'] === 'an');
                                $avatarInitial = strtoupper(mb_substr($rev['nguoi_danh_gia_ten'] ?? 'U', 0, 1, 'UTF-8'));
                            ?>
                            <tr class="hover:bg-slate-50/60 transition-colors <?= $isPending ? 'bg-amber-50/40' : '' ?>" id="review-row-<?= $rev['id'] ?>">
                                <!-- Checkbox -->
                                <td class="py-3.5 px-3 text-center">
                                    <input type="checkbox" value="<?= $rev['id'] ?>" onchange="updateSelectedCount()" class="review-checkbox w-4 h-4 rounded text-secondary focus:ring-0 cursor-pointer accent-secondary"/>
                                </td>

                                <!-- Người Đánh Giá -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-start gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                                            <?= $avatarInitial ?>
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="font-bold text-primary truncate max-w-[150px]" title="<?= htmlspecialchars($rev['nguoi_danh_gia_ten']) ?>">
                                                <?= htmlspecialchars($rev['nguoi_danh_gia_ten']) ?>
                                            </span>
                                            <span class="text-[11px] text-slate-400 truncate max-w-[150px]" title="<?= htmlspecialchars($rev['nguoi_danh_gia_email']) ?>">
                                                <?= htmlspecialchars($rev['nguoi_danh_gia_email']) ?>
                                            </span>
                                            <?php if (!empty($rev['don_hang_id'])): ?>
                                                <span class="mt-0.5 inline-flex items-center gap-0.5 text-[10px] text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded w-fit border border-emerald-200">
                                                    <span class="material-symbols-outlined text-[12px]">verified</span>
                                                    Đã mua hàng
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <!-- Tựa Sách & Đơn Hàng -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-start gap-2.5">
                                        <img class="w-9 h-13 object-cover rounded shadow-xs shrink-0 border border-slate-200" 
                                             src="<?= htmlspecialchars(get_book_cover($rev['sach_anh_bia'])) ?>" 
                                             alt="<?= htmlspecialchars($rev['sach_tieu_de']) ?>" loading="lazy"/>
                                        <div class="flex flex-col min-w-0">
                                            <a href="<?= base_url('chi-tiet/' . $rev['sach_id']) ?>" target="_blank" class="font-bold text-primary hover:text-secondary truncate max-w-[170px]" title="<?= htmlspecialchars($rev['sach_tieu_de']) ?>">
                                                <?= htmlspecialchars($rev['sach_tieu_de']) ?>
                                            </a>
                                            <span class="text-[11px] text-slate-400"><?= htmlspecialchars($rev['the_loai_ten'] ?? 'Sách') ?></span>
                                            <?php if (!empty($rev['don_hang_id'])): ?>
                                                <a href="<?= base_url('admin/don-hang?q=' . $rev['don_hang_id']) ?>" class="text-[10px] text-secondary font-mono hover:underline">
                                                    Đơn: #BK-<?= $rev['don_hang_id'] ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-[10px] text-slate-400 font-mono">Đánh giá chung</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <!-- Số Sao -->
                                <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                    <div class="flex flex-col items-center">
                                        <div class="flex items-center text-amber-500">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' <?= $i <= $rev['so_sao'] ? '1' : '0' ?>;">star</span>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="font-bold text-primary text-[11px] mt-0.5"><?= number_format($rev['so_sao'], 1) ?> / 5.0</span>
                                    </div>
                                </td>

                                <!-- Nhận Xét & Nội Dung -->
                                <td class="py-3.5 px-4 min-w-[280px]">
                                    <p class="text-slate-700 leading-relaxed font-medium">
                                        <?= nl2br(htmlspecialchars($rev['noi_dung'] ?? 'Không có lời bình luận.')) ?>
                                    </p>
                                </td>

                                <!-- Thời Gian -->
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700"><?= date('H:i', strtotime($rev['ngay_tao'])) ?></span>
                                        <span class="text-[11px] text-slate-400"><?= date('d/m/Y', strtotime($rev['ngay_tao'])) ?></span>
                                    </div>
                                </td>

                                <!-- Trạng Thái -->
                                <td class="py-3.5 px-3 text-center whitespace-nowrap" id="status-badge-<?= $rev['id'] ?>">
                                    <?php if ($isPending): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                            Chờ duyệt
                                        </span>
                                    <?php elseif ($isApproved): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold border border-emerald-200">
                                            <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                            Hiển thị
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 text-[11px] font-bold border border-rose-200">
                                            <span class="material-symbols-outlined text-[13px]">visibility_off</span>
                                            Đã ẩn
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Thao Tác Nhanh -->
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1">
                                        <?php if ($rev['trang_thai'] !== 'hien_thi'): ?>
                                            <button onclick="changeReviewStatus(<?= $rev['id'] ?>, 'hien_thi')" 
                                                    class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white flex items-center justify-center transition-colors shadow-xs" 
                                                    title="Duyệt hiển thị">
                                                <span class="material-symbols-outlined text-[18px]">check</span>
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($rev['trang_thai'] !== 'an'): ?>
                                            <button onclick="changeReviewStatus(<?= $rev['id'] ?>, 'an')" 
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-amber-600 text-slate-600 hover:text-white flex items-center justify-center transition-colors" 
                                                    title="Ẩn đánh giá này">
                                                <span class="material-symbols-outlined text-[18px]">visibility_off</span>
                                            </button>
                                        <?php endif; ?>

                                        <button onclick="deleteReviewItem(<?= $rev['id'] ?>)" 
                                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-600 text-slate-600 hover:text-white flex items-center justify-center transition-colors" 
                                                title="Xóa vĩnh viễn">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <?php if ($totalPages > 1): ?>
            <div class="px-4 py-3 bg-slate-50/60 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-on-surface-variant font-medium">
                <div>
                    <span>Đang hiển thị <strong class="text-primary font-bold"><?= ($page - 1) * $perPage + 1 ?> - <?= min($total, $page * $perPage) ?></strong> trong tổng số <strong class="text-primary font-bold"><?= number_format($total) ?></strong> đánh giá</span>
                </div>
                <div class="flex items-center gap-1">
                    <?php if ($page > 1): ?>
                        <a href="<?= base_url('admin/danh-gia?page=' . ($page - 1) . '&status=' . $currentStatus . '&star=' . ($currentStar ?? '') . '&q=' . urlencode($search) . '&sort=' . $currentSort) ?>" 
                           class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-primary flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                        </a>
                    <?php endif; ?>

                    <?php for ($p = max(1, $page - 2); $p <= min($totalPages, $page + 2); $p++): ?>
                        <a href="<?= base_url('admin/danh-gia?page=' . $p . '&status=' . $currentStatus . '&star=' . ($currentStar ?? '') . '&q=' . urlencode($search) . '&sort=' . $currentSort) ?>" 
                           class="w-8 h-8 rounded-lg <?= $p === $page ? 'bg-primary text-white font-bold shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' ?> flex items-center justify-center transition-colors">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?= base_url('admin/danh-gia?page=' . ($page + 1) . '&status=' . $currentStatus . '&star=' . ($currentStar ?? '') . '&q=' . urlencode($search) . '&sort=' . $currentSort) ?>" 
                           class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-primary flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 6. TOAST NOTIFICATION THÔNG BÁO TỨC THÌ                                    -->
<!-- ========================================================================= -->
<div class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white shadow-2xl translate-y-20 opacity-0 transition-all duration-300 pointer-events-none" id="statusToast">
    <span class="material-symbols-outlined text-secondary text-[22px]" id="toastIcon">check_circle</span>
    <div class="flex flex-col">
        <span class="text-xs font-bold" id="toastTitle">Đã cập nhật đánh giá!</span>
        <span class="text-[11px] text-slate-300" id="toastDesc">Thao tác duyệt đã được lưu vào hệ thống CSDL.</span>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 7. JAVASCRIPT XỬ LÝ DUYỆT, ẨN, BULK ACTIONS & AJAX                        -->
<!-- ========================================================================= -->
<script>
    let toastTimer = null;

    function showToast(title, desc, isError = false) {
        const toast = document.getElementById('statusToast');
        const toastTitle = document.getElementById('toastTitle');
        const toastDesc = document.getElementById('toastDesc');
        const toastIcon = document.getElementById('toastIcon');

        if (!toast) return;
        toastTitle.textContent = title;
        toastDesc.textContent = desc;

        if (isError) {
            toast.className = "fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-700 text-white shadow-2xl transition-all duration-300 pointer-events-none";
            toastIcon.textContent = "error";
        } else {
            toast.className = "fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white shadow-2xl transition-all duration-300 pointer-events-none";
            toastIcon.textContent = "check_circle";
        }

        toast.classList.remove('translate-y-20', 'opacity-0');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3500);
    }

    // Đổi trạng thái 1 đánh giá qua AJAX
    function changeReviewStatus(id, newStatus) {
        fetch('<?= base_url('admin/danh-gia/cap-nhat-trang-thai') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                'review_id': id,
                'trang_thai': newStatus
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(`Đánh giá #${id}`, data.message);
                updateReviewRowBadge(id, newStatus);
            } else {
                showToast(`Lỗi #${id}`, data.message, true);
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Lỗi kết nối', 'Không thể gửi yêu cầu cập nhật: ' + err, true);
        });
    }

    // Xóa đánh giá
    function deleteReviewItem(id) {
        if (!confirm(`Bạn có chắc chắn muốn xóa vĩnh viễn đánh giá #${id}? Thao tác này không thể hoàn tác.`)) {
            return;
        }

        fetch(`<?= base_url('admin/danh-gia/xoa/') ?>${id}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(`Đánh giá #${id}`, data.message);
                const row = document.getElementById(`review-row-${id}`);
                if (row) {
                    row.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => row.remove(), 300);
                }
            } else {
                showToast(`Lỗi #${id}`, data.message, true);
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Lỗi', 'Không thể xóa đánh giá: ' + err, true);
        });
    }

    // Cập nhật badge trong DOM tại chỗ
    function updateReviewRowBadge(id, status) {
        const container = document.getElementById(`status-badge-${id}`);
        const row = document.getElementById(`review-row-${id}`);
        if (!container) return;

        if (status === 'hien_thi') {
            container.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold border border-emerald-200"><span class="material-symbols-outlined text-[13px]">check_circle</span>Hiển thị</span>`;
            if (row) row.classList.remove('bg-amber-50/40');
        } else if (status === 'an') {
            container.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 text-[11px] font-bold border border-rose-200"><span class="material-symbols-outlined text-[13px]">visibility_off</span>Đã ẩn</span>`;
            if (row) row.classList.remove('bg-amber-50/40');
        } else {
            container.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>Chờ duyệt</span>`;
            if (row) row.classList.add('bg-amber-50/40');
        }
    }

    // Bulk selection logic
    function toggleSelectAll(checked) {
        const checkboxes = document.querySelectorAll('.review-checkbox');
        checkboxes.forEach(cb => cb.checked = checked);
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.review-checkbox:checked');
        const count = checked.length;
        const toolbar = document.getElementById('bulkToolbar');
        const countEl = document.getElementById('selectedCount');
        const selectAllCb = document.getElementById('selectAllCheckbox');

        if (countEl) countEl.textContent = count;

        if (count > 0) {
            toolbar.classList.remove('hidden');
            toolbar.classList.add('flex');
        } else {
            toolbar.classList.add('hidden');
            toolbar.classList.remove('flex');
            if (selectAllCb) selectAllCb.checked = false;
        }
    }

    function deselectAll() {
        toggleSelectAll(false);
    }

    function executeBulkAction(action) {
        const checked = document.querySelectorAll('.review-checkbox:checked');
        const ids = Array.from(checked).map(cb => cb.value);

        if (ids.length === 0) {
            alert('Vui lòng chọn ít nhất một đánh giá.');
            return;
        }

        let confirmMsg = `Bạn có chắc chắn muốn thực hiện thao tác trên ${ids.length} đánh giá đã chọn?`;
        if (action === 'xoa') {
            confirmMsg = `CẢNH BÁO: Bạn có chắc chắn muốn xóa vĩnh viễn ${ids.length} đánh giá đã chọn?`;
        }
        if (!confirm(confirmMsg)) return;

        fetch('<?= base_url('admin/danh-gia/bulk') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                'ids': ids.join(','),
                'action': action
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Thao tác hàng loạt', data.message);
                deselectAll();
                setTimeout(() => window.location.reload(), 1200);
            } else {
                showToast('Lỗi', data.message, true);
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Lỗi', 'Không thể thực hiện thao tác hàng loạt: ' + err, true);
        });
    }
</script>
