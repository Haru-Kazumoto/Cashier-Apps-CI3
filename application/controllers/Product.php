<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Product_model');
        $this->load->model('Category_model');

        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    // ini untuk tampilan list produk
    public function index()
    {
        $data = [
            'title' => 'Produk',
            'active_menu' => 'produk',
            'products' => $this->Product_model->get_all(),
        ];

        $this->render('products/index', $data, 'admin');
    }

    // ini untuk 
    public function new()
    {
        $data = [
            'title' => 'Tambah Produk',
            'categories' => $this->Category_model->get_all(),
        ];

        $this->render('products/create', $data, 'admin');
    }

    public function save()
    {
        // Hanya terima POST
        if ($this->input->method() !== 'post') {
            redirect('kasir/produk');
        }

        $this->form_validation->set_rules('nama',  'Nama Produk', 'required|trim|max_length[200]');
        $this->form_validation->set_rules('harga', 'Harga',       'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('stok',  'Stok',        'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() === false) {
            $data['categories']   = $this->Product_model->get_categories();
            $data['upload_error'] = null;
            $this->render('kasir/produk/tambah', $data, 'admin');
            return;
        }

        $result = $this->Product_model->save(
            $this->input->post(null, true),
            $_FILES
        );

        if ($result === true) {
            $this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
            redirect('kasir/produk');
        } else {
            // Error dari upload atau DB
            $data['categories']   = $this->Product_model->get_categories();
            $data['upload_error'] = $result;
            $this->render('kasir/produk/tambah', $data, 'admin');
        }
    }

    // ini untuk edit bray
    public function edit(int $id)
    {
        $data_produk = $this->Product_model->get_by_id($id);

        if (!$data_produk) return;

        $data = [
            'title'         => 'Edit Produk',
            'categories'    => $this->Category_model->get_all(),
            'produk'        => $data_produk
        ];

        $this->render('products/edit', $data, 'admin');
    }

    public function update(int $id)
    {
        $produk = $this->Product_model->get_by_id($id);
        if (!$produk) show_404();

        $this->form_validation->set_rules('nama', 'Nama Produk', 'required|trim');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $data_update = [
            'name'        => $this->input->post('nama'),
            'category_id' => $this->input->post('kategori') ?: null,
            'price'       => $this->input->post('harga'),
            'stock'       => $this->input->post('stok'),
            'description' => $this->input->post('deskripsi'),
        ];

        // Logika gambar — 3 skenario
        $hapus_gambar = $this->input->post('hapus_gambar') == '1';
        $ada_upload   = !empty($_FILES['gambar']['name']);

        if ($ada_upload) {
            // 1. Upload gambar baru → ganti yang lama
            $config = [
                'upload_path'   => './uploads/produk/',
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => 2048,
                'encrypt_name'  => TRUE,
                'file_name'     => 'gambar_produk_' . time()
            ];
            if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0755, true);

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('gambar')) {
                $data = [
                    'title'        => 'Edit Produk',
                    'layout'       => 'kasir',
                    'active_menu'  => 'produk',
                    'produk'       => $produk,
                    'kategori'     => $this->Category_model->get_all(),
                    'upload_error' => $this->upload->display_errors('', ''),
                ];
                return $this->load->view('kasir/produk/form_edit', $data);
            }

            // Hapus file lama (kalau ada)
            if (!empty($produk->image)) {
                $old = './uploads/produk/' . $produk->image;
                if (file_exists($old)) unlink($old);
            }
            $data_update['image'] = $this->upload->data('file_name');
        } elseif ($hapus_gambar && !empty($produk->image)) {
            // 2. User klik X tanpa upload → hapus yang lama
            $old = './uploads/produk/' . $produk->image;
            if (file_exists($old)) unlink($old);
            $data_update['image'] = null;
        }
        // 3. Tidak upload + tidak hapus → kolom 'gambar' gak diutak-atik

        $this->Product_model->update($id, $data_update);
        $this->session->set_flashdata('success', 'Produk berhasil diperbarui.');
        redirect('kasir/produk');
    }
}
