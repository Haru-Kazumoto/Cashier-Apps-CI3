<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{

    protected $table = 'products';

    public function get_all()
    {
        return $this->db
            ->select('products.*, categories.name as category_name')
            ->from($this->table)
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.deleted_at', null)
            ->get()
            ->result();
    }

    public function get_by_id(int $id)
    {
        return $this->db
            ->where('id', $id)
            ->where('deleted_at', null)
            ->get($this->table)
            ->row();
    }

    public function save(array $data, array $file)
    {
        $file_name = null;

        if (!empty($file['gambar']['name'])) {
            $config_upload = [
                'upload_path'   => './uploads/produk/',
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => 2048, // KB
                'encrypt_name'  => true,
                'file_name'     => 'gambar_produk_' . time()
            ];

            // Buat folder jika belum ada
            if (!is_dir('./uploads/produk/')) {
                mkdir('./uploads/produk/', 0755, true);
            }

            $this->load->library('upload', $config_upload);

            if (!$this->upload->do_upload('gambar')) {
                return $this->upload->display_errors('', '');
            }

            $file_name = $this->upload->data('file_name');
        }

        $insert = [
            'name'          => $this->security->xss_clean($data['nama']),
            'category_id'   => !empty($data['kategori']) ? (int) $data['kategori'] : null,
            'code'          => random_int(10000, 99999),
            'price'         => (int) $data['harga'],
            'stock'         => (int) $data['stok'],
            'description'   => $this->security->xss_clean($data['deskripsi'] ?? ''),
            'image'         => $file_name,
            'created_by'    => $data['kasir_id']
        ];

        $this->db->insert('products', $insert);

        return $this->db->affected_rows() > 0 ? true : 'Gagal menyimpan ke database.';
    }

    public function update(int $id, array $data)
    {
        $clean = $this->sanitize($data);
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, $clean);
    }

    public function update_stock(int $id, int $qty)
    {
        return $this->db
            ->set('stock', 'stock - ' . $qty, false)
            ->where('id', $id)
            ->update($this->table);
    }

    public function delete(int $id)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->table, [
                'deleted_at' => date('Y-m-d H:i:s')
            ]);
    }

    private function sanitize(array $data)
    {
        $allowed = [
            'name',
            'category_id',
            'price',
            'stock',
            'description',
            'image'
        ];
        return array_intersect_key($data, array_flip($allowed));
    }

    public function decrease_stock(int $id, int $qty)
    {
        return $this->db
            ->set('stock', "GREATEST(stock - " . (int) $qty . ", 0)", false)
            ->where('id', (int) $id)
            ->update($this->table);
    }
}
