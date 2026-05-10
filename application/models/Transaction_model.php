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
            'payment_method' => strtoupper($payment_method),
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
        // 1. Ambil header transaksi + join nama kasir
        $rows = $this->db
            ->select('t.*, u.fullname AS kasir_name')
            ->from('transactions t')
            ->join('users u', 'u.id = t.created_by', 'left')
            ->where('DATE(t.created_at) >=', $dari)
            ->where('DATE(t.created_at) <=', $sampai)
            ->order_by('t.created_at', 'desc')
            ->get()
            ->result_array();

        if (empty($rows)) return [];

        // 2. Batch fetch semua details sekaligus pakai WHERE IN.
        //    Lebih efisien dibanding query per transaksi (N+1 problem).
        $trx_ids = array_column($rows, 'id');
        $items_raw = $this->db
            ->select('td.transaction_id, td.quantity, td.price, td.subtotal, p.name AS product_name')
            ->from('transaction_details td')
            ->join('products p', 'p.id = td.product_id', 'left')
            ->where_in('td.transaction_id', $trx_ids)
            ->get()
            ->result_array();

        // 3. Group items per transaction_id, sambil hitung subtotal & jumlah_item
        $items_per_trx    = [];
        $subtotal_per_trx = [];
        $qty_per_trx      = [];

        foreach ($items_raw as $i) {
            $tid = $i['transaction_id'];

            $items_per_trx[$tid][] = [
                'nama'  => $i['product_name'] ?? 'Produk dihapus',
                'harga' => (int) $i['price'],
                'qty'   => (int) $i['quantity'],
            ];

            $subtotal_per_trx[$tid] = ($subtotal_per_trx[$tid] ?? 0) + (int) $i['subtotal'];
            $qty_per_trx[$tid]      = ($qty_per_trx[$tid]      ?? 0) + (int) $i['quantity'];
        }

        // 4. Map ke struktur yang sesuai dengan kebutuhan view
        $result = [];
        foreach ($rows as $r) {
            $tid      = (int) $r['id'];
            $subtotal = $subtotal_per_trx[$tid] ?? 0;
            $total    = (int) $r['total_price'];

            // Diskon = selisih subtotal vs total_price (kalau ada)
            $diskon = max(0, $subtotal - $total);

            $result[] = [
                'id'              => $tid,
                'kode'            => $r['invoice_number'],
                'waktu'           => $r['created_at'],
                'waktu_formatted' => $this->format_waktu_id($r['created_at']),
                'kasir'           => $r['kasir_name'] ?? 'Kasir',
                'metode'          => $r['payment_method'],
                'jumlah_item'     => $qty_per_trx[$tid] ?? 0,
                'subtotal'        => $subtotal,
                'diskon'          => $diskon,
                'total'           => $total,
                'uang_diterima'   => $r['payment_method'] === 'tunai' ? (int) $r['total_pay'] : null,
                'items'           => $items_per_trx[$tid] ?? [],
            ];
        }

        return $result;
    }

    public function get_today_summary()
    {
        $today = date('Y-m-d');

        // 1. Ambil semua transaksi hari ini
        $rows = $this->db
            ->select('t.*, u.fullname AS kasir_name')
            ->from('transactions t')
            ->join('users u', 'u.id = t.created_by', 'left')
            ->where('DATE(t.created_at)', $today)
            ->order_by('t.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->result_array();

        if (empty($rows)) {
            return [
                'omzet'            => 0,
                'jumlah_transaksi' => 0,
                'produk_terjual'   => 0,
                'transaksi'        => [],
            ];
        }

        // 2. Ambil detail item semua transaksi sekaligus
        $trx_ids = array_column($rows, 'id');

        $details = $this->db
            ->select('
            td.transaction_id,
            td.quantity,
            td.price,
            td.subtotal,
            p.name AS product_name
        ')
            ->from('transaction_details td')
            ->join('products p', 'p.id = td.product_id', 'left')
            ->where_in('td.transaction_id', $trx_ids)
            ->get()
            ->result_array();

        // 3. Grouping
        $items_per_trx = [];
        $qty_per_trx   = [];

        foreach ($details as $d) {
            $tid = $d['transaction_id'];

            $items_per_trx[$tid][] = [
                'nama'  => $d['product_name'] ?? 'Produk dihapus',
                'harga' => (int) $d['price'],
                'qty'   => (int) $d['quantity'],
            ];

            $qty_per_trx[$tid] = ($qty_per_trx[$tid] ?? 0) + (int) $d['quantity'];
        }

        // 4. Mapping final
        $transaksi = [];
        $omzet = 0;
        $total_item = 0;

        foreach ($rows as $r) {
            $tid = (int) $r['id'];
            $total = (int) $r['total_price'];
            $qty = $qty_per_trx[$tid] ?? 0;

            $omzet += $total;
            $total_item += $qty;

            $transaksi[] = [
                'id'              => $tid,
                'kode'            => $r['invoice_number'],
                'waktu'           => $r['created_at'],
                'jam'             => date('H:i', strtotime($r['created_at'])),
                'kasir'           => $r['kasir_name'] ?? 'Kasir',
                'metode'          => $r['payment_method'],
                'jumlah_item'     => $qty,
                'total'           => $total,
                'items'           => $items_per_trx[$tid] ?? [],
            ];
        }

        return [
            'omzet'            => $omzet,
            'jumlah_transaksi' => count($rows),
            'produk_terjual'   => $total_item,
            'transaksi'        => $transaksi,
        ];
    }

    public function count_today()
    {
        return $this->db
            ->where('DATE(created_at)', date('Y-m-d'))
            ->count_all_results('transactions');
    }

    public function omzet_today()
    {
        $row = $this->db
            ->select_sum('total_price')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->get('transactions')
            ->row_array();
        return (int) ($row['total_price'] ?? 0);
    }

    public function get_recent($limit = 5)
    {
        return $this->db
            ->select('t.*, u.fullname AS kasir')
            ->from('transactions t')
            ->join('users u', 'u.id = t.created_by', 'left')
            ->order_by('t.created_at', 'desc')
            ->limit($limit)
            ->get()
            ->result_array();
    }

    private function format_waktu_id(string $datetime)
    {
        $bulan = [
            1 => 'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des',
        ];

        $ts = strtotime($datetime);
        if (!$ts) return $datetime;

        return date('d', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y, H:i', $ts);
    }
}
