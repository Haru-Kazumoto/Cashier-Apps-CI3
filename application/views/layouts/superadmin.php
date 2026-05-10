<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' — Aksir Admin' : 'Aksir Admin' ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        .sidebar-link {
            color: rgba(255, 255, 255, 0.65);
            transition: all 0.15s ease;
        }

        .sidebar-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
        }

        .sidebar-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border-left: 3px solid #fff;
            padding-left: calc(0.75rem - 3px);
        }

        /* Sidebar transitions di mobile */
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 50;
                transform: translateX(-100%);
                transition: transform 0.25s ease-out;
            }

            #sidebar.open {
                transform: translateX(0);
            }

            #sidebar-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.25s ease-out;
            }

            #sidebar-backdrop.open {
                opacity: 1;
                pointer-events: auto;
            }
        }
    </style>

    <?= $extra_css ?? '' ?>
</head>

<body class="bg-slate-100">

    <?php
    $navy = '#1e3a5f';
    $active = $active_menu ?? 'dashboard';
    $menus = [
        [
            'key'   => 'dashboard',
            'label' => 'Dashboard',
            'url'   => 'dashboard/superadmin',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
        ],
        [
            'key'   => 'users',
            'label' => 'Pengguna',
            'url'   => 'superadmin/users',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
        ],
        [
            'key'   => 'products',
            'label' => 'Produk',
            'url'   => 'superadmin/products',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
        ],
        [
            'key'   => 'transactions',
            'label' => 'Transaksi',
            'url'   => 'superadmin/transactions/reports',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h4M9 17H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-4m-6 0v4m0 0h6m-6 0l3-3m3 3l-3-3"/>',
        ],
    ];

    $username = $this->session->userdata('username') ?? 'Admin';
    ?>

    <div class="flex h-screen overflow-hidden">

        <!-- Backdrop (mobile only) -->
        <div id="sidebar-backdrop" onclick="toggleSidebar()"></div>

        <!-- SIDEBAR -->
        <aside id="sidebar" class="w-64 flex flex-col flex-shrink-0" style="background-color: <?= $navy ?>;">

            <!-- Logo -->
            <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center border border-white/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white tracking-wide" style="font-family:'Playfair Display',serif;">Aksir</h1>
                        <p class="text-[10px] text-white/50 -mt-0.5">Admin Panel</p>
                    </div>
                </div>

                <!-- Close button (mobile only) -->
                <button type="button" onclick="toggleSidebar()"
                    class="lg:hidden w-8 h-8 rounded-lg flex items-center justify-center text-white/60 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
                <p class="text-[10px] uppercase tracking-widest text-white/40 px-3 mb-2">Menu Utama</p>

                <?php foreach ($menus as $m): ?>
                    <a href="<?= base_url('index.php/' . $m['url']) ?>"
                        class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-sm <?= $active === $m['key'] ? 'active' : '' ?>">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <?= $m['icon'] ?>
                        </svg>
                        <span><?= $m['label'] ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <!-- Footer info -->
            <div class="px-6 py-4 border-t border-white/10">
                <p class="text-[10px] text-white/40">v1.0.0 · &copy; <?= date('Y') ?></p>
            </div>
        </aside>

        <!-- CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Header -->
            <header class="bg-white border-b border-slate-200 px-4 sm:px-6 py-3 flex items-center justify-between flex-shrink-0">

                <div class="flex items-center gap-3 min-w-0">
                    <!-- Hamburger (mobile only) -->
                    <button type="button" onclick="toggleSidebar()"
                        class="lg:hidden w-9 h-9 rounded-lg flex items-center justify-center text-slate-600 hover:bg-slate-100 transition-colors flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <h2 class="text-base font-semibold text-slate-800 truncate">
                        <?= $page_title ?? ($menus[array_search($active, array_column($menus, 'key'))]['label'] ?? 'Dashboard') ?>
                    </h2>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <!-- User info (hide name on small screen) -->
                    <div class="flex items-center gap-2.5 px-2 sm:px-3 py-1.5 rounded-lg hover:bg-slate-50 transition-colors">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                            style="background-color: <?= $navy ?>;">
                            <?= strtoupper(substr($username, 0, 1)) ?>
                        </div>
                        <span class="hidden sm:inline text-sm font-medium text-slate-700"><?= $username ?></span>
                    </div>

                    <!-- Logout -->
                    <a href="<?= base_url('index.php/logout') ?>"
                        class="flex items-center gap-1.5 px-2 sm:px-3 py-1.5 text-sm text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                        title="Logout">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline">Logout</span>
                    </a>
                </div>
            </header>

            <!-- Main -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6">

                <?php if ($this->session->flashdata('success')): ?>
                    <div id="successBox"
                        class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-2 transition-all duration-500 transform opacity-100">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <?= $this->session->flashdata('success') ?>
                    </div>

                    <script>
                        setTimeout(() => {
                            const alertBox = document.getElementById('successBox');
                            if (alertBox) {
                                alertBox.classList.add('opacity-0', '-translate-y-2');
                                setTimeout(() => alertBox.remove(), 500);
                            }
                        }, 3000);
                    </script>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?= $content ?>

            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('open');
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
        }

        // Close sidebar with ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && document.getElementById('sidebar').classList.contains('open')) {
                toggleSidebar();
            }
        });
    </script>

    <?= $extra_js ?? '' ?>

</body>

</html>