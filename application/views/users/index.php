<!-- application/views/users/index.php -->
<!-- Dirender dengan $layout = 'admin', $active_menu = 'users' -->

<?php $navy = '#1e3a5f'; ?>

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Pengguna</h1>
        <p class="text-sm text-slate-500 mt-0.5"><?= count($users ?? []) ?> akun terdaftar</p>
    </div>
    <button type="button" onclick="openModalTambah()"
        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white rounded-lg transition-opacity hover:opacity-90"
        style="background-color: <?= $navy ?>;">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah User
    </button>
</div>

<!-- Search -->
<div class="relative mb-4">
    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </span>
    <input id="search-user" type="text" placeholder="Cari username atau nama..."
        class="w-full max-w-md pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none transition-colors"
        onfocus="this.style.borderColor='<?= $navy ?>';"
        onblur="this.style.borderColor='';">
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">User</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Username</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Role</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Dibuat</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody id="users-tbody" class="divide-y divide-slate-100">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr class="user-row hover:bg-slate-50 transition-colors"
                            data-search="<?= strtolower($u['username'] . ' ' . $u['fullname']) ?>">

                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0"
                                        style="background-color: <?= $navy ?>;">
                                        <?= strtoupper(substr($u['fullname'], 0, 1)) ?>
                                    </div>
                                    <span class="font-medium text-slate-800"><?= $u['fullname'] ?></span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-slate-600">@<?= $u['username'] ?></td>
                            <td class="px-5 py-3">
                                <?php if ($u['is_admin']): ?>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-violet-50 text-violet-700">
                                        Admin
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        Kasir
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-3 text-slate-400 text-xs">
                                <?= !empty($u['created_at']) ? date('d M Y', strtotime($u['created_at'])) : '—' ?>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <button type="button"
                                    data-user='<?= htmlspecialchars(json_encode($u), ENT_QUOTES) ?>'
                                    onclick="openModalEdit(this)"
                                    class="inline-flex w-8 h-8 rounded-lg items-center justify-center text-slate-500 hover:bg-slate-100 transition-colors"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button type="button"
                                    onclick="confirmHapus(<?= $u['id'] ?>, '<?= addslashes($u['fullname']) ?>')"
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
                            <p class="text-sm text-slate-400">Belum ada pengguna terdaftar</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============== Modal Tambah/Edit ============== -->
<div id="modal-form" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">

        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 id="modal-title" class="text-base font-bold text-slate-800">Tambah User</h3>
            <button type="button" onclick="closeModal()"
                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="form-user" method="post" action="" class="p-6 space-y-4">

            <!-- Fullname -->
            <div>
                <label for="fullname" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="fullname" name="fullname" required
                    placeholder="Contoh: Budi Santoso"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none transition-colors"
                    onfocus="this.style.borderColor='<?= $navy ?>';"
                    onblur="this.style.borderColor='';">
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">
                    Username <span class="text-red-500">*</span>
                </label>
                <input type="text" id="username" name="username" required
                    placeholder="contoh: budi" pattern="[a-zA-Z0-9_]+"
                    title="Hanya huruf, angka, dan underscore"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none transition-colors"
                    onfocus="this.style.borderColor='<?= $navy ?>';"
                    onblur="this.style.borderColor='';">
                <p class="text-xs text-slate-400 mt-1">Hanya huruf, angka, dan underscore.</p>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">
                    Password <span id="password-required" class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                        placeholder="Minimal 6 karakter" minlength="6"
                        class="w-full px-4 py-2.5 pr-11 border border-slate-200 rounded-lg text-sm focus:outline-none transition-colors"
                        onfocus="this.style.borderColor='<?= $navy ?>';"
                        onblur="this.style.borderColor='';">
                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <p id="password-hint" class="text-xs text-slate-400 mt-1 hidden">Kosongkan jika tidak ingin mengubah password.</p>
            </div>

            <!-- Is Admin -->
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                <div>
                    <label for="is_admin" class="text-sm font-medium text-slate-700">Akses Admin</label>
                    <p class="text-xs text-slate-500 mt-0.5">User dapat akses panel superadmin</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="is_admin" name="is_admin" value="1" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 rounded-full peer-checked:bg-[<?= $navy ?>] transition-colors relative"
                        style="--tw-peer-bg: <?= $navy ?>;">
                        <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5"></span>
                    </div>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeModal()"
                    class="flex-1 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 text-sm font-semibold text-white rounded-lg transition-opacity hover:opacity-90"
                    style="background-color: <?= $navy ?>;">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============== Modal Konfirmasi Hapus ============== -->
<div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-sm">
        <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-800 text-center mb-1">Hapus User?</h3>
        <p class="text-sm text-slate-500 text-center mb-5">
            User <span id="hapus-nama" class="font-semibold text-slate-700"></span> akan dihapus permanen.
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
        const URL_TAMBAH = '<?= base_url('index.php/users/store') ?>';
        const URL_UPDATE = '<?= base_url('index.php/users/update/') ?>'; // + id
        const URL_HAPUS = '<?= base_url('index.php/users/delete/') ?>'; // + id

        const modalForm = document.getElementById('modal-form');
        const modalHapus = document.getElementById('modal-hapus');
        const form = document.getElementById('form-user');
        const passField = document.getElementById('password');
        const passReq = document.getElementById('password-required');
        const passHint = document.getElementById('password-hint');

        // ===== Modal Tambah =====
        window.openModalTambah = function() {
            document.getElementById('modal-title').textContent = 'Tambah User';
            form.action = URL_TAMBAH;
            form.reset();

            // Password wajib saat tambah
            passField.required = true;
            passField.minLength = 6;
            passReq.classList.remove('hidden');
            passHint.classList.add('hidden');
            passField.placeholder = 'Minimal 6 karakter';

            showModal(modalForm);
        };

        // ===== Modal Edit =====
        window.openModalEdit = function(btn) {
            const u = JSON.parse(btn.dataset.user);

            document.getElementById('modal-title').textContent = 'Edit User';
            form.action = URL_UPDATE + u.id;
            document.getElementById('fullname').value = u.fullname;
            document.getElementById('username').value = u.username;
            document.getElementById('is_admin').checked = u.is_admin == 1;

            // Password opsional saat edit
            passField.value = '';
            passField.required = false;
            passField.minLength = 0;
            passReq.classList.add('hidden');
            passHint.classList.remove('hidden');
            passField.placeholder = 'Biarkan kosong jika tidak diubah';

            showModal(modalForm);
        };

        window.closeModal = function() {
            hideModal(modalForm);
        };

        // ===== Hapus =====
        window.confirmHapus = function(id, nama) {
            document.getElementById('hapus-nama').textContent = nama;
            document.getElementById('btn-confirm-hapus').href = URL_HAPUS + id;
            showModal(modalHapus);
        };

        window.closeHapus = function() {
            hideModal(modalHapus);
        };

        // ===== Toggle Password =====
        window.togglePassword = function() {
            passField.type = passField.type === 'password' ? 'text' : 'password';
        };

        // ===== Modal Helpers =====
        function showModal(m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function hideModal(m) {
            m.classList.add('hidden');
            m.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Close on backdrop click
        [modalForm, modalHapus].forEach(m => {
            m.addEventListener('click', (e) => {
                if (e.target === m) hideModal(m);
            });
        });

        // Close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!modalForm.classList.contains('hidden')) closeModal();
                if (!modalHapus.classList.contains('hidden')) closeHapus();
            }
        });

        // ===== Search =====
        document.getElementById('search-user').addEventListener('input', (e) => {
            const q = e.target.value.toLowerCase();
            document.querySelectorAll('.user-row').forEach(row => {
                row.classList.toggle('hidden', !row.dataset.search.includes(q));
            });
        });
    })();
</script>