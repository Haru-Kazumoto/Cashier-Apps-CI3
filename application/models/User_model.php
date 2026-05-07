<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    protected $table = 'users';

    public function get_all()
    {
        return $this->db
            ->where('deleted_at', null)
            ->get($this->table)
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

    public function get_by_username(string $username)
    {
        return $this->db
            ->where('username', $username)
            ->where('deleted_at', null)
            ->get($this->table)
            ->row();
    }

    public function get_status_role_by_id(int $id)
    {
        return $this->db->where('id', $id)
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

    public function delete(int $id)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->table, [
                'deleted_at' => date('Y-m-d H:i:s')
            ]);
    }
}
