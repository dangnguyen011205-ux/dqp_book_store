<?php
// Views/client/detail.php
use Core\Auth;

$fallbackImages = [
    've_di_tuoi_tho.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDOW-5R4g1lDrfnMUOBxJj5M7wo1WvsCQsDwqCLZmRbhk62RKvqRmJ6kw99wmW4BL3kUyyuaJxRgNogYU_x2QdDcnRW_LBtMGmp79xXaMbxkRZ4bHIZoMpwXWDCJ7Pvls8dvP-ELZwksZT5YPUqgfGgxK5gSFD6V1vymZXlpL8eIseOO0YSoWJWC2quEc3Mx-vk7ZBDIFgGPjRMOFdjSbsGjFvpQi9TUtOpuFtb0eIbMSr9xSJA0Ea9',
    'mat_biec.jpg'       => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBCROvi1laus0XIWz6LpZ0P5HeiGIVthmPumUmskuWEVVOZ4g16-h2hSTzztGGZhp3ySzUs39iPTECYlyKqk3F-sF8zIYzga0ECMPk62VcPgJZiTrO5COR64605DXYENgpvk0xshkGjXydPbKKpYp1KJtjEyRYtzNrgEuMKLNLAdTT3Y1yZ5ru24pASdUUyHkZJAzNbXtYnYMHk1V34UsiXk0_J45PIDn4wCNahTosqdLaFovyqa9WF',
    'rung_na_uy.jpg'     => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB39kchJl2airUEEKvNTfjBIF1Au5AuIqYbzVd3O5AW9bhoYX8DD4iKlOh7ckPeiaov9e_7QU4iNI2n92tjJh_BJjhKLBwOl7IIgnM9D9fSDPm4Ft8xl5JLIgpqX20cfC-EFsO7Gg9sJ9WC8QXTjK84zwWY6qL6U1sHgA3eWQM7ptpHB2-a3ZTY389Jrth4Nt0hWFrtBBG_TFFefN8Wwyq93ImOQ6t7IqyAi96zrwGpfRBw1u3VdP0y',
    'dac_nhan_tam.jpg'   => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAUKERVQmDszz25tjdhGJ60sosu-sOcfvaC1RZfWv7AStopNuMi_XSLesJg9-0UgVd6UOaAw8mRt8xsMUg1rDkrSaQkuodwGR9EXPkk53zp7mg9pwkeaeb0F9LNIJ5gJ1TBQuDW-cJfvykpacXFoIyxBTrFXz5TA3a4ta-5hPFPhjiZLpKWTbmWMI6XZ1QTYx6qOJ22BRb4-cYJH9RnzP5cqVKONtQ-P9twqUAbxN_XOjY6kABmewFk',
    'clean_code.jpg'     => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAILnt2jy0fXHs8-Kz6PipSF15EraoOkGE17r5yZ-6i-yk08UovomgdSCTxKnn8IWUWwh9AVb39DQl_f-glZfVZ2kcqLICo6mAbmRXCkIvLQIQLpRBBuPlK2DJgZwD_WCK0S1NWUZ_4h1PYjRF5Yh7aud_twOOz7URBV1F3Ex105YAIzve59PV2W46hmi_YoxqJblnmTpvO9riKDFLWNQJ7EktUtK2hAodfs1Oa-3fMR6ueCB3o61ci',
    'nha_gia_kim.jpg'    => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAZ6n_FWa_IMAKlR9ocIW0hh47Cy9-wPVvDcRq3T2Weupyyo2Ed6SVlnhOMZYz7bn3dW4n-3XOpGtOJsyCbrzkf5Ku2I2a-3jDK2KBjCaWmv83sglAAvoNLwtCPKBVuG294MYWI2qWcEmiBrgi9o_YzCBJ2LrNvIeNQEuSkueeI2sP269-pZefkGWuLfYEZ8xhmg0DX4j6-mHKtaeDaxF7Or-irm4NKSRPwXXR9r05ciXqLfLME6of1'
];

function getDetailBookCover(?string $anhBia, array $fallbackMap): string {
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

$coverUrl = getDetailBookCover($book['anh_bia'], $fallbackImages);
$originalPrice = (float)$book['gia'] * 1.30;
$savingAmount = $originalPrice - (float)$book['gia'];
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500">
        <a href="<?= base_url() ?>" class="hover:text-primary transition-colors">Trang Chủ</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <a href="<?= base_url('tim-kiem?the_loai=' . $book['the_loai_id']) ?>" class="hover:text-primary transition-colors">
            <?= htmlspecialchars($book['the_loai_ten'] ?? 'Sách Tuyển Chọn') ?>
        </a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-bold truncate max-w-sm"><?= htmlspecialchars($book['tieu_de']) ?></span>
    </nav>

    <!-- ================= 1. KHỐI GIỚI THIỆU SẢN PHẨM ================= -->
    <section class="bg-surface-container-lowest rounded-3xl p-6 md:p-10 shadow-sm border border-slate-100">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 items-start">
            
            <!-- Gallery Ảnh Bên Trái (4.5 cols) -->
            <div class="lg:col-span-5 flex flex-col items-center">
                <div class="relative w-full max-w-sm aspect-[2/3] rounded-2xl overflow-hidden shadow-2xl bg-slate-50 border border-slate-200/80 group">
                    <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($book['tieu_de']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                    <div class="absolute top-3 left-3 bg-secondary text-white text-[11px] font-bold px-3 py-1 rounded-full shadow">
                        100% Sách Thật Chuẩn NXB
                    </div>
                </div>

                <!-- Cam kết nhỏ dưới ảnh -->
                <div class="grid grid-cols-3 gap-2 w-full max-w-sm pt-4 text-center text-[11px] text-slate-500">
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center gap-1">
                        <span class="material-symbols-outlined text-secondary text-[18px]">verified</span>
                        <span>Bản quyền NXB</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center gap-1">
                        <span class="material-symbols-outlined text-secondary text-[18px]">local_shipping</span>
                        <span>Freeship từ 250k</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center gap-1">
                        <span class="material-symbols-outlined text-secondary text-[18px]">sync</span>
                        <span>Đổi trả 7 ngày</span>
                    </div>
                </div>
            </div>

            <!-- Thông Tin Sách Bên Phải (7.5 cols) -->
            <div class="lg:col-span-7 space-y-5">
                
                <!-- Category Tag & ISBN -->
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <a href="<?= base_url('tim-kiem?the_loai=' . $book['the_loai_id']) ?>" class="px-3 py-1 rounded-full text-xs font-bold bg-secondary/10 text-secondary hover:bg-secondary hover:text-white transition-colors">
                        <?= htmlspecialchars($book['the_loai_ten'] ?? 'Sách Hay') ?>
                    </a>
                    <span class="text-xs font-mono text-slate-400">Mã Sách: #DQP-BK-<?= str_pad($book['id'], 5, '0', STR_PAD_LEFT) ?></span>
                </div>

                <!-- Tiêu đề H1 -->
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-primary tracking-tight leading-snug">
                        <?= htmlspecialchars($book['tieu_de']) ?>
                    </h1>
                </div>

                <!-- Metadata Grid -->
                <div class="bg-surface-container-low p-4 rounded-2xl grid grid-cols-2 sm:grid-cols-3 gap-4 border border-slate-200/60 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Tác giả</span>
                        <span class="font-bold text-primary text-sm mt-0.5 block truncate">
                            <?= htmlspecialchars($book['tac_gia_ten'] ?? 'Đang cập nhật') ?>
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Nhà xuất bản</span>
                        <span class="font-bold text-primary text-sm mt-0.5 block truncate">
                            <?= htmlspecialchars($book['nxb_ten'] ?? 'NXB Tổng Hợp') ?>
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Năm xuất bản</span>
                        <span class="font-bold text-primary text-sm mt-0.5 block">
                            <?= htmlspecialchars($book['nam_xuat_ban'] ?? '2023') ?>
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Số trang</span>
                        <span class="font-bold text-primary text-sm mt-0.5 block">
                            <?= htmlspecialchars($book['so_trang'] ?? 'Đang cập nhật') ?> trang
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Tình trạng kho</span>
                        <span class="font-bold <?= $book['so_luong_ton'] > 0 ? 'text-emerald-700' : 'text-rose-600' ?> text-sm mt-0.5 block">
                            <?= $book['so_luong_ton'] > 0 ? 'Còn hàng (' . $book['so_luong_ton'] . ' cuốn)' : 'Tạm hết hàng' ?>
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Hình thức bìa</span>
                        <span class="font-bold text-primary text-sm mt-0.5 block">Bìa Mềm Tiêu Chuẩn</span>
                    </div>
                </div>

                <!-- Rating & Sales Status -->
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                        <span class="text-sm font-bold text-primary"><?= number_format($book['diem_sao'], 1) ?></span>
                        <div class="flex text-amber-500">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' <?= $s <= round($book['diem_sao']) ? 1 : 0 ?>;">star</span>
                            <?php endfor; ?>
                        </div>
                        <a href="#reviews-section" class="text-xs text-secondary font-semibold hover:underline ml-1">
                            (<?= $book['so_danh_gia'] ?> đánh giá)
                        </a>
                    </div>
                    <span class="text-xs text-slate-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-secondary text-[16px]">verified_user</span>
                        <span>Đã lưu hành tại hệ thống</span>
                    </span>
                </div>

                <!-- Price Box & Form Đặt Hàng -->
                <div class="p-6 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-5">
                    <div class="flex items-baseline gap-3 flex-wrap">
                        <span class="text-3xl font-bold text-rose-600 leading-none">
                            <?= format_currency($book['gia']) ?>
                        </span>
                        <span class="text-sm text-slate-400 line-through">
                            <?= format_currency($originalPrice) ?>
                        </span>
                        <span class="px-2.5 py-0.5 bg-rose-100 text-rose-700 text-xs font-bold rounded-lg">
                            Tiết kiệm <?= format_currency($savingAmount) ?>
                        </span>
                    </div>

                    <!-- Form Thêm Giỏ Hàng & Mua Ngay -->
                    <form action="<?= base_url('gio-hang/them') ?>" method="POST" class="space-y-4">
                        <?= csrf_field() ?>
                        <input type="hidden" name="sach_id" value="<?= $book['id'] ?>"/>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                            <!-- Bộ tăng giảm số lượng -->
                            <div class="flex items-center bg-white rounded-xl p-1 border border-slate-200 self-start sm:self-auto">
                                <span class="text-xs font-bold text-slate-500 px-3 hidden md:inline">Số lượng:</span>
                                <button type="button" onclick="updateDetailQty(-1)" class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-primary hover:bg-slate-200 font-bold transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">remove</span>
                                </button>
                                <input type="number" id="detail-qty" name="so_luong" value="1" min="1" max="<?= max(1, $book['so_luong_ton']) ?>" readonly
                                       class="w-12 text-center bg-transparent font-bold text-sm text-primary focus:outline-none"/>
                                <button type="button" onclick="updateDetailQty(1)" class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-primary hover:bg-slate-200 font-bold transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">add</span>
                                </button>
                            </div>

                            <!-- Buttons Action -->
                            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <button type="submit" name="action" value="add_cart" <?= $book['so_luong_ton'] <= 0 ? 'disabled' : '' ?>
                                        class="w-full py-3 px-4 bg-white text-primary border border-primary hover:bg-primary hover:text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                                    <span>Thêm Vào Giỏ</span>
                                </button>
                                <button type="submit" name="action" value="buy_now" <?= $book['so_luong_ton'] <= 0 ? 'disabled' : '' ?>
                                        class="w-full py-3 px-4 bg-secondary text-white hover:bg-secondary-container hover:text-on-secondary-container rounded-xl text-xs font-bold flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="material-symbols-outlined text-[18px]">bolt</span>
                                    <span>Mua Ngay</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= 2. HỆ THỐNG 3 TABS THÔNG TIN CHI TIẾT ================= -->
    <section class="bg-surface-container-lowest rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Tab Headers -->
        <div class="flex items-center bg-slate-50 px-6 pt-3 gap-2 border-b border-slate-200/80 overflow-x-auto">
            <button onclick="switchDetailTab(1)" id="tab-btn-1" class="tab-header-btn px-6 py-3.5 text-xs font-bold rounded-t-2xl bg-white text-primary flex items-center gap-2 shadow-xs transition-all border-t border-x border-slate-200">
                <span class="material-symbols-outlined text-[18px] text-secondary">menu_book</span>
                <span>Mô Tả Nội Dung Sách</span>
            </button>
            <button onclick="switchDetailTab(2)" id="tab-btn-2" class="tab-header-btn px-6 py-3.5 text-xs font-semibold rounded-t-2xl text-slate-500 hover:text-primary transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">person</span>
                <span>Thông Tin Tác Giả</span>
            </button>
            <button onclick="switchDetailTab(3)" id="tab-btn-3" class="tab-header-btn px-6 py-3.5 text-xs font-semibold rounded-t-2xl text-slate-500 hover:text-primary transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">info</span>
                <span>Thông Số Kỹ Thuật</span>
            </button>
        </div>

        <!-- Tab Contents -->
        <div class="p-6 md:p-8">
            <!-- Tab 1: Mô tả sách -->
            <div id="tab-content-1" class="tab-pane space-y-4 text-xs md:text-sm text-slate-700 leading-relaxed">
                <h3 class="text-base font-bold text-primary">Giới Thiệu Tác Phẩm</h3>
                <div class="prose max-w-none space-y-3">
                    <p><?= nl2br(htmlspecialchars($book['mo_ta'] ?? 'Tác phẩm đang được cập nhật thông tin mô tả chi tiết từ ban biên tập DQP Book Store.')) ?></p>
                </div>
            </div>

            <!-- Tab 2: Thông tin tác giả -->
            <div id="tab-content-2" class="tab-pane hidden space-y-6">
                <h3 class="text-base font-bold text-primary">Tiểu Sử Tác Giả</h3>
                <?php if (!empty($book['tac_gia_list'])): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($book['tac_gia_list'] as $author): ?>
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-lg">
                                        <?= strtoupper(mb_substr($author['ten'], 0, 1, 'UTF-8')) ?>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-primary"><?= htmlspecialchars($author['ten']) ?></h4>
                                        <span class="text-[11px] text-secondary font-semibold">Tác giả văn học</span>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    <?= nl2br(htmlspecialchars($author['tieu_su'] ?? 'Thông tin tác giả đang được cập nhật.')) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-slate-500">Đang cập nhật tiểu sử tác giả.</p>
                <?php endif; ?>
            </div>

            <!-- Tab 3: Thông số kỹ thuật -->
            <div id="tab-content-3" class="tab-pane hidden">
                <h3 class="text-base font-bold text-primary mb-4">Bảng Thông Số Kỹ Thuật</h3>
                <div class="max-w-2xl divide-y divide-slate-100 text-xs">
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-400">Mã ISBN-13</span>
                        <span class="font-bold text-primary font-mono">978-604-1-<?= str_pad($book['id'], 5, '0', STR_PAD_LEFT) ?>-X</span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-400">Nhà Xuất Bản</span>
                        <span class="font-bold text-primary"><?= htmlspecialchars($book['nxb_ten'] ?? 'NXB Trẻ') ?></span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-400">Năm Xuất Bản</span>
                        <span class="font-bold text-primary"><?= htmlspecialchars($book['nam_xuat_ban'] ?? '2023') ?></span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-400">Số Trang</span>
                        <span class="font-bold text-primary"><?= htmlspecialchars($book['so_trang'] ?? '320') ?> trang</span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-400">Kích Thước Bao Bì</span>
                        <span class="font-bold text-primary">13 x 20.5 cm</span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-400">Trọng Lượng Ước Tính</span>
                        <span class="font-bold text-primary">380 gram</span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-400">Ngôn Ngữ</span>
                        <span class="font-bold text-primary">Tiếng Việt (Bản dịch chuẩn quyền tác giả)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= 3. KHỐI ĐÁNH GIÁ & NHẬN XÉT CỦA ĐỘC GIẢ ================= -->
    <section class="bg-surface-container-lowest rounded-3xl p-6 md:p-10 shadow-sm border border-slate-100 space-y-8" id="reviews-section">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <h2 class="text-xl font-bold text-primary tracking-tight">Đánh Giá & Nhận Xét Độc Giả</h2>
                <p class="text-xs text-slate-500 mt-1">Các ý kiến nhận xét chân thực từ những độc giả đã mua và thưởng thức tác phẩm.</p>
            </div>
            <div class="flex items-center gap-1.5 text-xs font-bold text-secondary">
                <span class="material-symbols-outlined text-[18px]">verified</span>
                <span>Kiểm Duyệt Minh Bạch</span>
            </div>
        </div>

        <!-- Thống Kê Rating Đa Chiều -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center bg-slate-50 p-6 rounded-2xl border border-slate-200/60">
            <!-- Cột Điểm Trung Bình -->
            <div class="md:col-span-4 text-center md:border-r border-slate-200/80 pr-4 space-y-1">
                <span class="text-4xl md:text-5xl font-bold text-primary leading-none"><?= number_format($ratingSummary['average'], 1) ?></span>
                <span class="text-slate-400 text-sm">/ 5.0</span>
                <div class="flex justify-center text-amber-500 pt-1">
                    <?php for ($s = 1; $s <= 5; $s++): ?>
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' <?= $s <= round($ratingSummary['average']) ? 1 : 0 ?>;">star</span>
                    <?php endfor; ?>
                </div>
                <p class="text-xs text-slate-500 pt-1"><?= $ratingSummary['total'] ?> lượt đánh giá được phê duyệt</p>
            </div>

            <!-- Cột Thanh Tỷ Lệ 1 - 5 Sao -->
            <div class="md:col-span-8 space-y-2">
                <?php for ($star = 5; $star >= 1; $star--): 
                    $pct = $ratingSummary['percentages'][$star] ?? 0;
                    $cnt = $ratingSummary['stars'][$star] ?? 0;
                ?>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="w-12 text-slate-600 font-semibold flex items-center gap-1">
                            <?= $star ?> <span class="material-symbols-outlined text-[14px] text-amber-500">star</span>
                        </span>
                        <div class="flex-1 h-2 rounded-full bg-slate-200 overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full" style="width: <?= $pct ?>%;"></div>
                        </div>
                        <span class="w-12 text-right text-slate-400 font-mono"><?= $cnt ?> (<?= $pct ?>%)</span>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Form Gửi Đánh Giá Của Bạn -->
        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-4">
            <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">rate_review</span>
                Gửi Nhận Xét Của Bạn Về Cuốn Sách
            </h3>

            <?php if ($canReview): ?>
                <form action="<?= base_url('danh-gia/gui') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="sach_id" value="<?= $book['id'] ?>"/>

                    <!-- Chọn sao -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Đánh giá điểm số của bạn:</label>
                        <div class="flex items-center gap-2" id="star-picker">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label class="cursor-pointer text-amber-500 hover:scale-110 transition-transform">
                                    <input type="radio" name="so_sao" value="<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?> class="sr-only"/>
                                    <span class="material-symbols-outlined text-[28px] star-icon" data-value="<?= $i ?>" style="font-variation-settings: 'FILL' 1;">star</span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Nội dung nhận xét -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Cảm nhận chi tiết (Nội dung, bài học, chất lượng in ấn...):</label>
                        <textarea name="noi_dung" rows="3" required placeholder="Chia sẻ suy nghĩ của bạn để cộng đồng độc giả cùng tham khảo..."
                                  class="w-full p-3 bg-white rounded-xl text-xs border border-slate-200 focus:outline-none focus:ring-2 focus:ring-secondary"></textarea>
                    </div>

                    <button type="submit" class="px-6 py-2.5 bg-secondary text-white rounded-xl text-xs font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-all shadow">
                        Gửi Đánh Giá (Chờ Phê Duyệt)
                    </button>
                </form>
            <?php else: ?>
                <div class="p-4 rounded-xl bg-white border border-slate-200 text-xs text-slate-600 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-600 text-[20px]">info</span>
                        <span><?= htmlspecialchars($reviewBlockReason) ?></span>
                    </div>
                    <?php if (!Auth::check()): ?>
                        <a href="<?= base_url('dang-nhap') ?>" class="px-4 py-2 bg-primary text-white rounded-lg font-bold hover:bg-secondary transition-colors shrink-0">
                            Đăng Nhập Ngay
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Danh Sách Đánh Giá Hiện Có -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-primary">Các Nhận Xét Đã Được Duyệt (<?= count($reviews) ?>)</h3>

            <?php if (empty($reviews)): ?>
                <p class="text-xs text-slate-500 italic py-4">Chưa có đánh giá nào cho cuốn sách này. Hãy là người đầu tiên mua và để lại cảm nhận!</p>
            <?php else: ?>
                <div class="divide-y divide-slate-100">
                    <?php foreach ($reviews as $rev): ?>
                        <div class="py-5 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-xs">
                                        <?= strtoupper(mb_substr($rev['ho_ten'], 0, 1, 'UTF-8')) ?>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-primary"><?= htmlspecialchars($rev['ho_ten']) ?></span>
                                            <span class="inline-flex items-center gap-0.5 text-[10px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.2 rounded-full">
                                                <span class="material-symbols-outlined text-[12px]">verified</span> Đã mua tại DQP Book
                                            </span>
                                        </div>
                                        <span class="text-[10px] text-slate-400"><?= date('d/m/Y H:i', strtotime($rev['ngay_tao'])) ?></span>
                                    </div>
                                </div>

                                <!-- Sao -->
                                <div class="flex text-amber-500">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' <?= $s <= $rev['so_sao'] ? 1 : 0 ?>;">star</span>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <p class="text-xs text-slate-700 leading-relaxed pl-10">
                                <?= nl2br(htmlspecialchars($rev['noi_dung'])) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ================= 4. SÁCH CÙNG THỂ LOẠI ================= -->
    <?php if (!empty($relatedBooks)): ?>
        <section class="space-y-6 pt-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-secondary uppercase tracking-wider">Cùng Chủ Đề</span>
                    <h2 class="text-xl font-bold text-primary tracking-tight mt-1">Các Tác Phẩm Liên Quan</h2>
                </div>
                <a href="<?= base_url('tim-kiem?the_loai=' . $book['the_loai_id']) ?>" class="text-xs font-bold text-secondary hover:text-primary transition-colors flex items-center gap-1">
                    <span>Xem thêm</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <?php foreach ($relatedBooks as $rb): 
                    $rbCover = getDetailBookCover($rb['anh_bia'], $fallbackImages);
                ?>
                    <div class="bg-surface-container-lowest rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between group border border-slate-100">
                        <div>
                            <div class="relative aspect-[2/3] rounded-xl overflow-hidden bg-slate-100 mb-3">
                                <img src="<?= $rbCover ?>" alt="<?= htmlspecialchars($rb['tieu_de']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform"/>
                            </div>
                            <span class="text-[10px] font-bold text-secondary uppercase tracking-wider block truncate"><?= htmlspecialchars($rb['the_loai_ten'] ?? 'Sách Hay') ?></span>
                            <h3 class="text-xs font-bold text-primary line-clamp-2 mt-1 group-hover:text-secondary transition-colors">
                                <a href="<?= base_url('chi-tiet/' . $rb['id']) ?>"><?= htmlspecialchars($rb['tieu_de']) ?></a>
                            </h3>
                            <p class="text-[11px] text-slate-500 truncate mt-0.5"><?= htmlspecialchars($rb['tac_gia_ten'] ?? 'Nhiều tác giả') ?></p>
                            <span class="text-xs font-bold text-primary mt-2 block"><?= format_currency($rb['gia']) ?></span>
                        </div>

                        <div class="pt-3 border-t border-slate-100 mt-3">
                            <a href="<?= base_url('chi-tiet/' . $rb['id']) ?>" class="w-full py-2 rounded-xl bg-primary text-white text-xs font-bold hover:bg-secondary transition-colors text-center block shadow-sm">
                                Xem Sách
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<!-- JavaScript Cho Tabs & Quantity -->
<script>
function updateDetailQty(delta) {
    const input = document.getElementById('detail-qty');
    let current = parseInt(input.value) || 1;
    let min = parseInt(input.min) || 1;
    let max = parseInt(input.max) || 999;
    let next = current + delta;
    if (next >= min && next <= max) {
        input.value = next;
    }
}

function switchDetailTab(tabNum) {
    // Ẩn tất cả panes
    document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
    // Bỏ active headers
    document.querySelectorAll('.tab-header-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'text-primary', 'border-t', 'border-x', 'border-slate-200');
        btn.classList.add('text-slate-500');
    });

    // Bật tab được chọn
    const activePane = document.getElementById('tab-content-' + tabNum);
    const activeBtn = document.getElementById('tab-btn-' + tabNum);
    if (activePane) activePane.classList.remove('hidden');
    if (activeBtn) {
        activeBtn.classList.remove('text-slate-500');
        activeBtn.classList.add('bg-white', 'text-primary', 'border-t', 'border-x', 'border-slate-200');
    }
}
</script>
