<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Kasir' ?></title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        .sidebar-link.active {
            background: rgba(255, 255, 255, .12);
            border-left: 3px solid #fff;
        }
    </style>

    <?= $extra_css ?? '' ?>
</head>

<body class="bg-slate-100">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col">

            <div class="px-6 py-5 border-b border-white/10">
                <h1 class="text-xl font-bold">
                    Aksir
                </h1>
            </div>

            <nav class="flex-1 px-3 py-5 space-y-1">

                <a href="<?= base_url('dashboard') ?>"
                    class="sidebar-link flex items-center px-3 py-2 rounded-lg">
                    Dashboard
                </a>

                <a href="<?= base_url('products') ?>"
                    class="sidebar-link flex items-center px-3 py-2 rounded-lg">
                    Produk
                </a>

                <a href="<?= base_url('transactions') ?>"
                    class="sidebar-link flex items-center px-3 py-2 rounded-lg">
                    Transaksi
                </a>

            </nav>

        </aside>

        <!-- CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white border-b px-6 py-4 flex justify-between">
                <h2 class="font-semibold">
                    <?= $page_title ?? 'Dashboard' ?>
                </h2>

                <a href="<?= base_url('logout') ?>">
                    Logout
                </a>
            </header>

            <main class="flex-1 overflow-y-auto p-6">

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="mb-4 bg-green-100 text-green-700 p-4 rounded-lg">
                        <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="mb-4 bg-red-100 text-red-700 p-4 rounded-lg">
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?= $content ?>

            </main>
        </div>

    </div>

    <?= $extra_js ?? '' ?>

</body>

</html>