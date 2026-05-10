<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        // Pastikan hanya admin yang bisa akses
        // if (!$this->session->userdata('is_admin')) redirect('dashboard');
    }

    public function index()
    {
        $data = [
            'page_title'  => 'Pengguna',
            'active_menu' => 'users',
            'users'       => $this->User_model->get_all(),
        ];
        $this->render('users/index', $data, 'superadmin');
    }

    public function store()
    {
        $this->form_validation->set_rules('fullname', 'Nama Lengkap', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('username', 'Username',     'required|trim|min_length[3]|alpha_dash|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password',     'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('users');
        }

        $this->User_model->insert([
            'fullname' => $this->input->post('fullname'),
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'is_admin' => $this->input->post('is_admin') ? 1 : 0,
        ]);

        $this->session->set_flashdata('success', 'User berhasil ditambahkan.');
        redirect('users');
    }

    public function update(int $id)
    {
        $user = $this->User_model->get($id);
        if (!$user) show_404();

        // Username harus unik kecuali username sendiri
        $this->form_validation->set_rules('fullname', 'Nama Lengkap', 'required|trim|min_length[3]');
        $this->form_validation->set_rules(
            'username',
            'Username',
            'required|trim|min_length[3]|alpha_dash|callback_unique_username[' . $id . ']'
        );

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('users');
        }

        $data = [
            'fullname' => $this->input->post('fullname'),
            'username' => $this->input->post('username'),
            'is_admin' => $this->input->post('is_admin') ? 1 : 0,
        ];

        // Update password hanya kalau diisi
        $password = $this->input->post('password');
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $this->session->set_flashdata('error', 'Password minimal 6 karakter.');
                redirect('users');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->User_model->update($id, $data);
        $this->session->set_flashdata('success', 'User berhasil diperbarui.');
        redirect('users');
    }

    public function delete(int $id)
    {
        // Cegah hapus diri sendiri
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Tidak bisa menghapus akun sendiri.');
            redirect('users');
        }

        $this->User_model->delete($id);
        $this->session->set_flashdata('success', 'User berhasil dihapus.');
        redirect('users');
    }

    /**
     * Validasi: username unik kecuali untuk user yang sedang di-edit.
     */
    public function unique_username(string $username,int $id)
    {
        $exists = $this->db->where('username', $username)
            ->where('id !=', $id)
            ->count_all_results('users');

        if ($exists > 0) {
            $this->form_validation->set_message('unique_username', 'Username sudah dipakai.');
            return false;
        }
        return true;
    }
}
