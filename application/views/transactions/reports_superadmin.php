<!-- application/views/transactions/reports.php -->
<!-- Dirender dengan $layout = 'admin', $active_menu = 'transactions' -->

<?php $navy = '#1e3a5f'; ?>

<!-- Header -->
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Laporan Transaksi</h1>
        <p class="text-sm text-slate-500 mt-0.5">Periode <?= date('d M Y', strtotime($filter_dari)) ?> — <?= date('d M Y', strtotime($filter_sampai)) ?></p>
    </div>

    <div class="flex gap-2">
        <a href="<?= base_url('index.php/superadmin/transactions/export?dari=' . $filter_dari . '&sampai=' . $filter_sampai) ?>"
            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            Export CSV
        </a>
    </div>
</div>

<!-- Filter Periode -->
<form method="get" action="<?= base_url('index.php/superadmin/transactions/reports') ?>"
    class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Dari</label>
            <input type="date" name="dari" value="<?= $filter_dari ?>"
                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none transition-colors"
                onfocus="this.style.borderColor='<?= $navy ?>';"
                onblur="this.style.borderColor='';">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Sampai</label>
            <input type="date" name="sampai" value="<?= $filter_sampai ?>"
                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none transition-colors"
                onfocus="this.style.borderColor='<?= $navy ?>';"
                onblur="this.style.borderColor='';">
        </div>
        <div class="flex items-end">
            <button type="submit"
                class="w-full py-2 text-sm font-semibold text-white rounded-lg transition-opacity hover:opacity-90"
                style="background-color: <?= $navy ?>;">
                Terapkan
            </button>
        </div>
    </div>
</form>

<!-- Stat Summary -->
<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-5">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Transaksi</p>
        <p class="text-xl font-bold text-slate-800"><?= count($transaksi ?? []) ?></p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Item Terjual</p>
        <p class="text-xl font-bold text-slate-800">
            <?= array_sum(array_column($transaksi ?? [], 'jumlah_item')) ?>
        </p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Omzet</p>
        <p class="text-xl font-bold" style="color: <?= $navy ?>;">
            Rp <?= number_format(array_sum(array_column($transaksi ?? [], 'total')), 0, ',', '.') ?>
        </p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Rata-rata Transaksi</p>
        <p class="text-xl font-bold text-slate-800">
            <?php
            $jml = count($transaksi ?? []);
            $avg = $jml > 0 ? array_sum(array_column($transaksi, 'total')) / $jml : 0;
            echo 'Rp ' . number_format($avg, 0, ',', '.');
            ?>
        </p>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Invoice</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Waktu</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Kasir</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Item</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Metode</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Total</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($transaksi)): ?>
                    <?php foreach ($transaksi as $t): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 font-medium text-slate-800">#<?= $t['kode'] ?></td>
                            <td class="px-5 py-3 text-slate-500 text-xs">
                                <?= $t['waktu_formatted'] ?>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-[10px] font-semibold flex-shrink-0"
                                        style="background-color: <?= $navy ?>;">
                                        <?= strtoupper(substr($t['kasir'] ?? 'K', 0, 1)) ?>
                                    </div>
                                    <span class="text-slate-600"><?= $t['kasir'] ?></span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-center text-slate-600"><?= $t['jumlah_item'] ?></td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium capitalize
                                    <?= $t['metode'] === 'tunai' ? 'bg-emerald-50 text-emerald-700' : ($t['metode'] === 'transfer' ? 'bg-blue-50 text-blue-700' : 'bg-violet-50 text-violet-700') ?>">
                                    <?= $t['metode'] ?>
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right font-semibold text-slate-800">
                                Rp <?= number_format($t['total'], 0, ',', '.') ?>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <button type="button"
                                    data-trx='<?= htmlspecialchars(json_encode($t), ENT_QUOTES) ?>'
                                    onclick="openStruk(this)"
                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium rounded-lg hover:bg-slate-100 transition-colors"
                                    style="color: <?= $navy ?>;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <p class="text-sm text-slate-400">Tidak ada transaksi pada periode ini</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============== Modal Struk ============== -->
<div id="modal-struk" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
    <div class="bg-white w-full max-w-sm rounded-2xl max-h-[90vh] flex flex-col">

        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div>
                <p class="text-xs text-slate-400">Struk Transaksi</p>
                <p id="struk-kode" class="text-base font-bold text-slate-800">#TRX</p>
            </div>
            <button type="button" onclick="closeStruk()"
                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-5 py-4">

            <div class="text-center mb-4 pb-4 border-b border-dashed border-slate-200">
                <p class="text-lg font-bold" style="color: <?= $navy ?>; font-family:'Playfair Display',serif;">Aksir</p>
                <p class="text-xs text-slate-400 mt-0.5">Aksir Kasir System</p>
            </div>

            <div class="space-y-1 text-xs mb-4 pb-4 border-b border-dashed border-slate-200">
                <div class="flex justify-between"><span class="text-slate-500">Waktu</span><span id="struk-waktu" class="text-slate-700 font-medium">-</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Kasir</span><span id="struk-kasir" class="text-slate-700 font-medium">-</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Metode</span><span id="struk-metode" class="text-slate-700 font-medium capitalize">-</span></div>
            </div>

            <div id="struk-items" class="space-y-2 mb-4 pb-4 border-b border-dashed border-slate-200 text-sm"></div>

            <div class="space-y-1 text-sm">
                <div class="flex justify-between text-slate-600"><span>Subtotal</span><span id="struk-subtotal">Rp 0</span></div>
                <div class="flex justify-between text-slate-600"><span>Diskon</span><span id="struk-diskon">Rp 0</span></div>
                <div class="flex justify-between font-bold text-base text-slate-800 pt-2 border-t border-slate-100">
                    <span>Total</span><span id="struk-total" style="color: <?= $navy ?>;">Rp 0</span>
                </div>
                <div id="struk-tunai-wrap" class="pt-2 space-y-1 hidden">
                    <div class="flex justify-between text-slate-600"><span>Tunai</span><span id="struk-tunai">Rp 0</span></div>
                    <div class="flex justify-between text-slate-600"><span>Kembalian</span><span id="struk-kembalian">Rp 0</span></div>
                </div>
            </div>

            <div class="text-center mt-5 pt-4 border-t border-dashed border-slate-200">
                <p class="text-xs text-slate-500">Terima kasih atas kunjungan Anda</p>
            </div>
        </div>

        <div class="px-5 py-3 border-t border-slate-100 flex gap-2 flex-shrink-0">
            <button type="button" onclick="window.print()"
                class="flex-1 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                Cetak
            </button>
            <button type="button" onclick="closeStruk()"
                class="flex-1 py-2.5 text-sm font-semibold text-white rounded-lg transition-opacity hover:opacity-90"
                style="background-color: <?= $navy ?>;">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    (function() {
        const fmt = n => 'Rp ' + (n || 0).toLocaleString('id-ID');
        const modal = document.getElementById('modal-struk');

        window.openStruk = function(btn) {
            const t = JSON.parse(btn.dataset.trx);

            document.getElementById('struk-kode').textContent = '#' + t.kode;
            document.getElementById('struk-waktu').textContent = t.waktu_formatted || t.waktu;
            document.getElementById('struk-kasir').textContent = t.kasir || '-';
            document.getElementById('struk-metode').textContent = t.metode || '-';

            document.getElementById('struk-items').innerHTML = (t.items || []).map(i => `
            <div>
                <p class="font-medium text-slate-800">${i.nama}</p>
                <div class="flex justify-between text-xs text-slate-500 mt-0.5">
                    <span>${fmt(i.harga)} × ${i.qty}</span>
                    <span class="text-slate-700 font-semibold">${fmt(i.harga * i.qty)}</span>
                </div>
            </div>
        `).join('');

            document.getElementById('struk-subtotal').textContent = fmt(t.subtotal || t.total);
            document.getElementById('struk-diskon').textContent = fmt(t.diskon || 0);
            document.getElementById('struk-total').textContent = fmt(t.total);

            const tunaiWrap = document.getElementById('struk-tunai-wrap');
            if (t.metode === 'tunai' && t.uang_diterima) {
                tunaiWrap.classList.remove('hidden');
                document.getElementById('struk-tunai').textContent = fmt(t.uang_diterima);
                document.getElementById('struk-kembalian').textContent = fmt(t.uang_diterima - t.total);
            } else {
                tunaiWrap.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        };

        window.closeStruk = function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        };

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeStruk();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeStruk();
        });
    })();
</script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #modal-struk,
        #modal-struk * {
            visibility: visible;
        }

        #modal-struk {
            position: absolute;
            inset: 0;
            background: white;
            display: block !important;
        }

        #modal-struk .px-5.py-3.border-t {
            display: none !important;
        }
    }
</style>