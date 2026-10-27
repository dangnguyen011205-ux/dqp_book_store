<?php
// Views/admin/authors/index.php
// Quản lý Tác Giả Sách - Athenaeum Modern v2.4
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
                <span class="text-primary font-bold">Tác Giả Sách</span>
                <span class="ml-1.5 px-2 py-0.5 rounded-full bg-slate-100 text-[10px] font-bold text-slate-500">CSDL: tac_gia</span>
            </nav>
            <div class="flex items-baseline gap-2 mt-0.5">
                <h1 class="text-2xl lg:text-3xl font-extrabold text-primary tracking-tight">Quản Lý Tác Giả</h1>
                <span class="hidden sm:inline-block px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[11px] font-bold">
                    Hồ Sơ Tác Giả • v2.4
                </span>
            </div>
            <p class="text-xs text-on-surface-variant">
                Quản lý thông tin tác giả, dịch giả, tiểu sử và danh mục các ấn phẩm xuất bản trên hệ thống.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openAddAuthorModal()" class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm transition-all active:scale-95" type="button">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                <span>Thêm Tác Giả Mới</span>
            </button>
            <a href="<?= base_url('admin/tac-gia') ?>" class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-primary text-xs font-semibold shadow-sm border border-slate-200 transition-all active:scale-95" title="Làm mới">
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
        <a href="<?= base_url('admin/tac-gia') ?>" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold shadow-sm">
            <span class="material-symbols-outlined text-[18px]">person_edit</span>
            <span>Tác Giả</span>
            <span class="px-1.5 py-0.5 rounded-full bg-white/20 text-[10px]"><?= $totalAuthors ?></span>
        </a>
        <a href="<?= base_url('admin/nha-xuat-ban') ?>" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200 transition-colors">
            <span class="material-symbols-outlined text-[18px]">business</span>
            <span>Nhà Xuất Bản</span>
        </a>
    </div>

    <!-- 2. KPI Bento Cards (3 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Card 1: Tổng số tác giả -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Tổng số Tác giả</span>
                <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-primary"><?= number_format($totalAuthors) ?></span>
                <span class="text-xs text-secondary font-bold">tác giả &amp; dịch giả</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Hồ sơ tác giả:</span>
                <span class="font-bold text-emerald-700">Đã chuẩn hóa danh mục</span>
            </div>
        </div>

        <!-- Card 2: Tổng tác phẩm -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-sky-700 uppercase tracking-wider">Tổng Tác Phẩm Liên Kết</span>
                <span class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center text-sky-800">
                    <span class="material-symbols-outlined text-[18px]">menu_book</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-sky-700"><?= number_format($totalBooks) ?></span>
                <span class="text-xs text-sky-600 font-bold">lượt tác giả</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Liên kết đa tác giả:</span>
                <span class="font-bold text-primary">Hỗ trợ qua bảng sach_tac_gia</span>
            </div>
        </div>

        <!-- Card 3: Tác giả nhiều sách nhất -->
        <div class="relative overflow-hidden rounded-2xl bg-white p-4 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Tác Giả Tiêu Biểu Nhất</span>
                <span class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-800">
                    <span class="material-symbols-outlined text-[18px]">stars</span>
                </span>
            </div>
            <div class="mt-2 flex flex-col">
                <span class="text-lg font-black text-primary truncate" title="<?= htmlspecialchars($topAuthor['ten'] ?? 'Chưa có') ?>">
                    <?= htmlspecialchars($topAuthor['ten'] ?? 'Chưa có') ?>
                </span>
                <span class="text-xs text-amber-700 font-bold"><?= number_format($topAuthor['tong_sach'] ?? 0) ?> tác phẩm đã xuất bản</span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-on-surface-variant">
                <span>Trạng thái:</span>
                <span class="font-bold text-primary">Bán chạy nhất hệ thống</span>
            </div>
        </div>
    </div>

    <!-- 3. Filter Bar & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="<?= base_url('admin/tac-gia') ?>" class="relative w-full max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
            <input name="q" value="<?= htmlspecialchars($search) ?>" class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-on-surface placeholder:text-outline text-xs focus:outline-none focus:bg-white focus:border-secondary transition-all" placeholder="Tìm theo tên tác giả, từ khóa tiểu sử..." type="text"/>
        </form>

        <div class="flex items-center gap-2">
            <?php if (!empty($search)): ?>
                <a href="<?= base_url('admin/tac-gia') ?>" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                    <span>Bỏ tìm kiếm</span>
                </a>
            <?php endif; ?>
            <span class="text-xs text-on-surface-variant font-medium">Hiển thị <strong><?= count($authors) ?></strong> tác giả</span>
        </div>
    </div>

    <!-- 4. Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-on-surface-variant text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4 w-16 text-center">ID</th>
                        <th class="py-3 px-4 min-w-[220px]">Tên Tác Giả</th>
                        <th class="py-3 px-4 min-w-[340px]">Tiểu Sử &amp; Giới Thiệu</th>
                        <th class="py-3 px-4 w-32 text-center">Số Tác Phẩm</th>
                        <th class="py-3 px-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-on-surface">
                    <?php if (empty($authors)): ?>
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">person_off</span>
                                <p class="font-semibold text-sm">Không tìm thấy tác giả nào phù hợp.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($authors as $author): ?>
                            <?php $avatarInitial = strtoupper(mb_substr($author['ten'], 0, 1, 'UTF-8')); ?>
                            <tr class="hover:bg-slate-50/60 transition-colors group" id="author-row-<?= $author['id'] ?>">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                    #<?= $author['id'] ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                                            <?= $avatarInitial ?>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-primary text-sm"><?= htmlspecialchars($author['ten']) ?></span>
                                            <a href="<?= base_url('tim-kiem?tac_gia%5B%5D=' . $author['id']) ?>" target="_blank" class="text-[11px] text-secondary hover:underline flex items-center gap-0.5">
                                                <span>Xem sách trên shop</span>
                                                <span class="material-symbols-outlined text-[12px]">open_in_new</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="text-slate-600 line-clamp-2 leading-relaxed">
                                        <?= !empty($author['tieu_su']) ? htmlspecialchars($author['tieu_su']) : '<span class="text-slate-400 italic">Chưa có thông tin tiểu sử chi tiết.</span>' ?>
                                    </p>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-800 text-xs font-bold border border-sky-200">
                                        <?= number_format($author['tong_sach']) ?> tác phẩm
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1">
                                        <button onclick="openEditAuthorModal(<?= $author['id'] ?>)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-primary flex items-center justify-center transition-colors" title="Sửa tác giả">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>
                                        <button onclick="deleteAuthor(<?= $author['id'] ?>, '<?= htmlspecialchars(addslashes($author['ten'])) ?>', <?= (int)$author['tong_sach'] ?>)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-600 text-slate-600 hover:text-white flex items-center justify-center transition-colors" title="Xóa tác giả">
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
<!-- 5. MODAL THÊM / SỬA TÁC GIẢ SÁCH                                          -->
<!-- ========================================================================= -->
<div id="authorModalBackdrop" class="fixed inset-0 bg-primary/40 backdrop-blur-[2px] z-50 opacity-0 pointer-events-none transition-opacity duration-300" onclick="closeAuthorModal()"></div>

<div id="authorModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-2xl z-50 opacity-0 pointer-events-none scale-95 transition-all duration-300 overflow-hidden flex flex-col">
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]" id="modalIcon">person_edit</span>
            </span>
            <h3 class="text-base font-bold text-primary" id="modalTitle">Thêm Tác Giả Mới</h3>
        </div>
        <button onclick="closeAuthorModal()" class="w-8 h-8 rounded-xl hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>

    <form id="authorForm" method="POST" action="<?= base_url('admin/tac-gia/them') ?>" class="p-6 flex flex-col gap-4 text-xs">
        <input type="hidden" name="id" id="authorId" value=""/>

        <div class="flex flex-col gap-1.5">
            <label class="font-bold text-primary">Họ và tên tác giả <span class="text-rose-600">*</span></label>
            <input type="text" name="ten" id="authorName" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary transition-all" placeholder="Ví dụ: Nguyễn Nhật Ánh, Haruki Murakami, Dale Carnegie..."/>
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="font-bold text-primary">Tiểu sử &amp; Thông tin giới thiệu</label>
            <textarea name="tieu_su" id="authorBio" rows="4" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-on-surface text-xs focus:outline-none focus:bg-white focus:border-secondary transition-all" placeholder="Tiểu sử tóm tắt, phong cách văn học, các giải thưởng đạt được..."></textarea>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            <button type="button" onclick="closeAuthorModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-colors">
                Hủy bỏ
            </button>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-white font-bold transition-all shadow-sm" id="modalSubmitBtn">
                Lưu Tác Giả
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

    function openAddAuthorModal() {
        document.getElementById('modalTitle').textContent = 'Thêm Tác Giả Mới';
        document.getElementById('authorForm').action = baseUrl + 'admin/tac-gia/them';
        document.getElementById('authorId').value = '';
        document.getElementById('authorName').value = '';
        document.getElementById('authorBio').value = '';
        document.getElementById('modalSubmitBtn').textContent = 'Thêm Tác Giả';

        document.getElementById('authorModalBackdrop').classList.remove('opacity-0', 'pointer-events-none');
        const modal = document.getElementById('authorModal');
        modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
        modal.classList.add('scale-100');
    }

    function openEditAuthorModal(id) {
        fetch(baseUrl + 'admin/tac-gia/chi-tiet/' + id)
            .then(res => res.json())
            .then(res => {
                if (!res.success || !res.data) {
                    alert(res.message || 'Không thể tải thông tin tác giả.');
                    return;
                }
                const author = res.data;
                document.getElementById('modalTitle').textContent = 'Chỉnh Sửa Tác Giả #' + id;
                document.getElementById('authorForm').action = baseUrl + 'admin/tac-gia/sua/' + id;
                document.getElementById('authorId').value = author.id;
                document.getElementById('authorName').value = author.ten;
                document.getElementById('authorBio').value = author.tieu_su || '';
                document.getElementById('modalSubmitBtn').textContent = 'Cập Nhật Tác Giả';

                document.getElementById('authorModalBackdrop').classList.remove('opacity-0', 'pointer-events-none');
                const modal = document.getElementById('authorModal');
                modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
                modal.classList.add('scale-100');
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối khi tải tác giả: ' + err);
            });
    }

    function closeAuthorModal() {
        document.getElementById('authorModalBackdrop').classList.add('opacity-0', 'pointer-events-none');
        const modal = document.getElementById('authorModal');
        modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
        modal.classList.remove('scale-100');
    }

    function deleteAuthor(id, name, bookCount) {
        if (bookCount > 0) {
            alert(`CẢNH BÁO RÀNG BUỘC TOÀN VẸN:\nTác giả '${name}' hiện đang có ${bookCount} tác phẩm liên kết.\nBạn không thể xóa tác giả này cho đến khi chuyển các tác phẩm sang tác giả khác!`);
            return;
        }

        if (!confirm(`Bạn có chắc chắn muốn xóa tác giả '${name}'? Thao tác này không thể hoàn tác.`)) {
            return;
        }

        fetch(baseUrl + 'admin/tac-gia/xoa/' + id, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(`Tác giả #${id}`, data.message);
                const row = document.getElementById('author-row-' + id);
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
            showToast('Lỗi', 'Không thể xóa tác giả: ' + err, true);
        });
    }
</script>
