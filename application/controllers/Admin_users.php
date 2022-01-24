<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin/Admin_users_model', 'Users');
        $this->load->model('Admin/Admin_config_model', 'Config');

        $this->role         = 'admin';
        $this->redirect     = 'admin/master/users';


        cek_login('Admin');
    }

    public function index()
    {
        $data = [
            'title' => 'Data User',
            'desc'  => 'Berfungsi untuk melihat Data User',
            'users' => $this->Users->getAllData()->result()
        ];



        $page = '/admin/users/index';
        pageBackend($this->role, $page, $data);
    }

    public function update($id, $type)
    {
        $explode = explode(":", $type);
        if ($explode[1] === 'status') {
            $dataUpdate   = [
                'is_active' => $explode[0]
            ];
            $where          = [
                'id'        => $id
            ];
            $flashdata  = $this->session->set_flashdata('success', 'Data Berhasil di update');
        }

        if ($explode[1] === 'password') {
            $newPassword = random_string('alnum', 8);
            $dataUpdate   = [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT)
            ];
            $where          = [
                'id'        => $id
            ];
            $flashdata  = $this->session->set_flashdata('success', 'Password berhasil di reset dengan password baru <b>' . $newPassword . '</b>');
        }

        $update = $this->Users->update($dataUpdate, $where);
        if ($update > 0) {
            $flashdata;
        } else {
            $this->session->set_flashdata('error', 'Server Sedang sibuk, silahkan coba lagi');
        }
        redirect($this->redirect);
    }
}
