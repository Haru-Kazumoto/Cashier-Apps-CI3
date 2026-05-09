<!-- application/views/kasir/laporan.php -->
<!-- Dirender via layout: $layout = 'kasir', $active_menu = 'laporan' -->

<?php $navy = '#1e3a5f'; ?>

<!-- Header -->
<div class="mb-5">
    <h1 class="text-xl font-bold text-slate-800" style="font-family:'Playfair Display',serif;">Laporan Transaksi</h1>
    <p class="text-xs text-slate-400 mt-0.5">Ketuk transaksi untuk lihat detail struk</p>
</div>

<!-- Filter Periode -->
<form method="get" action="<?= site_url('kasir/laporan') ?>" class="grid grid-cols-2 gap-2 mb-4">
    <div>
        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Dari</label>
        <input type="date" name="dari" value="<?= $filter_dari ?? date('Y-m-01') ?>"
            class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm bg-white focus:outline-none transition-colors"
            onfocus="this.style.borderColor='<?= $navy ?>';"
            onblur="this.style.borderColor='';">
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Sampai</label>
        <input type="date" name="sampai" value="<?= $filter_sampai ?? date('Y-m-d') ?>"
            class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm bg-white focus:outline-none transition-colors"
            onfocus="this.style.borderColor='<?= $navy ?>';"
            onblur="this.style.borderColor='';">
    </div>
    <button type="submit"
        class="col-span-2 py-2.5 text-sm font-semibold text-white rounded-xl transition-opacity hover:opacity-90"
        style="background-color: <?= $navy ?>;">
        Terapkan Filter
    </button>
</form>

<!-- Ringkasan -->
<div class="grid grid-cols-3 gap-2 mb-5">
    <div class="bg-white border border-slate-200 rounded-xl p-3">
        <p class="text-[10px] text-slate-500 uppercase tracking-wide mb-1">Transaksi</p>
        <p class="text-base font-bold text-slate-800"><?= count($transaksi ?? []) ?></p>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-3">
        <p class="text-[10px] text-slate-500 uppercase tracking-wide mb-1">Item</p>
        <p class="text-base font-bold text-slate-800">
            <?= array_sum(array_column($transaksi ?? [], 'jumlah_item')) ?>
        </p>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-3">
        <p class="text-[10px] text-slate-500 uppercase tracking-wide mb-1">Omzet</p>
        <p class="text-sm font-bold" style="color: <?= $navy ?>;">
            Rp <?= number_format(array_sum(array_column($transaksi ?? [], 'total')), 0, ',', '.') ?>
        </p>
    </div>
</div>

<!-- List Transaksi (grouped by tanggal) -->
<?php
$grouped = [];
foreach ($transaksi ?? [] as $t) {
    $tgl = date('Y-m-d', strtotime($t['waktu']));
    $grouped[$tgl][] = $t;
}
krsort($grouped); // tanggal terbaru duluan
?>

<?php if (!empty($grouped)): ?>
    <?php foreach ($grouped as $tgl => $list): ?>
        <?php
        $total_hari = array_sum(array_column($list, 'total'));
        $hari_label = date('l', strtotime($tgl));
        $hari_id = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'][$hari_label] ?? $hari_label;
        ?>
        <div class="mb-4">
            <!-- Date header -->
            <div class="flex items-center justify-between mb-2 px-1">
                <div>
                    <p class="text-sm font-bold text-slate-700"><?= $hari_id ?>, <?= date('d M Y', strtotime($tgl)) ?></p>
                    <p class="text-[11px] text-slate-400"><?= count($list) ?> transaksi</p>
                </div>
                <p class="text-sm font-bold" style="color: <?= $navy ?>;">
                    Rp <?= number_format($total_hari, 0, ',', '.') ?>
                </p>
            </div>

            <!-- Items -->
            <div class="bg-white border border-slate-200 rounded-xl divide-y divide-slate-100">
                <?php foreach ($list as $t): ?>
                    <button type="button"
                        data-trx='<?= htmlspecialchars(json_encode($t), ENT_QUOTES) ?>'
                        class="trx-item w-full px-4 py-3 flex items-center gap-3 text-left hover:bg-slate-50 transition-colors">

                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(30,58,95,0.08);">
                            <svg class="w-4 h-4" style="color: <?= $navy ?>;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800">#<?= $t['kode'] ?></p>
                            <p class="text-xs text-slate-400">
                                <?= date('H:i', strtotime($t['waktu'])) ?> · <?= $t['jumlah_item'] ?> item ·
                                <span class="capitalize"><?= $t['metode'] ?? 'tunai' ?></span>
                            </p>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-bold text-slate-800">Rp <?= number_format($t['total'], 0, ',', '.') ?></p>
                            <svg class="w-3.5 h-3.5 text-slate-300 inline-block ml-auto mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="bg-white border border-slate-200 rounded-xl px-4 py-12 text-center">
        <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center" style="background-color: rgba(30,58,95,0.08);">
            <svg class="w-5 h-5" style="color: <?= $navy ?>;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h4M9 17H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-4m-6 0v4m0 0h6m-6 0l3-3m3 3l-3-3" />
            </svg>
        </div>
        <p class="text-sm text-slate-500 mb-1">Belum ada transaksi</p>
        <p class="text-xs text-slate-400">Pada periode yang dipilih</p>
    </div>
<?php endif; ?>

<!-- ========== Modal Struk ========== -->
<div id="modal-struk" class="fixed inset-0 z-50 hidden items-end sm:items-center justify-center bg-black/50 px-0 sm:px-5">
    <div class="bg-white w-full sm:max-w-sm rounded-t-2xl sm:rounded-2xl max-h-[85vh] flex flex-col">

        <!-- Modal header -->
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

        <!-- Modal body (scroll) -->
        <div class="flex-1 overflow-y-auto px-5 py-4">

            <!-- Brand -->
            <div class="text-center mb-4 pb-4 border-b border-dashed border-slate-200">
                <p class="text-lg font-bold" style="color: <?= $navy ?>; font-family:'Playfair Display',serif;">Aksir</p>
                <p class="text-xs text-slate-400 mt-0.5">Aksir Kasir System</p>
            </div>

            <!-- Meta -->
            <div class="space-y-1 text-xs mb-4 pb-4 border-b border-dashed border-slate-200">
                <div class="flex justify-between">
                    <span class="text-slate-500">Waktu</span>
                    <span id="struk-waktu" class="text-slate-700 font-medium">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Kasir</span>
                    <span id="struk-kasir" class="text-slate-700 font-medium">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Metode</span>
                    <span id="struk-metode" class="text-slate-700 font-medium capitalize">-</span>
                </div>
            </div>

            <!-- Items -->
            <div id="struk-items" class="space-y-2 mb-4 pb-4 border-b border-dashed border-slate-200 text-sm">
                <!-- diisi via JS -->
            </div>

            <!-- Total -->
            <div class="space-y-1 text-sm">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal</span>
                    <span id="struk-subtotal">Rp 0</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Diskon</span>
                    <span id="struk-diskon">Rp 0</span>
                </div>
                <div class="flex justify-between font-bold text-base text-slate-800 pt-2 border-t border-slate-100">
                    <span>Total</span>
                    <span id="struk-total" style="color: <?= $navy ?>;">Rp 0</span>
                </div>
                <div id="struk-tunai-wrap" class="pt-2 space-y-1 hidden">
                    <div class="flex justify-between text-slate-600">
                        <span>Tunai</span>
                        <span id="struk-tunai">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Kembalian</span>
                        <span id="struk-kembalian">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Thank you -->
            <div class="text-center mt-5 pt-4 border-t border-dashed border-slate-200">
                <p class="text-xs text-slate-500">Terima kasih atas kunjungan Anda</p>
                <p class="text-[10px] text-slate-400 mt-1">~ Aksir ~</p>
            </div>
        </div>

        <!-- Modal footer -->
        <div class="px-5 py-3 border-t border-slate-100 flex gap-2 flex-shrink-0">
            <button type="button" onclick="window.print()"
                class="flex-1 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                Cetak
            </button>
            <a id="btn-detail" href="#"
                class="flex-1 py-2.5 text-sm font-semibold text-white rounded-xl transition-opacity hover:opacity-90 text-center"
                style="background-color: <?= $navy ?>;">
                Detail Lengkap
            </a>
        </div>
    </div>
</div>

<script>
    (function() {
        const fmt = n => 'Rp ' + (n || 0).toLocaleString('id-ID');
        const modal = document.getElementById('modal-struk');

        function openStruk(t) {
            // Header
            document.getElementById('struk-kode').textContent = '#' + t.kode;

            // Meta
            document.getElementById('struk-waktu').textContent = t.waktu_formatted || t.waktu;
            document.getElementById('struk-kasir').textContent = t.kasir || '-';
            document.getElementById('struk-metode').textContent = t.metode || 'tunai';

            // Items
            const itemsWrap = document.getElementById('struk-items');
            itemsWrap.innerHTML = (t.items || []).map(i => `
            <div>
                <p class="font-medium text-slate-800">${i.nama}</p>
                <div class="flex justify-between text-xs text-slate-500 mt-0.5">
                    <span>${fmt(i.harga)} × ${i.qty}</span>
                    <span class="text-slate-700 font-semibold">${fmt(i.harga * i.qty)}</span>
                </div>
            </div>
        `).join('');

            // Totals
            document.getElementById('struk-subtotal').textContent = fmt(t.subtotal || t.total);
            document.getElementById('struk-diskon').textContent = fmt(t.diskon || 0);
            document.getElementById('struk-total').textContent = fmt(t.total);

            // Tunai info (kalau metode tunai)
            const tunaiWrap = document.getElementById('struk-tunai-wrap');
            if (t.metode === 'tunai' && t.uang_diterima) {
                tunaiWrap.classList.remove('hidden');
                document.getElementById('struk-tunai').textContent = fmt(t.uang_diterima);
                document.getElementById('struk-kembalian').textContent = fmt(t.uang_diterima - t.total);
            } else {
                tunaiWrap.classList.add('hidden');
            }

            // Link detail
            document.getElementById('btn-detail').href = '<?= site_url('kasir/laporan/detail/') ?>' + t.id;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        window.closeStruk = function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        };

        // Bind klik tiap baris transaksi
        document.querySelectorAll('.trx-item').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = JSON.parse(btn.dataset.trx);
                openStruk(data);
            });
        });

        // Close saat klik backdrop
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeStruk();
        });

        // Close pakai ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeStruk();
        });
    })();
</script>

<style>
    /* Print: cuma struk yang muncul */
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

        #modal-struk .px-5.py-3.border-t,
        #modal-struk button[onclick="closeStruk()"] {
            display: none !important;
        }
    }
</style>