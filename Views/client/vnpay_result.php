<?php
/**
 * Giao diện Kết Quả Thanh Toán Trực Tuyến VNPAY - DQP Book Store
 * Phong cách: Athenaeum Modern (Material You Inspired)
 */

$orderId = (int)($verify['order_id'] ?? 0);
$payDateRaw = $verify['pay_date'] ?? '';
$payDateFormatted = 'N/A';
if (strlen($payDateRaw) === 14) {
    // Format YmdHis -> H:i:s d/m/Y
    $year = substr($payDateRaw, 0, 4);
    $month = substr($payDateRaw, 4, 2);
    $day = substr($payDateRaw, 6, 2);
    $hour = substr($payDateRaw, 8, 2);
    $minute = substr($payDateRaw, 10, 2);
    $second = substr($payDateRaw, 12, 2);
    $payDateFormatted = "{$hour}:{$minute}:{$second} - {$day}/{$month}/{$year}";
} else {
    $payDateFormatted = date('H:i:s - d/m/Y');
}
?>

<!-- Banner Tiến Trình -->
<section class="w-full bg-surface-container-low py-6 shadow-sm border-b border-surface-container">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between max-w-3xl mx-auto relative">
            <div class="absolute left-8 right-8 top-1/2 -translate-y-1/2 h-1 <?= $isSuccess ? 'bg-secondary' : 'bg-surface-container-high' ?> z-0"></div>

            <!-- Bước 1 -->
            <div class="flex flex-col items-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-secondary text-on-secondary flex items-center justify-center font-bold text-sm shadow-md">
                    <span class="material-symbols-outlined text-[20px]">check</span>
                </div>
                <span class="mt-2 text-xs font-bold text-secondary">1. Giỏ Hàng</span>
            </div>

            <!-- Bước 2 -->
            <div class="flex flex-col items-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-secondary text-on-secondary flex items-center justify-center font-bold text-sm shadow-md">
                    <span class="material-symbols-outlined text-[20px]">check</span>
                </div>
                <span class="mt-2 text-xs font-bold text-secondary">2. Giao Nhận</span>
            </div>

            <!-- Bước 3 (Kết quả VNPAY) -->
            <div class="flex flex-col items-center relative z-10">
                <?php if ($isSuccess): ?>
                    <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm shadow-lg ring-4 ring-secondary-container">
                        <span class="material-symbols-outlined text-[20px]">task_alt</span>
                    </div>
                    <span class="mt-2 text-xs font-bold text-primary">3. Hoàn Tất</span>
                <?php else: ?>
                    <div class="w-10 h-10 rounded-full bg-error text-on-error flex items-center justify-center font-bold text-sm shadow-lg ring-4 ring-error/20">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </div>
                    <span class="mt-2 text-xs font-bold text-error">3. Thất Bại</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Nội Dung Chi Tiết Kết Quả VNPAY -->
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <div class="bg-surface-container-lowest rounded-2xl p-6 sm:p-10 shadow-sm border border-surface-container">
        
        <!-- Header Trạng Thái -->
        <div class="text-center pb-8 border-b border-surface-container">
            <?php if ($isSuccess): ?>
                <div class="w-20 h-20 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 ring-8 ring-emerald-50/50">
                    <span class="material-symbols-outlined text-[42px]">check_circle</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-primary mb-2">Thanh Toán VNPAY Thành Công!</h1>
                <p class="text-sm text-on-surface-variant max-w-lg mx-auto leading-relaxed">
                    Giao dịch qua Cổng thanh toán trực tuyến <strong>VNPAY</strong> đã được xác nhận. Đơn hàng của bạn đã sẵn sàng chuyển sang khâu xử lý đóng gói và vận chuyển.
                </p>
            <?php else: ?>
                <div class="w-20 h-20 mx-auto rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mb-4 ring-8 ring-rose-50/50">
                    <span class="material-symbols-outlined text-[42px]">error</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-primary mb-2">Thanh Toán Chưa Thành Công</h1>
                <p class="text-sm text-on-surface-variant max-w-lg mx-auto leading-relaxed">
                    <?= htmlspecialchars($message) ?>
                </p>
                <p class="text-xs text-on-surface-variant mt-2">
                    Đơn hàng của bạn vẫn được lưu trong hệ thống ở trạng thái <em>Chờ xác nhận</em>. Bạn có thể thực hiện thanh toán lại ngay bây giờ.
                </p>
            <?php endif; ?>

            <?php if ($orderId > 0): ?>
                <div class="mt-5 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary-container/40 text-on-secondary-container text-xs font-bold">
                    <span>Mã Đơn Hàng:</span>
                    <span class="text-primary text-sm font-mono tracking-wider">#BK-<?= $orderId ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Thông Tin Biên Lai VNPAY (Bento Grid) -->
        <div class="py-6 border-b border-surface-container">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-sm text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-secondary">receipt_long</span>
                    <span>Biên Lai Giao Dịch VNPAY</span>
                </h3>
                <span class="inline-flex items-center gap-1 text-[11px] px-2.5 py-1 rounded-md <?= $isSuccess ? 'bg-emerald-50 text-emerald-700 font-bold border border-emerald-200' : 'bg-rose-50 text-rose-700 font-bold border border-rose-200' ?>">
                    <span class="w-1.5 h-1.5 rounded-full <?= $isSuccess ? 'bg-emerald-500' : 'bg-rose-500' ?>"></span>
                    <?= $isSuccess ? 'Giao dịch thành công' : 'Giao dịch lỗi / Hủy' ?>
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                <!-- Số tiền -->
                <div class="p-3.5 rounded-xl bg-surface-container-low/70 flex items-center justify-between">
                    <span class="text-on-surface-variant">Số tiền thanh toán:</span>
                    <strong class="text-secondary text-sm font-bold">
                        <?= format_currency($verify['amount'] ?? 0) ?>
                    </strong>
                </div>

                <!-- Ngân hàng / Phương thức -->
                <div class="p-3.5 rounded-xl bg-surface-container-low/70 flex items-center justify-between">
                    <span class="text-on-surface-variant">Ngân hàng thanh toán:</span>
                    <span class="font-semibold text-primary font-mono uppercase">
                        <?= !empty($verify['bank_code']) ? htmlspecialchars($verify['bank_code']) : 'VNPAY-QR' ?>
                    </span>
                </div>

                <!-- Mã giao dịch VNPAY -->
                <div class="p-3.5 rounded-xl bg-surface-container-low/70 flex items-center justify-between">
                    <span class="text-on-surface-variant">Mã giao dịch VNPAY:</span>
                    <span class="font-mono text-primary font-semibold">
                        <?= !empty($verify['transaction_no']) ? htmlspecialchars($verify['transaction_no']) : 'N/A' ?>
                    </span>
                </div>

                <!-- Mã tham chiếu giao dịch -->
                <div class="p-3.5 rounded-xl bg-surface-container-low/70 flex items-center justify-between">
                    <span class="text-on-surface-variant">Mã tham chiếu (TxnRef):</span>
                    <span class="font-mono text-primary">
                        <?= !empty($verify['txn_ref']) ? htmlspecialchars($verify['txn_ref']) : 'N/A' ?>
                    </span>
                </div>

                <!-- Thời gian thanh toán -->
                <div class="p-3.5 rounded-xl bg-surface-container-low/70 flex items-center justify-between">
                    <span class="text-on-surface-variant">Thời gian phản hồi:</span>
                    <span class="text-primary font-medium">
                        <?= htmlspecialchars($payDateFormatted) ?>
                    </span>
                </div>

                <!-- Loại thẻ / Cổng -->
                <div class="p-3.5 rounded-xl bg-surface-container-low/70 flex items-center justify-between">
                    <span class="text-on-surface-variant">Loại thẻ / Tài khoản:</span>
                    <span class="text-primary font-medium">
                        <?= !empty($verify['card_type']) ? htmlspecialchars($verify['card_type']) : 'Thẻ ATM / QR Code' ?>
                    </span>
                </div>
            </div>

            <!-- Nội dung thanh toán -->
            <?php if (!empty($verify['order_info'])): ?>
                <div class="mt-3.5 p-3 rounded-xl bg-surface-container-low/40 text-xs text-on-surface-variant">
                    <span class="font-medium text-primary">Nội dung chuyển khoản:</span>
                    <span class="italic"><?= htmlspecialchars($verify['order_info']) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Khối Nút Hành Động -->
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-center gap-3.5">
            <?php if ($isSuccess): ?>
                <a href="<?= base_url('tai-khoan/don-hang/' . $orderId) ?>"
                   class="w-full sm:w-auto px-6 py-3 rounded-xl bg-primary text-on-primary hover:bg-primary-dim font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">receipt</span>
                    <span>Xem Chi Tiết Đơn Hàng</span>
                </a>
                <a href="<?= base_url('tim-kiem') ?>"
                   class="w-full sm:w-auto px-6 py-3 rounded-xl border border-surface-container hover:bg-surface-container font-semibold text-primary text-xs transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">menu_book</span>
                    <span>Tiếp Tục Khám Phá Sách</span>
                </a>
            <?php else: ?>
                <?php if ($orderId > 0): ?>
                    <a href="<?= base_url('thanh-toan/vnpay-retry/' . $orderId) ?>"
                       class="w-full sm:w-auto px-6 py-3 rounded-xl bg-secondary text-on-secondary hover:bg-secondary-dim font-bold text-xs shadow-md transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">refresh</span>
                        <span>Thử Thanh Toán Lại Bằng VNPAY</span>
                    </a>
                    <a href="<?= base_url('tai-khoan/don-hang/' . $orderId) ?>"
                       class="w-full sm:w-auto px-6 py-3 rounded-xl border border-surface-container hover:bg-surface-container font-semibold text-primary text-xs transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                        <span>Xem Trạng Thái Đơn Hàng</span>
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('gio-hang') ?>"
                       class="w-full sm:w-auto px-6 py-3 rounded-xl bg-primary text-on-primary hover:bg-primary-dim font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                        <span>Quay Lại Giỏ Hàng</span>
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

