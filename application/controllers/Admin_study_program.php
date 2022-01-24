<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_study_program extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin/Admin_study_program_model', 'PS');
        $this->load->model('Admin/Admin_config_model', 'Config');
        $this->role         = 'admin';
        $this->redirect     = 'admin/master/prodi';
        cek_login('Admin');
    }

    public function index()
    {
        $data = [
            'title'         => 'Data Program Studi',
            'desc'          => 'Berfungsi untuk melihat Data Program Studi',
            'allData'          => $this->PS->getAllData()->result()
        ];

        $page = '/admin/prodi/index';
        pageBackend($this->role, $page, $data);
    }

    public function create()
    {
        $this->_validation();

        if ($this->form_validation->run() === false) {
            $data = [
                'title'         => 'Tambah Data Program Studi',
                'desc'          => 'Berfungsi untuk menambah Data Program Studi',
                'getmajor'      => $this->Config->getMajor()->result()
            ];

            $page = '/admin/prodi/create';
            pageBackend($this->role, $page, $data);
        } else {
            $this->db->set('id', 'UUID()', FALSE);
            $email          = htmlspecialchars($this->input->post('email'));
            $dataInputProdi = [
                'name'      => htmlspecialchars($this->input->post('name')),
                'code'      => htmlspecialchars($this->input->post('code')),
                'degree'    => htmlspecialchars($this->input->post('degree')),
                'email'     => $email,
                'major_id'  => htmlspecialchars($this->input->post('major'))
            ];
            $insertProdi    = $this->PS->insert($dataInputProdi);
            if ($insertProdi > 0) {
                $this->db->set('id', 'UUID()', FALSE);
                $dataInsertUser = [
                    'username'  => $email,
                    'password'  => password_hash('123456', PASSWORD_DEFAULT),
                    'role_id'   => '775b0ed2-b7a8-11eb-a91e-0cc47abcfaa6',
                ];
                $insertUser = $this->Config->insertUserTable($dataInsertUser);
                if ($insertUser > 0) {
                    $this->session->set_flashdata('success', 'Data berhasil di tambah');
                } else {
                    $this->session->set_flashdata('error', 'Server Data User Sedang sibuk, silahkan coba lagi');
                }
            } else {
                $this->session->set_flashdata('error', 'Server Data Prodi sedang sibuk, silahkan coba lagi');
            }
            redirect($this->redirect);
        }
    }

    public function update($id)
    {
        $decodeId   = decodeEncrypt($id);
        $prodi      = $this->PS->getDataBy(['id' => $decodeId])->row();
        if ($prodi) {
            $oldEmail   = $prodi->email;
            $oldCode    = $prodi->code;
            $this->_validation($oldEmail, $oldCode);
            if ($this->form_validation->run() === false) {
                $data = [
                    'title'         => 'Ubah Data Program Studi',
                    'desc'          => 'Berfungsi untuk mengubah data Program Studi',
                    'prodi'         => $prodi,
                    'getmajor'      => $this->Config->getMajor()->result()
                ];
                $page = '/admin/prodi/update';
                pageBackend($this->role, $page, $data);
            } else {
                $newEmail   = htmlspecialchars($this->input->post('email'));
                $dataUpdateprodi    = [
                    'name'          => htmlspecialchars($this->input->post('name')),
                    'code'          => htmlspecialchars($this->input->post('code')),
                    'degree'        => htmlspecialchars($this->input->post('degree')),
                    'email'         => $newEmail,
                    'major_id'      => htmlspecialchars($this->input->post('major')),
                    'updated_at'    => date('Y-m-d H:i:s')
                ];
                $updateprodi        = $this->PS->update($dataUpdateprodi, ['id' => $decodeId]);
                if ($updateprodi > 0) {
                    $cekUserByEmail = $this->Config->getDataUserBy(['username' => $oldEmail])->row();
                    $dataUpdateUser     = [
                        'username'          => $newEmail,
                        'updated_at'        => date('Y-m-d H:i:s')
                    ];
                    $userId         = $cekUserByEmail->id;
                    $updateUser     = $this->Config->updateUser($dataUpdateUser, ['id' => $userId]);
                    if ($updateUser > 0) {
                        $this->session->set_flashdata('success', 'Data berhasil di update');
                    } else {
                        $this->session->set_flashdata('error', 'Update Data User Sedang sibuk, silahkan coba lagi');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Update Data Jurusan Sedang sibuk, silahkan coba lagi');
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
        $prodi      = $this->PS->getDataBy(['id' => $decodeId])->row();
        if ($prodi) {
            $deleteprodi    = $this->PS->delete(['id' => $decodeId]);
            if ($deleteprodi > 0) {
                $deleteUser = $this->Config->deleteUser(['username' => $prodi->email]);
                if ($deleteUser > 0) {
                    $this->session->set_flashdata('success', 'Data berhasil di hapus');
                } else {
                    $this->session->set_flashdata('error', 'Server data user sedang sibuk, silahkan coba lagi');
                }
            } else {
                $this->session->set_flashdata('error', 'Server data jurusan sedang sibuk, silahkan coba lagi');
            }
        } else {
            $this->session->set_flashdata('error', 'Data yang anda masukan tidak ada');
        }
        redirect($this->redirect);
    }

    public function exportExcel()
    {
        $data   =   [
            'title'             => 'Data Program Studi',
            'allData'           => $this->PS->getAllData()->result()
        ];

        $this->load->view('admin/prodi/export/excel', $data);
    }

    private function _validation($old_value = null, $oldCode = null)
    {
        $this->form_validation->set_rules(
            'name',
            'Prodi',
            'trim|required',
            [
                'required' => '%s wajib di isi',
            ]
        );

        if ($this->input->post('code') !== $oldCode) {
            $is_unique = '|is_unique[prodi.code]';
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

        if ($this->input->post('email') !== $old_value) {
            $is_unique  = '|is_unique[user.username]';
        } else {
            $is_unique  = '';
        }
        $this->form_validation->set_rules(
            'email',
            'Email',
            'trim|required' . $is_unique,
            [
                'required' => '%s wajib di isi',
            ]
        );

        if ($this->input->post('major') === "") {
            $this->form_validation->set_rules(
                'major',
                'Jurusan',
                'trim|required',
                [
                    'required' => '%s wajib di isi',
                ]
            );
        }
    }
}
