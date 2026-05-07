<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Transaction_model');

        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function admin()
    {
        $data = [
            'title'       => 'Beranda',
            'active_menu' => 'beranda',     // tab aktif di bottom nav
            'user'        => ['nama' => 'Andi'],
            'omzet_hari_ini'    => 1250000,
            'jumlah_transaksi'  => 12,
            'produk_terjual'    => 47,
            'total_stok'        => 320,
            'stok_menipis'      => 5,
            'transaksi_terbaru' => [
                ['kode' => 'TRX0012', 'waktu' => '14:22', 'item' => 3, 'total' => 145000],
                ['kode' => 'TRX0011', 'waktu' => '13:55', 'item' => 1, 'total' => 28000],
                ['kode' => 'TRX0010', 'waktu' => '13:10', 'item' => 5, 'total' => 312000],
            ],
        ];

        $this->render('admin/dashboard', $data, 'admin');
    }

    public function superadmin()
    {
        $this->render('superadmin/dashboard', [
            'title' => 'Dashboard',
            'transactions' => []
        ], 'superadmin');
    }
}
