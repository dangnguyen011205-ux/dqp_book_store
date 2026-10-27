<?php
// Views/admin/publishers/index.php
// Quản lý Nhà Xuất Bản - Athenaeum Modern v2.4
?>

<div class="px-6 py-5 max-w-[1440px] w-full mx-auto flex flex-col gap-5">
    <!-- 1. Header & Navigation Tabs -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="flex flex-col gap-1">
            <nav class="flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant">
                <a class="hover:text-primary transition-colors flex items-center gap-1" href="<?= base_url('admin') ?>">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    <span>Bảng điều khiển</span>
                </a>
                <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
                <span class="text-primary font-bold">Nhà Xuất Bản</span>
                <span class="ml-1.5 px-2 py-0.5 rounded-full bg-slate-100 text-[10px] font-bold text-slate-500">CSDL: nha_xuat_ban</span>
            </nav>
            <div class="flex items-baseline gap-2 mt-0.5">
                <h1 class="text-2xl lg:text-3xl font-extrabold text-primary tracking-tight">Quản Lý Nhà Xuất Bản</h1>
                <span class="hidden sm:inline-block px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[11px] font-bold">
                    Đối Tác Phát Hành • v2.4
                </span>
            </div>
            <p class="text-xs text-on-surface-variant">
                Quản lý các nhà xuất bản đối tác, thông tin liên hệ, địa chỉ trụ sở và số lượng đầu sách phát hành.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openAddPublisherModal()" class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm transition-all active:scale-95" type="button">
                <span class="material-symbols-outlined text-[18px]">add_business</span>
                <span>Thêm Nhà Xuất Bản Mới</span>
            </button>
            <a href="<?= base_url('admin/nha-xuat-ban') ?>" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-primary text-xs font-semibold shadow-sm border border-slate-200 transition-all active:scale-95" title="Làm mới">
                <span class="material-symbols-outlined text-[18px] text-amber-600">sync</span>
                <span>Làm Mới</span>
            </a>
        </div>
    </div>

    <!-- Metadata Sub-Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
        <a href="<?= base_url('admin/the-loai') ?>" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200 transition-colors">
            <span class="material-symbols-outlined text-[18px]">category</span>
            <span>Thể Loại Sách</span>
        </a>
        <a href="<?= base_url('admin/tac-gia') ?>" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200 transition-colors">
            <span class="material-symbols-outlined text-[18px]">person_edit</span>
            <span>Tác Giả</span>
        </a>
        <a href="<?= base_url('admin/nha-xuat-ban') ?>" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold shadow-sm">
            <span class="material-symbols-outlined text-[18px]">business</span>
            <span>Nhà Xuất Bản</span>
            <span class="px-1.5 py-0.5 rounded-full bg-white/20 text-[10px]"><?= $totalPublishers ?></span>
        </a>
    </div>

    <!-- 2. KPI Bento Cards (3 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Card 1: Tổng số NXB -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Tổng số NXB Đối Tác</span>
                <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[18px]">corporate_fare</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-primary"><?= number_format($totalPublishers) ?></span>
                <span class="text-xs text-secondary font-bold">nhà xuất bản</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Hợp tác:</span>
                <span class="font-bold text-emerald-700">100% bản quyền chính hãng</span>
            </div>
        </div>

        <!-- Card 2: Tổng đầu sách phát hành -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-sky-700 uppercase tracking-wider">Tổng Đầu Sách Phát Hành</span>
                <span class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center text-sky-800">
                    <span class="material-symbols-outlined text-[18px]">menu_book</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-sky-700"><?= number_format($totalBooks) ?></span>
                <span class="text-xs text-sky-600 font-bold">tựa sách</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Liên kết:</span>
                <span class="font-bold text-primary">Qua khóa ngoại nxb_id</span>
            </div>
        </div>

        <!-- Card 3: NXB phát hành nhiều sách nhất -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">NXB Phát Hành Nhiều Nhất</span>
                <span class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-800">
                    <span class="material-symbols-outlined text-[18px]">award_star</span>
                </span>
            </div>
            <div class="mt-2 flex flex-col">
                <span class="text-lg font-black text-primary truncate" title="<?= htmlspecialchars($topPublisher['ten'] ?? 'Chưa có') ?>">
                    <?= htmlspecialchars($topPublisher['ten'] ?? 'Chưa có') ?>
                </span>
                <span class="text-xs text-amber-700 font-bold"><?= number_format($topPublisher['tong_sach'] ?? 0) ?> tựa sách phát hành</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Thị phần sàn:</span>
                <span class="font-bold text-primary"><?= $totalBooks > 0 ? round((($topPublisher['tong_sach'] ?? 0) / $totalBooks) * 100, 1) : 0 ?>%</span>
            </div>
        </div>
    </div>

    <!-- 3. Filter Bar & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="<?= base_url('admin/nha-xuat-ban') ?>" class="relative w-full max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
            <input name="q" value="<?= htmlspecialchars($search) ?>" class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-on-surface placeholder:text-outline text-xs focus:outline-none focus:bg-white focus:border-secondary transition-all" placeholder="Tìm theo tên NXB, địa chỉ trụ sở, liên hệ..." type="text"/>
        </form>

        <div class="flex items-center gap-2">
            <?php if (!empty($search)): ?>
                <a href="<?= base_url('admin/nha-xuat-ban') ?>" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                    <span>Bỏ tìm kiếm</span>
                </a>
            <?php endif; ?>
            <span class="text-xs text-on-surface-variant font-medium">Hiển thị <strong><?= count($publishers) ?></strong> nhà xuất bản</span>
        </div>
    </div>

    <!-- 4. Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-on-surface-variant text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4 w-16 text-center">ID</th>
                        <th class="py-3 px-4 min-w-[220px]">Tên Nhà Xuất Bản</th>
                        <th class="py-3 px-4 min-w-[280px]">Địa Chỉ Trụ Sở</th>
                        <th class="py-3 px-4 min-w-[180px]">Liên Hệ</th>
                        <th class="py-3 px-4 w-32 text-center">Số Tựa Sách</th>
                        <th class="py-3 px-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-on-surface">
                    <?php if (empty($publishers)): ?>
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">domain_disabled</span>
                                <p class="font-semibold text-sm">Không tìm thấy nhà xuất bản nào phù hợp.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($publishers as $pub): ?>
                            <tr class="hover:bg-slate-50/60 transition-colors group" id="publisher-row-<?= $pub['id'] ?>">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                    #<?= $pub['id'] ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                                            <span class="material-symbols-outlined text-[20px]">business</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-primary text-sm"><?= htmlspecialchars($pub['ten']) ?></span>
                                            <a href="<?= base_url('tim-kiem?nxb%5B%5D=' . $pub['id']) ?>" target="_blank" class="text-[11px] text-secondary hover:underline flex items-center gap-0.5">
                                                <span>Xem sách trên shop</span>
                                                <span class="material-symbols-outlined text-[12px]">open_in_new</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="text-slate-600 line-clamp-2 leading-relaxed">
                                        <?= !empty($pub['dia_chi']) ? htmlspecialchars($pub['dia_chi']) : '<span class="text-slate-400 italic">Chưa cập nhật địa chỉ.</span>' ?>
                                    </p>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-slate-700 font-medium">
                                            <?= !empty($pub['lien_he']) ? htmlspecialchars($pub['lien_he']) : '<span class="text-slate-400 italic">Chưa có liên hệ</span>' ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-800 text-xs font-bold border border-sky-200">
                                        <?= number_format($pub['tong_sach']) ?> tựa sách
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1">
                                        <button onclick="openEditPublisherModal(<?= $pub['id'] ?>)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-primary flex items-center justify-center transition-colors" title="Sửa nhà xuất bản">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>
                                        <button onclick="deletePublisher(<?= $pub['id'] ?>, '<?= htmlspecialchars(addslashes($pub['ten'])) ?>', <?= (int)$pub['tong_sach'] ?>)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-600 text-slate-600 hover:text-white flex items-center justify-center transition-colors" title="Xóa nhà xuất bản">
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
    </div>
</div>

<!-- ========================================================================= -->
<!-- 5. MODAL THÊM / SỬA NHÀ XUẤT BẢN                                          -->
<!-- ========================================================================= -->
<div id="publisherModalBackdrop" class="fixed inset-0 bg-primary/40 backdrop-blur-[2px] z-50 opacity-0 pointer-events-none transition-opacity duration-300" onclick="closePublisherModal()"></div>

<div id="publisherModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-2xl z-50 opacity-0 pointer-events-none scale-95 transition-all duration-300 overflow-hidden flex flex-col">
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]" id="modalIcon">business</span>
            </span>
            <h3 class="text-base font-bold text-primary" id="modalTitle">Thêm Nhà Xuất Bản Mới</h3>
        </div>
        <button onclick="closePublisherModal()" class="w-8 h-8 rounded-xl hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>

    <form id="publisherForm" method="POST" action="<?= base_url('admin/nha-xuat-ban/them') ?>" class="p-6 flex flex-col gap-4 text-xs">
        <input type="hidden" name="id" id="publisherId" value=""/>

        <div class="flex flex-col gap-1.5">
            <label class="font-bold text-primary">Tên nhà xuất bản <span class="text-rose-600">*</span></label>
            <input type="text" name="ten" id="publisherName" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary transition-all" placeholder="Ví dụ: NXB Trẻ, NXB Kim Đồng, NXB Hội Nhà Văn..."/>
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="font-bold text-primary">Địa chỉ trụ sở</label>
            <input type="text" name="dia_chi" id="publisherAddress" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary transition-all" placeholder="Ví dụ: 161B Lý Chính Thắng, Quận 3, TP. Hồ Chí Minh"/>
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="font-bold text-primary">Thông tin liên hệ (Email / Hotline)</label>
            <input type="text" name="lien_he" id="publisherContact" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary transition-all" placeholder="Ví dụ: hopthu@nxbtre.com.vn - (028) 39316289"/>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            <button type="button" onclick="closePublisherModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-colors">
                Hủy bỏ
            </button>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-white font-bold transition-all shadow-sm" id="modalSubmitBtn">
                Lưu Nhà Xuất Bản
            </button>
        </div>
    </form>
</div>

<!-- Toast notification -->
<div class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white shadow-2xl translate-y-20 opacity-0 transition-all duration-300 pointer-events-none" id="statusToast">
    <span class="material-symbols-outlined text-secondary text-[22px]" id="toastIcon">check_circle</span>
    <div class="flex flex-col">
        <span class="text-xs font-bold" id="toastTitle">Thông báo</span>
        <span class="text-[11px] text-slate-300" id="toastDesc">Thao tác thành công.</span>
    </div>
</div>

<script>
    const baseUrl = '<?= base_url() ?>';
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

    function openAddPublisherModal() {
        document.getElementById('modalTitle').textContent = 'Thêm Nhà Xuất Bản Mới';
        document.getElementById('publisherForm').action = baseUrl + 'admin/nha-xuat-ban/them';
        document.getElementById('publisherId').value = '';
        document.getElementById('publisherName').value = '';
        document.getElementById('publisherAddress').value = '';
        document.getElementById('publisherContact').value = '';
        document.getElementById('modalSubmitBtn').textContent = 'Thêm Nhà Xuất Bản';

        document.getElementById('publisherModalBackdrop').classList.remove('opacity-0', 'pointer-events-none');
        const modal = document.getElementById('publisherModal');
        modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
        modal.classList.add('scale-100');
    }

    function openEditPublisherModal(id) {
        fetch(baseUrl + 'admin/nha-xuat-ban/chi-tiet/' + id)
            .then(res => res.json())
            .then(res => {
                if (!res.success || !res.data) {
                    alert(res.message || 'Không thể tải thông tin nhà xuất bản.');
                    return;
                }
                const pub = res.data;
                document.getElementById('modalTitle').textContent = 'Chỉnh Sửa Nhà Xuất Bản #' + id;
                document.getElementById('publisherForm').action = baseUrl + 'admin/nha-xuat-ban/sua/' + id;
                document.getElementById('publisherId').value = pub.id;
                document.getElementById('publisherName').value = pub.ten;
                document.getElementById('publisherAddress').value = pub.dia_chi || '';
                document.getElementById('publisherContact').value = pub.lien_he || '';
                document.getElementById('modalSubmitBtn').textContent = 'Cập Nhật Nhà Xuất Bản';

                document.getElementById('publisherModalBackdrop').classList.remove('opacity-0', 'pointer-events-none');
                const modal = document.getElementById('publisherModal');
                modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
                modal.classList.add('scale-100');
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối khi tải nhà xuất bản: ' + err);
            });
    }

    function closePublisherModal() {
        document.getElementById('publisherModalBackdrop').classList.add('opacity-0', 'pointer-events-none');
        const modal = document.getElementById('publisherModal');
        modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
        modal.classList.remove('scale-100');
    }

    function deletePublisher(id, name, bookCount) {
        if (bookCount > 0) {
            alert(`CẢNH BÁO RÀNG BUỘC TOÀN VẸN:\nNhà xuất bản '${name}' hiện đang có ${bookCount} tựa sách liên kết.\nBạn không thể xóa NXB này cho đến khi chuyển các tựa sách sang NXB khác!`);
            return;
        }

        if (!confirm(`Bạn có chắc chắn muốn xóa Nhà xuất bản '${name}'? Thao tác này không thể hoàn tác.`)) {
            return;
        }

        fetch(baseUrl + 'admin/nha-xuat-ban/xoa/' + id, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(`NXB #${id}`, data.message);
                const row = document.getElementById('publisher-row-' + id);
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
            showToast('Lỗi', 'Không thể xóa nhà xuất bản: ' + err, true);
        });
    }
</script>
