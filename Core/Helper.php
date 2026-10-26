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

if (!function_exists('get_book_cover')) {
    function get_book_cover(?string $anhBia): string {
        $fallbackMap = [
            've_di_tuoi_tho.jpg' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDOW-5R4g1lDrfnMUOBxJj5M7wo1WvsCQsDwqCLZmRbhk62RKvqRmJ6kw99wmW4BL3kUyyuaJxRgNogYU_x2QdDcnRW_LBtMGmp79xXaMbxkRZ4bHIZoMpwXWDCJ7Pvls8dvP-ELZwksZT5YPUqgfGgxK5gSFD6V1vymZXlpL8eIseOO0YSoWJWC2quEc3Mx-vk7ZBDIFgGPjRMOFdjSbsGjFvpQi9TUtOpuFtb0eIbMSr9xSJA0Ea9',
            'mat_biec.jpg'       => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBCROvi1laus0XIWz6LpZ0P5HeiGIVthmPumUmskuWEVVOZ4g16-h2hSTzztGGZhp3ySzUs39iPTECYlyKqk3F-sF8zIYzga0ECMPk62VcPgJZiTrO5COR64605DXYENgpvk0xshkGjXydPbKKpYp1KJtjEyRYtzNrgEuMKLNLAdTT3Y1yZ5ru24pASdUUyHkZJAzNbXtYnYMHk1V34UsiXk0_J45PIDn4wCNahTosqdLaFovyqa9WF',
            'rung_na_uy.jpg'     => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB39kchJl2airUEEKvNTfjBIF1Au5AuIqYbzVd3O5AW9bhoYX8DD4iKlOh7ckPeiaov9e_7QU4iNI2n92tjJh_BJjhKLBwOl7IIgnM9D9fSDPm4Ft8xl5JLIgpqX20cfC-EFsO7Gg9sJ9WC8QXTjK84zwWY6qL6U1sHgA3eWQM7ptpHB2-a3ZTY389Jrth4Nt0hWFrtBBG_TFFefN8Wwyq93ImOQ6t7IqyAi96zrwGpfRBw1u3VdP0y',
            'dac_nhan_tam.jpg'   => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAUKERVQmDszz25tjdhGJ60sosu-sOcfvaC1RZfWv7AStopNuMi_XSLesJg9-0UgVd6UOaAw8mRt8xsMUg1rDkrSaQkuodwGR9EXPkk53zp7mg9pwkeaeb0F9LNIJ5gJ1TBQuDW-cJfvykpacXFoIyxBTrFXz5TA3a4ta-5hPFPhjiZLpKWTbmWMI6XZ1QTYx6qOJ22BRb4-cYJH9RnzP5cqVKONtQ-P9twqUAbxN_XOjY6kABmewFk',
            'clean_code.jpg'     => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAILnt2jy0fXHs8-Kz6PipSF15EraoOkGE17r5yZ-6i-yk08UovomgdSCTxKnn8IWUWwh9AVb39DQl_f-glZfVZ2kcqLICo6mAbmRXCkIvLQIQLpRBBuPlK2DJgZwD_WCK0S1NWUZ_4h1PYjRF5Yh7aud_twOOz7URBV1F3Ex105YAIzve59PV2W46hmi_YoxqJblnmTpvO9riKDFLWNQJ7EktUtK2hAodfs1Oa-3fMR6ueCB3o61ci',
            'nha_gia_kim.jpg'    => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAZ6n_FWa_IMAKlR9ocIW0hh47Cy9-wPVvDcRq3T2Weupyyo2Ed6SVlnhOMZYz7bn3dW4n-3XOpGtOJsyCbrzkf5Ku2I2a-3jDK2KBjCaWmv83sglAAvoNLwtCPKBVuG294MYWI2qWcEmiBrgi9o_YzCBJ2LrNvIeNQEuSkueeI2sP269-pZefkGWuLfYEZ8xhmg0DX4j6-mHKtaeDaxF7Or-irm4NKSRPwXXR9r05ciXqLfLME6of1'
        ];

        if (!empty($anhBia)) {
            if (str_starts_with($anhBia, 'http://') || str_starts_with($anhBia, 'https://')) {
                return $anhBia;
            }
            if (defined('ROOT_PATH')) {
                $localPath = ROOT_PATH . '/Public/uploads/books/' . $anhBia;
                if (file_exists($localPath)) {
                    return asset('uploads/books/' . $anhBia);
                }
            }
            if (isset($fallbackMap[$anhBia])) {
                return $fallbackMap[$anhBia];
            }
        }
        return 'https://lh3.googleusercontent.com/aida-public/AB6AXuAUz2h5NbRRpQxcVtF5NPJD_iS9y0RXn5uwAPV0D9DJcKseQpT7V-mhEMQrFOtRq9VGbSv5umrMiFHWzcQoyNiuSN0Q5GWTjaPPf7tDrnd0UM41X0iM8jiG6rSV-Qso0bjWxZZwKU3ZW-CMRmhOd2jEnf0IIVCt_vBGUR1D7PQcAHq6Wf1_lJqQj7u5F1ZhpKyX1w15CdFG0PsLVH263eNelWhvV99odJk1qBi7NxYEbClVt4p3Z0vQ';
    }
}
