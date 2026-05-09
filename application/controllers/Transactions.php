<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transactions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Transaction_model');
        $this->load->model('Product_model');

        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    // ini tampilan list transaksi.
    public function index()
    {
        $data = [
            'title' => 'Transaksi',
            'active_menu' => 'transaksi',
            'transactions' => [],
            'produk' => $this->Product_model->get_all(),
        ];

        $this->render('transactions/index_create', $data, 'admin');
    }

    public function save()
    {
        $cart = json_decode($this->input->post('cart_data'), true);

        $payment_method = $this->input->post('metode') ?: 'tunai';
        $uang_diterima  = (int) $this->input->post('uang_diterima');
        $user_id        = $this->session->userdata('user_id'); // sesuaikan key session

        $result = $this->Transaction_model->save(
            $cart ?: [],
            $payment_method,
            $uang_diterima,
            $user_id
        );

        if (!$result['success']) {
            $this->session->set_flashdata('error', $result['error']);
            redirect('kasir/transaksi');
        }

        $this->session->set_flashdata('success', "Transaksi berhasil! Invoice: {$result['invoice']}");
        redirect('kasir/transaksi'); // atau ke halaman struk: kasir/struk/{id}
    }

    public function reports()
    {
        // ini untuk filtering, gausah dihiraukan nanti kerjaan gue (jiaur)
        $dari   = $this->input->get('dari')   ?: date('Y-m-01');
        $sampai = $this->input->get('sampai') ?: date('Y-m-d');

        // Ambil transaksi + items via model
        // Pastikan tiap item punya struktur lengkap (lihat di bawah)
        // $transaksi = $this->transaksi_model->get_by_periode($dari, $sampai);
        // bentuk datanya harus begini ya
        $transaksi = [
            [
                'id'              => 12,
                'kode'            => 'TRX0012',
                'waktu'           => '2026-05-08 14:22:00',
                'waktu_formatted' => '08 Mei 2026, 14:22',
                'kasir'           => 'Andi',
                'metode'          => 'tunai',          // tunai/transfer/qris
                'jumlah_item'     => 3,
                'subtotal'        => 145000,
                'diskon'          => 0,
                'total'           => 145000,
                'uang_diterima'   => 150000,           // hanya kalau tunai
                'items'           => [                 // array detail produk
                    ['nama' => 'Kopi Susu', 'harga' => 18000, 'qty' => 2],
                    ['nama' => 'Roti Bakar', 'harga' => 15000, 'qty' => 1],
                ],
            ]
        ]; // sementara gua pake begini, sisanya lu bikin sendiri ya :)

        $data = [
            'title'         => 'Laporan',
            'layout'        => 'kasir',
            'active_menu'   => 'laporan',
            'filter_dari'   => $dari,
            'filter_sampai' => $sampai,
            'transaksi'     => $transaksi,
        ];

        $this->render('transactions/reports', $data, 'admin');
    }
}
