<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa_krs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mahasiswa/Mahasiswa_krs_model', 'Krs');
        $this->load->model('Auth_model', 'Auth');
        $this->role = 'mahasiswa';
        cek_login('Mahasiswa');
        $this->redirectUrl = 'mahasiswa/krs';
    }

    public function index()
    {
        // cek tahun akademik aktif
        $akademik = $this->Krs->getActiveAcademicYear();

        $data = [
            'title'         => 'Buat KRS',
            'desc'          => 'Berfungsi untuk membuat krs',
            'akademik'      => $akademik
        ];
        $page       = '/mahasiswa/krs/index';
        if (date("Y-m-d") > $akademik->last_register_krs) {
            $page       = '/mahasiswa/krs/index-closed';
        }
        pageBackend($this->role, $page, $data);
    }
}
