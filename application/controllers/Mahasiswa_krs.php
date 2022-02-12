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
        $student = $this->session->userdata('username');
        // check current krs
        $currentKrs = $this->Krs->getKrsCurrent($akademik->id, $student->id)->row();
        // var_dump($currentKrs);
        // die;
        $data = [
            'title'         => 'Buat KRS',
            'desc'          => 'Berfungsi untuk membuat krs',
            'akademik'      => $akademik,
            'currentKrs'    => $currentKrs,
        ];
        $page       = '/mahasiswa/krs/index';
        if (date("Y-m-d") > $akademik->last_register_krs) {
            $page       = '/mahasiswa/krs/index-closed';
        }
        pageBackend($this->role, $page, $data);
    }
}
