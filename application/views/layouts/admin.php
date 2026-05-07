<!-- application/views/layouts/kasir.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' — Aksir' : 'Aksir Kasir' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-active { color: #1e3a5f; }
        .nav-active .nav-icon-wrap { background-color: rgba(30, 58, 95, 0.1); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen pb-24">

<?php
    $navy = '#1e3a5f';
    $active = $active_menu ?? 'beranda';

    $menus = [
        ['key' => 'beranda',   'label' => 'Beranda',   'url' => 'kasir/dashboard'],
        ['key' => 'transaksi', 'label' => 'Transaksi', 'url' => 'kasir/transaksi'],
        ['key' => 'produk',    'label' => 'Produk',    'url' => 'kasir/produk'],
        ['key' => 'laporan',   'label' => 'Laporan',   'url' => 'kasir/laporan'],
        ['key' => 'profil',    'label' => 'Profil',    'url' => 'kasir/profil'],
    ];
?>

<!-- Top Header -->
<header class="bg-white border-b border-slate-200 sticky top-0 z-30">
    <div class="max-w-3xl mx-auto px-5 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: <?= $navy ?>;">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
            </div>
            <span class="text-lg font-bold" style="color: <?= $navy ?>; font-family:'Playfair Display',serif;">Aksir</span>
        </div>

        <div class="flex items-center gap-1">
            <button class="p-2 text-slate-500 hover:text-slate-700 relative" aria-label="Notifikasi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full" style="background-color: <?= $navy ?>;"></span>
            </button>
            <a href="<?= site_url('logout') ?>" class="p-2 text-slate-500 hover:text-red-600 transition-colors" aria-label="Keluar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </a>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="max-w-3xl mx-auto px-5 py-6">
    <?= $content ?>
</main>

<!-- Bottom Navbar -->
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-40 shadow-[0_-2px_10px_rgba(0,0,0,0.04)]">
    <div class="max-w-3xl mx-auto grid grid-cols-5 px-2 py-2">
        <?php foreach ($menus as $m): ?>
            <?php $is = $active === $m['key']; ?>
            <a href="<?= site_url($m['url']) ?>" class="flex flex-col items-center justify-center gap-1 py-1.5 transition-colors <?= $is ? 'nav-active' : 'text-slate-400 hover:text-slate-600' ?>">
                <span class="nav-icon-wrap w-10 h-7 rounded-lg flex items-center justify-center transition-colors">
                    <?php if ($m['key'] === 'beranda'): ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <?php elseif ($m['key'] === 'transaksi'): ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <?php elseif ($m['key'] === 'produk'): ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <?php elseif ($m['key'] === 'laporan'): ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <?php else: ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <?php endif; ?>
                </span>
                <span class="text-[11px] font-medium"><?= $m['label'] ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</nav>

</body>
</html>