<?php
// Views/admin/advertisements/index.php
// Quản lý Banner & Quảng Cáo Kèm Voucher - Athenaeum Modern v2.4
?>

<div class="px-6 py-5 max-w-[1440px] w-full mx-auto flex flex-col gap-5">
    <!-- 1. Breadcrumb & Header Actions -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="flex flex-col gap-1">
            <nav class="flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant">
                <a class="hover:text-primary transition-colors flex items-center gap-1" href="<?= base_url('admin') ?>">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    <span>Bảng điều khiển</span>
                </a>
                <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
                <span class="text-primary font-bold">Quảng Cáo &amp; Banner Voucher</span>
            </nav>
            <div class="flex items-baseline gap-2 mt-0.5">
                <h1 class="text-2xl lg:text-3xl font-extrabold text-primary tracking-tight">Quảng Cáo Kèm Voucher Trang Chủ</h1>
                <span class="hidden sm:inline-block px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[11px] font-bold">
                    Marketing Hub • v2.4
                </span>
            </div>
            <p class="text-xs text-on-surface-variant">
                Đăng banner khuyến mãi hiển thị trực tiếp ở trang chính, đính kèm voucher ưu đãi cho khách hàng xem chương trình và bấm lưu vào ví.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openAddAdModal()" class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-primary hover:bg-secondary text-white text-xs font-bold shadow-sm transition-all active:scale-95" type="button">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>Thêm Quảng Cáo Kèm Voucher</span>
            </button>
            <a href="<?= base_url('admin/quang-cao') ?>" class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-primary text-xs font-semibold shadow-sm border border-slate-200 transition-all active:scale-95" title="Làm mới">
                <span class="material-symbols-outlined text-[18px] text-amber-600">sync</span>
                <span>Làm Mới</span>
            </a>
        </div>
    </div>

    <!-- 2. Danh sách quảng cáo -->
    <div class="rounded-2xl bg-white border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[20px]">campaign</span>
                <span>Danh Sách Chiến Dịch Quảng Cáo &amp; Banner Hiện Hữu (<?= count($ads) ?>)</span>
            </h2>
            <span class="text-xs text-slate-400">Hiển thị trực tiếp trên trang chủ cho độc giả</span>
        </div>

        <?php if (empty($ads)): ?>
            <div class="py-16 text-center flex flex-col items-center justify-center gap-3">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                    <span class="material-symbols-outlined text-[36px]">campaign</span>
                </div>
                <h3 class="font-bold text-slate-700 text-sm">Chưa có chương trình quảng cáo nào</h3>
                <p class="text-xs text-slate-400 max-w-sm">Hãy tạo quảng cáo đầu tiên kèm voucher để thu hút khách hàng lưu mã và mua sắm ngay tại trang chính!</p>
                <button onclick="openAddAdModal()" class="px-4 py-2 rounded-xl bg-primary text-white font-bold text-xs shadow hover:bg-secondary transition-colors">
                    Tạo Ngay Bây Giờ
                </button>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 p-5">
                <?php foreach ($ads as $ad): 
                    $bannerImg = !empty($ad['anh_banner']) 
                        ? (str_starts_with($ad['anh_banner'], 'http') ? $ad['anh_banner'] : base_url('uploads/banners/' . $ad['anh_banner']))
                        : 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=800&q=80';
                ?>
                    <div class="rounded-2xl border border-slate-200 overflow-hidden flex flex-col bg-white shadow-xs hover:shadow-md transition-all">
                        <!-- Banner Preview -->
                        <div class="relative h-44 w-full bg-slate-100 overflow-hidden group">
                            <img src="<?= htmlspecialchars($bannerImg) ?>" alt="<?= htmlspecialchars($ad['tieu_de']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.src='https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=800&q=80'">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent"></div>
                            
                            <!-- Badges on banner -->
                            <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                <?php if ($ad['trang_thai']): ?>
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-500 text-white text-[10px] font-bold shadow-xs flex items-center gap-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        Đang Hiển Thị
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 rounded-full bg-slate-500 text-white text-[10px] font-bold shadow-xs">
                                        Tạm Ẩn
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="absolute bottom-3 left-3 right-3 text-white">
                                <h3 class="font-bold text-sm line-clamp-1 drop-shadow"><?= htmlspecialchars($ad['tieu_de']) ?></h3>
                                <?php if (!empty($ad['phu_de'])): ?>
                                    <p class="text-[11px] text-slate-200 line-clamp-1 mt-0.5"><?= htmlspecialchars($ad['phu_de']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Content info -->
                        <div class="p-4 flex-1 flex flex-col justify-between gap-3">
                            <!-- Voucher đính kèm -->
                            <div class="p-3 rounded-xl bg-amber-50/80 border border-amber-200/80 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">local_activity</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-amber-800 font-bold uppercase tracking-wider">Voucher Tặng Kèm</span>
                                        <?php if (!empty($ad['voucher_code'])): ?>
                                            <span class="font-mono font-black text-rose-700 text-xs">
                                                <?= htmlspecialchars($ad['voucher_code']) ?>
                                                <span class="text-[10px] font-semibold text-slate-500">
                                                    (<?= $ad['voucher_loai'] === 'phan_tram' ? '-' . (int)$ad['voucher_gia_tri'] . '%' : '-' . format_currency($ad['voucher_gia_tri']) ?>)
                                                </span>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs text-slate-400 italic">Chưa liên kết voucher</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Lượt khách lưu voucher -->
                            <?php 
                            $maxSaved = (int)($ad['so_luot_luu_toi_da'] ?? 0);
                            $cntSaved = (int)($ad['so_luot_da_luu'] ?? 0);
                            $isFull = ($maxSaved > 0 && $cntSaved >= $maxSaved);
                            ?>
                            <div class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px] text-amber-600">bookmark_added</span>
                                    <span>Khách lưu voucher:</span>
                                </span>
                                <span class="font-extrabold flex items-center gap-1 <?= $isFull ? 'text-rose-600' : 'text-primary' ?>">
                                    <?= $cntSaved ?> / <?= $maxSaved > 0 ? $maxSaved : '∞' ?>
                                    <?php if ($isFull): ?>
                                        <span class="text-[9px] bg-rose-100 text-rose-700 px-1.5 py-0.5 rounded-full font-bold">Đã Hết Lượt</span>
                                    <?php endif; ?>
                                </span>
                            </div>

                            <!-- Rules & Details snippet -->
                            <?php if (!empty($ad['noi_dung'])): ?>
                                <p class="text-[11px] text-slate-500 line-clamp-2 italic bg-slate-50 p-2 rounded-lg">
                                    "<?= htmlspecialchars(mb_strimwidth($ad['noi_dung'], 0, 110, '...')) ?>"
                                </p>
                            <?php endif; ?>

                            <!-- Footer actions -->
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400 font-medium">Thứ tự: <?= (int)$ad['thu_tu'] ?></span>
                                <div class="flex items-center gap-1">
                                    <!-- Sửa -->
                                    <button type="button" onclick="openEditAdModal(<?= $ad['id'] ?>)" class="p-1.5 rounded-lg text-slate-600 hover:text-primary hover:bg-slate-100 transition-colors" title="Chỉnh sửa">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>

                                    <!-- Bật/Tắt -->
                                    <form method="POST" action="<?= base_url('admin/quang-cao/toggle-status/' . $ad['id']) ?>" class="inline">
                                        <button type="submit" class="p-1.5 rounded-lg <?= $ad['trang_thai'] ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' ?> transition-colors" title="<?= $ad['trang_thai'] ? 'Tạm ẩn' : 'Kích hoạt' ?>">
                                            <span class="material-symbols-outlined text-[18px]"><?= $ad['trang_thai'] ? 'visibility_off' : 'visibility' ?></span>
                                        </button>
                                    </form>

                                    <!-- Xóa -->
                                    <form method="POST" action="<?= base_url('admin/quang-cao/xoa/' . $ad['id']) ?>" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chiến dịch quảng cáo này?')">
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Xóa">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ================= MODAL THÊM QUẢNG CÁO KÈM VOUCHER ================= -->
<div id="modal-add-ad" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary/40 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-100 overflow-hidden transform scale-95 transition-transform duration-200 max-h-[90vh] flex flex-col">
        <div class="p-5 bg-gradient-to-r from-amber-600 via-rose-600 to-primary text-white flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[24px]">campaign</span>
                <div>
                    <h3 class="font-bold text-sm">Đăng Quảng Cáo Kèm Voucher Mới</h3>
                    <p class="text-[11px] text-amber-100">Chiến dịch sẽ hiển thị nổi bật trên đầu hoặc giữa trang chủ</p>
                </div>
            </div>
            <button type="button" onclick="closeAddAdModal()" class="text-white/80 hover:text-white p-1 rounded-lg">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="<?= base_url('admin/quang-cao/them') ?>" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto flex-1 flex flex-col gap-4">
            <!-- Tiêu đề & Phụ đề -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-primary">Tiêu đề chương trình quảng cáo <span class="text-rose-500">*</span></label>
                <input name="tieu_de" type="text" required placeholder="Ví dụ: Đại Tiệc Sách Mùa Lễ Hội - Ưu Đãi Độc Quyền" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-secondary focus:outline-none"/>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-primary">Phụ đề ngắn / Slogan thu hút</label>
                <input name="phu_de" type="text" placeholder="Ví dụ: Giảm giá đến 30% và lưu ngay voucher để nhận ưu đãi kép" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-secondary focus:outline-none"/>
            </div>

            <!-- Chọn Voucher đính kèm -->
            <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200 flex flex-col gap-2">
                <label class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px] text-amber-600">local_activity</span>
                    <span>Voucher đính kèm để khách hàng lưu vào ví</span>
                </label>
                <select name="ma_giam_gia_id" class="w-full px-3.5 py-2 rounded-lg bg-white border border-amber-300 text-xs font-bold text-primary focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <option value="">-- Không đính kèm voucher (Chỉ quảng cáo) --</option>
                    <?php foreach ($activeCoupons as $c): ?>
                        <option value="<?= $c['id'] ?>">
                            <?= htmlspecialchars($c['ma']) ?> (<?= $c['loai'] === 'phan_tram' ? 'Giảm ' . (int)$c['gia_tri'] . '%' : 'Giảm ' . format_currency($c['gia_tri']) ?> - Đơn từ <?= format_currency($c['don_toi_thieu']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="text-[11px] text-amber-700">Khách hàng khi xem quảng cáo ở trang chủ có thể ấn nút "Lưu Voucher" để lưu trực tiếp mã này vào tài khoản!</span>
            </div>

            <!-- Giới hạn số lượng khách hàng được lưu voucher -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-primary flex items-center justify-between">
                    <span>Số lượng khách hàng được lưu voucher tối đa</span>
                    <span class="text-[10px] font-normal text-slate-400">0 = Không giới hạn</span>
                </label>
                <div class="relative">
                    <input name="so_luot_luu_toi_da" type="number" min="0" value="0" placeholder="VD: 50" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-primary focus:bg-white focus:border-secondary focus:outline-none"/>
                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[11px] text-slate-400 font-medium">lượt lưu</span>
                </div>
                <span class="text-[10px] text-slate-400">Khi số người lưu voucher này đạt mức tối đa, các tài khoản còn lại sẽ thấy hiển thị "Đã hết lượt lưu".</span>
            </div>

            <!-- Ảnh Banner -->
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-primary">Hình ảnh Banner quảng cáo</label>
                <input type="file" name="anh_banner" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-primary hover:file:bg-slate-200"/>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] text-slate-400">Hoặc dán URL ảnh trực tuyến:</span>
                    <input name="anh_banner_url" type="url" placeholder="https://example.com/banner.jpg" class="flex-1 px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:outline-none"/>
                </div>
            </div>

            <!-- Thể lệ & Nội dung chi tiết chương trình -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-primary">Thể lệ &amp; Nội dung chương trình chi tiết (Hiển thị khi khách ấn "Xem chương trình")</label>
                <textarea name="noi_dung" rows="4" placeholder="Nhập thể lệ, điều kiện áp dụng, thời gian và các quyền lợi đặc biệt của chương trình..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-secondary focus:outline-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-primary">Thứ tự ưu tiên (Nhỏ hơn lên trước)</label>
                    <input name="thu_tu" type="number" value="0" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs"/>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-primary">Link điều hướng khi click</label>
                    <input name="link_dieu_huong" type="text" value="sach" placeholder="sach, khuyen-mai-hot,..." class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs"/>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeAddAdModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Hủy</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-primary hover:bg-secondary text-white shadow-sm flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    <span>Đăng Quảng Cáo</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL SỬA QUẢNG CÁO KÈM VOUCHER ================= -->
<div id="modal-edit-ad" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary/40 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-100 overflow-hidden transform scale-95 transition-transform duration-200 max-h-[90vh] flex flex-col">
        <div class="p-5 bg-gradient-to-r from-slate-800 to-primary text-white flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[24px]">edit_note</span>
                <div>
                    <h3 class="font-bold text-sm">Chỉnh Sửa Quảng Cáo Kèm Voucher</h3>
                    <p class="text-[11px] text-slate-300" id="edit-ad-title-label">Cập nhật thông tin và chương trình ưu đãi</p>
                </div>
            </div>
            <button type="button" onclick="closeEditAdModal()" class="text-white/80 hover:text-white p-1 rounded-lg">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="edit-ad-form" action="" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto flex-1 flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-primary">Tiêu đề chương trình quảng cáo <span class="text-rose-500">*</span></label>
                <input id="edit_tieu_de" name="tieu_de" type="text" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-secondary focus:outline-none"/>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-primary">Phụ đề ngắn / Slogan thu hút</label>
                <input id="edit_phu_de" name="phu_de" type="text" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-secondary focus:outline-none"/>
            </div>

            <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200 flex flex-col gap-2">
                <label class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px] text-amber-600">local_activity</span>
                    <span>Voucher đính kèm để khách hàng lưu vào ví</span>
                </label>
                <select id="edit_ma_giam_gia_id" name="ma_giam_gia_id" class="w-full px-3.5 py-2 rounded-lg bg-white border border-amber-300 text-xs font-bold text-primary focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <option value="">-- Không đính kèm voucher (Chỉ quảng cáo) --</option>
                    <?php foreach ($activeCoupons as $c): ?>
                        <option value="<?= $c['id'] ?>">
                            <?= htmlspecialchars($c['ma']) ?> (<?= $c['loai'] === 'phan_tram' ? 'Giảm ' . (int)$c['gia_tri'] . '%' : 'Giảm ' . format_currency($c['gia_tri']) ?> - Đơn từ <?= format_currency($c['don_toi_thieu']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Giới hạn số lượng khách hàng được lưu voucher -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-primary flex items-center justify-between">
                    <span>Số lượng khách hàng được lưu voucher tối đa</span>
                    <span class="text-[10px] font-normal text-slate-400">0 = Không giới hạn</span>
                </label>
                <div class="relative">
                    <input id="edit_so_luot_luu_toi_da" name="so_luot_luu_toi_da" type="number" min="0" value="0" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-primary focus:bg-white focus:border-secondary focus:outline-none"/>
                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[11px] text-slate-400 font-medium">lượt lưu</span>
                </div>
                <span class="text-[10px] text-slate-400">Khi đạt mức này, các tài khoản chưa lưu sẽ thấy trạng thái "Đã hết lượt lưu".</span>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-primary">Đổi hình ảnh Banner</label>
                <input type="file" name="anh_banner" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-primary hover:file:bg-slate-200"/>
                <input id="edit_anh_banner_url" name="anh_banner_url" type="url" placeholder="Hoặc dán URL ảnh banner mới..." class="w-full px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:outline-none"/>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-primary">Thể lệ &amp; Nội dung chương trình chi tiết</label>
                <textarea id="edit_noi_dung" name="noi_dung" rows="4" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-secondary focus:outline-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-primary">Thứ tự ưu tiên</label>
                    <input id="edit_thu_tu" name="thu_tu" type="number" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs"/>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-primary">Trạng thái hiển thị</label>
                    <select id="edit_trang_thai" name="trang_thai" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold">
                        <option value="1">Đang hiển thị</option>
                        <option value="0">Tạm ẩn</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditAdModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Hủy</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-primary hover:bg-secondary text-white shadow-sm flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    <span>Cập Nhật Thay Đổi</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddAdModal() {
    const modal = document.getElementById('modal-add-ad');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.firstElementChild.classList.remove('scale-95');
}

function closeAddAdModal() {
    const modal = document.getElementById('modal-add-ad');
    modal.classList.add('opacity-0', 'pointer-events-none');
    modal.firstElementChild.classList.add('scale-95');
}

function openEditAdModal(id) {
    const modal = document.getElementById('modal-edit-ad');
    const form = document.getElementById('edit-ad-form');
    form.action = `<?= base_url('admin/quang-cao/sua/') ?>${id}`;

    fetch(`<?= base_url('admin/quang-cao/chi-tiet/') ?>${id}`)
        .then(res => res.json())
        .then(resData => {
            if (!resData.success || !resData.data) {
                alert('Không thể tải thông tin quảng cáo.');
                return;
            }
            const d = resData.data;
            document.getElementById('edit_tieu_de').value = d.tieu_de || '';
            document.getElementById('edit_phu_de').value = d.phu_de || '';
            document.getElementById('edit_ma_giam_gia_id').value = d.ma_giam_gia_id || '';
            document.getElementById('edit_so_luot_luu_toi_da').value = parseInt(d.so_luot_luu_toi_da) || 0;
            document.getElementById('edit_noi_dung').value = d.noi_dung || '';
            document.getElementById('edit_thu_tu').value = d.thu_tu || 0;
            document.getElementById('edit_trang_thai').value = d.trang_thai;
            if (d.anh_banner && d.anh_banner.startsWith('http')) {
                document.getElementById('edit_anh_banner_url').value = d.anh_banner;
            } else {
                document.getElementById('edit_anh_banner_url').value = '';
            }

            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.firstElementChild.classList.remove('scale-95');
        })
        .catch(err => {
            console.error(err);
            alert('Lỗi kết nối: ' + err);
        });
}

function closeEditAdModal() {
    const modal = document.getElementById('modal-edit-ad');
    modal.classList.add('opacity-0', 'pointer-events-none');
    modal.firstElementChild.classList.add('scale-95');
}
</script>
