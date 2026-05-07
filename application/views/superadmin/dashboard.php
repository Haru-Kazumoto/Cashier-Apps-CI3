<!-- application/views/dashboard/index.php -->
<!-- Dirender dengan $layout = 'dashboard' -->

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    <?php
    $stats = [
        [
            'label' => 'Total Pengguna',
            'value' => $total_users ?? '0',
            'change' => '+12% bulan ini',
            'up' => true,
            'color' => '#1e3a5f',
            'light' => '#eef2ff',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
        ],
        [
            'label' => 'Transaksi Hari Ini',
            'value' => $transaksi_hari_ini ?? '0',
            'change' => '+5% dari kemarin',
            'up' => true,
            'color' => '#0f766e',
            'light' => '#f0fdf9',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
        ],
        [
            'label' => 'Total Laporan',
            'value' => $total_laporan ?? '0',
            'change' => '-2% minggu ini',
            'up' => false,
            'color' => '#b45309',
            'light' => '#fffbeb',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
        ],
        [
            'label' => 'Aktivitas Sistem',
            'value' => $aktivitas ?? '0',
            'change' => '+8% hari ini',
            'up' => true,
            'color' => '#7c3aed',
            'light' => '#f5f3ff',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
        ],
    ];
    foreach ($stats as $s): ?>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                    style="background:<?= $s['light'] ?>;">
                    <svg class="w-5 h-5" fill="none" stroke="<?= $s['color'] ?>" stroke-width="2" viewBox="0 0 24 24">
                        <?= $s['icon'] ?>
                    </svg>
                </div>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full <?= $s['up'] ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' ?>">
                    <?= $s['up'] ? '↑' : '↓' ?> <?= $s['change'] ?>
                </span>
            </div>
            <p class="text-2xl font-bold text-slate-800 mb-0.5"><?= $s['value'] ?></p>
            <p class="text-sm text-slate-500"><?= $s['label'] ?></p>
        </div>
    <?php endforeach; ?>
</div>

<!-- Content Row -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    <!-- Aktivitas Terbaru (2/3) -->
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-sm font-semibold text-slate-800">Aktivitas Terbaru</h2>
            <a href="<?= base_url('laporan') ?>" class="text-xs font-medium" style="color:#1e3a5f;">Lihat semua →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Pengguna</th>
                        <th class="pb-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Aksi</th>
                        <th class="pb-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Waktu</th>
                        <th class="pb-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($aktivitas_log)): ?>
                        <?php foreach ($aktivitas_log as $log): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                                            style="background:linear-gradient(135deg,#1e3a5f,#2563eb);">
                                            <?= strtoupper(substr($log['username'], 0, 1)) ?>
                                        </div>
                                        <span class="font-medium text-slate-700"><?= $log['username'] ?></span>
                                    </div>
                                </td>
                                <td class="py-3 pr-4 text-slate-500"><?= $log['aksi'] ?></td>
                                <td class="py-3 pr-4 text-slate-400 text-xs"><?= $log['waktu'] ?></td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    <?= $log['status'] === 'Berhasil' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' ?>">
                                        <?= $log['status'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Dummy rows jika data kosong -->
                        <?php
                        $dummy = [
                            ['user' => 'Admin', 'aksi' => 'Login ke sistem', 'waktu' => '2 menit lalu', 'status' => 'Berhasil'],
                            ['user' => 'Budi', 'aksi' => 'Tambah laporan baru', 'waktu' => '15 menit lalu', 'status' => 'Berhasil'],
                            ['user' => 'Sari', 'aksi' => 'Update data pengguna', 'waktu' => '1 jam lalu', 'status' => 'Berhasil'],
                            ['user' => 'Unknown', 'aksi' => 'Percobaan login', 'waktu' => '2 jam lalu', 'status' => 'Gagal'],
                            ['user' => 'Doni', 'aksi' => 'Ekspor laporan PDF', 'waktu' => '3 jam lalu', 'status' => 'Berhasil'],
                        ];
                        foreach ($dummy as $d): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                                            style="background:linear-gradient(135deg,#1e3a5f,#2563eb);">
                                            <?= strtoupper(substr($d['user'], 0, 1)) ?>
                                        </div>
                                        <span class="font-medium text-slate-700"><?= $d['user'] ?></span>
                                    </div>
                                </td>
                                <td class="py-3 pr-4 text-slate-500"><?= $d['aksi'] ?></td>
                                <td class="py-3 pr-4 text-slate-400 text-xs"><?= $d['waktu'] ?></td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    <?= $d['status'] === 'Berhasil' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' ?>">
                                        <?= $d['status'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Panel (1/3) -->
    <div class="flex flex-col gap-5">

        <!-- Ringkasan Sistem -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="text-sm font-semibold text-slate-800 mb-4">Ringkasan Sistem</h2>
            <div class="space-y-4">
                <?php
                $infos = [
                    ['label' => 'Versi Aplikasi', 'val' => '1.0.0'],
                    ['label' => 'Framework', 'val' => 'CodeIgniter 3'],
                    ['label' => 'Status Server', 'val' => 'Online'],
                    ['label' => 'Login Terakhir', 'val' => date('d M Y, H:i')],
                ];
                foreach ($infos as $i): ?>
                    <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                        <span class="text-xs text-slate-500"><?= $i['label'] ?></span>
                        <span class="text-xs font-semibold text-slate-700 <?= $i['val'] === 'Online' ? 'text-emerald-600' : '' ?>">
                            <?= $i['val'] === 'Online' ? '<span class="flex items-center gap-1"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full inline-block"></span> Online</span>' : $i['val'] ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Aksi Cepat -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="text-sm font-semibold text-slate-800 mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-2 gap-3">
                <?php
                $actions = [
                    ['label' => 'Tambah User', 'href' => 'users/tambah', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>'],
                    ['label' => 'Buat Laporan', 'href' => 'laporan/buat', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>'],
                    ['label' => 'Pengaturan', 'href' => 'pengaturan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                    ['label' => 'Logout', 'href' => 'logout', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>'],
                ];
                foreach ($actions as $a): ?>
                    <a href="<?= base_url($a['href']) ?>"
                        class="flex flex-col items-center gap-2 p-3 rounded-xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50 transition-all group">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform"
                            style="background:#eef2ff;">
                            <svg class="w-4 h-4" fill="none" stroke="#1e3a5f" stroke-width="2" viewBox="0 0 24 24">
                                <?= $a['icon'] ?>
                            </svg>
                        </div>
                        <span class="text-xs text-slate-600 text-center leading-tight"><?= $a['label'] ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>