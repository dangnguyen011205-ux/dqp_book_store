<?php
// Views/client/account/addresses.php
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Cột trái: Sidebar độc giả -->
        <?php require __DIR__ . '/_sidebar.php'; ?>

        <!-- Cột phải: Quản lý sổ địa chỉ -->
        <section class="lg:col-span-9 space-y-6">
            <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-slate-100 space-y-6">
                
                <!-- Header & Nút thêm mới -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <h1 class="text-xl font-bold text-primary tracking-tight">Sổ Địa Chỉ Nhận Hàng</h1>
                        <p class="text-xs text-slate-500 mt-1">Lưu nhiều địa chỉ để việc thanh toán và giao nhận sách diễn ra nhanh chóng.</p>
                    </div>
                    <button onclick="openModal('modal-add-address')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-secondary transition-all shadow shrink-0">
                        <span class="material-symbols-outlined text-[18px]">add_location_alt</span>
                        <span>+ Thêm Địa Chỉ Mới</span>
                    </button>
                </div>

                <!-- Danh sách địa chỉ -->
                <?php if (empty($addresses)): ?>
                    <div class="py-12 text-center space-y-3">
                        <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[36px]">location_off</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700">Chưa Có Địa Chỉ Nào</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">Bạn chưa thêm địa chỉ nhận hàng nào. Hãy thêm địa chỉ để nhận sách tận nơi thuận tiện.</p>
                        <button onclick="openModal('modal-add-address')" class="px-5 py-2 bg-secondary text-white rounded-lg text-xs font-semibold hover:bg-secondary-container hover:text-on-secondary-container transition-colors">
                            Thêm Địa Chỉ Đầu Tiên
                        </button>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($addresses as $addr): ?>
                            <div class="p-5 rounded-2xl border <?= $addr['mac_dinh'] ? 'border-secondary/40 bg-secondary/5' : 'border-slate-200 bg-white' ?> shadow-sm relative flex flex-col justify-between space-y-4 transition-all hover:shadow-md">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-sm text-primary"><?= htmlspecialchars($addr['nguoi_nhan']) ?></span>
                                            <?php if ($addr['mac_dinh']): ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-secondary text-white">
                                                    <span class="material-symbols-outlined text-[12px]">verified</span> Mặc định
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <p class="text-xs text-slate-600 flex items-center gap-1.5 font-medium">
                                        <span class="material-symbols-outlined text-[16px] text-slate-400">call</span>
                                        <?= htmlspecialchars($addr['so_dien_thoai']) ?>
                                    </p>

                                    <p class="text-xs text-slate-600 flex items-start gap-1.5 leading-relaxed">
                                        <span class="material-symbols-outlined text-[16px] text-slate-400 shrink-0 mt-0.5">pin_drop</span>
                                        <span><?= htmlspecialchars($addr['dia_chi']) ?></span>
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        <button onclick='openEditModal(<?= json_encode($addr, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)' 
                                                class="text-secondary font-bold hover:underline flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[15px]">edit</span> Sửa
                                        </button>
                                        <span class="text-slate-300">|</span>
                                        <a href="<?= base_url('tai-khoan/so-dia-chi/xoa/' . $addr['id']) ?>" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');" 
                                           class="text-rose-600 font-semibold hover:underline flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[15px]">delete</span> Xóa
                                        </a>
                                    </div>

                                    <?php if (!$addr['mac_dinh']): ?>
                                        <a href="<?= base_url('tai-khoan/so-dia-chi/mac-dinh/' . $addr['id']) ?>" 
                                           class="text-xs font-semibold text-slate-500 hover:text-secondary transition-colors">
                                            Thiết lập mặc định
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<!-- ================= MODAL: THÊM ĐỊA CHỈ MỚI ================= -->
<div id="modal-add-address" class="fixed inset-0 z-50 hidden items-center justify-center bg-primary/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 md:p-8 shadow-2xl animate-fade-in relative">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <h3 class="text-base font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">add_location</span>
                Thêm Địa Chỉ Nhận Hàng Mới
            </h3>
            <button onclick="closeModal('modal-add-address')" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <form action="<?= base_url('tai-khoan/so-dia-chi/them') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase">Họ Tên Người Nhận *</label>
                <input type="text" name="nguoi_nhan" required placeholder="Ví dụ: Nguyễn Văn An"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary"/>
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase">Số Điện Thoại Nhận Hàng *</label>
                <input type="tel" name="so_dien_thoai" required placeholder="Ví dụ: 0912 345 678"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary"/>
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase">Địa Chỉ Chi Tiết (Số nhà, đường, phường/xã, quận/huyện, tỉnh/TP) *</label>
                <textarea name="dia_chi" rows="3" required placeholder="Ví dụ: 123 Đường Nguyễn Huệ, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh"
                          class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary"></textarea>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="add-mac-dinh" name="mac_dinh" value="1" class="w-4 h-4 text-secondary rounded border-slate-300 focus:ring-secondary"/>
                <label for="add-mac-dinh" class="text-xs font-medium text-slate-700 cursor-pointer">Đặt làm địa chỉ nhận hàng mặc định</label>
            </div>

            <div class="pt-3 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-add-address')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Hủy bỏ
                </button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-secondary transition-colors shadow">
                    Lưu Địa Chỉ
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL: CHỈNH SỬA ĐỊA CHỈ ================= -->
<div id="modal-edit-address" class="fixed inset-0 z-50 hidden items-center justify-center bg-primary/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 md:p-8 shadow-2xl animate-fade-in relative">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <h3 class="text-base font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">edit_location</span>
                Cập Nhật Địa Chỉ Nhận Hàng
            </h3>
            <button onclick="closeModal('modal-edit-address')" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <form id="form-edit-address" action="" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase">Họ Tên Người Nhận *</label>
                <input type="text" id="edit-nguoi-nhan" name="nguoi_nhan" required
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary"/>
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase">Số Điện Thoại Nhận Hàng *</label>
                <input type="tel" id="edit-so-dien-thoai" name="so_dien_thoai" required
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary"/>
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase">Địa Chỉ Chi Tiết *</label>
                <textarea id="edit-dia-chi" name="dia_chi" rows="3" required
                          class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-secondary"></textarea>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="edit-mac-dinh" name="mac_dinh" value="1" class="w-4 h-4 text-secondary rounded border-slate-300 focus:ring-secondary"/>
                <label for="edit-mac-dinh" class="text-xs font-medium text-slate-700 cursor-pointer">Đặt làm địa chỉ nhận hàng mặc định</label>
            </div>

            <div class="pt-3 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-edit-address')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Hủy bỏ
                </button>
                <button type="submit" class="px-5 py-2.5 bg-secondary text-white rounded-xl text-xs font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-colors shadow">
                    Lưu Cập Nhật
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(addr) {
    document.getElementById('form-edit-address').action = '<?= base_url('tai-khoan/so-dia-chi/sua/') ?>' + addr.id;
    document.getElementById('edit-nguoi-nhan').value = addr.nguoi_nhan;
    document.getElementById('edit-so-dien-thoai').value = addr.so_dien_thoai;
    document.getElementById('edit-dia-chi').value = addr.dia_chi;
    document.getElementById('edit-mac-dinh').checked = parseInt(addr.mac_dinh) === 1;
    openModal('modal-edit-address');
}
</script>
