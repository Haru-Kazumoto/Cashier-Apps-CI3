<!-- application/views/kasir/produk/index.php -->
<!-- Dirender via layout: $layout = 'kasir', $active_menu = 'produk' -->

<?php $navy = '#1e3a5f'; ?>

<!-- Header -->
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-slate-800" style="font-family:'Playfair Display',serif;">Produk</h1>
        <p class="text-xs text-slate-400 mt-0.5"><?= count($produk ?? []) ?> produk terdaftar</p>
    </div>
    <a href="<?= site_url('kasir/produk/tambah') ?>"
        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white rounded-xl transition-opacity hover:opacity-90"
        style="background-color: <?= $navy ?>;">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah
    </a>
</div>

<!-- Flash message -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="mb-4 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Search -->
<div class="relative mb-4">
    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </span>
    <input id="search-produk" type="text" placeholder="Cari produk..."
        class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-white focus:outline-none transition-colors"
        onfocus="this.style.borderColor='<?= $navy ?>';"
        onblur="this.style.borderColor='';">
</div>

<!-- List Produk -->
<div id="produk-list" class="bg-white border border-slate-200 rounded-xl divide-y divide-slate-100 mb-4">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $p): ?>
            <div class="produk-row flex items-stretch border-b border-slate-100 last:border-0"
                data-nama="<?= strtolower($p->name) ?>">

                <!-- Gambar — lebih besar, stretch penuh -->
                <div class="w-20 flex-shrink-0 bg-slate-100 relative" style="min-height: 72px;">
                    <?php if (!empty($p->image)): ?>
                        <img src="<?= base_url() ?>uploads/produk/<?= $p->image ?>"
                            alt="<?= $p->name ?>"
                            class="absolute inset-0 w-full h-full object-cover">
                    <?php else: ?>
                        <div class="absolute inset-0 flex items-center justify-center text-slate-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0 px-3 py-2.5 flex flex-col justify-center gap-1">
                    <p class="text-sm font-semibold text-slate-800 truncate"><?= $p->name ?></p>
                    <p class="text-sm font-bold" style="color: <?= $navy ?>;">
                        Rp <?= number_format($p->price, 0, ',', '.') ?>
                    </p>
                    <div class="flex items-center gap-1.5">
                        <?php if (($p->stock ?? 0) <= 5): ?>
                            <span class="text-xs font-semibold text-red-500 flex items-center gap-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                </svg>
                                Stok: <?= $p->stock ?? 0 ?>
                            </span>
                        <?php else: ?>
                            <span class="text-xs text-slate-400">Stok: <?= $p->stock ?? 0 ?></span>
                        <?php endif; ?>
                        <?php if (!empty($p->category_name)): ?>
                            <span class="text-slate-300 text-xs">·</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 border border-slate-200">
                                <?= $p->category_name ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions — vertikal di kanan -->
                <div class="flex flex-col justify-center gap-1 pr-2 pl-1 flex-shrink-0">
                    <a href="<?= site_url('kasir/produk/edit/' . $p->id) ?>"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                        title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <button type="button"
                        onclick="confirmHapus(<?= $p->id ?>, '<?= addslashes($p->name) ?>')"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:bg-red-50 hover:text-red-500 transition-colors"
                        title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                        </svg>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="px-4 py-12 text-center">
            <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center" style="background-color: rgba(30,58,95,0.08);">
                <svg class="w-5 h-5" style="color: <?= $navy ?>;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <p class="text-sm text-slate-500 mb-1">Belum ada produk</p>
            <p class="text-xs text-slate-400">Tambahkan produk pertamamu</p>
        </div>
    <?php endif; ?>
</div>

<!-- Empty state untuk hasil search -->
<div id="search-empty" class="hidden text-center py-8 text-sm text-slate-400">
    Tidak ada produk yang cocok.
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-5">
    <div class="bg-white rounded-2xl p-6 w-full max-w-sm">
        <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-800 text-center mb-1">Hapus Produk?</h3>
        <p class="text-sm text-slate-500 text-center mb-5">
            Produk <span id="hapus-nama" class="font-semibold text-slate-700"></span> akan dihapus permanen.
        </p>
        <div class="flex gap-2">
            <button type="button" onclick="closeHapus()"
                class="flex-1 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                Batal
            </button>
            <a id="btn-confirm-hapus" href="#"
                class="flex-1 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl transition-colors text-center">
                Hapus
            </a>
        </div>
    </div>
</div>

<script>
    (function() {
        // Search filter
        const search = document.getElementById('search-produk');
        const rows = document.querySelectorAll('.produk-row');
        const empty = document.getElementById('search-empty');

        search.addEventListener('input', (e) => {
            const q = e.target.value.toLowerCase();
            let visible = 0;
            rows.forEach(row => {
                const match = row.dataset.nama.includes(q);
                row.classList.toggle('hidden', !match);
                if (match) visible++;
            });
            empty.classList.toggle('hidden', visible > 0 || rows.length === 0);
        });

        // Modal hapus
        window.confirmHapus = function(id, nama) {
            document.getElementById('hapus-nama').textContent = nama;
            document.getElementById('btn-confirm-hapus').href = '<?= site_url('kasir/produk/hapus/') ?>' + id;
            const modal = document.getElementById('modal-hapus');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        window.closeHapus = function() {
            const modal = document.getElementById('modal-hapus');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };

        // Close modal saat klik backdrop
        document.getElementById('modal-hapus').addEventListener('click', (e) => {
            if (e.target.id === 'modal-hapus') closeHapus();
        });
    })();
</script>