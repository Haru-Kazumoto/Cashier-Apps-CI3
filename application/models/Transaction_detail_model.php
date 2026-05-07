<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_detail_model extends CI_Model
{

    protected $table = 'transaction_details';

    public function get_by_transaction(int $transaction_id)
    {
        return $this->db
            ->select('transaction_details.*, products.name as product_name')
            ->from($this->table)
            ->join('products', 'products.id = transaction_details.product_id')
            ->where('transaction_id', $transaction_id)
            ->get()
            ->result();
    }

    public function create(array $data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function create_batch(array $data)
    {
        return $this->db->insert_batch($this->table, $data);
    }
}
