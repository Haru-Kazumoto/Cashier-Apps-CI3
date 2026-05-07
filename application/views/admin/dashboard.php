<!-- application/views/kasir/dashboard.php -->
<!-- Dirender via layout: $layout = 'kasir', $active_menu = 'beranda' -->

<?php $navy = '#1e3a5f'; ?>

<!-- Greeting -->
<div class="mb-6">
    <p class="text-sm text-slate-500">Selamat datang kembali,</p>
    <h1 class="text-2xl font-bold text-slate-800" style="font-family:'Playfair Display',serif;">
        <?= $user['nama'] ?? 'Kasir' ?>
    </h1>
    <p class="text-xs text-slate-400 mt-1"><?= date('l, d F Y') ?></p>
</div>

<!-- Stat Highlight (Omzet Hari Ini) -->
<div class="rounded-2xl p-5 mb-4 text-white" style="background-color: <?= $navy ?>;">
    <div class="flex items-center justify-between mb-2">
        <span class="text-xs uppercase tracking-wide text-white/60">Omzet Hari Ini</span>
        <span class="text-xs px-2 py-0.5 rounded-full bg-white/15">Live</span>
    </div>
    <p class="text-3xl font-bold mb-3">Rp <?= number_format($omzet_hari_ini ?? 0, 0, ',', '.') ?></p>
    <div class="flex items-center gap-4 pt-3 border-t border-white/15 text-sm">
        <div>
            <p class="text-white/60 text-xs">Transaksi</p>
            <p class="font-semibold"><?= $jumlah_transaksi ?? 0 ?></p>
        </div>
        <div class="w-px h-8 bg-white/15"></div>
        <div>
            <p class="text-white/60 text-xs">Produk Terjual</p>
            <p class="font-semibold"><?= $produk_terjual ?? 0 ?></p>
        </div>
    </div>
</div>

<!-- Mini Stats -->
<div class="grid grid-cols-2 gap-3 mb-6">
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-2" style="background-color: rgba(30,58,95,0.08);">
            <svg class="w-4 h-4" style="color: <?= $navy ?>;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <p class="text-xs text-slate-500">Total Stok</p>
        <p class="text-lg font-bold text-slate-800"><?= $total_stok ?? 0 ?></p>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-2 bg-red-50">
            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <p class="text-xs text-slate-500">Stok Menipis</p>
        <p class="text-lg font-bold text-slate-800"><?= $stok_menipis ?? 0 ?></p>
    </div>
</div>

<!-- Quick Action -->
<a href="<?= site_url('kasir/transaksi') ?>" class="block w-full mb-7 rounded-xl py-4 px-5 text-white font-semibold text-center transition-opacity hover:opacity-90 active:opacity-80" style="background-color: <?= $navy ?>;">
    <div class="flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Mulai Transaksi Baru
    </div>
</a>

<!-- Recent Transactions -->
<div class="mb-4">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-slate-800">Transaksi Terbaru</h2>
        <a href="<?= site_url('kasir/laporan') ?>" class="text-xs font-medium" style="color: <?= $navy ?>;">Lihat semua</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl divide-y divide-slate-100">
        <?php if (!empty($transaksi_terbaru)): ?>
            <?php foreach ($transaksi_terbaru as $t): ?>
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background-color: rgba(30,58,95,0.08);">
                            <svg class="w-4 h-4" style="color: <?= $navy ?>;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">#<?= $t['kode'] ?? 'TRX001' ?></p>
                            <p class="text-xs text-slate-400"><?= $t['waktu'] ?? '10:24' ?> · <?= $t['item'] ?? 0 ?> item</p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-slate-800">Rp <?= number_format($t['total'] ?? 0, 0, ',', '.') ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="px-4 py-10 text-center text-sm text-slate-400">
                Belum ada transaksi hari ini.
            </div>
        <?php endif; ?>
    </div>
</div>

