<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_pkl extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin/Admin_config_model', 'Config');
        $this->load->model('Admin/Admin_pkl_model', 'PKL');
        $this->role         = 'admin';
        $this->redirect     = 'admin/master/pkl';
        cek_login('Admin');
    }

    public function index()
    {

        $major      = $this->PKL->getAllMajor()->result();
        $academic   = $this->Config->getDataAcademicYear()->result();
        $data       = [
            'title'         => 'Data PKL Program Studi',
            'desc'          => 'Berfungsi untuk melihat Data PKL Program Studi',
            'academicyear'  => $academic,
            'majors'        => $major
        ];

        $page = '/admin/pkl/index';
        pageBackend($this->role, $page, $data);
    }
}
