<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_course extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin/Admin_course_model', 'course');
        $this->load->model('Admin/Admin_config_model', 'Config');
        $this->role         = 'admin';
        $this->redirect     = 'admin/master/course';
        cek_login('Admin');
    }

    public function index()
    {
        $data = [
            'title'         => 'Data Matakuliah',
            'desc'          => 'Berfungsi untuk melihat Data Matakuliah',
            'allData'          => $this->course->getAllData()->result()
        ];

        $page = '/admin/course/index';
        pageBackend($this->role, $page, $data);
    }

    public function create()
    {
        $this->_validation();

        if ($this->form_validation->run() === false) {
            $data = [
                'title'         => 'Tambah Data Matakuliah',
                'desc'          => 'Berfungsi untuk menambah Data Matakuliah',
                'getprodi'      => $this->Config->getProdi()->result()
            ];

            $page = '/admin/course/create';
            pageBackend($this->role, $page, $data);
        } else {
            $this->db->set('id', 'UUID()', FALSE);
            $dataInput = [
                'name'      => htmlspecialchars($this->input->post('name')),
                'code'      => htmlspecialchars($this->input->post('code')),
                'sks'       => htmlspecialchars($this->input->post('sks')),
                'semester'  => htmlspecialchars($this->input->post('semester')),
                'prodi_id'  => htmlspecialchars($this->input->post('prodi'))
            ];
            $insert    = $this->course->insert($dataInput);
            if ($insert > 0) {
                $this->session->set_flashdata('success', 'Data berhasil di tambah');
            } else {
                $this->session->set_flashdata('error', 'Server Data Matakuliah sedang sibuk, silahkan coba lagi');
            }
            redirect($this->redirect);
        }
    }

    public function update($id)
    {
        $decodeId   = decodeEncrypt($id);
        $course      = $this->course->getDataBy(['id' => $decodeId])->row();
        if ($course) {
            $oldCode    = $course->code;
            $this->_validation($oldCode);
            if ($this->form_validation->run() === false) {
                $data = [
                    'title'         => 'Ubah Data Matakuliah',
                    'desc'          => 'Berfungsi untuk mengubah data Matakuliah',
                    'course'        => $course,
                    'getprodi'      => $this->Config->getProdi()->result()
                ];
                $page = '/admin/course/update';
                pageBackend($this->role, $page, $data);
            } else {
                $dataUpdate    = [
                    'name'      => htmlspecialchars($this->input->post('name')),
                    'code'      => htmlspecialchars($this->input->post('code')),
                    'sks'       => htmlspecialchars($this->input->post('sks')),
                    'semester'  => htmlspecialchars($this->input->post('semester')),
                    'prodi_id'  => htmlspecialchars($this->input->post('prodi'))
                ];
                $update        = $this->course->update($dataUpdate, ['id' => $decodeId]);
                if ($update > 0) {
                    $this->session->set_flashdata('success', 'Data berhasil di update');
                } else {
                    $this->session->set_flashdata('error', 'Update Data Matakuliah Sedang sibuk, silahkan coba lagi');
                }
                redirect($this->redirect);
            }
        } else {
            $this->session->set_flashdata('error', 'Data yang anda masukan tidak ada');
            redirect($this->redirect);
        }
    }

    public function delete($id)
    {
        $decodeId   = decodeEncrypt($id);
        $prodi      = $this->course->getDataBy(['id' => $decodeId])->row();
        if ($prodi) {
            $delete    = $this->course->delete(['id' => $decodeId]);
            if ($delete > 0) {
                $this->session->set_flashdata('success', 'Data berhasil di hapus');
            } else {
                $this->session->set_flashdata('error', 'Server data matakuliah sedang sibuk, silahkan coba lagi');
            }
        } else {
            $this->session->set_flashdata('error', 'Data yang anda masukan tidak ada');
        }
        redirect($this->redirect);
    }

    public function exportExcel()
    {
        $data   =   [
            'title'             => 'Data Matakuliah',
            'allData'           => $this->course->getAllData()->result()
        ];

        $this->load->view('admin/course/export/excel', $data);
    }

    private function _validation($oldCode = null)
    {
        $this->form_validation->set_rules(
            'name',
            'Matakuliah',
            'trim|required',
            [
                'required' => '%s wajib di isi',
            ]
        );

        $this->form_validation->set_rules(
            'sks',
            'SKS',
            'trim|required',
            [
                'required' => '%s wajib di isi',
            ]
        );

        if ($this->input->post('code') !== $oldCode) {
            $is_unique = '|is_unique[course.code]';
        } else {
            $is_unique = '';
        }
        $this->form_validation->set_rules(
            'code',
            'Kode',
            'trim|required' . $is_unique,
            [
                'required' => '%s wajib di isi',
            ]
        );

        $this->form_validation->set_rules(
            'semester',
            'Semester',
            'trim|required',
            [
                'required' => '%s wajib di isi',
            ]
        );

        if ($this->input->post('prodi') === "") {
            $this->form_validation->set_rules(
                'prodi',
                'Program Studi',
                'trim|required',
                [
                    'required' => '%s wajib di isi',
                ]
            );
        }
    }
}
