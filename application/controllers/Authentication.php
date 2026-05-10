<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends MY_Controller
{

    private $base_layout = 'guest';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    private function redirect_authenticated_user(int $user_id)
    {
        if ($user_id) {
            $user = $this->User_model->get_status_role_by_id($user_id);

            if ($user) {
                if (!$user->is_admin) {
                    redirect('dashboard/superadmin');
                } else {
                    redirect('kasir/dashboard');
                }
            }
        }
    }

    private function build_session_data(array $user)
    {
        return [
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'is_admin'  => $user['is_admin'],
            'role'      => $user['is_admin'] > 0 ? 'admin' : 'superadmin',
            'fullname'  => $user['fullname'],
            'logged_in' => true
        ];
    }

    public function login()
    {
        $data = [
            'title' => 'Login',
        ];

        $this->render('auth/login', $data, $this->base_layout);
    }

    public function authenticate()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->login();
            return;
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->User_model->get_by_username($username);

            if ($user && password_verify($password, $user['password'])) {
                $this->session->set_userdata($this->build_session_data((array)$user));
                $this->session->set_flashdata('success', 'Login berhasil! Halo '.$user['fullname'].' Selamat datang kembali!');

                $this->redirect_authenticated_user($user['id']);
            } else {
                $this->session->set_flashdata('error', 'Kredensial tidak valid! Coba lagi.');

                $this->login();
            }
        }
    }

    public function logout()
    {
        $this->session->unset_userdata(['user_id', 'username', 'is_admin', 'fullname', 'logged_in']);
        $this->session->set_flashdata('logout_success', 'Logout berhasil!');
        redirect('authentication/login');
    }
}
