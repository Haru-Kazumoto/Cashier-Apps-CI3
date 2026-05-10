<!-- application/views/products/index.php -->
<!-- Dirender dengan $layout = 'admin', $active_menu = 'products' -->

<?php $navy = '#1e3a5f'; ?>

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Produk</h1>
        <p class="text-sm text-slate-500 mt-0.5"><?= count($produk ?? []) ?> produk dalam katalog</p>
    </div>
    <a href="<?= base_url('index.php/superadmin/products/tambah') ?>"
        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white rounded-lg transition-opacity hover:opacity-90"
        style="background-color: <?= $navy ?>;">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah Produk
    </a>
</div>

<!-- Filter & Search -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
    
    <div class="sm:col-span-2 relative">
        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </span>
        <input id="search-produk" type="text" placeholder="Cari produk..."
            class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none transition-colors"
            onfocus="this.style.borderColor='<?= $navy ?>';"
            onblur="this.style.borderColor='';">
    </div>
    <select id="filter-stok"
        class="px-4 py-2.5 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none transition-colors"
        onfocus="this.style.borderColor='<?= $navy ?>';"
        onblur="this.style.borderColor='';">
        <option value="all">Semua stok</option>
        <option value="aman">Stok aman (>5)</option>
        <option value="menipis">Stok menipis (≤5)</option>
        <option value="habis">Stok habis (0)</option>
    </select>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Produk</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Kategori</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Harga</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Stok</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody id="produk-tbody" class="divide-y divide-slate-100">
                <?php if (!empty($produk)): ?>
                    <?php foreach ($produk as $p): ?>
                        <?php
                        $stok = (int) ($p->stock ?? 0);
                        $stok_class = $stok === 0 ? 'habis' : ($stok <= 5 ? 'menipis' : 'aman');
                        ?>
                        <tr class="produk-row hover:bg-slate-50 transition-colors"
                            data-search="<?= strtolower($p->name) ?>"
                            data-stok-class="<?= $stok_class ?>">

                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 flex items-center justify-center"
                                        style="background-color: rgba(30,58,95,0.08);">
                                        <?php if (!empty($p->image)): ?>
                                            <img src="<?= base_url('uploads/produk/' . $p->image) ?>"
                                                alt="<?= $p->name ?>"
                                                class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <svg class="w-4 h-4" style="color: <?= $navy ?>;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800"><?= $p->name ?></p>
                                        <p class="text-xs text-slate-400">ID #<?= $p->id ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                <?= !empty($p->category_name) ? $p->category_name : '<span class="text-slate-300">—</span>' ?>
                            </td>
                            <td class="px-5 py-3 text-right font-semibold text-slate-800">
                                Rp <?= number_format($p->price, 0, ',', '.') ?>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    <?= $stok === 0 ? 'bg-red-100 text-red-700' : ($stok <= 5 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') ?>">
                                    <?= $stok ?>
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="<?= base_url('index.php/kasir/produk/edit/' . $p->id) ?>"
                                    class="inline-flex w-8 h-8 rounded-lg items-center justify-center text-slate-500 hover:bg-slate-100 transition-colors"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button type="button"
                                    onclick="confirmHapus(<?= $p->id ?>, '<?= addslashes($p->name) ?>')"
                                    class="inline-flex w-8 h-8 rounded-lg items-center justify-center text-red-500 hover:bg-red-50 transition-colors"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center">
                            <p class="text-sm text-slate-400">Belum ada produk</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Hapus -->
<div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
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
                class="flex-1 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                Batal
            </button>
            <a id="btn-confirm-hapus" href="#"
                class="flex-1 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors text-center">
                Hapus
            </a>
        </div>
    </div>
</div>

<script>
    (function() {
        const search = document.getElementById('search-produk');
        const filter = document.getElementById('filter-stok');
        const rows = document.querySelectorAll('.produk-row');

        function applyFilters() {
            const q = search.value.toLowerCase();
            const f = filter.value;

            rows.forEach(row => {
                const matchSearch = row.dataset.search.includes(q);
                const matchFilter = f === 'all' || row.dataset.stokClass === f;
                row.classList.toggle('hidden', !(matchSearch && matchFilter));
            });
        }

        search.addEventListener('input', applyFilters);
        filter.addEventListener('change', applyFilters);

        // ===== Hapus =====
        const modalHapus = document.getElementById('modal-hapus');

        window.confirmHapus = function(id, nama) {
            document.getElementById('hapus-nama').textContent = nama;
            document.getElementById('btn-confirm-hapus').href = '<?= base_url('index.php/kasir/produk/delete/') ?>' + id;
            modalHapus.classList.remove('hidden');
            modalHapus.classList.add('flex');
        };
        window.closeHapus = function() {
            modalHapus.classList.add('hidden');
            modalHapus.classList.remove('flex');
        };
        modalHapus.addEventListener('click', (e) => {
            if (e.target === modalHapus) closeHapus();
        });
    })();
</script>