<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $layout = 'admin';

    public function __construct()
    {
        parent::__construct();
        
    }

    protected function render(string $view, $data = [], $layout = null)
    {
        $layout = $layout ?? $this->layout;

        $data['content'] = $this->load->view($view, $data, true);

        $this->load->view("layouts/{$layout}", $data);
    }
}
