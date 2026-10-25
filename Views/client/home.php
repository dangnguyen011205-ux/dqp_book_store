<?php
// Views/client/home.php
use Core\Auth;

// Helper chọn ảnh bìa (fallback nếu file local chưa có)
$fallbackImages = [
    've_di_tuoi_tho.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDOW-5R4g1lDrfnMUOBxJj5M7wo1WvsCQsDwqCLZmRbhk62RKvqRmJ6kw99wmW4BL3kUyyuaJxRgNogYU_x2QdDcnRW_LBtMGmp79xXaMbxkRZ4bHIZoMpwXWDCJ7Pvls8dvP-ELZwksZT5YPUqgfGgxK5gSFD6V1vymZXlpL8eIseOO0YSoWJWC2quEc3Mx-vk7ZBDIFgGPjRMOFdjSbsGjFvpQi9TUtOpuFtb0eIbMSr9xSJA0Ea9',
    'mat_biec.jpg'       => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBCROvi1laus0XIWz6LpZ0P5HeiGIVthmPumUmskuWEVVOZ4g16-h2hSTzztGGZhp3ySzUs39iPTECYlyKqk3F-sF8zIYzga0ECMPk62VcPgJZiTrO5COR64605DXYENgpvk0xshkGjXydPbKKpYp1KJtjEyRYtzNrgEuMKLNLAdTT3Y1yZ5ru24pASdUUyHkZJAzNbXtYnYMHk1V34UsiXk0_J45PIDn4wCNahTosqdLaFovyqa9WF',
    'rung_na_uy.jpg'     => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB39kchJl2airUEEKvNTfjBIF1Au5AuIqYbzVd3O5AW9bhoYX8DD4iKlOh7ckPeiaov9e_7QU4iNI2n92tjJh_BJjhKLBwOl7IIgnM9D9fSDPm4Ft8xl5JLIgpqX20cfC-EFsO7Gg9sJ9WC8QXTjK84zwWY6qL6U1sHgA3eWQM7ptpHB2-a3ZTY389Jrth4Nt0hWFrtBBG_TFFefN8Wwyq93ImOQ6t7IqyAi96zrwGpfRBw1u3VdP0y',
    'dac_nhan_tam.jpg'   => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAUKERVQmDszz25tjdhGJ60sosu-sOcfvaC1RZfWv7AStopNuMi_XSLesJg9-0UgVd6UOaAw8mRt8xsMUg1rDkrSaQkuodwGR9EXPkk53zp7mg9pwkeaeb0F9LNIJ5gJ1TBQuDW-cJfvykpacXFoIyxBTrFXz5TA3a4ta-5hPFPhjiZLpKWTbmWMI6XZ1QTYx6qOJ22BRb4-cYJH9RnzP5cqVKONtQ-P9twqUAbxN_XOjY6kABmewFk',
    'clean_code.jpg'     => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAILnt2jy0fXHs8-Kz6PipSF15EraoOkGE17r5yZ-6i-yk08UovomgdSCTxKnn8IWUWwh9AVb39DQl_f-glZfVZ2kcqLICo6mAbmRXCkIvLQIQLpRBBuPlK2DJgZwD_WCK0S1NWUZ_4h1PYjRF5Yh7aud_twOOz7URBV1F3Ex105YAIzve59PV2W46hmi_YoxqJblnmTpvO9riKDFLWNQJ7EktUtK2hAodfs1Oa-3fMR6ueCB3o61ci',
    'nha_gia_kim.jpg'    => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAZ6n_FWa_IMAKlR9ocIW0hh47Cy9-wPVvDcRq3T2Weupyyo2Ed6SVlnhOMZYz7bn3dW4n-3XOpGtOJsyCbrzkf5Ku2I2a-3jDK2KBjCaWmv83sglAAvoNLwtCPKBVuG294MYWI2qWcEmiBrgi9o_YzCBJ2LrNvIeNQEuSkueeI2sP269-pZefkGWuLfYEZ8xhmg0DX4j6-mHKtaeDaxF7Or-irm4NKSRPwXXR9r05ciXqLfLME6of1'
];

function getBookCover(?string $anhBia, array $fallbackMap): string {
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

<div class="flex flex-col w-full space-y-12">

    <!-- ================= SECTION 1: HERO & SIDE BANNERS MOSAIC ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            
            <!-- Main Hero Showcase (8 cols) -->
            <div class="lg:col-span-8 relative bg-gradient-to-br from-primary-container via-[#133352] to-primary rounded-2xl overflow-hidden p-8 md:p-12 flex flex-col justify-between shadow-2xl min-h-[460px]">
                <div class="absolute -right-24 -top-24 w-96 h-96 rounded-full bg-secondary/15 blur-3xl pointer-events-none"></div>
                <div class="absolute left-1/3 bottom-0 w-80 h-40 bg-secondary-container/10 blur-2xl pointer-events-none"></div>

                <div class="relative z-10 space-y-4 max-w-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-secondary/20 text-secondary-fixed text-xs font-semibold backdrop-blur-md">
                        <span class="material-symbols-outlined text-[16px] animate-pulse">local_fire_department</span>
                        <span>HỘI SÁCH MÙA THU 2026 • ĐỘC QUYỀN DQP BOOK</span>
                    </div>

                    <h1 class="text-3xl md:text-5xl font-bold leading-tight text-white tracking-tight">
                        Khai Mở Trí Tuệ <br/>
                        <span class="text-secondary-fixed font-serif italic">Tủ Sách Tư Duy & Kinh Doanh</span>
                    </h1>

                    <p class="text-xs md:text-sm text-on-primary-container leading-relaxed">
                        Ưu đãi đặc biệt giảm đến 40% cho hơn 1.500 tựa sách quản trị, đổi mới sáng tạo và tâm lý học ứng dụng từ các nhà xuất bản danh tiếng thế giới.
                    </p>
                </div>

                <div class="relative z-10 pt-6 flex flex-wrap items-center gap-4">
                    <a href="<?= base_url('tim-kiem') ?>" class="px-6 py-3 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary-fixed hover:text-primary shadow-lg transition-all flex items-center gap-2">
                        <span>Khám phá ngay</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl text-white text-xs font-medium border border-white/10">
                        <span class="material-symbols-outlined text-secondary-fixed text-[18px]">schedule</span>
                        <span class="text-on-primary-container">Kết thúc sau:</span>
                        <span class="font-bold text-secondary-fixed text-sm" id="hero-countdown">36:14:08</span>
                    </div>
                </div>

                <!-- 3D Render Floating Book Cover Visual -->
                <div class="hidden md:block absolute right-8 bottom-8 w-64 lg:w-72 aspect-[2/3] rounded-xl shadow-2xl overflow-hidden transform rotate-6 hover:rotate-0 transition-transform duration-500 border border-white/20">
                    <img class="w-full h-full object-cover" 
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuAUz2h5NbRRpQxcVtF5NPJD_iS9y0RXn5uwAPV0D9DJcKseQpT7V-mhEMQrFOtRq9VGbSv5umrMiFHWzcQoyNiuSN0Q5GWTjaPPf7tDrnd0UM41X0iM8jiG6rSV-Qso0bjWxZZwKU3ZW-CMRmhOd2jEnf0IIVCt_vBGUR1D7PQcAHq6Wf1_lJqQj7u5F1ZhpKyX1w15CdFG0PsLVH263eNelWhvV99odJk1qBi7NxYEbClVt4p3Z0vQ" 
                         alt="Ấn bản đặc biệt giới hạn"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-transparent to-transparent flex items-end p-4">
                        <span class="text-xs font-bold text-secondary-fixed">Ấn bản đặc biệt giới hạn</span>
                    </div>
                </div>
            </div>

            <!-- Secondary Curated Trio Banners (4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-4 justify-between">
                <!-- Banner 1: Nobel Laureates -->
                <a href="<?= base_url('tim-kiem?q=Murakami') ?>" class="group relative flex-1 p-4 rounded-2xl bg-surface-container-low hover:bg-white transition-all shadow-sm flex items-center gap-4 overflow-hidden border border-slate-200/60 hover:shadow-md">
                    <div class="w-20 h-24 rounded-lg overflow-hidden flex-shrink-0 shadow">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform" 
                             src="https://lh3.googleusercontent.com/aida-public/AB6AXuAgRKjm_mmwi9A-zc56eCtTttVAIcnteuY90VR0sKXs14XHeMft5jYhr76UVrjNUZki-TnG2I1Fkg81V5q934Vca_cPVZgyTwaxtxT0TzhYn_9guBT5w6ZeROOsekYsw5pyrG_OCZbDHAbdEugKVKNXehQ4mOSvGjtl-fuTPQcHXKt08duwxFzwUj9kWb_5qi2eiZ7p7rNCTM1UOy-qLlPEjfj44u49H9pGUC4Thms2XLnUP0X_8qdG" alt="Nobel Laureates"/>
                    </div>
                    <div class="flex-1 space-y-1">
                        <span class="text-[10px] font-bold text-secondary uppercase tracking-wider">Tác Phẩm Danh Giá</span>
                        <h3 class="text-sm font-bold text-primary group-hover:text-secondary transition-colors">Tác Giả Đoạt Giải Nobel</h3>
                        <p class="text-[11px] text-slate-500 line-clamp-1">Văn chương kinh điển định hình thời đại</p>
                        <span class="text-xs font-semibold text-primary flex items-center gap-1 pt-0.5">Khám phá <span class="material-symbols-outlined text-[14px]">chevron_right</span></span>
                    </div>
                </a>

                <!-- Banner 2: Sách Thiếu Nhi -->
                <a href="<?= base_url('tim-kiem?the_loai=5') ?>" class="group relative flex-1 p-4 rounded-2xl bg-surface-container-low hover:bg-white transition-all shadow-sm flex items-center gap-4 overflow-hidden border border-slate-200/60 hover:shadow-md">
                    <div class="w-20 h-24 rounded-lg overflow-hidden flex-shrink-0 shadow">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform" 
                             src="https://lh3.googleusercontent.com/aida-public/AB6AXuAQ9jzYB6_IbflXM6hoZ8xvArYfmsGRN_vK-kwY62KHqxxCTnH5w0Puamag8dNAXW2yztD69xe819tPexycjrY8HaVbMia13h2q_P_HnrU3CQWEV7Bj6fMEFjOaQOSVV6MdKN8C1qQwfWXFm7wlXvIcps5MpQ5xpWANfv8f63rQWi6kI-aipq1e6YyEZ0OXBmJXWQ8yIGyy7Cl19Bt7g34mo8ymb_TjOo6vWKr2r-_27e41_bx76cY9" alt="Sách Thiếu Nhi"/>
                    </div>
                    <div class="flex-1 space-y-1">
                        <span class="text-[10px] font-bold text-secondary uppercase tracking-wider">Ươm Mầm Tri Thức</span>
                        <h3 class="text-sm font-bold text-primary group-hover:text-secondary transition-colors">Sách Thiếu Nhi Tuyển Chọn</h3>
                        <p class="text-[11px] text-slate-500 line-clamp-1">Hình ảnh sinh động, nuôi dưỡng tâm hồn</p>
                        <span class="text-xs font-semibold text-primary flex items-center gap-1 pt-0.5">Xem ngay <span class="material-symbols-outlined text-[14px]">chevron_right</span></span>
                    </div>
                </a>

                <!-- Banner 3: Chữa Lành -->
                <a href="<?= base_url('tim-kiem?the_loai=3') ?>" class="group relative flex-1 p-4 rounded-2xl bg-surface-container-low hover:bg-white transition-all shadow-sm flex items-center gap-4 overflow-hidden border border-slate-200/60 hover:shadow-md">
                    <div class="w-20 h-24 rounded-lg overflow-hidden flex-shrink-0 shadow">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform" 
                             src="https://lh3.googleusercontent.com/aida-public/AB6AXuCWXGsYJtd7B2gtpJOQo4fubk1DQFyLr39Q_vzDjqncr6wwUPu00ZmKUa5NOf32XH5QC5StDJjqxwg4d0Eqo9pc8UaOJPCspFdL-VZdkdxavYyTYY4QJ4UbLDKsFTcJ1mOxk3mrPqFNUeLso0eMQWnnpcQm188olJaLxLv9bJZwBUvy9o61SjZHAGSiPNNzuCITjeddOIQssYdB9SoB53eG7yQAootlX4hVLgbE178vOKH-dkJAvqQ5" alt="Chữa Lành"/>
                    </div>
                    <div class="flex-1 space-y-1">
                        <span class="text-[10px] font-bold text-secondary uppercase tracking-wider">Chữa Lành & Tỉnh Thức</span>
                        <h3 class="text-sm font-bold text-primary group-hover:text-secondary transition-colors">Combo Xoa Dịu Tâm Hồn</h3>
                        <p class="text-[11px] text-slate-500 line-clamp-1">Món quà an yên cho chuỗi ngày bận rộn</p>
                        <span class="text-xs font-semibold text-primary flex items-center gap-1 pt-0.5">Tìm hiểu <span class="material-symbols-outlined text-[14px]">chevron_right</span></span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- ================= SECTION 2: TRUST & VALUE PROPOSITIONS ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-slate-100 grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary shrink-0">
                    <span class="material-symbols-outlined text-[26px]">verified</span>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-primary leading-tight">100% Sách Thật</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Chuẩn bản quyền NXB</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary shrink-0">
                    <span class="material-symbols-outlined text-[26px]">inventory_2</span>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-primary leading-tight">Đóng Gói 2 Lớp</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Màng co và hộp chống sốc</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary shrink-0">
                    <span class="material-symbols-outlined text-[26px]">rocket_launch</span>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-primary leading-tight">Giao Hỏa Tốc 2H</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Nội thành TP.HCM & HN</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary shrink-0">
                    <span class="material-symbols-outlined text-[26px]">sync_saved_locally</span>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-primary leading-tight">Đổi Trả 7 Ngày</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Miễn phí nếu lỗi in bao bì</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= SECTION 3: FEATURED CATEGORIES ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex items-end justify-between mb-6">
            <div>
                <span class="text-xs font-bold text-secondary uppercase tracking-wider">Hệ Thống Phân Loại Độc Bản</span>
                <h2 class="text-xl md:text-2xl font-bold text-primary tracking-tight mt-1">Danh Mục Sách Nổi Bật</h2>
            </div>
            <a href="<?= base_url('tim-kiem') ?>" class="hidden sm:flex items-center gap-1 text-xs font-bold text-secondary hover:text-primary transition-colors">
                <span>Xem tất cả thể loại</span>
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <?php 
            $icons = ['auto_stories', 'trending_up', 'psychology', 'biotech', 'child_care'];
            foreach ($categories as $idx => $cat): 
                $icon = $icons[$idx % count($icons)];
            ?>
                <a href="<?= base_url('tim-kiem?the_loai=' . $cat['id']) ?>" 
                   class="group bg-surface-container-lowest p-5 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all flex flex-col items-center text-center border border-slate-100">
                    <div class="w-14 h-14 rounded-full bg-secondary/10 flex items-center justify-center text-secondary mb-3 group-hover:bg-secondary group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-[26px]"><?= $icon ?></span>
                    </div>
                    <h3 class="text-xs font-bold text-primary group-hover:text-secondary transition-colors line-clamp-1"><?= htmlspecialchars($cat['ten']) ?></h3>
                    <span class="text-[11px] text-slate-400 mt-1"><?= $cat['tong_sach'] ?? 0 ?> tựa sách</span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ================= SECTION 4: FLASH SALE GIỜ VÀNG ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="bg-gradient-to-r from-primary-container via-[#163654] to-primary-container rounded-3xl p-6 md:p-8 text-white shadow-xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-600 flex items-center justify-center text-white shadow-lg animate-bounce">
                        <span class="material-symbols-outlined text-[26px]">bolt</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl md:text-2xl font-bold tracking-tight text-white">Sách Giờ Vàng - Flash Deals</h2>
                            <span class="px-2 py-0.5 rounded bg-rose-500 text-white text-[10px] font-bold uppercase">Trực Tiếp</span>
                        </div>
                        <p class="text-xs text-on-primary-container mt-0.5">Mức giá độc quyền có giới hạn số lượng dành cho độc giả trực tuyến</p>
                    </div>
                </div>

                <!-- Countdown Timer -->
                <div class="flex items-center gap-2 text-xs">
                    <span class="text-on-primary-container">Chỉ còn:</span>
                    <div class="flex items-center gap-1 font-bold">
                        <div class="px-2.5 py-1.5 rounded-lg bg-white text-primary text-sm shadow" id="timer-hours">03</div>
                        <span class="text-secondary-fixed">:</span>
                        <div class="px-2.5 py-1.5 rounded-lg bg-white text-primary text-sm shadow" id="timer-minutes">45</div>
                        <span class="text-secondary-fixed">:</span>
                        <div class="px-2.5 py-1.5 rounded-lg bg-white text-primary text-sm shadow" id="timer-seconds">12</div>
                    </div>
                </div>
            </div>

            <!-- Flash Sale Book Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($flashDeals as $book): 
                    $coverUrl = getBookCover($book['anh_bia'], $fallbackImages);
                    $originalPrice = (float)$book['gia'] * 1.35; // Giá niêm yết giả định cao hơn 35%
                ?>
                    <div class="bg-white rounded-2xl p-4 text-slate-800 shadow-md flex flex-col justify-between group hover:-translate-y-1.5 transition-all">
                        <div>
                            <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-slate-100 mb-3 shadow-inner">
                                <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($book['tieu_de']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform"/>
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-rose-600 text-white text-[10px] font-bold">-26%</span>
                                <span class="absolute top-2 right-2 px-2 py-0.5 rounded-md bg-white/90 text-primary text-[10px] font-bold"><?= htmlspecialchars($book['nxb_ten'] ?? 'NXB Trẻ') ?></span>
                            </div>

                            <div class="flex items-center gap-1 text-[11px] mb-1">
                                <span class="material-symbols-outlined text-[15px] text-amber-500" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="font-bold text-slate-800"><?= number_format($book['diem_sao'], 1) ?></span>
                                <span class="text-slate-400">(<?= $book['so_danh_gia'] ?>)</span>
                            </div>

                            <h3 class="text-xs font-bold text-primary line-clamp-1 group-hover:text-secondary transition-colors">
                                <a href="<?= base_url('chi-tiet/' . $book['id']) ?>"><?= htmlspecialchars($book['tieu_de']) ?></a>
                            </h3>
                            <p class="text-[11px] text-slate-500 truncate"><?= htmlspecialchars($book['tac_gia_ten'] ?? 'Nhiều tác giả') ?></p>

                            <div class="flex items-baseline gap-2 mt-2">
                                <span class="text-sm font-bold text-rose-600"><?= format_currency($book['gia']) ?></span>
                                <span class="text-[11px] text-slate-400 line-through"><?= format_currency($originalPrice) ?></span>
                            </div>
                        </div>

                        <!-- Progress & CTA -->
                        <div class="mt-4 space-y-1.5">
                            <div class="flex justify-between text-[11px] text-slate-500">
                                <span>Tồn kho: <strong class="text-primary"><?= $book['so_luong_ton'] ?> cuốn</strong></span>
                                <span class="text-emerald-600 font-semibold">Đang bán chạy</span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full bg-rose-500 rounded-full" style="width: 75%;"></div>
                            </div>
                            <a href="<?= base_url('chi-tiet/' . $book['id']) ?>" class="w-full mt-2 py-2.5 rounded-xl bg-secondary text-white text-xs font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">shopping_cart</span>
                                <span>Mua Ngay</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ================= SECTION 5: WEEKLY BESTSELLERS WITH TABS ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <span class="text-xs font-bold text-secondary uppercase tracking-wider">Lựa Chọn Hàng Đầu Của Độc Giả</span>
                <h2 class="text-xl md:text-2xl font-bold text-primary tracking-tight mt-1">Sách Bán Chạy Trong Tuần</h2>
            </div>

            <!-- Tab Thể Loại -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1" id="bestseller-tabs">
                <a href="<?= base_url('tim-kiem?sort=ban_chay') ?>" class="px-4 py-1.5 rounded-full bg-primary text-white text-xs font-bold shadow-sm whitespace-nowrap">Tất Cả</a>
                <?php foreach (array_slice($categories, 0, 4) as $cat): ?>
                    <a href="<?= base_url('tim-kiem?the_loai=' . $cat['id'] . '&sort=ban_chay') ?>" 
                       class="px-4 py-1.5 rounded-full bg-surface-container-low text-slate-600 hover:text-primary text-xs font-semibold whitespace-nowrap transition-colors">
                        <?= htmlspecialchars($cat['ten']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            <?php foreach ($bestsellers as $idx => $book): 
                $coverUrl = getBookCover($book['anh_bia'], $fallbackImages);
                $originalPrice = (float)$book['gia'] * 1.25;
            ?>
                <div class="bg-surface-container-lowest rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between group border border-slate-100">
                    <div>
                        <div class="relative aspect-[2/3] rounded-xl overflow-hidden bg-slate-100 mb-3">
                            <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($book['tieu_de']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform"/>
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-primary text-white text-[10px] font-bold">#<?= $idx + 1 ?> Bán Chạy</span>
                            <span class="absolute bottom-2 right-2 px-1.5 py-0.5 rounded-md bg-white/95 text-secondary text-[10px] font-bold shadow-sm">-20%</span>
                        </div>

                        <span class="text-[10px] font-bold text-secondary uppercase tracking-wider block"><?= htmlspecialchars($book['the_loai_ten'] ?? 'Sách Hay') ?></span>
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
                            <span class="text-xs font-bold text-primary"><?= format_currency($book['gia']) ?></span>
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
    </section>

    <!-- ================= SECTION 6: EDITORIAL CURATED SPOTLIGHT ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="bg-surface-container-low rounded-3xl p-8 md:p-12 border border-slate-200/60">
            <div class="flex flex-col lg:flex-row items-center gap-8 md:gap-12">
                <div class="w-full lg:w-5/12 flex justify-center">
                    <div class="relative w-64 md:w-80 aspect-[2/3] rounded-2xl overflow-hidden shadow-2xl group border-2 border-white">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZJQFD44vEJh38TiqqyfUBL3CF-fDdNQykjmZhwSAZHPwqKbCuW7pMEGDHigmV2m56FiIUNepAdBUrGtRYbBF5rspEd1QEuClNA4c2nY7gwOQ5momkXbOjaS5i1GQojnSLZEjQ0QWZgZkU_aeDUSX0ynPWAfnmN0594-8sbKRcvgXHK3s4fWK7Ka-2txijwGK2YV0Zea280tL5FG90XYDdUv-pMnN1KkIDzZUQfxZQ3JuFEXckAMUp" 
                             alt="Tuyển tập Đông Á" class="w-full h-full object-cover"/>
                        <div class="absolute top-4 left-4 bg-secondary text-white text-xs font-bold px-3 py-1 rounded-full shadow">
                            Phát Hành Độc Quyền
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-7/12 space-y-4">
                    <div class="inline-flex items-center gap-2 text-secondary text-xs font-bold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[18px]">menu_book</span>
                        <span>TUYỂN TẬP TINH HOA TỪ BAN BIÊN TẬP DQP BOOK</span>
                    </div>

                    <h2 class="text-2xl md:text-3xl font-bold text-primary leading-tight">
                        Văn Minh Đông Á & Hành Trình Chữ Viết
                    </h2>

                    <p class="text-xs md:text-sm text-slate-600 leading-relaxed italic">
                        "Không chỉ là cuốn khảo cứu công phu, tác phẩm đưa độc giả vào hành trình phục dựng từng đường nét ký ức qua hàng ngàn năm lịch sử chữ Hán - Nôm và văn hóa sách cổ. Một công trình xuất sắc mà mọi người yêu thư tịch học đều nên trân quý trên giá sách của mình."
                    </p>

                    <div class="p-4 bg-white rounded-2xl flex flex-wrap items-center justify-between gap-4 shadow-sm border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-secondary/15 flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined text-[22px]">workspace_premium</span>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-primary">GS. Đặng Hoàng Thư</p>
                                <p class="text-[11px] text-slate-500">Chủ biên & Hiệu đính bản dịch</p>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-lg font-bold text-secondary">385.000đ</span>
                            <span class="text-xs text-slate-400 line-through">450.000đ</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <a href="<?= base_url('tim-kiem') ?>" class="px-6 py-3 rounded-xl bg-primary text-white text-xs font-bold hover:bg-secondary transition-colors shadow">
                            Đặt Trước Ngay (Tặng Kèm Bookmark Đồng)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= SECTION 7: FEATURED AUTHORS ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider">Không Gian Vinh Danh</span>
            <h2 class="text-xl md:text-2xl font-bold text-primary tracking-tight mt-1">Tác Giả Tiêu Biểu Trong Tháng</h2>
        </div>

        <div class="bg-surface-container-lowest rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <!-- Author Portrait & Bio -->
            <div class="lg:col-span-5 flex flex-col sm:flex-row items-center gap-6">
                <div class="w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden shadow-lg shrink-0 border-4 border-white">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBtcLn9SY9PZ2fFHLlNs4aK_eSp2dNEmj_J2eQQ-Qv22ClQ3ihEDYkYJdzYHIrobGmx91Vr6jH2Wixcei8Oqtu6yr9NGDx3F1gGmAZS_ih3gfQ6L-3PMfOCoB5XK-4_9MRwDpTjxEBioWbyl7PpQVEtcYmLm587IDFeblum3qVT43P9xUSjYcGbXt7Zq1qiGaDNYeaK63-G-tTUjUsHWJFHIVdaj_gUJeCuZ1r8nEUUa7xiF_eEnQN4" 
                         alt="Nguyễn Nhật Ánh" class="w-full h-full object-cover"/>
                </div>
                <div class="space-y-1.5 text-center sm:text-left">
                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-secondary/10 text-secondary text-[10px] font-bold uppercase tracking-wider">
                        Tác Giả Tháng 10
                    </span>
                    <h3 class="text-lg font-bold text-primary">Nguyễn Nhật Ánh</h3>
                    <p class="text-xs text-secondary font-semibold">Nhà văn của tuổi thơ & ký ức trong trẻo</p>
                    <p class="text-[11px] text-slate-500 leading-relaxed pt-1">
                        Hơn 40 năm đồng hành cùng nhiều thế hệ bạn đọc Việt qua những trang văn hồn hậu, giàu chất thơ và đậm đà tình người.
                    </p>
                </div>
            </div>

            <!-- Author's Books -->
            <div class="lg:col-span-7">
                <div class="grid grid-cols-3 gap-4">
                    <a href="<?= base_url('chi-tiet/2') ?>" class="bg-slate-50 p-3 rounded-xl hover:shadow-md transition-all group text-center border border-slate-100">
                        <div class="aspect-[2/3] rounded-lg overflow-hidden mb-2 bg-slate-200 shadow-sm">
                            <img src="<?= $fallbackImages['mat_biec.jpg'] ?>" alt="Mắt Biếc" class="w-full h-full object-cover group-hover:scale-105 transition-transform"/>
                        </div>
                        <h4 class="text-xs font-bold text-primary line-clamp-1 group-hover:text-secondary">Mắt Biếc</h4>
                        <span class="text-xs font-bold text-secondary mt-1 block">110.000đ</span>
                    </a>

                    <a href="<?= base_url('chi-tiet/1') ?>" class="bg-slate-50 p-3 rounded-xl hover:shadow-md transition-all group text-center border border-slate-100">
                        <div class="aspect-[2/3] rounded-lg overflow-hidden mb-2 bg-slate-200 shadow-sm">
                            <img src="<?= $fallbackImages['ve_di_tuoi_tho.jpg'] ?>" alt="Cho Tôi Xin Một Vé" class="w-full h-full object-cover group-hover:scale-105 transition-transform"/>
                        </div>
                        <h4 class="text-xs font-bold text-primary line-clamp-1 group-hover:text-secondary">Cho Tôi Xin Một Vé...</h4>
                        <span class="text-xs font-bold text-secondary mt-1 block">85.000đ</span>
                    </a>

                    <a href="<?= base_url('tim-kiem?tac_gia=1') ?>" class="bg-slate-50 p-3 rounded-xl hover:shadow-md transition-all group text-center border border-slate-100 flex flex-col justify-center items-center">
                        <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary mb-2 group-hover:bg-secondary group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[24px]">arrow_forward</span>
                        </div>
                        <h4 class="text-xs font-bold text-primary group-hover:text-secondary">Xem Tất Cả</h4>
                        <span class="text-[10px] text-slate-400 mt-1 block">Tác phẩm khác</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= SECTION 8: READER TESTIMONIALS ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pb-8">
        <div class="flex items-end justify-between mb-6">
            <div>
                <span class="text-xs font-bold text-secondary uppercase tracking-wider">Cảm Nhận Độc Giả</span>
                <h2 class="text-xl md:text-2xl font-bold text-primary tracking-tight mt-1">Tiếng Nói Từ Cộng Đồng Yêu Sách</h2>
            </div>
            <div class="hidden sm:flex items-center gap-1.5 text-xs font-bold text-slate-700">
                <span class="material-symbols-outlined text-amber-500 text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                <span>4.95 / 5.0 (Hơn 14.800 đánh giá được kiểm duyệt)</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if (!empty($featuredReviews)): ?>
                <?php foreach ($featuredReviews as $rev): ?>
                    <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="flex text-amber-500 gap-0.5">
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' <?= $s <= $rev['so_sao'] ? 1 : 0 ?>;">star</span>
                                <?php endfor; ?>
                            </div>
                            <p class="text-xs text-slate-700 leading-relaxed italic">
                                "<?= htmlspecialchars($rev['noi_dung']) ?>"
                            </p>
                        </div>
                        <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                            <div class="w-9 h-9 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-xs shrink-0">
                                <?= strtoupper(mb_substr($rev['ho_ten'], 0, 1, 'UTF-8')) ?>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-primary truncate"><?= htmlspecialchars($rev['ho_ten']) ?></p>
                                <p class="text-[11px] text-slate-400 truncate">Về: <?= htmlspecialchars($rev['sach_tieu_de']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback testimonials -->
                <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex text-amber-500 gap-0.5">
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <p class="text-xs text-slate-700 leading-relaxed italic">
                            "Sách được đóng hộp carton cứng cáp 2 lớp, bốn góc sách bọc xốp cẩn thận không một vết cấn. Đặt hỏa tốc buổi sáng thì đầu giờ chiều đã có trên bàn làm việc."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                        <div class="w-9 h-9 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-xs">P</div>
                        <div>
                            <p class="text-xs font-bold text-primary">Trần Mai Phương</p>
                            <p class="text-[11px] text-slate-400">Độc giả thân thiết • TP.HCM</p>
                        </div>
                    </div>
                </div>

                <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex text-amber-500 gap-0.5">
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <p class="text-xs text-slate-700 leading-relaxed italic">
                            "Cực kỳ ấn tượng với cách tuyển chọn đầu sách kinh doanh của DQP Book. Không bị tràn lan sách phong trào, cuốn nào xuất hiện trên kệ cũng đều sâu sắc và có giá trị ứng dụng cao."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                        <div class="w-9 h-9 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-xs">D</div>
                        <div>
                            <p class="text-xs font-bold text-primary">Lê Hoàng Dũng</p>
                            <p class="text-[11px] text-slate-400">Giám đốc Điều hành • Hà Nội</p>
                        </div>
                    </div>
                </div>

                <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex text-amber-500 gap-0.5">
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <p class="text-xs text-slate-700 leading-relaxed italic">
                            "Bookmark tặng kèm bằng đồng chạm khắc hoa văn tinh xảo đến bất ngờ. Thẻ thành viên tích điểm rất nhanh, định kỳ lại có bản tin giới thiệu sách theo chủ đề rất tinh tế."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                        <div class="w-9 h-9 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-xs">N</div>
                        <div>
                            <p class="text-xs font-bold text-primary">Nguyễn Nhã Thư</p>
                            <p class="text-[11px] text-slate-400">Nhà viết kịch bản • Đà Nẵng</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>

<!-- Countdown Timer JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    let hours = 3;
    let minutes = 45;
    let seconds = 12;

    const hEl = document.getElementById('timer-hours');
    const mEl = document.getElementById('timer-minutes');
    const sEl = document.getElementById('timer-seconds');
    const heroCountdown = document.getElementById('hero-countdown');

    setInterval(() => {
        if (seconds > 0) {
            seconds--;
        } else {
            seconds = 59;
            if (minutes > 0) {
                minutes--;
            } else {
                minutes = 59;
                if (hours > 0) hours--;
            }
        }

        const hStr = String(hours).padStart(2, '0');
        const mStr = String(minutes).padStart(2, '0');
        const sStr = String(seconds).padStart(2, '0');

        if (hEl) hEl.textContent = hStr;
        if (mEl) mEl.textContent = mStr;
        if (sEl) sEl.textContent = sStr;
        if (heroCountdown) heroCountdown.textContent = `${hStr}:${mStr}:${sStr}`;
    }, 1000);
});
</script>
