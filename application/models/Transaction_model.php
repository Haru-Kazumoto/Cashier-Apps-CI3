<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_model extends CI_Model
{

    protected $table = 'transactions';

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Product_model");
    }

    public function get_all()
    {
        return $this->db
            ->select('transactions.*, users.fullname')
            ->from($this->table)
            ->join('users', 'users.id = transactions.created_by')
            ->order_by('transactions.id', 'DESC')
            ->get()
            ->result();
    }

    public function get_by_id(int $id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row();
    }

    public function create(array $data)
    {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function save(array $cart, string $payment_method, $uang_diterima = 0, $created_by = null)
    {
        if (empty($cart)) {
            return ['success' => false, 'error' => 'Keranjang kosong.'];
        }

        $this->db->trans_begin();

        // 1. Validasi & hitung ulang total dari DB (JANGAN percaya total dari client)
        $total = 0;
        $items = [];

        foreach ($cart as $item) {
            $produk = $this->Product_model->get_by_id($item['id']);

            if (!$produk) {
                $this->db->trans_rollback();
                return ['success' => false, 'error' => "Produk #{$item['id']} tidak ditemukan."];
            }

            $qty = (int) $item['quantity'];
            if ($produk->stock < $qty) {
                $this->db->trans_rollback();
                return [
                    'success' => false,
                    'error'   => "Stok {$produk['nama']} tidak cukup (tersisa {$produk->stock}).",
                ];
            }

            // Ambil harga dari DB, bukan dari client (anti-tampering)
            $price    = (int) $produk->price;
            $subtotal = $price * $qty;
            $total   += $subtotal;

            $items[] = [
                'product_id' => (int) $produk->id,
                'quantity'   => $qty,
                'price'      => $price,
                'subtotal'   => $subtotal,
            ];
        }

        // 2. Hitung total_pay & total_return berdasarkan metode
        if ($payment_method === 'tunai') {
            $total_pay = (int) $uang_diterima;
            if ($total_pay < $total) {
                $this->db->trans_rollback();
                return ['success' => false, 'error' => 'Uang diterima kurang dari total.'];
            }
            $total_return = $total_pay - $total;
        } else {
            // Non-tunai: dianggap dibayar pas
            $total_pay    = $total;
            $total_return = 0;
        }

        // 3. Insert header transaksi
        $this->db->insert('transactions', [
            'invoice_number' => $this->generate_invoice(),
            'total_price'    => $total,
            'total_pay'      => $total_pay,
            'total_return'   => $total_return,
            'payment_method' => $payment_method,
            'created_by'     => $created_by,
        ]);

        $transaction_id = $this->db->insert_id();

        // 4. Insert details + kurangi stok produk
        foreach ($items as $it) {
            $it['transaction_id'] = $transaction_id;
            $this->db->insert('transaction_details', $it);
            $this->Product_model->decrease_stock($it['product_id'], $it['quantity']);
        }

        // 5. Commit / Rollback
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return ['success' => false, 'error' => 'Gagal menyimpan transaksi (DB error).'];
        }

        $this->db->trans_commit();

        // Ambil invoice yang baru di-insert
        $invoice = $this->db->select('invoice_number')
            ->where('id', $transaction_id)
            ->get('transactions')->row()->invoice_number;

        return [
            'success'      => true,
            'id'           => $transaction_id,
            'invoice'      => $invoice,
            'total'        => $total,
            'total_pay'    => $total_pay,
            'total_return' => $total_return,
        ];
    }

    /**
     * Generate invoice format: TRX-YYYYMMDD-XXXX (sequential per hari).
     */
    private function generate_invoice()
    {
        $prefix = 'TRX-' . date('Ymd');
        $count  = $this->db
            ->like('invoice_number', $prefix, 'after')
            ->count_all_results('transactions');

        return $prefix . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Ambil 1 transaksi lengkap dengan detail items (untuk struk).
     */
    public function get_with_details($id)
    {
        $trx = $this->db
            ->where('id', (int) $id)
            ->get('transactions')->row_array();

        if (!$trx) return null;

        $trx['items'] = $this->db
            ->select('td.*, p.nama AS product_name')
            ->from('transaction_details td')
            ->join('produk p', 'p.id = td.product_id', 'left')
            ->where('td.transaction_id', $id)
            ->get()->result_array();

        return $trx;
    }

    /**
     * Ambil transaksi by periode (untuk laporan).
     */
    public function get_by_periode(string $dari, string $sampai)
    {
        $rows = $this->db
            ->where('DATE(created_at) >=', $dari)
            ->where('DATE(created_at) <=', $sampai)
            ->order_by('created_at', 'desc')
            ->get('transactions')->result_array();

        foreach ($rows as &$r) {
            $r['items'] = $this->db
                ->select('td.*, p.nama AS product_name')
                ->from('transaction_details td')
                ->join('produk p', 'p.id = td.product_id', 'left')
                ->where('td.transaction_id', $r['id'])
                ->get()->result_array();
            $r['jumlah_item'] = array_sum(array_column($r['items'], 'quantity'));
        }
        return $rows;
    }
}
