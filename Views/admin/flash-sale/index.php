<?php
// Views/admin/flash-sale/index.php
$title = $title ?? 'Quản Lý Flash Sale | ' . APP_NAME;
$currentUrl = trim($_GET['url'] ?? '', '/');
$filter = $filter ?? 'all';

$now = time();

function fsStatus(string $batDau, string $ketThuc): string {
    $now = time();
    $start = strtotime($batDau);
    $end   = strtotime($ketThuc);
    if ($now >= $start && $now < $end) return 'active';
    if ($start > $now) return 'upcoming';
    return 'expired';
}

function fsStatusBadge(string $status): string {
    return match($status) {
        'active'   => '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse inline-block"></span>Đang diễn ra</span>',
        'upcoming' => '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700"><span class="material-symbols-outlined text-[14px]">schedule</span>Sắp bắt đầu</span>',
        default    => '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500"><span class="material-symbols-outlined text-[14px]">event_busy</span>Đã kết thúc</span>',
    };
}
?>

<div class="max-w-screen-xl mx-auto space-y-6">

    <!-- ── PAGE HEADER ──────────────────────────────────── -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center shadow">
                <span class="material-symbols-outlined text-white text-[22px]">bolt</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-on-surface">Quản Lý Flash Sale</h1>
                <p class="text-xs text-on-surface-variant">Tạo &amp; quản lý các chương trình flash sale theo thời gian</p>
            </div>
        </div>
        <button onclick="openCreateModal()"
                class="flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold shadow transition-colors">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tạo Flash Sale Mới
        </button>
    </div>

    <!-- ── STATS ─────────────────────────────────────────── -->
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-outline-variant p-4 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-emerald-600 text-[20px]">play_circle</span>
            </div>
            <div>
                <p class="text-2xl font-bold text-emerald-700"><?= (int)($stats['active'] ?? 0) ?></p>
                <p class="text-xs text-on-surface-variant font-medium">Đang diễn ra</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-outline-variant p-4 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600 text-[20px]">schedule</span>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-600"><?= (int)($stats['upcoming'] ?? 0) ?></p>
                <p class="text-xs text-on-surface-variant font-medium">Sắp bắt đầu</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-outline-variant p-4 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-slate-500 text-[20px]">event_busy</span>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-500"><?= (int)($stats['expired'] ?? 0) ?></p>
                <p class="text-xs text-on-surface-variant font-medium">Đã kết thúc</p>
            </div>
        </div>
    </div>

    <!-- ── FILTER TABS ──────────────────────────────────── -->
    <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="flex border-b border-outline-variant">
            <?php
            $tabs = [
                'all'      => ['Tất cả', 'list'],
                'active'   => ['Đang chạy', 'play_circle'],
                'upcoming' => ['Sắp diễn ra', 'schedule'],
                'expired'  => ['Đã kết thúc', 'event_busy'],
            ];
            foreach ($tabs as $key => [$label, $icon]):
                $active = $filter === $key;
            ?>
            <a href="<?= base_url('admin/flash-sale?filter=' . $key) ?>"
               class="flex items-center gap-1.5 px-5 py-3 text-xs font-semibold transition-colors border-b-2
                      <?= $active ? 'border-amber-500 text-amber-600 bg-amber-50' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low' ?>">
                <span class="material-symbols-outlined text-[16px]"><?= $icon ?></span>
                <?= $label ?>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-xs font-bold uppercase tracking-wide">
                        <th class="px-4 py-3 text-left">Sách</th>
                        <th class="px-4 py-3 text-right">Giá gốc</th>
                        <th class="px-4 py-3 text-right">Giá sale</th>
                        <th class="px-4 py-3 text-right">Giảm</th>
                        <th class="px-4 py-3 text-left">Bắt đầu</th>
                        <th class="px-4 py-3 text-left">Kết thúc</th>
                        <th class="px-4 py-3 text-center">Trạng thái</th>
                        <th class="px-4 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/40">
                    <?php if (empty($flashSales)): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-[48px] text-slate-300 block mb-2">bolt</span>
                            <p class="font-medium">Chưa có flash sale nào</p>
                            <p class="text-xs mt-1">Nhấn "+ Tạo Flash Sale Mới" để bắt đầu</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($flashSales as $fs):
                        $status    = fsStatus($fs['bat_dau'], $fs['ket_thuc']);
                        $discount  = $fs['gia'] > 0 ? round((1 - $fs['gia_sale'] / $fs['gia']) * 100) : 0;
                        $isActive  = $status === 'active';
                        $endTs     = strtotime($fs['ket_thuc']);
                        $startTs   = strtotime($fs['bat_dau']);
                    ?>
                    <tr class="hover:bg-surface-container-low/60 transition-colors">
                        <!-- Sách -->
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="<?= htmlspecialchars($fs['anh_bia'] ?? '') ?>"
                                     onerror="this.src='https://via.placeholder.com/40x56?text=No+Img'"
                                     class="w-10 h-14 object-cover rounded-lg border border-outline-variant shadow-sm" alt="">
                                <div>
                                    <p class="font-semibold text-on-surface line-clamp-2 max-w-[200px]"><?= htmlspecialchars($fs['tieu_de']) ?></p>
                                    <p class="text-[11px] text-on-surface-variant mt-0.5">ID #<?= $fs['id'] ?> · Tồn: <?= (int)$fs['so_luong_ton'] ?></p>
                                </div>
                            </div>
                        </td>
                        <!-- Giá gốc -->
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <span class="text-on-surface-variant line-through text-xs"><?= number_format($fs['gia'], 0, ',', '.') ?>₫</span>
                        </td>
                        <!-- Giá sale -->
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <span class="font-bold text-rose-600"><?= number_format($fs['gia_sale'], 0, ',', '.') ?>₫</span>
                        </td>
                        <!-- Giảm % -->
                        <td class="px-4 py-3 text-right">
                            <span class="inline-block px-2 py-0.5 bg-rose-100 text-rose-700 rounded-full text-[11px] font-bold">-<?= $discount ?>%</span>
                        </td>
                        <!-- Bắt đầu -->
                        <td class="px-4 py-3 text-xs text-on-surface-variant whitespace-nowrap">
                            <?= date('d/m/Y H:i', $startTs) ?>
                        </td>
                        <!-- Kết thúc -->
                        <td class="px-4 py-3 text-xs whitespace-nowrap">
                            <?php if ($isActive): ?>
                                <span class="text-rose-600 font-semibold countdown" data-end="<?= $endTs ?>"></span>
                            <?php else: ?>
                                <span class="text-on-surface-variant"><?= date('d/m/Y H:i', $endTs) ?></span>
                            <?php endif; ?>
                        </td>
                        <!-- Trạng thái -->
                        <td class="px-4 py-3 text-center"><?= fsStatusBadge($status) ?></td>
                        <!-- Thao tác -->
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick='openEditModal(<?= $fs["id"] ?>, <?= htmlspecialchars(json_encode([
                                    "sach_id"  => $fs["sach_id"],
                                    "tieu_de"  => $fs["tieu_de"],
                                    "gia"      => $fs["gia"],
                                    "gia_sale" => $fs["gia_sale"],
                                    "bat_dau"  => $fs["bat_dau"],
                                    "ket_thuc" => $fs["ket_thuc"],
                                ], JSON_UNESCAPED_UNICODE), ENT_QUOTES) ?>)'
                                        class="p-1.5 text-secondary hover:bg-secondary/10 rounded-lg transition-colors" title="Sửa">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <form method="POST" action="<?= base_url('admin/flash-sale/xoa/' . $fs['id']) ?>"
                                      onsubmit="return confirm('Xóa flash sale này?')">
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Xóa">
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
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     MODAL TẠO / SỬA FLASH SALE
══════════════════════════════════════════════════════════ -->
<div id="fsModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal()"></div>

    <!-- Panel -->
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden">
        <!-- Modal header -->
        <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[22px]">bolt</span>
                <h2 class="font-bold text-lg" id="modalTitle">Tạo Flash Sale Mới</h2>
            </div>
            <button onclick="closeModal()" class="p-1 hover:bg-white/20 rounded-lg transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Form -->
        <form id="fsForm" method="POST" action="<?= base_url('admin/flash-sale/them') ?>" class="p-6 space-y-5">
            <!-- Chọn sách -->
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5">Chọn sách <span class="text-rose-500">*</span></label>
                <select id="sachSelect" name="sach_id" required
                        class="w-full border border-outline-variant rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white"
                        onchange="onBookSelect(this)">
                    <option value="">-- Chọn sách --</option>
                    <?php foreach ($books as $b): ?>
                    <option value="<?= $b['id'] ?>"
                            data-gia="<?= $b['gia'] ?>"
                            data-title="<?= htmlspecialchars($b['tieu_de'], ENT_QUOTES) ?>">
                        <?= htmlspecialchars($b['tieu_de']) ?>
                        (<?= number_format($b['gia'], 0, ',', '.') ?>₫)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Giá sale + hiển thị giá gốc + % giảm -->
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5">Giá sale (₫) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <input type="number" id="giaSaleInput" name="gia_sale" min="1" step="1000" required
                           class="w-full border border-outline-variant rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 pr-28"
                           placeholder="Nhập giá sale..."
                           oninput="onGiaSaleChange()">
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold" id="discountBadge"></div>
                </div>
                <p class="text-xs text-on-surface-variant mt-1" id="origPriceHint"></p>
            </div>

            <!-- Thời gian -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-1.5">Bắt đầu <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" id="batDauInput" name="bat_dau" required
                           class="w-full border border-outline-variant rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-1.5">Kết thúc <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" id="ketThucInput" name="ket_thuc" required
                           class="w-full border border-outline-variant rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
            </div>

            <!-- Nút tắt nhanh -->
            <div>
                <p class="text-xs font-bold text-on-surface-variant mb-2">Đặt nhanh thời gian:</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="setQuick('today20')"
                            class="px-3 py-1.5 text-xs bg-slate-100 hover:bg-amber-100 hover:text-amber-700 rounded-lg font-medium transition-colors">
                        🌙 Hôm nay 20:00
                    </button>
                    <button type="button" onclick="setQuick('tomorrow12')"
                            class="px-3 py-1.5 text-xs bg-slate-100 hover:bg-amber-100 hover:text-amber-700 rounded-lg font-medium transition-colors">
                        🌞 Ngày mai 12:00
                    </button>
                    <button type="button" onclick="setQuick('weekend')"
                            class="px-3 py-1.5 text-xs bg-slate-100 hover:bg-amber-100 hover:text-amber-700 rounded-lg font-medium transition-colors">
                        🎉 Cuối tuần
                    </button>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-2 border-t border-outline-variant">
                <button type="button" onclick="closeModal()"
                        class="flex-1 py-2.5 border border-outline-variant text-on-surface-variant rounded-xl text-sm font-semibold hover:bg-surface-container-low transition-colors">
                    Hủy
                </button>
                <button type="submit" id="submitBtn"
                        class="flex-1 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold transition-colors shadow flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">bolt</span>
                    <span id="submitLabel">Tạo Flash Sale</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════════════ -->
<script>
// ── Countdown timers ──────────────────────────────────────
document.querySelectorAll('.countdown').forEach(el => {
    const endTs = parseInt(el.dataset.end, 10) * 1000;
    function tick() {
        const diff = endTs - Date.now();
        if (diff <= 0) { el.textContent = 'Đã kết thúc'; return; }
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        el.textContent = `⏱ ${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    }
    tick();
    setInterval(tick, 1000);
});

// ── Modal state ───────────────────────────────────────────
let editingId = null;
let origPrice = 0;

function openCreateModal() {
    editingId = null;
    origPrice = 0;
    document.getElementById('modalTitle').textContent = 'Tạo Flash Sale Mới';
    document.getElementById('submitLabel').textContent = 'Tạo Flash Sale';
    document.getElementById('fsForm').action = '<?= base_url('admin/flash-sale/them') ?>';
    document.getElementById('fsForm').reset();
    document.getElementById('discountBadge').textContent = '';
    document.getElementById('origPriceHint').textContent = '';
    document.getElementById('fsModal').classList.remove('hidden');
}

function openEditModal(id, data) {
    editingId = id;
    origPrice = parseFloat(data.gia) || 0;
    document.getElementById('modalTitle').textContent = 'Sửa Flash Sale';
    document.getElementById('submitLabel').textContent = 'Lưu thay đổi';
    document.getElementById('fsForm').action = '<?= base_url('admin/flash-sale/sua') ?>/' + id;

    // Điền dữ liệu
    document.getElementById('sachSelect').value = data.sach_id;
    document.getElementById('giaSaleInput').value = data.gia_sale;
    document.getElementById('batDauInput').value = toDatetimeLocal(data.bat_dau);
    document.getElementById('ketThucInput').value = toDatetimeLocal(data.ket_thuc);

    updateOrigHint();
    updateDiscount();
    document.getElementById('fsModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('fsModal').classList.add('hidden');
}

// ── Book select ───────────────────────────────────────────
function onBookSelect(sel) {
    const opt = sel.options[sel.selectedIndex];
    origPrice = parseFloat(opt.dataset.gia) || 0;
    updateOrigHint();
    updateDiscount();
}

function onGiaSaleChange() { updateDiscount(); }

function updateOrigHint() {
    const hint = document.getElementById('origPriceHint');
    if (origPrice > 0) {
        hint.textContent = 'Giá gốc: ' + origPrice.toLocaleString('vi-VN') + '₫';
    } else {
        hint.textContent = '';
    }
}

function updateDiscount() {
    const badge = document.getElementById('discountBadge');
    const salePrice = parseFloat(document.getElementById('giaSaleInput').value) || 0;
    if (origPrice > 0 && salePrice > 0) {
        const pct = Math.round((1 - salePrice / origPrice) * 100);
        if (pct > 0) {
            badge.textContent = '-' + pct + '%';
            badge.className = 'absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-rose-600';
        } else {
            badge.textContent = '';
        }
    } else {
        badge.textContent = '';
    }
}

// ── Quick presets ─────────────────────────────────────────
function setQuick(preset) {
    const now = new Date();
    let start, end;

    if (preset === 'today20') {
        start = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 20, 0);
        end   = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59);
    } else if (preset === 'tomorrow12') {
        const tmr = new Date(now); tmr.setDate(now.getDate() + 1);
        start = new Date(tmr.getFullYear(), tmr.getMonth(), tmr.getDate(), 12, 0);
        end   = new Date(tmr.getFullYear(), tmr.getMonth(), tmr.getDate(), 18, 0);
    } else if (preset === 'weekend') {
        const day = now.getDay(); // 0=Sun..6=Sat
        const daysToSat = day === 6 ? 7 : (6 - day);
        const sat = new Date(now); sat.setDate(now.getDate() + daysToSat);
        const sun = new Date(sat); sun.setDate(sat.getDate() + 1);
        start = new Date(sat.getFullYear(), sat.getMonth(), sat.getDate(), 8, 0);
        end   = new Date(sun.getFullYear(), sun.getMonth(), sun.getDate(), 23, 59);
    }

    document.getElementById('batDauInput').value = toInputDatetime(start);
    document.getElementById('ketThucInput').value = toInputDatetime(end);
}

function toInputDatetime(d) {
    if (!d) return '';
    const pad = n => String(n).padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
}

function toDatetimeLocal(str) {
    // "2026-11-19 20:00:00" -> "2026-11-19T20:00"
    if (!str) return '';
    return str.replace(' ', 'T').substring(0, 16);
}
</script>
