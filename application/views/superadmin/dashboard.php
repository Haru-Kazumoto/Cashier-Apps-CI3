<!-- application/views/dashboard/index.php -->
<!-- Dirender dengan $layout = 'admin', $active_menu = 'dashboard' -->

<?php $navy = '#1e3a5f'; ?>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    <?php
    $stats = [
        [
            'label' => 'Total Pengguna',
            'value' => $total_users ?? 0,
            'sub'   => 'Akun aktif',
            'color' => '#1e3a5f',
            'light' => '#eef2ff',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
        ],
        [
            'label' => 'Transaksi Hari Ini',
            'value' => $transaksi_hari_ini ?? 0,
            'sub'   => 'Selesai diproses',
            'color' => '#0f766e',
            'light' => '#f0fdf9',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
        ],
        [
            'label' => 'Total Produk',
            'value' => $total_produk ?? 0,
            'sub'   => 'Tersedia di katalog',
            'color' => '#b45309',
            'light' => '#fffbeb',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
        ],
        [
            'label' => 'Omzet Hari Ini',
            'value' => 'Rp ' . number_format($omzet_hari_ini ?? 0, 0, ',', '.'),
            'sub'   => 'Total penjualan',
            'color' => '#7c3aed',
            'light' => '#f5f3ff',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        ],
    ];
    foreach ($stats as $s): ?>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4"
                style="background:<?= $s['light'] ?>;">
                <svg class="w-5 h-5" fill="none" stroke="<?= $s['color'] ?>" stroke-width="2" viewBox="0 0 24 24">
                    <?= $s['icon'] ?>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-800 mb-0.5"><?= $s['value'] ?></p>
            <p class="text-sm text-slate-500"><?= $s['label'] ?></p>
            <p class="text-xs text-slate-400 mt-2"><?= $s['sub'] ?></p>
        </div>
    <?php endforeach; ?>
</div>

<!-- Content Row -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
    <!-- Transaksi Terbaru (2/3) -->
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Transaksi Terbaru</h2>
                <p class="text-xs text-slate-400 mt-0.5">5 transaksi paling baru</p>
            </div>
            <a href="<?= base_url('transactions/reports') ?>"
                class="text-xs font-medium hover:underline" style="color: <?= $navy ?>;">
                Lihat semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Invoice</th>
                        <th class="pb-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Kasir</th>
                        <th class="pb-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Waktu</th>
                        <th class="pb-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Metode</th>
                        <th class="pb-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wide">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($transaksi_terbaru)): ?>
                        <?php foreach ($transaksi_terbaru as $t): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 pr-4 font-medium text-slate-700">#<?= $t['invoice_number'] ?></td>
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-[10px] font-semibold flex-shrink-0"
                                            style="background-color: <?= $navy ?>;">
                                            <?= strtoupper(substr($t['kasir'] ?? 'K', 0, 1)) ?>
                                        </div>
                                        <span class="text-slate-600"><?= $t['kasir'] ?? '—' ?></span>
                                    </div>
                                </td>
                                <td class="py-3 pr-4 text-slate-400 text-xs">
                                    <?= date('d M, H:i', strtotime($t['created_at'])) ?>
                                </td>
                                <td class="py-3 pr-4">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium capitalize
                                        <?= $t['payment_method'] === 'tunai' ? 'bg-emerald-50 text-emerald-700' : ($t['payment_method'] === 'transfer' ? 'bg-blue-50 text-blue-700' : 'bg-violet-50 text-violet-700') ?>">
                                        <?= $t['payment_method'] ?>
                                    </span>
                                </td>
                                <td class="py-3 text-right font-semibold text-slate-800">
                                    Rp <?= number_format($t['total_price'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <p class="text-sm text-slate-400">Belum ada transaksi hari ini</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Panel (1/3) -->
    <div class="flex flex-col gap-5">

        <!-- Stok Menipis -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-800">Stok Menipis</h2>
                <a href="<?= base_url('products') ?>"
                    class="text-xs font-medium hover:underline" style="color: <?= $navy ?>;">Kelola</a>
            </div>

            <?php if (!empty($stok_menipis)): ?>
                <div class="space-y-3">
                    <?php foreach (array_slice($stok_menipis, 0, 5) as $p): ?>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 bg-red-50">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-700 truncate"><?= $p['name'] ?></p>
                                <p class="text-xs text-slate-400">Sisa <?= $p['stock'] ?? 0 ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-6">
                    <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-emerald-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-xs text-slate-500">Semua stok aman</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Aksi Cepat -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="text-sm font-semibold text-slate-800 mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-2 gap-3">
                <?php
                $actions = [
                    [
                        'label' => 'Tambah User',
                        'href'  => 'superadmin/users',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>',
                    ],
                    [
                        'label' => 'Tambah Produk',
                        'href'  => 'superadmin/products/tambah',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>',
                    ],
                    [
                        'label' => 'Lihat Laporan',
                        'href'  => 'superadmin/transactions',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                    ],
                    [
                        'label' => 'Logout',
                        'href'  => 'logout',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>',
                    ],
                ];
                foreach ($actions as $a): ?>
                    <a href="<?= base_url('index.php/'.$a['href']) ?>"
                        class="flex flex-col items-center gap-2 p-3 rounded-xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50 transition-all group">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform"
                            style="background: rgba(30,58,95,0.08);">
                            <svg class="w-4 h-4" fill="none" stroke="<?= $navy ?>" stroke-width="2" viewBox="0 0 24 24">
                                <?= $a['icon'] ?>
                            </svg>
                        </div>
                        <span class="text-xs text-slate-600 text-center leading-tight font-medium"><?= $a['label'] ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>