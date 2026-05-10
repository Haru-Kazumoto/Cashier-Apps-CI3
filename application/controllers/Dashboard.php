<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(['User_model', 'Product_model', 'Transaction_model']);

        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function admin()
    {
        $data = [
            'title'       => 'Beranda',
            'todays_summary' => $this->Transaction_model->get_today_summary(),
        ];

        $this->render('admin/dashboard', $data, 'admin');
    }

    public function superadmin()
    {
        $data = [
            'page_title'         => 'Dashboard',
            'active_menu'        => 'dashboard',
            'total_users'        => $this->User_model->count_all(),
            'total_produk'       => $this->Product_model->count_all(),
            'transaksi_hari_ini' => $this->Transaction_model->count_today(),
            'omzet_hari_ini'     => $this->Transaction_model->omzet_today(),
            'transaksi_terbaru'  => $this->Transaction_model->get_recent(5),
            'stok_menipis'       => $this->Product_model->get_stok_menipis(5),
        ];

        $this->render('superadmin/dashboard', $data, 'superadmin');
    }
}
