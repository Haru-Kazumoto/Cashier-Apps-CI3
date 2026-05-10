<!-- application/views/kasir/produk/form_tambah.php -->
<!-- Dirender via layout: $layout = 'kasir', $active_menu = 'produk' -->

<?php
$navy = '#1e3a5f';
$back_route = $this->session->userdata('is_admin') > 0 ? site_url('kasir/produk') : site_url('superadmin/products');
?>

<!-- Header dengan back -->
<div class="flex items-center gap-3 mb-6">
    <a href="<?= $back_route ?>"
        class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-600 hover:bg-slate-100 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
    </a>
    <div>
        <h1 class="text-xl font-bold text-slate-800" style="font-family:'Playfair Display',serif;">Tambah Produk</h1>
        <p class="text-xs text-slate-400 mt-0.5">Lengkapi data produk baru</p>
    </div>
</div>

<!-- Validation error -->
<?php if (validation_errors() || (isset($upload_error) && $upload_error)): ?>
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
        <?= validation_errors() ?>
        <?= $upload_error ?? '' ?>
    </div>
<?php endif; ?>

<?= form_open_multipart('kasir/produk/save', ['class' => 'space-y-4']) ?>

<div class="bg-white border border-slate-200 rounded-xl p-5 space-y-4">

    <!-- Upload Gambar -->
    <div>
        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">
            Gambar Produk <span class="text-slate-400 normal-case font-normal">(opsional)</span>
        </label>

        <!-- Preview area -->
        <label for="gambar" class="block cursor-pointer">
            <div id="upload-area" class="relative border-2 border-dashed border-slate-200 rounded-xl overflow-hidden hover:border-slate-300 transition-colors"
                style="aspect-ratio: 4/3;">

                <!-- Placeholder (default) -->
                <div id="upload-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mb-2" style="background-color: rgba(30,58,95,0.08);">
                        <svg class="w-5 h-5" style="color: <?= $navy ?>;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-600">Tap untuk pilih gambar</p>
                    <p class="text-xs text-slate-400 mt-0.5">JPG, PNG, atau WebP · Maks 2MB</p>
                </div>

                <!-- Preview gambar -->
                <img id="upload-preview" src="" alt="Preview" class="hidden absolute inset-0 w-full h-full object-cover">

                <!-- Tombol hapus preview -->
                <button type="button" id="btn-hapus-gambar"
                    class="hidden absolute top-2 right-2 w-8 h-8 rounded-full bg-black/60 hover:bg-black/80 text-white flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </label>

        <input type="file" id="gambar" name="gambar" accept="image/jpeg,image/png,image/webp" class="hidden">
    </div>

    <!-- Nama Produk -->
    <div>
        <label for="nama" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">
            Nama Produk <span class="text-red-500">*</span>
        </label>
        <input type="text" id="nama" name="nama" value="<?= set_value('nama') ?>"
            placeholder="Contoh: Kopi Susu Gula Aren" required
            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none transition-colors"
            onfocus="this.style.borderColor='<?= $navy ?>';"
            onblur="this.style.borderColor='';">
    </div>

    <!-- Kategori -->
    <div>
        <label for="kategori" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">
            Kategori
        </label>
        <select id="kategori" name="kategori"
            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-white focus:outline-none transition-colors"
            onfocus="this.style.borderColor='<?= $navy ?>';"
            onblur="this.style.borderColor='';">
            <option value="">— Pilih kategori —</option>
            <?php foreach ($categories ?? [] as $k): ?>
                <option value="<?= $k->id ?>" <?= set_select('kategori', $k->id) ?>>
                    <?= $k->name ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Harga -->
    <div>
        <label for="harga" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">
            Harga <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm text-slate-400 pointer-events-none">Rp</span>
            <input type="number" id="harga" name="harga" value="<?= set_value('harga') ?>"
                placeholder="0" min="0" required
                class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none transition-colors"
                onfocus="this.style.borderColor='<?= $navy ?>';"
                onblur="this.style.borderColor='';">
        </div>
    </div>

    <!-- Stok -->
    <div>
        <label for="stok" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">
            Stok Awal <span class="text-red-500">*</span>
        </label>
        <input type="number" id="stok" name="stok" value="<?= set_value('stok', 0) ?>"
            placeholder="0" min="0" required
            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none transition-colors"
            onfocus="this.style.borderColor='<?= $navy ?>';"
            onblur="this.style.borderColor='';">
    </div>

    <!-- Deskripsi -->
    <div>
        <label for="deskripsi" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">
            Deskripsi <span class="text-slate-400 normal-case font-normal">(opsional)</span>
        </label>
        <textarea id="deskripsi" name="deskripsi" rows="3"
            placeholder="Tambahkan catatan atau detail produk..."
            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none transition-colors resize-none"
            onfocus="this.style.borderColor='<?= $navy ?>';"
            onblur="this.style.borderColor='';"><?= set_value('deskripsi') ?></textarea>
    </div>
</div>

<!-- Actions -->
<div class="flex gap-2">
    <a href="<?= site_url('kasir/produk') ?>"
        class="flex-1 py-3 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 text-center">
        Batal
    </a>
    <button type="submit"
        class="flex-[2] py-3 text-sm font-semibold text-white rounded-xl transition-opacity hover:opacity-90"
        style="background-color: <?= $navy ?>;">
        Simpan Produk
    </button>
</div>

<?= form_close() ?>

<script>
    (function() {
        const input = document.getElementById('gambar');
        const preview = document.getElementById('upload-preview');
        const placeholder = document.getElementById('upload-placeholder');
        const btnHapus = document.getElementById('btn-hapus-gambar');
        const MAX_SIZE = 2 * 1024 * 1024; // 2MB

        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            // Validasi ukuran
            if (file.size > MAX_SIZE) {
                alert('Ukuran file maksimal 2MB');
                input.value = '';
                return;
            }

            // Validasi tipe
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                alert('Format harus JPG, PNG, atau WebP');
                input.value = '';
                return;
            }

            // Tampilkan preview
            const reader = new FileReader();
            reader.onload = (ev) => {
                preview.src = ev.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                btnHapus.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });

        btnHapus.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            input.value = '';
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            btnHapus.classList.add('hidden');
        });
    })();
</script>