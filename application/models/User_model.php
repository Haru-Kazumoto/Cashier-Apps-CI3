<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    protected $table = 'users';

    public function get_all()
    {
        return $this->db
            ->order_by('created_at', 'desc')
            ->get($this->table)
            ->result_array();
    }

    public function get($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->get($this->table)
            ->row_array();
    }

    public function get_by_username(string $username)
    {
        return $this->db
            ->where('username', $username)
            ->get($this->table)
            ->row_array();
    }

    public function insert(array $data)
    {
        $clean = $this->sanitize($data);
        $clean['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $clean);
        return $this->db->insert_id();
    }

    public function update(int $id, array $data)
    {
        $clean = $this->sanitize($data);
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, $clean);
    }

    public function get_status_role_by_id(int $id)
    {
        return $this->db->where('id', $id)
            ->get($this->table)
            ->row();
    }

    public function delete(int $id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->delete($this->table);
    }

    private function sanitize(array $data)
    {
        $allowed = ['fullname', 'username', 'password', 'is_admin'];
        return array_intersect_key($data, array_flip($allowed));
    }

    public function count_all()
    {
        return $this->db->count_all('users');
    }
}
