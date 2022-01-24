<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_head_program_study extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin/Admin_head_program_study_model', 'Kaprodi');
        $this->load->model('Admin/Admin_config_model', 'Config');


        $this->role         = 'admin';
        $this->redirect     = 'admin/master/head-of-program-study';


        cek_login('Admin');
    }

    public function index()
    {
        $allKaprodi           = $this->Kaprodi->getAllData()->result();
        $data = [
            'title'         => 'Data Kepala Program Studi',
            'desc'          => 'Berfungsi untuk melihat Data Kepala Program Studi',
            'allKaprodi'    => $allKaprodi
        ];

        $page = '/admin/kaprodi/index';
        pageBackend($this->role, $page, $data);
    }

    public function create()
    {
        $this->_validation();
        if ($this->form_validation->run() === false) {
            $data = [
                'title'         => 'Tambah Data Kepala Program Studi',
                'desc'          => 'Berfungsi untuk menambah Data Ketua Jurusan',
                'lectures'      => $this->Kaprodi->getLecture()->result()
            ];
            $page = '/admin/kaprodi/create';
            pageBackend($this->role, $page, $data);
        } else {
            $this->db->set('id', 'UUID()', FALSE);
            $stringPost = $this->input->post('lecture');
            $explode    = explode(":", $stringPost);
            $dataInput = [
                'lecture_id'    => htmlspecialchars($explode[0]),
                'prodi_id'      => htmlspecialchars($explode[1]),
                'no_hp'         => htmlspecialchars($this->input->post('nohp'))
            ];
            $insertKajur      = $this->Kaprodi->insert($dataInput);
            if ($insertKajur > 0) {
                $this->session->set_flashdata('success', 'Data berhasil di tambah');
            } else {
                $this->session->set_flashdata('error', 'Server sedang sibuk, silahkan coba lagi');
            }
            redirect($this->redirect);
        }
    }

    public function update()
    {
        $id         = $this->input->post('id');
        $status     = $this->input->post('status');
        $dataUpdate = [
            'status'        => $status,
            'updated_at'    => date('Y-m-d H:i:s')
        ];
        $update     = $this->Kaprodi->update($dataUpdate, ['id' => $id]);
        $this->output->set_content_type('application/json')->set_output(json_encode($update));
    }

    private function _validation()
    {
        $this->form_validation->set_rules(
            'lecture',
            'Kaprodi',
            'trim|required',
            [
                'required' => '%s wajib di isi',
            ]
        );

        $this->form_validation->set_rules(
            'nohp',
            'Nomor Hp',
            'trim|required|numeric|min_length[10]|max_length[14]',
            [
                'required'      => '%s wajib di isi',
                'numeric'       => '%s wajib angka',
                'min_length'    => '%s minimal 10 angka',
                'max_length'    => '%s maksimal 14 angka',
            ]
        );
    }
}
