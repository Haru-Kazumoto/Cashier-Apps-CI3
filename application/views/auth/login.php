<!-- application/views/auth/login.php -->
<!-- Dirender melalui layout dengan $layout = 'auth' -->

<?php $navy = '#1e3a5f'; ?>

<div class="min-h-screen flex bg-white">

    <!-- Left Panel — Form Login -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">

            <!-- Logo -->
            <div class="flex items-center gap-2 mb-10">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: <?= $navy ?>;">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <span class="text-2xl font-bold" style="color: <?= $navy ?>; font-family:'Playfair Display',serif;">Aksir</span>
            </div>

            <div class="mb-7">
                <h3 class="text-2xl font-bold text-slate-800 mb-1" style="font-family:'Playfair Display',serif;">Masuk ke Akun</h3>
                <p class="text-sm text-slate-500">Masukkan kredensial Anda untuk melanjutkan</p>
            </div>

            <!-- Flash error -->
            <?php if ($this->session->flashdata('login_error')): ?>
                <div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <?= $this->session->flashdata('login_error') ?>
                </div>
            <?php endif; ?>

            <?= form_open('authenticate', ['class' => 'space-y-5']) ?>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-slate-700 mb-1.5">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?= set_value('username') ?>"
                    placeholder="Masukkan username"
                    autocomplete="username"
                    required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition-colors"
                    onfocus="this.style.borderColor='<?= $navy ?>';"
                    onblur="this.style.borderColor='';">
                <?php if (form_error('username')): ?>
                    <p class="mt-1 text-xs text-red-500"><?= form_error('username') ?></p>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                        class="w-full px-4 py-2.5 pr-11 border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition-colors"
                        onfocus="this.style.borderColor='<?= $navy ?>';"
                        onblur="this.style.borderColor='';">
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg id="eye-icon" class="w-4 h-4 text-slate-400 hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <?php if (form_error('password')): ?>
                    <p class="mt-1 text-xs text-red-500"><?= form_error('password') ?></p>
                <?php endif; ?>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full py-2.5 text-sm font-semibold text-white rounded-lg transition-opacity hover:opacity-90 active:opacity-80 mt-2"
                style="background-color: <?= $navy ?>;">
                Masuk
            </button>

            <?= form_close() ?>

            <p class="text-center text-xs text-slate-400 mt-8">&copy; <?= date('Y') ?> Aksir System</p>
        </div>
    </div>

    <!-- Right Panel — Branding Kasir -->
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-center px-16 py-14" style="background-color: <?= $navy ?>;">

        <h2 class="text-4xl font-bold text-white leading-tight mb-5" style="font-family:'Playfair Display',serif;">
            Aksir Kasir.<br>
            Cepat, Rapi, Akurat.
        </h2>
        <p class="text-white/70 text-base leading-relaxed mb-10 max-w-md">
            Aplikasi point-of-sale untuk mengelola transaksi, stok barang, dan laporan penjualan harian dalam satu tempat yang sederhana.
        </p>

        <div class="space-y-5">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white">Transaksi Cepat</h4>
                    <p class="text-xs text-white/60 mt-0.5">Proses pembayaran dengan beberapa klik saja.</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white">Manajemen Stok</h4>
                    <p class="text-xs text-white/60 mt-0.5">Pantau ketersediaan barang secara otomatis.</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h4m0 0l-3-3m3 3l-3 3M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white">Laporan Penjualan</h4>
                    <p class="text-xs text-white/60 mt-0.5">Rekap harian dan bulanan tersedia kapan saja.</p>
                </div>
            </div>
        </div>

        <p class="text-xs text-white/40 mt-12">&copy; <?= date('Y') ?> Aksir. All rights reserved.</p>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>