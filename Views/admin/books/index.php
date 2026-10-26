<?php
// Views/admin/books/index.php
// Quản lý sách & Quản lý tồn kho - Athenaeum Modern v2.4
?>

<div class="flex flex-col w-full">
    <!-- Top Operational Banner & Breadcrumbs -->
    <section class="px-6 py-5 flex flex-col gap-4">
        <!-- Breadcrumb & Quick Actions Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <nav class="flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant">
                <a class="hover:text-primary transition-colors flex items-center gap-1" href="<?= base_url('admin') ?>">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    <span>Bảng điều khiển</span>
                </a>
                <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
                <span class="text-primary font-bold">Quản lý Sách &amp; Kho Hàng</span>
            </nav>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-primary text-xs font-semibold shadow-sm border border-slate-200 transition-all active:scale-95" type="button">
                    <span class="material-symbols-outlined text-[18px] text-secondary">file_download</span>
                    <span>In báo cáo</span>
                </button>
                <button onclick="switchMainTab('tab-slips')" class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-primary text-xs font-semibold shadow-sm border border-slate-200 transition-all active:scale-95" type="button">
                    <span class="material-symbols-outlined text-[18px] text-primary">history</span>
                    <span>Lịch sử phiếu kho</span>
                </button>
                <a href="<?= base_url('admin/sach') ?>" class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-primary text-xs font-semibold shadow-sm border border-slate-200 transition-all active:scale-95" title="Làm mới dữ liệu">
                    <span class="material-symbols-outlined text-[18px] text-amber-600">sync</span>
                    <span>Làm mới</span>
                </a>
            </div>
        </div>

        <!-- Header Titles & Warehouse Badge -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-1">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[11px] font-bold">Kho Hub Miền Nam</span>
                    <span class="text-on-surface-variant text-[11px] flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Đồng bộ tự động thời gian thực
                    </span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-extrabold text-primary tracking-tight">
                    Quản Lý Sách &amp; Tồn Kho <span class="text-on-surface-variant font-normal text-lg">(Kho Trung Tâm - DQP BookStore)</span>
                </h1>
            </div>
            <div class="flex items-center gap-2 self-start md:self-auto">
                <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white text-xs font-bold shadow-md transition-all transform active:scale-95" onclick="openNewSlipModal()">
                    <span class="material-symbols-outlined text-[18px]">post_add</span>
                    <span>+ Tạo Phiếu Kho</span>
                </button>
                <button class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-md transition-all transform active:scale-95" onclick="openAddBookModal()">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>+ Thêm Sách Mới</span>
                </button>
            </div>
        </div>

        <!-- 4 KPI Metrics Bento Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- KPI 1: Tổng số đầu sách -->
            <div class="p-4 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Tổng số đầu sách</span>
                        <div class="text-3xl font-black text-primary mt-1"><?= number_format($stats['total_titles']) ?></div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-on-primary-fixed">
                        <span class="material-symbols-outlined text-[22px]">library_books</span>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-t border-slate-100 flex items-center gap-2 text-xs">
                    <span class="text-secondary font-bold flex items-center gap-0.5">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span> <?= number_format($stats['active_titles']) ?> Đang bán
                    </span>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-400 font-medium"><?= number_format($stats['inactive_titles']) ?> Ngừng bán</span>
                </div>
            </div>

            <!-- KPI 2: Tổng lượng tồn kho -->
            <div class="p-4 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Tổng lượng tồn kho</span>
                        <div class="text-3xl font-black text-primary mt-1"><?= number_format($stats['total_stock']) ?></div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined text-[22px]">inventory_2</span>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-t border-slate-100 flex items-center gap-1.5 text-xs text-secondary font-semibold">
                    <span class="material-symbols-outlined text-[14px]">warehouse</span>
                    <span><?= number_format($stats['total_stock']) ?> bản sách sẵn sàng phục vụ</span>
                </div>
            </div>

            <!-- KPI 3: Cảnh báo sắp hết hàng -->
            <div class="p-4 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Cảnh báo sắp hết hàng</span>
                        <div class="text-3xl font-black text-rose-600 mt-1"><?= number_format($stats['low_stock_count']) ?></div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center text-rose-700">
                        <span class="material-symbols-outlined text-[22px]">warning</span>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-t border-slate-100 flex items-center gap-1 text-xs text-rose-600 font-semibold">
                    <span class="material-symbols-outlined text-[14px]">error</span>
                    <span>Tồn kho &le; 10 cuốn (Cần lập phiếu nhập)</span>
                </div>
            </div>

            <!-- KPI 4: Giá trị tồn kho ước tính -->
            <div class="p-4 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Giá trị tồn kho ước tính</span>
                        <div class="text-2xl font-black text-primary mt-1"><?= format_currency($stats['estimated_stock_value']) ?></div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-800">
                        <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                    <span>Tính theo giá niêm yết hiện hành</span>
                    <span class="text-secondary font-bold">100% Khả dụng</span>
                </div>
            </div>
        </div>

        <!-- Main Segment Navigation Tabs -->
        <div class="flex items-center gap-3 mt-2">
            <button class="flex items-center gap-2 px-5 py-3 rounded-xl <?= $activeTab !== 'slips' ? 'bg-primary text-white shadow-sm' : 'bg-white hover:bg-slate-100 text-on-surface-variant border border-slate-200' ?> text-xs font-bold transition-all" id="tab-books-btn" onclick="switchMainTab('tab-books')">
                <span class="material-symbols-outlined text-[18px]">menu_book</span>
                <span>Danh Sách Sách &amp; Tồn Kho</span>
                <span class="ml-1 px-2 py-0.5 rounded-full <?= $activeTab !== 'slips' ? 'bg-primary-container text-white' : 'bg-slate-200 text-slate-700' ?> text-[11px] font-bold"><?= number_format($total) ?></span>
            </button>
            <button class="flex items-center gap-2 px-5 py-3 rounded-xl <?= $activeTab === 'slips' ? 'bg-primary text-white shadow-sm' : 'bg-white hover:bg-slate-100 text-on-surface-variant border border-slate-200' ?> text-xs font-bold transition-all" id="tab-slips-btn" onclick="switchMainTab('tab-slips')">
                <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
                <span>Quản Lý Phiếu Kho &amp; Luân Chuyển</span>
                <span class="ml-1 px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container text-[11px] font-bold"><?= $todaySlipsCount ?> phát sinh hôm nay</span>
            </button>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- TAB 1: DANH SÁCH SÁCH & TỒN KHO                                          -->
    <!-- ========================================================================= -->
    <div class="px-6 pb-12 flex flex-col gap-4 <?= $activeTab === 'slips' ? 'hidden' : '' ?>" id="tab-books-content">
        <!-- Filter Control Panel -->
        <form method="GET" action="<?= base_url('admin/sach') ?>" class="p-4 rounded-2xl bg-white shadow-sm border border-slate-100 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
            <input type="hidden" name="tab" value="books" />
            <!-- Search Input -->
            <div class="relative flex-1 max-w-lg">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                <input name="q" value="<?= htmlspecialchars($search) ?>" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface placeholder:text-outline text-xs focus:outline-none focus:bg-white focus:border-secondary transition-all" placeholder="Tìm theo tựa sách, mã #BK, tác giả, NXB..." type="text"/>
            </div>

            <!-- Quick Select Dropdowns -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Dropdown Thể loại -->
                <div class="relative">
                    <select name="category" onchange="this.form.submit()" class="appearance-none pl-3.5 pr-8 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs font-semibold focus:outline-none focus:border-secondary cursor-pointer">
                        <option value="0">Tất cả Thể Loại</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $selectedCat === (int)$cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['ten']) ?> (<?= $cat['tong_sach'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-[16px]">arrow_drop_down</span>
                </div>

                <!-- Dropdown Trạng thái -->
                <div class="relative">
                    <select name="status" onchange="this.form.submit()" class="appearance-none pl-3.5 pr-8 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs font-semibold focus:outline-none focus:border-secondary cursor-pointer">
                        <option value="all" <?= $selectedStatus === 'all' ? 'selected' : '' ?>>Trạng Thái: Tất cả</option>
                        <option value="dang_ban" <?= $selectedStatus === 'dang_ban' ? 'selected' : '' ?>>Đang Bán</option>
                        <option value="sap_het" <?= $selectedStatus === 'sap_het' ? 'selected' : '' ?>>Sắp Hết Hàng (&le; 10)</option>
                        <option value="ngung_ban" <?= $selectedStatus === 'ngung_ban' ? 'selected' : '' ?>>Ngừng Bán</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-[16px]">arrow_drop_down</span>
                </div>

                <!-- Nút Lọc & Nút Reset -->
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold flex items-center gap-1 transition-all">
                    <span class="material-symbols-outlined text-[16px]">filter_list</span>
                    <span>Lọc</span>
                </button>
                <?php if (!empty($search) || $selectedCat > 0 || $selectedStatus !== 'all'): ?>
                    <a href="<?= base_url('admin/sach') ?>" class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1 transition-all" title="Xóa bộ lọc">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                        <span>Bỏ lọc</span>
                    </a>
                <?php endif; ?>

                <button type="button" class="px-4 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold flex items-center gap-1.5 shadow-sm transition-all active:scale-95" onclick="openAddBookModal()">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    <span>Thêm Sách</span>
                </button>
            </div>
        </form>

        <!-- Books Data Table Container -->
        <div class="rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-on-surface-variant text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="py-3.5 px-4">Mã Sách</th>
                            <th class="py-3.5 px-2 text-center">Bìa</th>
                            <th class="py-3.5 px-4">Tựa Sách &amp; Quy Cách</th>
                            <th class="py-3.5 px-4">Thể Loại</th>
                            <th class="py-3.5 px-4">Tác Giả</th>
                            <th class="py-3.5 px-4">Giá Bán</th>
                            <th class="py-3.5 px-4">Tồn Kho</th>
                            <th class="py-3.5 px-4">Trạng Thái</th>
                            <th class="py-3.5 px-4 text-right">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-on-surface">
                        <?php if (empty($books)): ?>
                            <tr>
                                <td colspan="9" class="py-12 text-center text-slate-400">
                                    <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">menu_book</span>
                                    <p class="font-semibold text-sm">Không tìm thấy tựa sách nào phù hợp với bộ lọc hiện tại.</p>
                                    <a href="<?= base_url('admin/sach') ?>" class="inline-block mt-3 text-secondary text-xs font-bold hover:underline">Quay lại danh sách đầy đủ</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($books as $book): ?>
                                <?php 
                                    $isLowStock = (int)$book['so_luong_ton'] <= 10;
                                    $isOut = (int)$book['so_luong_ton'] <= 0;
                                ?>
                                <tr class="hover:bg-slate-50/60 transition-colors <?= $isLowStock && $book['dang_ban'] ? 'bg-rose-50/30' : '' ?>">
                                    <!-- ID / Mã -->
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="font-bold text-primary block">#BK-<?= $book['id'] ?></span>
                                        <span class="text-slate-400 text-[11px]">ID CSDL: <?= $book['id'] ?></span>
                                    </td>

                                    <!-- Bìa -->
                                    <td class="py-3 px-2 text-center">
                                        <div class="w-10 h-14 rounded-md overflow-hidden bg-slate-100 shadow-sm mx-auto flex items-center justify-center border border-slate-200">
                                            <img class="w-full h-full object-cover" 
                                                 src="<?= htmlspecialchars(get_book_cover($book['anh_bia'])) ?>" 
                                                 alt="<?= htmlspecialchars($book['tieu_de']) ?>"
                                                 loading="lazy" />
                                        </div>
                                    </td>

                                    <!-- Tựa Sách & Quy cách -->
                                    <td class="py-3 px-4 max-w-xs">
                                        <div class="font-bold text-primary hover:text-secondary cursor-pointer line-clamp-1" title="<?= htmlspecialchars($book['tieu_de']) ?>">
                                            <?= htmlspecialchars($book['tieu_de']) ?>
                                        </div>
                                        <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-400">
                                            <?php if (!empty($book['so_trang'])): ?>
                                                <span><?= $book['so_trang'] ?> trang</span>
                                            <?php endif; ?>
                                            <?php if (!empty($book['nxb_ten'])): ?>
                                                <span>• <?= htmlspecialchars($book['nxb_ten']) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($book['nam_xuat_ban'])): ?>
                                                <span>• <?= $book['nam_xuat_ban'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- Thể Loại -->
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-bold">
                                            <?= htmlspecialchars($book['the_loai_ten'] ?? 'Chưa phân loại') ?>
                                        </span>
                                    </td>

                                    <!-- Tác Giả -->
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-6 h-6 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center text-[10px] font-bold">
                                                <?= mb_strtoupper(mb_substr($book['tac_gia_ten'] ?? 'TG', 0, 1)) ?>
                                            </div>
                                            <span class="font-medium text-slate-700 max-w-[120px] truncate" title="<?= htmlspecialchars($book['tac_gia_ten'] ?? 'Đang cập nhật') ?>">
                                                <?= htmlspecialchars($book['tac_gia_ten'] ?? 'Đang cập nhật') ?>
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Giá Bán -->
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="font-black text-primary"><?= format_currency($book['gia']) ?></div>
                                    </td>

                                    <!-- Tồn Kho -->
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <?php if ($isOut): ?>
                                                <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                                                <span class="font-black text-rose-600">Hết hàng</span>
                                            <?php elseif ($isLowStock): ?>
                                                <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                                                <span class="font-black text-rose-600"><?= number_format($book['so_luong_ton']) ?></span>
                                                <span class="text-rose-500 text-[11px]">cuốn</span>
                                            <?php else: ?>
                                                <span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
                                                <span class="font-bold text-secondary"><?= number_format($book['so_luong_ton']) ?></span>
                                                <span class="text-slate-400 text-[11px]">cuốn</span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="text-[10px] <?= $isLowStock ? 'text-rose-600 font-bold' : 'text-slate-400' ?> block">
                                            <?= $isLowStock ? 'Cần nhập hàng' : 'Tồn an toàn' ?>
                                        </span>
                                    </td>

                                    <!-- Trạng Thái -->
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <?php if ($book['dang_ban']): ?>
                                            <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold">
                                                Đang bán
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 rounded-full bg-slate-200 text-slate-700 text-[11px] font-bold">
                                                Ngừng bán
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Thao Tác -->
                                    <td class="py-3 px-4 whitespace-nowrap text-right">
                                        <div class="inline-flex items-center gap-1">
                                            <!-- Nhập kho nhanh -->
                                            <button class="p-1.5 rounded-lg text-secondary hover:bg-secondary-fixed/50 transition-colors" 
                                                    onclick="openQuickSlipModal(<?= $book['id'] ?>, '<?= htmlspecialchars(addslashes($book['tieu_de'])) ?>', <?= (int)$book['so_luong_ton'] ?>)" 
                                                    title="Lập phiếu kho nhanh">
                                                <span class="material-symbols-outlined text-[18px]">add_box</span>
                                            </button>

                                            <!-- Sửa thông tin -->
                                            <button class="p-1.5 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors" 
                                                    onclick="openEditBookModal(<?= $book['id'] ?>)" 
                                                    title="Chỉnh sửa thông tin">
                                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                            </button>

                                            <!-- Toggle trạng thái -->
                                            <form method="POST" action="<?= base_url('admin/sach/toggle-status/' . $book['id']) ?>" class="inline" onsubmit="return confirm('Bạn có chắc muốn đổi trạng thái kinh doanh của tựa sách này?')">
                                                <button type="submit" class="p-1.5 rounded-lg <?= $book['dang_ban'] ? 'text-amber-600 hover:bg-amber-100' : 'text-emerald-600 hover:bg-emerald-100' ?> transition-colors" title="<?= $book['dang_ban'] ? 'Tạm ngừng bán' : 'Kích hoạt bán lại' ?>">
                                                    <span class="material-symbols-outlined text-[18px]"><?= $book['dang_ban'] ? 'pause_circle' : 'play_circle' ?></span>
                                                </button>
                                            </form>

                                            <!-- Xóa sách -->
                                            <form method="POST" action="<?= base_url('admin/sach/xoa/' . $book['id']) ?>" class="inline" onsubmit="return confirm('Xác nhận xóa tựa sách này? Nếu sách đã phát sinh đơn hàng, hệ thống sẽ tự động chuyển sang trạng thái Ngừng bán.')">
                                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Xóa sách">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Toolbar -->
            <?php if ($totalPages > 1): ?>
                <div class="px-4 py-3.5 bg-slate-50/60 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-on-surface-variant font-medium">
                    <div class="flex items-center gap-1">
                        <span>Hiển thị</span>
                        <span class="font-bold text-primary"><?= ($page - 1) * $perPage + 1 ?> - <?= min($total, $page * $perPage) ?></span>
                        <span>trong tổng số</span>
                        <span class="font-bold text-primary"><?= number_format($total) ?></span>
                        <span>đầu sách</span>
                    </div>

                    <div class="flex items-center gap-1">
                        <!-- Nút trang trước -->
                        <?php if ($page > 1): ?>
                            <a href="<?= base_url('admin/sach?q=' . urlencode($search) . '&category=' . $selectedCat . '&status=' . $selectedStatus . '&page=' . ($page - 1)) ?>" 
                               class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-primary flex items-center justify-center transition-colors">
                                <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                            </a>
                        <?php endif; ?>

                        <!-- Các trang -->
                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <a href="<?= base_url('admin/sach?q=' . urlencode($search) . '&category=' . $selectedCat . '&status=' . $selectedStatus . '&page=' . $i) ?>" 
                               class="w-8 h-8 rounded-lg <?= $i === $page ? 'bg-primary text-white font-bold shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' ?> flex items-center justify-center transition-colors">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <!-- Nút trang sau -->
                        <?php if ($page < $totalPages): ?>
                            <a href="<?= base_url('admin/sach?q=' . urlencode($search) . '&category=' . $selectedCat . '&status=' . $selectedStatus . '&page=' . ($page + 1)) ?>" 
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
    <!-- TAB 2: QUẢN LÝ PHIẾU KHO & LUÂN CHUYỂN                                   -->
    <!-- ========================================================================= -->
    <div class="px-6 pb-12 flex flex-col gap-4 <?= $activeTab !== 'slips' ? 'hidden' : '' ?>" id="tab-slips-content">
        <!-- Tab 2 Control Bar -->
        <div class="p-4 rounded-2xl bg-white shadow-sm border border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm font-bold text-primary">Lịch Sử Luân Chuyển Hàng Hóa &amp; Phiếu Kho</span>
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-xs font-semibold text-slate-700">Hôm nay: <?= $todaySlipsCount ?> lượt phát sinh</span>
            </div>
            <div class="flex items-center gap-2">
                <button class="px-4 py-2.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white text-xs font-bold flex items-center gap-1.5 shadow-sm transition-all active:scale-95" onclick="openNewSlipModal()">
                    <span class="material-symbols-outlined text-[18px]">post_add</span>
                    <span>+ Tạo Phiếu Kho Mới</span>
                </button>
            </div>
        </div>

        <!-- Slips Data Table -->
        <div class="rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-on-surface-variant text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="py-3.5 px-4">Mã Phiếu</th>
                            <th class="py-3.5 px-4">Tên Sách &amp; Mã</th>
                            <th class="py-3.5 px-4">Loại Phiếu</th>
                            <th class="py-3.5 px-4">Số Lượng</th>
                            <th class="py-3.5 px-4">Người Thực Hiện</th>
                            <th class="py-3.5 px-4">Thời Gian</th>
                            <th class="py-3.5 px-4">Lý Do / Ghi Chú</th>
                            <th class="py-3.5 px-4 text-right">In Phiếu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-on-surface">
                        <?php if (empty($slips)): ?>
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">swap_horiz</span>
                                    <p class="font-semibold text-sm">Chưa có bản ghi phiếu kho nào trong hệ thống.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($slips as $slip): ?>
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <!-- Mã Phiếu -->
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="font-bold text-primary block">#PK-<?= str_pad($slip['id'], 4, '0', STR_PAD_LEFT) ?></span>
                                    </td>

                                    <!-- Tên Sách & Mã -->
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-primary hover:text-secondary cursor-pointer line-clamp-1">
                                            <?= htmlspecialchars($slip['tieu_de'] ?? 'Sách đã xóa') ?>
                                        </div>
                                        <div class="text-slate-400 text-[11px]">#BK-<?= $slip['sach_id'] ?></div>
                                    </td>

                                    <!-- Loại Phiếu -->
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <?php if ($slip['loai'] === 'nhap'): ?>
                                            <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[13px]">arrow_downward</span> Nhập Kho
                                            </span>
                                        <?php elseif ($slip['loai'] === 'xuat'): ?>
                                            <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 text-[11px] font-bold inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[13px]">arrow_upward</span> Xuất Kho
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 rounded-full bg-indigo-100 text-indigo-800 text-[11px] font-bold inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[13px]">tune</span> Điều Chỉnh
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Số Lượng -->
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <?php if ($slip['loai'] === 'nhap'): ?>
                                            <span class="font-bold text-secondary text-sm">+<?= number_format($slip['so_luong']) ?> cuốn</span>
                                        <?php elseif ($slip['loai'] === 'xuat'): ?>
                                            <span class="font-bold text-rose-600 text-sm">-<?= number_format($slip['so_luong']) ?> cuốn</span>
                                        <?php else: ?>
                                            <span class="font-bold text-indigo-700 text-sm">&plusmn;<?= number_format($slip['so_luong']) ?> cuốn</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Người Thực Hiện -->
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-6 h-6 rounded-full bg-slate-800 text-white flex items-center justify-center text-[10px] font-bold">
                                                <?= mb_strtoupper(mb_substr($slip['ho_ten'] ?? 'QT', 0, 1)) ?>
                                            </div>
                                            <span class="font-medium text-slate-700"><?= htmlspecialchars($slip['ho_ten'] ?? 'Quản Trị Viên') ?></span>
                                        </div>
                                    </td>

                                    <!-- Thời Gian -->
                                    <td class="py-3 px-4 whitespace-nowrap text-slate-500 text-[11px]">
                                        <?= date('d/m/Y H:i', strtotime($slip['ngay_tao'])) ?>
                                    </td>

                                    <!-- Lý Do / Ghi Chú -->
                                    <td class="py-3 px-4 text-slate-600 max-w-xs truncate" title="<?= htmlspecialchars($slip['ghi_chu'] ?? '') ?>">
                                        <?= htmlspecialchars($slip['ghi_chu'] ?? '---') ?>
                                    </td>

                                    <!-- Thao Tác -->
                                    <td class="py-3 px-4 whitespace-nowrap text-right">
                                        <button onclick="window.print()" class="p-1.5 rounded-lg text-slate-500 hover:text-primary hover:bg-slate-100 transition-colors" title="In phiếu chứng từ">
                                            <span class="material-symbols-outlined text-[18px]">print</span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 1: THÊM SÁCH MỚI                                                    -->
<!-- ========================================================================= -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary/50 backdrop-blur-sm hidden" id="modal-add-book">
    <div class="w-full max-w-4xl max-h-[92vh] flex flex-col rounded-2xl bg-white shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">auto_stories</span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-primary">Thêm Sách Mới Vào Hệ Thống CSDL</h2>
                    <span class="text-xs text-on-surface-variant">Nhập thông tin xuất bản và thiết lập hạn mức kho khởi tạo</span>
                </div>
            </div>
            <button class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" onclick="closeAddBookModal()" type="button">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <!-- Modal Body (Form 2 Cột Chuyên Nghiệp) -->
        <form action="<?= base_url('admin/sach/them') ?>" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto flex-1 flex flex-col gap-4">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Cột Trái: Drag & Drop Upload Bìa Sách (4/12) -->
                <div class="lg:col-span-4 flex flex-col gap-3">
                    <label class="text-xs font-bold text-primary">Ảnh Bìa Sách (`anh_bia`)</label>
                    <div class="w-full aspect-[2/3] rounded-xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center p-4 text-center relative group cursor-pointer hover:bg-slate-100 transition-all overflow-hidden" onclick="document.getElementById('add-book-cover-input').click()">
                        <img id="add-book-cover-preview" class="w-full h-full object-cover hidden" alt="Bìa xem trước" />
                        <div id="add-book-cover-placeholder" class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full bg-white text-secondary flex items-center justify-center mb-2 shadow-sm group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[26px]">upload_file</span>
                            </div>
                            <span class="text-xs font-bold text-primary">Chọn ảnh từ máy tính</span>
                            <span class="text-slate-400 text-[11px] mt-1">JPG, PNG, WEBP (&le; 5MB)</span>
                        </div>
                    </div>
                    <input type="file" id="add-book-cover-input" name="anh_bia" accept="image/*" class="hidden" onchange="previewAddBookCover(this)" />

                    <!-- Nhập URL ảnh nếu có sẵn -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-semibold text-slate-500">Hoặc dán URL ảnh trực tiếp:</label>
                        <input name="anh_bia_url" type="url" placeholder="https://..." class="w-full px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-xs text-on-surface focus:outline-none focus:border-secondary" />
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 text-slate-600 text-[11px] flex items-start gap-2 border border-slate-100">
                        <span class="material-symbols-outlined text-[16px] text-secondary">info</span>
                        <span>Ảnh bìa tỷ lệ chuẩn 2:3 giúp tối ưu hiển thị trên các khối sản phẩm của cửa hàng.</span>
                    </div>
                </div>

                <!-- Cột Phải: Các Trường Nhập Liệu Nghiệp Vụ (8/12) -->
                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tiêu đề sách (Full width) -->
                    <div class="sm:col-span-2 flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Tiêu đề sách (`tieu_de`) <span class="text-rose-500">*</span></label>
                        <input name="tieu_de" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary" placeholder="Nhập tựa đề sách đầy đủ..." required type="text"/>
                    </div>

                    <!-- Thể loại -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Thể loại (`the_loai_id`) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="the_loai_id" class="w-full appearance-none px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary cursor-pointer" required>
                                <option value="">-- Chọn Thể Loại --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['ten']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-[16px]">arrow_drop_down</span>
                        </div>
                    </div>

                    <!-- Nhà xuất bản -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Nhà xuất bản (`nxb_id`)</label>
                        <div class="relative">
                            <select name="nxb_id" class="w-full appearance-none px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary cursor-pointer">
                                <option value="">-- Chọn Nhà Xuất Bản --</option>
                                <?php foreach ($publishers as $pub): ?>
                                    <option value="<?= $pub['id'] ?>"><?= htmlspecialchars($pub['ten']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-[16px]">arrow_drop_down</span>
                        </div>
                    </div>

                    <!-- Tác giả (Gán nhiều tác giả) -->
                    <div class="sm:col-span-2 flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Tác giả biên soạn (Giữ Ctrl / Command để chọn nhiều)</label>
                        <select name="tac_gia_ids[]" multiple class="w-full p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary h-24">
                            <?php foreach ($authors as $author): ?>
                                <option value="<?= $author['id'] ?>"><?= htmlspecialchars($author['ten']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Giá bán niêm yết -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Giá bán niêm yết (₫) <span class="text-rose-500">*</span></label>
                        <input name="gia" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary" placeholder="Ví dụ: 150000" min="0" step="1000" required type="number"/>
                    </div>

                    <!-- Số lượng tồn khởi tạo -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Số lượng tồn ban đầu (`so_luong_ton`)</label>
                        <input name="so_luong_ton" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary" placeholder="Ví dụ: 50" min="0" value="0" type="number"/>
                    </div>

                    <!-- Số trang & Năm XB -->
                    <div class="flex gap-2">
                        <div class="w-1/2 flex flex-col gap-1">
                            <label class="text-xs font-bold text-primary">Số trang</label>
                            <input name="so_trang" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary" placeholder="320" type="number"/>
                        </div>
                        <div class="w-1/2 flex flex-col gap-1">
                            <label class="text-xs font-bold text-primary">Năm XB</label>
                            <input name="nam_xuat_ban" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary" placeholder="2024" type="number"/>
                        </div>
                    </div>

                    <!-- Trạng thái kinh doanh -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Trạng thái phát hành</label>
                        <div class="flex items-center gap-4 py-2.5">
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-700">
                                <input name="dang_ban" value="1" checked type="radio" class="text-secondary focus:ring-secondary"/>
                                <span>Đăng bán ngay</span>
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-700">
                                <input name="dang_ban" value="0" type="radio" class="text-secondary focus:ring-secondary"/>
                                <span>Lưu nháp / Tạm ẩn</span>
                            </label>
                        </div>
                    </div>

                    <!-- Mô tả tóm tắt nội dung -->
                    <div class="sm:col-span-2 flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Mô tả tóm tắt nội dung sách (`mo_ta`)</label>
                        <textarea name="mo_ta" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary resize-none" placeholder="Giới thiệu khái quát thông điệp cuốn sách, mục lục trọng tâm hoặc giải thưởng đạt được..." rows="3"></textarea>
                    </div>
                </div>
            </div>

            <!-- Modal Actions Footer -->
            <div class="pt-4 bg-white flex items-center justify-end gap-2 border-t border-slate-100">
                <button class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors" onclick="closeAddBookModal()" type="button">
                    Hủy Bỏ
                </button>
                <button class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-md transition-all active:scale-95" type="submit">
                    Lưu &amp; Thêm Vào Kho
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 2: CHỈNH SỬA SÁCH                                                   -->
<!-- ========================================================================= -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary/50 backdrop-blur-sm hidden" id="modal-edit-book">
    <div class="w-full max-w-4xl max-h-[92vh] flex flex-col rounded-2xl bg-white shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-600 text-white flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-primary">Chỉnh Sửa Thông Tin Tựa Sách</h2>
                    <span class="text-xs text-on-surface-variant" id="edit-modal-subtitle">Cập nhật thông tin ấn phẩm và bảng giá</span>
                </div>
            </div>
            <button class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" onclick="closeEditBookModal()" type="button">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <!-- Form Edit -->
        <form id="edit-book-form" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto flex-1 flex flex-col gap-4">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Cột Trái: Bìa sách hiện tại & Upload mới -->
                <div class="lg:col-span-4 flex flex-col gap-3">
                    <label class="text-xs font-bold text-primary">Ảnh Bìa Sách Hiện Tại</label>
                    <div class="w-full aspect-[2/3] rounded-xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center p-2 text-center relative group cursor-pointer hover:bg-slate-100 transition-all overflow-hidden" onclick="document.getElementById('edit-book-cover-input').click()">
                        <img id="edit-book-cover-preview" class="w-full h-full object-cover rounded-lg" alt="Bìa sách" />
                    </div>
                    <input type="file" id="edit-book-cover-input" name="anh_bia" accept="image/*" class="hidden" onchange="previewEditBookCover(this)" />

                    <!-- Nhập URL ảnh nếu muốn đổi qua URL -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-semibold text-slate-500">Hoặc đổi bằng URL mới:</label>
                        <input id="edit-book-cover-url" name="anh_bia_url" type="url" placeholder="https://..." class="w-full px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-xs text-on-surface focus:outline-none focus:border-secondary" />
                    </div>
                </div>

                <!-- Cột Phải: Các trường thông tin -->
                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tiêu đề -->
                    <div class="sm:col-span-2 flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Tiêu đề sách <span class="text-rose-500">*</span></label>
                        <input id="edit-tieu-de" name="tieu_de" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary" required type="text"/>
                    </div>

                    <!-- Thể loại -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Thể loại <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select id="edit-the-loai-id" name="the_loai_id" class="w-full appearance-none px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary cursor-pointer" required>
                                <option value="">-- Chọn Thể Loại --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['ten']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-[16px]">arrow_drop_down</span>
                        </div>
                    </div>

                    <!-- NXB -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Nhà xuất bản</label>
                        <div class="relative">
                            <select id="edit-nxb-id" name="nxb_id" class="w-full appearance-none px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary cursor-pointer">
                                <option value="">-- Chọn Nhà Xuất Bản --</option>
                                <?php foreach ($publishers as $pub): ?>
                                    <option value="<?= $pub['id'] ?>"><?= htmlspecialchars($pub['ten']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-[16px]">arrow_drop_down</span>
                        </div>
                    </div>

                    <!-- Tác giả -->
                    <div class="sm:col-span-2 flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Tác giả biên soạn (Giữ Ctrl để chọn nhiều)</label>
                        <select id="edit-tac-gia-ids" name="tac_gia_ids[]" multiple class="w-full p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary h-20">
                            <?php foreach ($authors as $author): ?>
                                <option value="<?= $author['id'] ?>"><?= htmlspecialchars($author['ten']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Giá bán -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Giá bán niêm yết (₫) <span class="text-rose-500">*</span></label>
                        <input id="edit-gia" name="gia" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary" min="0" step="1000" required type="number"/>
                    </div>

                    <!-- Trạng thái kinh doanh -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Trạng thái phát hành</label>
                        <div class="flex items-center gap-4 py-2.5">
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-700">
                                <input id="edit-status-selling" name="dang_ban" value="1" type="radio" class="text-secondary focus:ring-secondary"/>
                                <span>Đang bán</span>
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-700">
                                <input id="edit-status-stopped" name="dang_ban" value="0" type="radio" class="text-secondary focus:ring-secondary"/>
                                <span>Ngừng bán</span>
                            </label>
                        </div>
                    </div>

                    <!-- Số trang & Năm XB -->
                    <div class="flex gap-2">
                        <div class="w-1/2 flex flex-col gap-1">
                            <label class="text-xs font-bold text-primary">Số trang</label>
                            <input id="edit-so-trang" name="so_trang" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary" type="number"/>
                        </div>
                        <div class="w-1/2 flex flex-col gap-1">
                            <label class="text-xs font-bold text-primary">Năm XB</label>
                            <input id="edit-nam-xuat-ban" name="nam_xuat_ban" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary" type="number"/>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <div class="sm:col-span-2 flex flex-col gap-1">
                        <label class="text-xs font-bold text-primary">Mô tả tóm tắt nội dung sách (`mo_ta`)</label>
                        <textarea id="edit-mo-ta" name="mo_ta" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary resize-none" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 bg-white flex items-center justify-end gap-2 border-t border-slate-100">
                <button class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors" onclick="closeEditBookModal()" type="button">
                    Hủy Bỏ
                </button>
                <button class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-md transition-all active:scale-95" type="submit">
                    Lưu Thay Đổi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 3: TẠO PHIẾU KHO NHANH / PHIẾU KHO MỚI (phieu_kho)                   -->
<!-- ========================================================================= -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary/50 backdrop-blur-sm hidden" id="modal-quick-slip">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-secondary text-white flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">swap_horiz</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-primary">Tạo Phiếu Kho Mới</h3>
                    <span class="text-xs text-on-surface-variant">Lập biên bản biến động kho &amp; cập nhật tồn kho tức thì</span>
                </div>
            </div>
            <button class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" onclick="closeQuickSlipModal()" type="button">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <form action="<?= base_url('admin/kho/tao-phieu') ?>" method="POST" class="p-6 flex flex-col gap-4">
            <!-- Loại phiếu Radio Pills -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-primary">Loại Nghiệp Vụ Phiếu Kho <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-3 gap-2 mt-1">
                    <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl bg-slate-100 hover:bg-emerald-100 cursor-pointer text-xs font-semibold has-[:checked]:bg-emerald-700 has-[:checked]:text-white transition-all">
                        <input checked class="hidden" name="slip_type" type="radio" value="nhap" onchange="toggleAdjustDirection(this.value)"/>
                        <span class="material-symbols-outlined text-[16px]">arrow_downward</span>
                        <span>Nhập Kho</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl bg-slate-100 hover:bg-rose-100 cursor-pointer text-xs font-semibold has-[:checked]:bg-rose-700 has-[:checked]:text-white transition-all">
                        <input class="hidden" name="slip_type" type="radio" value="xuat" onchange="toggleAdjustDirection(this.value)"/>
                        <span class="material-symbols-outlined text-[16px]">arrow_upward</span>
                        <span>Xuất Kho</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl bg-slate-100 hover:bg-indigo-100 cursor-pointer text-xs font-semibold has-[:checked]:bg-indigo-700 has-[:checked]:text-white transition-all">
                        <input class="hidden" name="slip_type" type="radio" value="dieu_chinh" onchange="toggleAdjustDirection(this.value)"/>
                        <span class="material-symbols-outlined text-[16px]">tune</span>
                        <span>Điều Chỉnh</span>
                    </label>
                </div>
            </div>

            <!-- Sách áp dụng -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-primary">Đầu Sách Áp Dụng <span class="text-rose-500">*</span></label>
                <!-- Sách chọn qua select dropdown hoặc cố định từ button row -->
                <div class="relative">
                    <select id="quick-slip-book-select" name="sach_id" class="w-full appearance-none px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs font-medium focus:outline-none focus:border-secondary cursor-pointer" required>
                        <option value="">-- Chọn tựa sách --</option>
                        <?php foreach ($books as $bk): ?>
                            <option value="<?= $bk['id'] ?>">
                                <?= htmlspecialchars($bk['tieu_de']) ?> (#BK-<?= $bk['id'] ?>) - Tồn: <?= $bk['so_luong_ton'] ?> cuốn
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-[16px]">arrow_drop_down</span>
                </div>
            </div>

            <!-- Hướng điều chỉnh nếu là phiếu điều chỉnh -->
            <div id="adjust-direction-group" class="hidden flex-col gap-1">
                <label class="text-xs font-bold text-primary">Hướng Điều Chỉnh Kiểm Kê</label>
                <div class="grid grid-cols-2 gap-2 mt-0.5">
                    <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl bg-slate-100 hover:bg-slate-200 cursor-pointer text-xs font-semibold has-[:checked]:bg-indigo-600 has-[:checked]:text-white transition-all">
                        <input name="adjust_direction" type="radio" value="tang" class="hidden"/>
                        <span>Tăng (+) Số Lượng</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl bg-slate-100 hover:bg-slate-200 cursor-pointer text-xs font-semibold has-[:checked]:bg-indigo-600 has-[:checked]:text-white transition-all">
                        <input checked name="adjust_direction" type="radio" value="giam" class="hidden"/>
                        <span>Giảm (-) Do Hư Hỏng / Mất</span>
                    </label>
                </div>
            </div>

            <!-- Số lượng luân chuyển -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-primary">Số Lượng Luân Chuyển <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <input name="quantity" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary font-bold text-base" placeholder="Nhập số cuốn (ví dụ: 50)" min="1" required type="number"/>
                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-medium text-slate-400">cuốn</span>
                </div>
            </div>

            <!-- Lý do / Ghi chú -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-primary">Lý Do / Ghi Chú Chứng Từ</label>
                <textarea name="ghi_chu" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary resize-none" placeholder="Ví dụ: Nhập bổ sung đợt tái bản NXB Trẻ, xuất giao đại lý..." rows="3"></textarea>
            </div>

            <!-- Buttons -->
            <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                <button class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors" onclick="closeQuickSlipModal()" type="button">
                    Hủy Bỏ
                </button>
                <button class="px-6 py-2.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white text-xs font-bold shadow-md transition-all active:scale-95" type="submit">
                    Xác Nhận Tạo Phiếu
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- JAVASCRIPT LOGIC CHO TABS & MODALS                                        -->
<!-- ========================================================================= -->
<script>
    // 1. Chuyển đổi tab chính (Sách & Tồn kho vs Phiếu kho)
    function switchMainTab(tabId) {
        const booksBtn = document.getElementById('tab-books-btn');
        const slipsBtn = document.getElementById('tab-slips-btn');
        const booksContent = document.getElementById('tab-books-content');
        const slipsContent = document.getElementById('tab-slips-content');

        if (tabId === 'tab-books') {
            booksContent.classList.remove('hidden');
            slipsContent.classList.add('hidden');

            booksBtn.className = "flex items-center gap-2 px-5 py-3 rounded-xl bg-primary text-white shadow-sm text-xs font-bold transition-all";
            slipsBtn.className = "flex items-center gap-2 px-5 py-3 rounded-xl bg-white hover:bg-slate-100 text-on-surface-variant border border-slate-200 text-xs font-bold transition-all";
        } else {
            slipsContent.classList.remove('hidden');
            booksContent.classList.add('hidden');

            slipsBtn.className = "flex items-center gap-2 px-5 py-3 rounded-xl bg-primary text-white shadow-sm text-xs font-bold transition-all";
            booksBtn.className = "flex items-center gap-2 px-5 py-3 rounded-xl bg-white hover:bg-slate-100 text-on-surface-variant border border-slate-200 text-xs font-bold transition-all";
        }
    }

    // 2. Mở Modal Thêm Sách
    function openAddBookModal() {
        document.getElementById('modal-add-book').classList.remove('hidden');
    }
    function closeAddBookModal() {
        document.getElementById('modal-add-book').classList.add('hidden');
    }

    // Xem trước ảnh bìa thêm sách
    function previewAddBookCover(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('add-book-cover-preview');
                const placeholder = document.getElementById('add-book-cover-placeholder');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 3. Mở Modal Tạo Phiếu Kho Nhanh cho 1 sách cụ thể
    function openQuickSlipModal(bookId, bookTitle, currentStock) {
        const select = document.getElementById('quick-slip-book-select');
        if (select) {
            // Kiểm tra xem option có tồn tại chưa
            let found = false;
            for (let i = 0; i < select.options.length; i++) {
                if (parseInt(select.options[i].value) === bookId) {
                    select.selectedIndex = i;
                    found = true;
                    break;
                }
            }
            if (!found) {
                const opt = new Option(`${bookTitle} (#BK-${bookId}) - Tồn: ${currentStock} cuốn`, bookId, true, true);
                select.add(opt);
            }
        }
        document.getElementById('modal-quick-slip').classList.remove('hidden');
    }

    // Mở Modal Tạo Phiếu Kho Trống
    function openNewSlipModal() {
        document.getElementById('modal-quick-slip').classList.remove('hidden');
    }
    function closeQuickSlipModal() {
        document.getElementById('modal-quick-slip').classList.add('hidden');
    }

    // Toggle hiện/ẩn hướng điều chỉnh
    function toggleAdjustDirection(type) {
        const group = document.getElementById('adjust-direction-group');
        if (type === 'dieu_chinh') {
            group.classList.remove('hidden');
            group.classList.add('flex');
        } else {
            group.classList.add('hidden');
            group.classList.remove('flex');
        }
    }

    // 4. Mở Modal Chỉnh Sửa Sách
    function openEditBookModal(bookId) {
        const form = document.getElementById('edit-book-form');
        form.action = `<?= base_url('admin/sach/sua/') ?>` + bookId;
        document.getElementById('edit-modal-subtitle').textContent = `Đang chỉnh sửa tựa sách #BK-${bookId}`;

        // Fetch dữ liệu qua API json
        fetch(`<?= base_url('admin/sach/sua/') ?>` + bookId + '?json=1')
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                document.getElementById('edit-tieu-de').value = data.tieu_de || '';
                document.getElementById('edit-the-loai-id').value = data.the_loai_id || '';
                document.getElementById('edit-nxb-id').value = data.nxb_id || '';
                document.getElementById('edit-gia').value = parseInt(data.gia) || 0;
                document.getElementById('edit-so-trang').value = data.so_trang || '';
                document.getElementById('edit-nam-xuat-ban').value = data.nam_xuat_ban || '';
                document.getElementById('edit-mo-ta').value = data.mo_ta || '';

                // Bìa xem trước
                const coverPreview = document.getElementById('edit-book-cover-preview');
                if (data.anh_bia) {
                    let coverUrl = data.anh_bia;
                    if (!coverUrl.startsWith('http')) {
                        coverUrl = `<?= asset('uploads/books/') ?>` + data.anh_bia;
                    }
                    coverPreview.src = coverUrl;
                } else {
                    coverPreview.src = `<?= asset('uploads/books/default.jpg') ?>`;
                }

                // Trạng thái kinh doanh
                if (parseInt(data.dang_ban) === 1) {
                    document.getElementById('edit-status-selling').checked = true;
                } else {
                    document.getElementById('edit-status-stopped').checked = true;
                }

                // Tác giả
                const authorSelect = document.getElementById('edit-tac-gia-ids');
                const authorIds = data.author_ids ? data.author_ids.map(id => parseInt(id)) : [];
                for (let i = 0; i < authorSelect.options.length; i++) {
                    const optVal = parseInt(authorSelect.options[i].value);
                    authorSelect.options[i].selected = authorIds.includes(optVal);
                }

                document.getElementById('modal-edit-book').classList.remove('hidden');
            })
            .catch(err => {
                console.error(err);
                alert('Không thể tải dữ liệu tựa sách: ' + err);
            });
    }

    function closeEditBookModal() {
        document.getElementById('modal-edit-book').classList.add('hidden');
    }

    function previewEditBookCover(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('edit-book-cover-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
