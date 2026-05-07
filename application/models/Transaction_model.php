<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_model extends CI_Model
{

    protected $table = 'transactions';

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
}
