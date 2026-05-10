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
        $dari   = $this->input->get('dari')   ?: date('Y-m-01');
        $sampai = $this->input->get('sampai') ?: date('Y-m-d');

        // Validasi simpel: pastikan format YYYY-MM-DD
        if (!$this->valid_date($dari))   $dari   = date('Y-m-01');
        if (!$this->valid_date($sampai)) $sampai = date('Y-m-d');

        // Kalau dari > sampai, swap supaya gak hasil kosong
        if (strtotime($dari) > strtotime($sampai)) {
            [$dari, $sampai] = [$sampai, $dari];
        }

        $data = [
            'title'         => 'Laporan',
            'layout'        => 'kasir',
            'active_menu'   => 'laporan',
            'filter_dari'   => $dari,
            'filter_sampai' => $sampai,
            'transaksi'     => $this->Transaction_model->get_by_periode($dari, $sampai),
        ];

        $this->render('transactions/reports', $data, 'admin');
    }

    public function reports_superadmin()
    {
        $dari   = $this->input->get('dari')   ?: date('Y-m-01');
        $sampai = $this->input->get('sampai') ?: date('Y-m-d');

        // Validasi simpel: pastikan format YYYY-MM-DD
        if (!$this->valid_date($dari))   $dari   = date('Y-m-01');
        if (!$this->valid_date($sampai)) $sampai = date('Y-m-d');

        // Kalau dari > sampai, swap supaya gak hasil kosong
        if (strtotime($dari) > strtotime($sampai)) {
            [$dari, $sampai] = [$sampai, $dari];
        }

        $data = [
            'title'         => 'Laporan',
            'layout'        => 'kasir',
            'active_menu'   => 'transactions',
            'filter_dari'   => $dari,
            'filter_sampai' => $sampai,
            'transaksi'     => $this->Transaction_model->get_by_periode($dari, $sampai),
        ];

        $this->render('transactions/reports_superadmin', $data, 'superadmin');
    }

    public function export()
    {
        $dari   = $this->input->get('dari')   ?: date('Y-m-01');
        $sampai = $this->input->get('sampai') ?: date('Y-m-d');

        if (!$this->valid_date($dari))   $dari   = date('Y-m-01');
        if (!$this->valid_date($sampai)) $sampai = date('Y-m-d');

        $transaksi = $this->Transaction_model->get_by_periode($dari, $sampai);

        // Output CSV
        $filename = "laporan_transaksi_{$dari}_sampai_{$sampai}.csv";
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");

        $out = fopen('php://output', 'w');
        // BOM untuk Excel-friendly UTF-8
        fputs($out, "\xEF\xBB\xBF");

        // Header CSV
        fputcsv($out, ['Invoice', 'Tanggal', 'Kasir', 'Metode', 'Jumlah Item', 'Subtotal', 'Diskon', 'Total']);

        // Data
        foreach ($transaksi as $t) {
            fputcsv($out, [
                $t['kode'],
                $t['waktu'],
                $t['kasir'],
                $t['metode'],
                $t['jumlah_item'],
                $t['subtotal'],
                $t['diskon'],
                $t['total'],
            ]);
        }

        fclose($out);
        exit;
    }

    private function valid_date(string $str)
    {
        $d = DateTime::createFromFormat('Y-m-d', $str);
        return $d && $d->format('Y-m-d') === $str;
    }
}
