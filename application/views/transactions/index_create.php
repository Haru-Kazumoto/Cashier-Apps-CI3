<!-- application/views/kasir/transaksi.php -->
<!-- Dirender via layout: $layout = 'kasir', $active_menu = 'transaksi' -->

<?php $navy = '#1e3a5f'; ?>

<!-- Step Indicator -->
<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-800 mb-4" style="font-family:'Playfair Display',serif;">Transaksi Baru</h1>

    <div class="flex items-center gap-2">
        <?php
        $steps = [
            ['n' => 1, 'label' => 'Pilih Produk'],
            ['n' => 2, 'label' => 'Checkout'],
            ['n' => 3, 'label' => 'Bayar'],
        ];
        ?>
        <?php foreach ($steps as $i => $s): ?>
            <div class="flex items-center gap-2 flex-1">
                <div data-step-indicator="<?= $s['n'] ?>"
                    class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold transition-all step-dot"
                    style="background-color: <?= $s['n'] === 1 ? $navy : '#e2e8f0' ?>; color: <?= $s['n'] === 1 ? '#fff' : '#94a3b8' ?>;">
                    <?= $s['n'] ?>
                </div>
                <span data-step-label="<?= $s['n'] ?>" class="text-xs font-medium <?= $s['n'] === 1 ? 'text-slate-800' : 'text-slate-400' ?>">
                    <?= $s['label'] ?>
                </span>
                <?php if ($i < count($steps) - 1): ?>
                    <div class="flex-1 h-px bg-slate-200 mx-1"></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ========== STEP 1: Pilih Produk ========== -->
<section data-step="1" class="step-section">

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

    <!-- Produk Grid -->
    <div id="produk-list" class="grid grid-cols-2 gap-3 mb-4">
        <?php if (!empty($produk)): ?>
            <?php foreach ($produk as $p): ?>
                <button type="button"
                    data-produk='<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>'
                    data-nama="<?= strtolower($p->name) ?>"
                    class="produk-item bg-white border border-slate-200 rounded-xl p-3 text-left hover:border-slate-300 transition-colors active:scale-95">

                    <div class="aspect-square rounded-lg overflow-hidden mb-2 bg-slate-100">

                        <?php if (!empty($p->image)): ?>
                            <img
                                src="<?= base_url('uploads/produk/' . $p->image) ?>"
                                alt="<?= $p->name ?>"
                                class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center"
                                style="background-color: rgba(30,58,95,0.06);">

                                <svg class="w-8 h-8"
                                    style="color: <?= $navy ?>;"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>

                            </div>
                        <?php endif; ?>

                    </div>

                    <p class="text-sm font-semibold text-slate-800 truncate">
                        <?= $p->name ?>
                    </p>

                    <p class="text-xs text-slate-400 mb-1.5">
                        Stok: <?= $p->stock ?>
                    </p>

                    <p class="text-sm font-bold" style="color: <?= $navy ?>;">
                        Rp <?= number_format($p->price, 0, ',', '.') ?>
                    </p>
                </button>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-2 py-10 text-center text-sm text-slate-400">Belum ada produk.</div>
        <?php endif; ?>
    </div>

    <!-- Floating cart summary -->
    <div id="cart-summary" class="fixed bottom-24 left-4 right-4 z-30 hidden">
        <div class="max-w-md mx-auto bg-white border border-slate-200 rounded-xl shadow-lg p-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: <?= $navy ?>;">
                    <span id="cart-count" class="text-white text-sm font-bold">0</span>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Total sementara</p>
                    <p id="cart-total-preview" class="text-sm font-bold text-slate-800">Rp 0</p>
                </div>
            </div>
            <button type="button" onclick="goToStep(2)"
                class="px-4 py-2 text-sm font-semibold text-white rounded-lg transition-opacity hover:opacity-90"
                style="background-color: <?= $navy ?>;">
                Lanjut →
            </button>
        </div>
    </div>
</section>

<!-- ========== STEP 2: Checkout ========== -->
<section data-step="2" class="step-section hidden">

    <div class="bg-white border border-slate-200 rounded-xl mb-4">
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800">Keranjang</h2>
            <button type="button" onclick="goToStep(1)" class="text-xs font-medium" style="color: <?= $navy ?>;">+ Tambah</button>
        </div>
        <div id="cart-items" class="divide-y divide-slate-100">
            <!-- diisi via JS -->
        </div>
        <div id="cart-empty" class="px-4 py-10 text-center text-sm text-slate-400 hidden">
            Keranjang kosong.
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="bg-white border border-slate-200 rounded-xl p-4 mb-4 space-y-2 text-sm">
        <div class="flex justify-between text-slate-600">
            <span>Subtotal</span>
            <span id="sum-subtotal">Rp 0</span>
        </div>
        <div class="flex justify-between text-slate-600">
            <span>Diskon</span>
            <div class="flex items-center gap-1">
                <span class="text-slate-400 text-xs">Rp</span>
                <input id="input-diskon" type="number" min="0" value="0"
                    class="w-24 text-right py-1 px-2 border border-slate-200 rounded-md text-sm focus:outline-none"
                    onfocus="this.style.borderColor='<?= $navy ?>';"
                    onblur="this.style.borderColor='';">
            </div>
        </div>
        <div class="flex justify-between font-bold text-slate-800 pt-2 border-t border-slate-100">
            <span>Total</span>
            <span id="sum-total" style="color: <?= $navy ?>;">Rp 0</span>
        </div>
    </div>

    <div class="flex gap-2">
        <button type="button" onclick="goToStep(1)"
            class="flex-1 py-3 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">
            ← Kembali
        </button>
        <button type="button" onclick="goToStep(3)" id="btn-to-bayar"
            class="flex-[2] py-3 text-sm font-semibold text-white rounded-xl transition-opacity hover:opacity-90 disabled:opacity-40"
            style="background-color: <?= $navy ?>;">
            Lanjut ke Pembayaran →
        </button>
    </div>
</section>

<!-- ========== STEP 3: Bayar ========== -->
<section data-step="3" class="step-section hidden">

    <form id="form-bayar" method="post" action="<?= site_url('kasir/transaksi/save') ?>">
        <input type="hidden" name="cart_data" id="cart-data-input">
        <input type="hidden" name="diskon" id="diskon-input">

        <!-- Total tagihan -->
        <div class="rounded-2xl p-5 mb-4 text-white" style="background-color: <?= $navy ?>;">
            <p class="text-xs uppercase tracking-wide text-white/60 mb-1">Total Tagihan</p>
            <p id="bayar-total" class="text-3xl font-bold">Rp 0</p>
        </div>

        <!-- Metode -->
        <div class="mb-4">
            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Metode Pembayaran</p>
            <div class="grid grid-cols-3 gap-2">
                <?php foreach (['tunai' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS'] as $key => $label): ?>
                    <label class="metode-option cursor-pointer">
                        <input type="radio" name="metode" value="<?= $key ?>" class="sr-only peer" <?= $key === 'tunai' ? 'checked' : '' ?>>
                        <div class="text-center py-3 px-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-600 transition-all peer-checked:border-transparent peer-checked:text-white"
                            style="--navy: <?= $navy ?>;">
                            <?= $label ?>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Bayar tunai -->
        <div id="bagian-tunai" class="mb-4">
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Uang Diterima</label>
            <input type="number" name="uang_diterima" id="uang-diterima" min="0" placeholder="0"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-base font-semibold focus:outline-none transition-colors"
                onfocus="this.style.borderColor='<?= $navy ?>';"
                onblur="this.style.borderColor='';">

            <!-- Quick nominal -->
            <div class="grid grid-cols-4 gap-2 mt-2">
                <?php foreach ([20000, 50000, 100000, 200000] as $n): ?>
                    <button type="button" onclick="setUang(<?= $n ?>)"
                        class="py-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        <?= number_format($n, 0, ',', '.') ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="mt-3 px-4 py-3 bg-slate-50 rounded-xl flex justify-between text-sm">
                <span class="text-slate-600">Kembalian</span>
                <span id="kembalian" class="font-bold" style="color: <?= $navy ?>;">Rp 0</span>
            </div>
        </div>

        <div class="flex gap-2">
            <button type="button" onclick="goToStep(2)"
                class="flex-1 py-3 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">
                ← Kembali
            </button>
            <button type="button" id="btn-selesai" onclick="bukaModal()"
                class="flex-[2] py-3 text-sm font-semibold text-white rounded-xl transition-opacity hover:opacity-90 disabled:opacity-40"
                style="background-color: <?= $navy ?>;">
                Selesaikan Transaksi
            </button>
        </div>
    </form>
</section>

<!-- ========== MODAL KONFIRMASI ========== -->
<div id="modal-konfirmasi" class="fixed inset-0 z-50 hidden items-center justify-center"
    style="background-color: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl w-full max-w-md mx-auto overflow-hidden"
        style="animation: slideUp .25s ease; max-height: 90vh; overflow-y: auto;">

        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-800">Konfirmasi Transaksi</h2>
            <button type="button" onclick="tutupModal()"
                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-sm">✕</button>
        </div>

        <!-- Tagihan -->
        <div class="m-4 rounded-2xl p-4 text-white" style="background-color: <?= $navy ?>;">
            <p class="text-xs uppercase tracking-widest text-white/60 mb-1">Total Tagihan</p>
            <p id="modal-total" class="text-3xl font-bold">Rp 0</p>
            <div id="modal-meta-tunai" class="flex gap-4 mt-3 pt-3 border-t border-white/20">
                <div>
                    <span class="text-xs text-white/60">Metode</span>
                    <p id="modal-metode-label" class="text-sm font-semibold">Tunai</p>
                </div>
                <div id="modal-uang-wrap">
                    <span class="text-xs text-white/60">Uang diterima</span>
                    <p id="modal-uang" class="text-sm font-semibold">Rp 0</p>
                </div>
                <div id="modal-kembalian-wrap">
                    <span class="text-xs text-white/60">Kembalian</span>
                    <p id="modal-kembalian" class="text-sm font-semibold">Rp 0</p>
                </div>
            </div>
        </div>

        <!-- Daftar produk -->
        <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Ringkasan Pesanan</p>
        <div id="modal-items" class="mx-4 mb-3 border border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100"></div>

        <!-- Rincian -->
        <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Rincian Pembayaran</p>
        <div class="mx-4 mb-4 border border-slate-200 rounded-xl overflow-hidden text-sm">
            <div class="flex justify-between px-4 py-2.5 text-slate-600 bg-white">
                <span>Subtotal</span><span id="modal-subtotal" class="font-semibold text-slate-800">Rp 0</span>
            </div>
            <div class="flex justify-between px-4 py-2.5 text-slate-600 bg-white border-t border-slate-100">
                <span>Diskon</span><span id="modal-diskon" class="font-semibold text-slate-800">Rp 0</span>
            </div>
            <div class="flex justify-between px-4 py-2.5 font-bold bg-slate-50 border-t border-slate-100">
                <span class="text-slate-800">Total</span>
                <span id="modal-total-2" style="color:<?= $navy ?>">Rp 0</span>
            </div>
            <div id="modal-kembalian-row" class="flex justify-between px-4 py-2.5 bg-white border-t border-slate-100">
                <span class="text-slate-600">Kembalian</span>
                <span id="modal-kembalian-2" class="font-bold text-emerald-600">Rp 0</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex gap-2 px-4 pb-6 border-t border-slate-100 pt-3">
            <button type="button" onclick="tutupModal()"
                class="flex-1 py-3 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">
                ← Kembali
            </button>
            <button type="button" id="btn-konfirmasi-submit"
                class="flex-[2] py-3 text-sm font-semibold text-white rounded-xl hover:opacity-90 flex items-center justify-center gap-2"
                style="background-color: <?= $navy ?>;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Proses Transaksi
            </button>
        </div>
    </div>
</div>

<style>
    /* Active state untuk metode pembayaran */
    .metode-option input:checked+div {
        background-color: var(--navy) !important;
        border-color: var(--navy) !important;
        color: white !important;
    }

    @keyframes slideUp {
        from {
            transform: translateY(40px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

<script>
    (function() {
        const NAVY = '<?= $navy ?>';
        let cart = []; // [{id, nama, harga, qty, stok}]
        let diskon = 0;

        const fmt = n => 'Rp ' + (n || 0).toLocaleString('id-ID');

        // ===== Step Navigation =====
        window.goToStep = function(n) {
            // Validasi
            if (n === 2 && cart.length === 0) {
                alert('Keranjang masih kosong. Pilih produk dulu.');
                return;
            }
            if (n === 3 && cart.length === 0) return;

            document.querySelectorAll('.step-section').forEach(el => {
                el.classList.toggle('hidden', el.dataset.step !== String(n));
            });

            // Update indikator
            document.querySelectorAll('[data-step-indicator]').forEach(el => {
                const sn = parseInt(el.dataset.stepIndicator);
                el.style.backgroundColor = sn <= n ? NAVY : '#e2e8f0';
                el.style.color = sn <= n ? '#fff' : '#94a3b8';
            });
            document.querySelectorAll('[data-step-label]').forEach(el => {
                const sn = parseInt(el.dataset.stepLabel);
                el.classList.toggle('text-slate-800', sn <= n);
                el.classList.toggle('text-slate-400', sn > n);
            });

            if (n === 2) renderCheckout();
            if (n === 3) renderBayar();

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        };

        // ===== Cart Management =====
        function addToCart(produk) {
            const found = cart.find(c => c.id === produk.id);
            if (found) {
                if (found.quantity < produk.stock) found.quantity++;
                else {
                    alert('Stok tidak cukup');
                    return;
                }
            } else {
                cart.push({
                    ...produk,
                    id: Number(produk.id), // ← normalize sini
                    price: Number(produk.price), // sekalian harga & stock biar perhitungan aman
                    stock: Number(produk.stock),
                    quantity: 1
                });
            }
            renderCartSummary();
        }

        function changeQty(id, delta) {
            const item = cart.find(c => c.id === id);
            if (!item) return;
            const next = item.quantity + delta;
            if (next <= 0) {
                cart = cart.filter(c => c.id !== id);
            } else if (next > item.stock) {
                alert('Stok tidak cukup');
                return;
            } else {
                item.quantity = next;
            }
            renderCheckout();
            renderCartSummary();
        }

        function calcSubtotal() {
            return cart.reduce((s, c) => s + c.price * c.quantity, 0);
        }

        function calcTotal() {
            return Math.max(0, calcSubtotal() - diskon);
        }

        // ===== Renderers =====
        function renderCartSummary() {
            const summary = document.getElementById('cart-summary');
            const count = cart.reduce((s, c) => s + c.quantity, 0);
            if (count === 0) {
                summary.classList.add('hidden');
                return;
            }
            summary.classList.remove('hidden');
            document.getElementById('cart-count').textContent = count;
            document.getElementById('cart-total-preview').textContent = fmt(calcSubtotal());
        }

        function renderCheckout() {
            const wrap = document.getElementById('cart-items');
            const empty = document.getElementById('cart-empty');

            if (cart.length === 0) {
                wrap.innerHTML = '';
                empty.classList.remove('hidden');
            } else {
                empty.classList.add('hidden');
                wrap.innerHTML = cart.map(c => `
                <div class="px-4 py-3 flex items-center gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">${c.name}</p>
                        <p class="text-xs text-slate-400">${fmt(c.price)} × ${c.quantity}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" data-id="${c.id}" data-d="-1" class="qty-btn w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold">−</button>
                        <span class="w-6 text-center text-sm font-semibold">${c.quantity}</span>
                        <button type="button" data-id="${c.id}" data-d="1" class="qty-btn w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold">+</button>
                    </div>
                    <p class="text-sm font-bold text-slate-800 w-24 text-right">${fmt(c.price * c.quantity)}</p>
                </div>
            `).join('');
            }

            document.getElementById('sum-subtotal').textContent = fmt(calcSubtotal());
            document.getElementById('sum-total').textContent = fmt(calcTotal());
        }

        function renderBayar() {
            const total = calcTotal();
            document.getElementById('bayar-total').textContent = fmt(total);
            document.getElementById('cart-data-input').value = JSON.stringify(cart);
            document.getElementById('diskon-input').value = diskon;
            hitungKembalian();
        }

        function hitungKembalian() {
            const uang = parseInt(document.getElementById('uang-diterima').value) || 0;
            const total = calcTotal();
            const kembalian = uang - total;
            document.getElementById('kembalian').textContent = fmt(Math.max(0, kembalian));
            document.getElementById('btn-selesai').disabled = uang < total &&
                document.querySelector('input[name="metode"]:checked').value === 'tunai';
        }

        window.setUang = function(n) {
            document.getElementById('uang-diterima').value = n;
            hitungKembalian();
        };

        window.bukaModal = function() {
            const total = calcTotal();
            const subtotal = calcSubtotal();
            const metode = document.querySelector('input[name="metode"]:checked').value;
            const uang = parseInt(document.getElementById('uang-diterima').value) || 0;
            const kembalian = Math.max(0, uang - total);
            const metodeLabel = {
                tunai: 'Tunai',
                transfer: 'Transfer',
                qris: 'QRIS'
            };

            // Isi nilai
            document.getElementById('modal-total').textContent = fmt(total);
            document.getElementById('modal-total-2').textContent = fmt(total);
            document.getElementById('modal-subtotal').textContent = fmt(subtotal);
            document.getElementById('modal-diskon').textContent = fmt(diskon);
            document.getElementById('modal-metode-label').textContent = metodeLabel[metode] || metode;

            // Tampilkan/sembunyikan kolom uang & kembalian
            const isTunai = metode === 'tunai';
            document.getElementById('modal-uang-wrap').classList.toggle('hidden', !isTunai);
            document.getElementById('modal-kembalian-wrap').classList.toggle('hidden', !isTunai);
            document.getElementById('modal-kembalian-row').classList.toggle('hidden', !isTunai);
            if (isTunai) {
                document.getElementById('modal-uang').textContent = fmt(uang);
                document.getElementById('modal-kembalian').textContent = fmt(kembalian);
                document.getElementById('modal-kembalian-2').textContent = fmt(kembalian);
            }

            // Render item list
            document.getElementById('modal-items').innerHTML = cart.map(c => `
        <div class="flex items-center justify-between px-4 py-2.5 bg-white text-sm">
            <div>
                <p class="font-semibold text-slate-800">${c.name}</p>
                <p class="text-xs text-slate-400">${c.quantity} × ${fmt(c.price)}</p>
            </div>
            <span class="font-bold text-slate-800">${fmt(c.price * c.quantity)}</span>
        </div>
    `).join('');

            // Tampilkan modal
            const modal = document.getElementById('modal-konfirmasi');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        window.tutupModal = function() {
            const modal = document.getElementById('modal-konfirmasi');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };

        // Tombol "Proses Transaksi" di dalam modal → submit form sungguhan
        document.getElementById('btn-konfirmasi-submit').addEventListener('click', function() {
            document.getElementById('cart-data-input').value = JSON.stringify(cart);
            document.getElementById('diskon-input').value = diskon;
            document.getElementById('form-bayar').submit();
        });

        // Tutup modal kalau klik backdrop
        document.getElementById('modal-konfirmasi').addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });

        // ===== Event Bindings =====
        // Klik produk
        document.querySelectorAll('.produk-item').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = JSON.parse(btn.dataset.produk);
                addToCart(data);
            });
        });

        // Search
        document.getElementById('search-produk').addEventListener('input', (e) => {
            const q = e.target.value.toLowerCase();
            document.querySelectorAll('.produk-item').forEach(btn => {
                btn.classList.toggle('hidden', !btn.dataset.nama.includes(q));
            });
        });

        // Qty buttons (delegasi)
        document.getElementById('cart-items').addEventListener('click', (e) => {
            const btn = e.target.closest('.qty-btn');
            if (!btn) return;
            changeQty(parseInt(btn.dataset.id), parseInt(btn.dataset.d));

        });

        // Diskon input
        document.getElementById('input-diskon').addEventListener('input', (e) => {
            diskon = parseInt(e.target.value) || 0;
            document.getElementById('sum-subtotal').textContent = fmt(calcSubtotal());
            document.getElementById('sum-total').textContent = fmt(calcTotal());
        });

        // Metode pembayaran
        document.querySelectorAll('input[name="metode"]').forEach(r => {
            r.addEventListener('change', () => {
                const tunai = r.value === 'tunai';
                document.getElementById('bagian-tunai').classList.toggle('hidden', !tunai);
                hitungKembalian();
            });
        });

        // Uang diterima
        document.getElementById('uang-diterima').addEventListener('input', hitungKembalian);

    })();
</script>