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

    public function create(array $data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update(int $id, array $data)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->table, $data);
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
}
