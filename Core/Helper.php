<?php
// Core/Helper.php

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string {
        $trimmed = ltrim($path, '/');
        return BASE_URL . ($trimmed !== '' ? '/' . $trimmed : '');
    }
}

if (!function_exists('asset')) {
    function asset(string $path = ''): string {
        return base_url('Public/' . ltrim($path, '/'));
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void {
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            $url = base_url($url);
        }
        header("Location: " . $url);
        exit;
    }
}

if (!function_exists('format_currency')) {
    function format_currency(float|int|string $amount): string {
        $num = (float)$amount;
        return number_format($num, 0, ',', '.') . 'đ';
    }
}

if (!function_exists('sanitize')) {
    function sanitize(mixed $data): string {
        if (is_array($data)) {
            return '';
        }
        return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('flash')) {
    function flash(string $key, ?string $message = null): ?string {
        if ($message !== null) {
            $_SESSION['flash'][$key] = $message;
            return null;
        }

        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }

        return null;
    }
}

if (!function_exists('set_flash')) {
    function set_flash(string $type, string $message): void {
        $_SESSION['flash'][$type] = $message;
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed {
        if (isset($_SESSION['old'][$key])) {
            $val = $_SESSION['old'][$key];
            unset($_SESSION['old'][$key]);
            return $val;
        }
        return $default;
    }
}

if (!function_exists('status_badge')) {
    function status_badge(string $status): string {
        $map = [
            'cho_xac_nhan' => [
                'label' => 'Chờ xác nhận',
                'bg'    => 'bg-amber-100',
                'text'  => 'text-amber-800',
                'border'=> 'border-amber-200',
                'icon'  => 'pending_actions'
            ],
            'cho_duyet' => [
                'label' => 'Chờ duyệt',
                'bg'    => 'bg-amber-100',
                'text'  => 'text-amber-800',
                'border'=> 'border-amber-200',
                'icon'  => 'pending_actions'
            ],
            'da_xac_nhan' => [
                'label' => 'Đã xác nhận',
                'bg'    => 'bg-slate-200',
                'text'  => 'text-slate-800',
                'border'=> 'border-slate-300',
                'icon'  => 'inventory'
            ],
            'dang_giao' => [
                'label' => 'Đang giao hàng',
                'bg'    => 'bg-sky-100',
                'text'  => 'text-sky-800',
                'border'=> 'border-sky-200',
                'icon'  => 'local_shipping'
            ],
            'hoan_thanh' => [
                'label' => 'Hoàn thành',
                'bg'    => 'bg-emerald-100',
                'text'  => 'text-emerald-800',
                'border'=> 'border-emerald-200',
                'icon'  => 'verified'
            ],
            'hien_thi' => [
                'label' => 'Hiển thị',
                'bg'    => 'bg-emerald-100',
                'text'  => 'text-emerald-800',
                'border'=> 'border-emerald-200',
                'icon'  => 'check_circle'
            ],
            'da_huy' => [
                'label' => 'Đã hủy',
                'bg'    => 'bg-rose-100',
                'text'  => 'text-rose-800',
                'border'=> 'border-rose-200',
                'icon'  => 'cancel'
            ],
            'an' => [
                'label' => 'Đã ẩn',
                'bg'    => 'bg-rose-100',
                'text'  => 'text-rose-800',
                'border'=> 'border-rose-200',
                'icon'  => 'warning'
            ],
            'nhap' => [
                'label' => 'Nhập kho',
                'bg'    => 'bg-emerald-100',
                'text'  => 'text-emerald-800',
                'border'=> 'border-emerald-200',
                'icon'  => 'add_circle'
            ],
            'xuat' => [
                'label' => 'Xuất kho',
                'bg'    => 'bg-rose-100',
                'text'  => 'text-rose-800',
                'border'=> 'border-rose-200',
                'icon'  => 'remove_circle'
            ],
            'dieu_chinh' => [
                'label' => 'Điều chỉnh',
                'bg'    => 'bg-indigo-100',
                'text'  => 'text-indigo-800',
                'border'=> 'border-indigo-200',
                'icon'  => 'tune'
            ]
        ];

        $info = $map[$status] ?? [
            'label' => ucfirst(str_replace('_', ' ', $status)),
            'bg'    => 'bg-gray-100',
            'text'  => 'text-gray-700',
            'border'=> 'border-gray-200',
            'icon'  => 'info'
        ];

        return sprintf(
            '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[12px] font-semibold %s %s border %s"><span class="material-symbols-outlined text-[14px]">%s</span>%s</span>',
            $info['bg'],
            $info['text'],
            $info['border'],
            $info['icon'],
            $info['label']
        );
    }
}

if (!function_exists('payment_status_badge')) {
    function payment_status_badge(string $status): string {
        $map = [
            'cho_thanh_toan' => [
                'label' => 'Chờ thanh toán',
                'bg'    => 'bg-amber-100',
                'text'  => 'text-amber-800',
                'icon'  => 'hourglass_top'
            ],
            'thanh_cong' => [
                'label' => 'Đã thanh toán',
                'bg'    => 'bg-emerald-100',
                'text'  => 'text-emerald-800',
                'icon'  => 'check_circle'
            ],
            'that_bai' => [
                'label' => 'Thất bại',
                'bg'    => 'bg-rose-100',
                'text'  => 'text-rose-800',
                'icon'  => 'error'
            ]
        ];

        $info = $map[$status] ?? [
            'label' => $status,
            'bg'    => 'bg-gray-100',
            'text'  => 'text-gray-700',
            'icon'  => 'help'
        ];

        return sprintf(
            '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[12px] font-semibold %s %s"><span class="material-symbols-outlined text-[14px]">%s</span>%s</span>',
            $info['bg'],
            $info['text'],
            $info['icon'],
            $info['label']
        );
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '" />';
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(?string $token): bool {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
    }
}

if (!function_exists('time_ago')) {
    function time_ago(string $datetime): string {
        $time = strtotime($datetime);
        $diff = time() - $time;
        if ($diff < 60) return 'Vừa xong';
        if ($diff < 3600) return floor($diff / 60) . ' phút trước';
        if ($diff < 86400) return floor($diff / 3600) . ' giờ trước';
        if ($diff < 2592000) return floor($diff / 86400) . ' ngày trước';
        return date('d/m/Y', $time);
    }
}
