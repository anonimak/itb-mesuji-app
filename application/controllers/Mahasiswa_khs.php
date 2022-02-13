<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa_khs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mahasiswa/Mahasiswa_krs_model', 'Khs');
        $this->load->model('Auth_model', 'Auth');
        $this->role = 'mahasiswa';
        cek_login('Mahasiswa');
        $this->redirectUrl = 'mahasiswa/khs';
    }

    public function index()
    {
        $student = $this->session->userdata('username');
        $dataKhs = $this->Khs->getKhs($student->id);
        $data = [
            'title'         => 'Data KHS',
            'desc'          => 'Berfungsi untuk melihat data khs',
            'dataKhs'      => $dataKhs->result()
        ];
        $page       = '/mahasiswa/khs/index';
        pageBackend($this->role, $page, $data);
    }

    public function indexDetail($id)
    {
        $id   = decodeEncrypt($id);
        $student = $this->session->userdata('username');
        $dataKhs = $this->Khs->getKhsbyId($id, $student->id)->row();
        $getsumkrs = $this->Khs->getSumKrs($student->id, $dataKhs->semester + 1);
        $dataKhs->total_kredit = ($getsumkrs) ? $getsumkrs->total_kredit : 0;
        $dataKhs->ipk = ($getsumkrs) ? $getsumkrs->ipk : 0;
        $detailKhs = $this->Khs->getKhsCourseTakenbyKhsId($dataKhs->id)->result();
        $score  = 0;
        foreach ($detailKhs as $detail) {
            $score += (int)$detail->score;
        }
        $data = [
            'title'         => 'Semester ' . $dataKhs->semester,
            'desc'          => 'Berfungsi untuk melihat detail khs Semester ' . $dataKhs->semester,
            'dataKhs'       => $dataKhs,
            'detailKhs'     => $detailKhs,
            'score'         => $score
        ];
        $page       = '/mahasiswa/khs/detail/index';

        pageBackend($this->role, $page, $data);
    }

    public function reporttoPDF($id)
    {
        $id   = decodeEncrypt($id);
    }
}
