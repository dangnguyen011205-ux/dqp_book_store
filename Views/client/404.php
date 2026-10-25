<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>404 - Không Tìm Thấy Trang | <?= APP_NAME ?></title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
    tailwind.config={darkMode:"class",theme:{extend:{colors:{"secondary-container":"#86f2e4","surface-container-low":"#f2f4f6","outline-variant":"#c3c6ce","surface":"#f7f9fb","surface-container-high":"#e6e8ea","primary-container":"#0f2942","secondary":"#006a61","primary":"#001428","background":"#f7f9fb","on-primary":"#ffffff","on-secondary":"#ffffff"},fontFamily:{sans:["Plus Jakarta Sans","sans-serif"]}}}};
    </script>
</head>
<body class="bg-background font-sans text-slate-800 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl p-8 md:p-12 text-center border border-slate-100">
        <div class="w-20 h-20 mx-auto rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-[44px]">menu_book</span>
        </div>
        <h1 class="text-4xl font-bold text-primary mb-3">404</h1>
        <h2 class="text-xl font-semibold text-slate-700 mb-3">Trang Sách Chưa Được Mở</h2>
        <p class="text-slate-500 mb-8 text-sm leading-relaxed">
            <?= htmlspecialchars($message ?? 'Địa chỉ bạn đang tìm kiếm không tồn tại hoặc đã được chuyển sang một kệ sách khác.') ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="<?= base_url() ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-primary text-white font-semibold hover:bg-secondary transition-colors text-sm shadow">
                <span class="material-symbols-outlined text-[18px]">home</span>
                Về Trang Chủ
            </a>
            <a href="<?= base_url('tim-kiem') ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-surface-container-low text-primary font-semibold hover:bg-slate-200 transition-colors text-sm">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Tìm Kiếm Sách
            </a>
        </div>
    </div>
</body>
</html>
