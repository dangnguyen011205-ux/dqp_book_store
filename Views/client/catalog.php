<?php
// Views/client/catalog.php

// Helper tạo link phân trang và filter giữ nguyên query params
function buildFilterUrl(array $newParams): string {
    $current = $_GET;
    unset($current['url']);
    $merged = array_merge($current, $newParams);
    // Loại bỏ param rỗng
    foreach ($merged as $k => $v) {
        if ($v === null || $v === '') {
            unset($merged[$k]);
        }
    }
    return base_url('tim-kiem?' . http_build_query($merged));
}

$fallbackImages = [
    've_di_tuoi_tho.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDOW-5R4g1lDrfnMUOBxJj5M7wo1WvsCQsDwqCLZmRbhk62RKvqRmJ6kw99wmW4BL3kUyyuaJxRgNogYU_x2QdDcnRW_LBtMGmp79xXaMbxkRZ4bHIZoMpwXWDCJ7Pvls8dvP-ELZwksZT5YPUqgfGgxK5gSFD6V1vymZXlpL8eIseOO0YSoWJWC2quEc3Mx-vk7ZBDIFgGPjRMOFdjSbsGjFvpQi9TUtOpuFtb0eIbMSr9xSJA0Ea9',
    'mat_biec.jpg'       => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBCROvi1laus0XIWz6LpZ0P5HeiGIVthmPumUmskuWEVVOZ4g16-h2hSTzztGGZhp3ySzUs39iPTECYlyKqk3F-sF8zIYzga0ECMPk62VcPgJZiTrO5COR64605DXYENgpvk0xshkGjXydPbKKpYp1KJtjEyRYtzNrgEuMKLNLAdTT3Y1yZ5ru24pASdUUyHkZJAzNbXtYnYMHk1V34UsiXk0_J45PIDn4wCNahTosqdLaFovyqa9WF',
    'rung_na_uy.jpg'     => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB39kchJl2airUEEKvNTfjBIF1Au5AuIqYbzVd3O5AW9bhoYX8DD4iKlOh7ckPeiaov9e_7QU4iNI2n92tjJh_BJjhKLBwOl7IIgnM9D9fSDPm4Ft8xl5JLIgpqX20cfC-EFsO7Gg9sJ9WC8QXTjK84zwWY6qL6U1sHgA3eWQM7ptpHB2-a3ZTY389Jrth4Nt0hWFrtBBG_TFFefN8Wwyq93ImOQ6t7IqyAi96zrwGpfRBw1u3VdP0y',
    'dac_nhan_tam.jpg'   => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAUKERVQmDszz25tjdhGJ60sosu-sOcfvaC1RZfWv7AStopNuMi_XSLesJg9-0UgVd6UOaAw8mRt8xsMUg1rDkrSaQkuodwGR9EXPkk53zp7mg9pwkeaeb0F9LNIJ5gJ1TBQuDW-cJfvykpacXFoIyxBTrFXz5TA3a4ta-5hPFPhjiZLpKWTbmWMI6XZ1QTYx6qOJ22BRb4-cYJH9RnzP5cqVKONtQ-P9twqUAbxN_XOjY6kABmewFk',
    'clean_code.jpg'     => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAILnt2jy0fXHs8-Kz6PipSF15EraoOkGE17r5yZ-6i-yk08UovomgdSCTxKnn8IWUWwh9AVb39DQl_f-glZfVZ2kcqLICo6mAbmRXCkIvLQIQLpRBBuPlK2DJgZwD_WCK0S1NWUZ_4h1PYjRF5Yh7aud_twOOz7URBV1F3Ex105YAIzve59PV2W46hmi_YoxqJblnmTpvO9riKDFLWNQJ7EktUtK2hAodfs1Oa-3fMR6ueCB3o61ci',
    'nha_gia_kim.jpg'    => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAZ6n_FWa_IMAKlR9ocIW0hh47Cy9-wPVvDcRq3T2Weupyyo2Ed6SVlnhOMZYz7bn3dW4n-3XOpGtOJsyCbrzkf5Ku2I2a-3jDK2KBjCaWmv83sglAAvoNLwtCPKBVuG294MYWI2qWcEmiBrgi9o_YzCBJ2LrNvIeNQEuSkueeI2sP269-pZefkGWuLfYEZ8xhmg0DX4j6-mHKtaeDaxF7Or-irm4NKSRPwXXR9r05ciXqLfLME6of1'
];

function getCatalogBookCover(?string $anhBia, array $fallbackMap): string {
    if (!empty($anhBia)) {
        if (str_starts_with($anhBia, 'http://') || str_starts_with($anhBia, 'https://')) {
            return $anhBia;
        }
        $localPath = ROOT_PATH . '/Public/uploads/books/' . $anhBia;
        if (file_exists($localPath)) {
            return asset('uploads/books/' . $anhBia);
        }
        if (isset($fallbackMap[$anhBia])) {
            return $fallbackMap[$anhBia];
        }
    }
    return 'https://lh3.googleusercontent.com/aida-public/AB6AXuAUz2h5NbRRpQxcVtF5NPJD_iS9y0RXn5uwAPV0D9DJcKseQpT7V-mhEMQrFOtRq9VGbSv5umrMiFHWzcQoyNiuSN0Q5GWTjaPPf7tDrnd0UM41X0iM8jiG6rSV-Qso0bjWxZZwKU3ZW-CMRmhOd2jEnf0IIVCt_vBGUR1D7PQcAHq6Wf1_lJqQj7u5F1ZhpKyX1w15CdFG0PsLVH263eNelWhvV99odJk1qBi7NxYEbClVt4p3Z0vQ';
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500">
        <a href="<?= base_url() ?>" class="hover:text-primary transition-colors">Trang Chủ</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-bold">Tìm Kiếm & Khám Phá Sách</span>
        <?php if (!empty($filters['q'])): ?>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-secondary italic">"<?= htmlspecialchars($filters['q']) ?>"</span>
        <?php endif; ?>
    </nav>

    <!-- Main Grid: Cột Trái (Bộ Lọc) & Cột Phải (Danh Mục Sách) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- ================= CỘT TRÁI: BỘ LỌC SIDEBAR (3 COLS) ================= -->
        <aside class="lg:col-span-3 space-y-6 bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h2 class="text-sm font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-secondary">tune</span>
                    <span>Bộ Lọc Tuyển Chọn</span>
                </h2>
                <a href="<?= base_url('tim-kiem') ?>" class="text-[11px] font-bold text-rose-600 hover:underline flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[14px]">restart_alt</span> Xóa tất cả
                </a>
            </div>

            <form action="<?= base_url('tim-kiem') ?>" method="GET" id="catalog-filter-form" class="space-y-6">
                <!-- Giữ lại từ khóa tìm kiếm nếu có -->
                <?php if (!empty($filters['q'])): ?>
                    <input type="hidden" name="q" value="<?= htmlspecialchars($filters['q']) ?>"/>
                <?php endif; ?>

                <!-- 1. Lọc theo Thể Loại -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-secondary">category</span>
                            Thể Loại Sách
                        </h3>
                    </div>
                    <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                        <?php foreach ($categories as $cat): 
                            $isChecked = false;
                            if (isset($filters['the_loai'])) {
                                if (is_array($filters['the_loai'])) {
                                    $isChecked = in_array($cat['id'], $filters['the_loai']);
                                } else {
                                    $isChecked = ((int)$filters['the_loai'] === (int)$cat['id']);
                                }
                            }
                        ?>
                            <label class="flex items-center justify-between text-xs text-slate-700 cursor-pointer select-none group py-0.5">
                                <div class="flex items-center gap-2.5">
                                    <input type="radio" name="the_loai" value="<?= $cat['id'] ?>" 
                                           onchange="document.getElementById('catalog-filter-form').submit();"
                                           <?= $isChecked ? 'checked' : '' ?>
                                           class="w-4 h-4 text-secondary border-slate-300 focus:ring-secondary cursor-pointer"/>
                                    <span class="group-hover:text-secondary transition-colors <?= $isChecked ? 'font-bold text-secondary' : '' ?>">
                                        <?= htmlspecialchars($cat['ten']) ?>
                                    </span>
                                </div>
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-semibold">
                                    <?= $cat['tong_sach'] ?? 0 ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="h-px bg-slate-100"></div>

                <!-- 2. Khoảng Giá (Presets & Inputs) -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-secondary">payments</span>
                        Khoảng Giá (VNĐ)
                    </h3>

                    <!-- Quick Preset Chips -->
                    <div class="flex flex-wrap gap-1.5">
                        <a href="<?= buildFilterUrl(['gia_min' => '', 'gia_max' => 100000]) ?>" 
                           class="text-[11px] px-2.5 py-1 rounded-lg border transition-colors <?= ($filters['gia_max'] == 100000 && empty($filters['gia_min'])) ? 'bg-primary text-white border-primary font-bold' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-secondary' ?>">
                            < 100k
                        </a>
                        <a href="<?= buildFilterUrl(['gia_min' => 100000, 'gia_max' => 200000]) ?>" 
                           class="text-[11px] px-2.5 py-1 rounded-lg border transition-colors <?= ($filters['gia_min'] == 100000 && $filters['gia_max'] == 200000) ? 'bg-primary text-white border-primary font-bold' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-secondary' ?>">
                            100k - 200k
                        </a>
                        <a href="<?= buildFilterUrl(['gia_min' => 200000, 'gia_max' => 500000]) ?>" 
                           class="text-[11px] px-2.5 py-1 rounded-lg border transition-colors <?= ($filters['gia_min'] == 200000 && $filters['gia_max'] == 500000) ? 'bg-primary text-white border-primary font-bold' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-secondary' ?>">
                            200k - 500k
                        </a>
                        <a href="<?= buildFilterUrl(['gia_min' => 500000, 'gia_max' => '']) ?>" 
                           class="text-[11px] px-2.5 py-1 rounded-lg border transition-colors <?= ($filters['gia_min'] == 500000 && empty($filters['gia_max'])) ? 'bg-primary text-white border-primary font-bold' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-secondary' ?>">
                            > 500k
                        </a>
                    </div>

                    <!-- Min - Max Input -->
                    <div class="grid grid-cols-2 gap-2 pt-1 items-center">
                        <input type="number" name="gia_min" placeholder="Từ (đ)" value="<?= htmlspecialchars($filters['gia_min'] ?? '') ?>"
                               class="w-full px-2.5 py-1.5 rounded-lg bg-slate-50 text-slate-800 text-xs border border-slate-200 focus:outline-none focus:bg-white focus:ring-1 focus:ring-secondary"/>
                        <input type="number" name="gia_max" placeholder="Đến (đ)" value="<?= htmlspecialchars($filters['gia_max'] ?? '') ?>"
                               class="w-full px-2.5 py-1.5 rounded-lg bg-slate-50 text-slate-800 text-xs border border-slate-200 focus:outline-none focus:bg-white focus:ring-1 focus:ring-secondary"/>
                    </div>
                    <button type="submit" class="w-full py-2 bg-primary text-white rounded-lg text-xs font-bold hover:bg-secondary transition-colors">
                        Áp Dụng Mức Giá
                    </button>
                </div>

                <div class="h-px bg-slate-100"></div>

                <!-- 3. Lọc theo Tác Giả -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-secondary">person_edit</span>
                        Tác Giả Tiêu Biểu
                    </h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        <?php foreach ($authors as $tg): 
                            $isAuthorChecked = false;
                            if (isset($filters['tac_gia'])) {
                                if (is_array($filters['tac_gia'])) {
                                    $isAuthorChecked = in_array($tg['id'], $filters['tac_gia']);
                                } else {
                                    $isAuthorChecked = ((int)$filters['tac_gia'] === (int)$tg['id']);
                                }
                            }
                        ?>
                            <label class="flex items-center justify-between text-xs text-slate-700 cursor-pointer select-none group py-0.5">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="tac_gia" value="<?= $tg['id'] ?>" 
                                           onchange="document.getElementById('catalog-filter-form').submit();"
                                           <?= $isAuthorChecked ? 'checked' : '' ?>
                                           class="w-4 h-4 text-secondary border-slate-300 focus:ring-secondary cursor-pointer"/>
                                    <span class="group-hover:text-secondary transition-colors <?= $isAuthorChecked ? 'font-bold text-secondary' : '' ?>">
                                        <?= htmlspecialchars($tg['ten']) ?>
                                    </span>
                                </div>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-400">
                                    <?= $tg['tong_sach'] ?? 0 ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="h-px bg-slate-100"></div>

                <!-- 4. Lọc theo Nhà Xuất Bản -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-secondary">corporate_fare</span>
                        Nhà Xuất Bản
                    </h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        <?php foreach ($publishers as $nxb): 
                            $isNxbChecked = false;
                            if (isset($filters['nxb'])) {
                                if (is_array($filters['nxb'])) {
                                    $isNxbChecked = in_array($nxb['id'], $filters['nxb']);
                                } else {
                                    $isNxbChecked = ((int)$filters['nxb'] === (int)$nxb['id']);
                                }
                            }
                        ?>
                            <label class="flex items-center justify-between text-xs text-slate-700 cursor-pointer select-none group py-0.5">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="nxb" value="<?= $nxb['id'] ?>" 
                                           onchange="document.getElementById('catalog-filter-form').submit();"
                                           <?= $isNxbChecked ? 'checked' : '' ?>
                                           class="w-4 h-4 text-secondary border-slate-300 focus:ring-secondary cursor-pointer"/>
                                    <span class="group-hover:text-secondary transition-colors <?= $isNxbChecked ? 'font-bold text-secondary' : '' ?>">
                                        <?= htmlspecialchars($nxb['ten']) ?>
                                    </span>
                                </div>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-400">
                                    <?= $nxb['tong_sach'] ?? 0 ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="h-px bg-slate-100"></div>

                <!-- 5. Lọc theo Đánh Giá Sao -->
                <div class="space-y-2">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-secondary">star</span>
                        Đánh Giá Sao
                    </h3>
                    <div class="space-y-1.5 text-xs">
                        <?php foreach ([5 => '5 sao tuyệt đối', 4 => 'Từ 4 sao trở lên', 3 => 'Từ 3 sao trở lên'] as $sao => $label): ?>
                            <label class="flex items-center gap-2 text-slate-700 cursor-pointer group">
                                <input type="radio" name="so_sao" value="<?= $sao ?>" 
                                       onchange="document.getElementById('catalog-filter-form').submit();"
                                       <?= ($filters['so_sao'] == $sao) ? 'checked' : '' ?>
                                       class="w-4 h-4 text-secondary border-slate-300 focus:ring-secondary cursor-pointer"/>
                                <span class="flex items-center text-amber-500">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' <?= $i <= $sao ? 1 : 0 ?>;">star</span>
                                    <?php endfor; ?>
                                </span>
                                <span class="group-hover:text-secondary text-[11px]"><?= $label ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="h-px bg-slate-100"></div>

                <!-- 6. Tình trạng còn hàng -->
                <div class="pt-1">
                    <label class="flex items-center gap-2.5 text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="con_hang" value="1" 
                               onchange="document.getElementById('catalog-filter-form').submit();"
                               <?= !empty($filters['con_hang']) ? 'checked' : '' ?>
                               class="w-4 h-4 text-secondary rounded border-slate-300 focus:ring-secondary cursor-pointer"/>
                        <span>Chỉ xem sách còn hàng</span>
                    </label>
                </div>
            </form>
        </aside>

        <!-- ================= CỘT PHẢI: KẾT QUẢ & TOOLBAR (9 COLS) ================= -->
        <main class="lg:col-span-9 space-y-6">

            <!-- Toolbar Header: Số kết quả, Sắp xếp -->
            <div class="bg-surface-container-lowest p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-primary tracking-tight">
                        <?php if (!empty($filters['q'])): ?>
                            Kết quả tìm kiếm cho: "<span class="text-secondary"><?= htmlspecialchars($filters['q']) ?></span>"
                        <?php else: ?>
                            Tủ Sách Tinh Hoa & Tác Phẩm Chọn Lọc
                        <?php endif; ?>
                    </h1>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Tìm thấy <strong class="text-primary font-bold"><?= $total ?></strong> tựa sách đáp ứng tiêu chí.
                    </p>
                </div>

                <!-- Dropdown Sắp Xếp -->
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <label class="text-xs text-slate-500 shrink-0">Sắp xếp:</label>
                    <select onchange="window.location.href = this.value;" class="bg-slate-50 text-xs font-semibold text-slate-700 py-2 px-3 rounded-xl border border-slate-200 focus:outline-none focus:bg-white focus:ring-1 focus:ring-secondary">
                        <option value="<?= buildFilterUrl(['sort' => 'moi_nhat']) ?>" <?= $filters['sort'] === 'moi_nhat' ? 'selected' : '' ?>>Mới Nhất</option>
                        <option value="<?= buildFilterUrl(['sort' => 'ban_chay']) ?>" <?= $filters['sort'] === 'ban_chay' ? 'selected' : '' ?>>Bán Chạy Nhất</option>
                        <option value="<?= buildFilterUrl(['sort' => 'gia_tang']) ?>" <?= $filters['sort'] === 'gia_tang' ? 'selected' : '' ?>>Giá: Thấp đến Cao</option>
                        <option value="<?= buildFilterUrl(['sort' => 'gia_giam']) ?>" <?= $filters['sort'] === 'gia_giam' ? 'selected' : '' ?>>Giá: Cao đến Thấp</option>
                        <option value="<?= buildFilterUrl(['sort' => 'danh_gia']) ?>" <?= $filters['sort'] === 'danh_gia' ? 'selected' : '' ?>>Đánh Giá Cao Nhất</option>
                    </select>
                </div>
            </div>

            <!-- Grid Sách -->
            <?php if (empty($books)): ?>
                <!-- Empty State -->
                <div class="bg-surface-container-lowest rounded-2xl p-12 text-center border border-slate-100 shadow-sm space-y-4">
                    <div class="w-20 h-20 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[44px]">menu_book</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-primary">Không Tìm Thấy Tác Phẩm Phù Hợp</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 leading-relaxed">
                            Rất tiếc, không có cuốn sách nào khớp với toàn bộ các bộ lọc đang chọn. Bạn hãy thử bỏ bớt điều kiện lọc hoặc nhập từ khóa tìm kiếm khác.
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="<?= base_url('tim-kiem') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-secondary transition-colors shadow">
                            <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                            <span>Xem Toàn Bộ Sách</span>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ($books as $book): 
                        $coverUrl = getCatalogBookCover($book['anh_bia'], $fallbackImages);
                        $originalPrice = (float)$book['gia'] * 1.25;
                    ?>
                        <div class="bg-surface-container-lowest rounded-2xl p-4 shadow-sm hover:shadow-xl transition-all flex flex-col justify-between group border border-slate-100 hover:-translate-y-1">
                            <div>
                                <div class="relative aspect-[2/3] rounded-xl overflow-hidden bg-slate-100 mb-3">
                                    <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($book['tieu_de']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform"/>
                                    <?php if ($book['so_luong_ton'] <= 0): ?>
                                        <div class="absolute inset-0 bg-primary/60 backdrop-blur-xs flex items-center justify-center">
                                            <span class="px-2.5 py-1 rounded bg-rose-600 text-white text-[10px] font-bold uppercase">Hết Hàng</span>
                                        </div>
                                    <?php else: ?>
                                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-secondary text-white text-[10px] font-bold">Chuẩn NXB</span>
                                    <?php endif; ?>
                                </div>

                                <span class="text-[10px] font-bold text-secondary uppercase tracking-wider block truncate"><?= htmlspecialchars($book['the_loai_ten'] ?? 'Sách Tuyển Chọn') ?></span>
                                <h3 class="text-xs font-bold text-primary line-clamp-2 mt-1 group-hover:text-secondary transition-colors leading-snug">
                                    <a href="<?= base_url('chi-tiet/' . $book['id']) ?>"><?= htmlspecialchars($book['tieu_de']) ?></a>
                                </h3>
                                <p class="text-[11px] text-slate-500 truncate mt-0.5"><?= htmlspecialchars($book['tac_gia_ten'] ?? 'Nhiều tác giả') ?></p>

                                <div class="flex items-center gap-1 text-[11px] mt-1.5">
                                    <span class="material-symbols-outlined text-[14px] text-amber-500" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="font-bold text-slate-800"><?= number_format($book['diem_sao'], 1) ?></span>
                                    <span class="text-slate-400">(<?= $book['so_danh_gia'] ?>)</span>
                                </div>

                                <div class="mt-2 flex items-baseline gap-2">
                                    <span class="text-sm font-bold text-primary"><?= format_currency($book['gia']) ?></span>
                                    <span class="text-[10px] text-slate-400 line-through"><?= format_currency($originalPrice) ?></span>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 mt-3 flex items-center gap-2">
                                <a href="<?= base_url('chi-tiet/' . $book['id']) ?>" class="flex-1 py-2 rounded-xl bg-primary text-white text-xs font-bold hover:bg-secondary transition-colors text-center shadow-sm">
                                    Xem Chi Tiết
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Phân Trang (Pagination) -->
                <?php if ($totalPages > 1): ?>
                    <div class="pt-8 flex items-center justify-center gap-2">
                        <?php if ($currentPage > 1): ?>
                            <a href="<?= buildFilterUrl(['page' => $currentPage - 1]) ?>" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-primary hover:border-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                            </a>
                        <?php endif; ?>

                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <a href="<?= buildFilterUrl(['page' => $p]) ?>" 
                               class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all <?= ($p === $currentPage) ? 'bg-primary text-white shadow-md' : 'bg-white border border-slate-200 text-slate-700 hover:border-secondary' ?>">
                                <?= $p ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="<?= buildFilterUrl(['page' => $currentPage + 1]) ?>" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-primary hover:border-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>
