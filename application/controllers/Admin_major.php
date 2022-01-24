<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_major extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Admin/Admin_major_model', 'Major');
    $this->load->model('Admin/Admin_config_model', 'Config');
    $this->role     = 'admin';
    $this->redirect = 'admin/master/major';
    cek_login('Admin');
  }

  public function index()
  {
    $data = [
      'title'         => 'Data Jurusan',
      'desc'          => 'Berfungsi untuk melihat data jurusan',
      'majors'        => $this->Major->getAllData()->result()
    ];

    $page = '/admin/major/index';
    pageBackend($this->role, $page, $data);
  }

  public function create()
  {
    $this->_validation();
    if ($this->form_validation->run() === false) {
      $data = [
        'title'         => 'Tambah Data Jurusan',
        'desc'          => 'Berfungsi untuk menambah data jurusan',
      ];
      $page = '/admin/major/create';
      pageBackend($this->role, $page, $data);
    } else {
      $this->db->set('id', 'UUID()', FALSE);
      $email  = htmlspecialchars($this->input->post('email'));
      $dataInsertMajor = [
        'name'  => htmlspecialchars($this->input->post('name')),
        'email' => $email
      ];

      $insertMajor    = $this->Major->insert($dataInsertMajor);
      if ($insertMajor > 0) {
        $this->db->set('id', 'UUID()', FALSE);
        $dataInsertUser = [
          'username'  => $email,
          'password'  => password_hash('123456', PASSWORD_DEFAULT),
          'role_id'   => '775b1040-b7a8-11eb-a91e-0cc47abcfaa6',
        ];
        $insertUser = $this->Config->insertUserTable($dataInsertUser);
        if ($insertUser > 0) {
          $this->session->set_flashdata('success', 'Data berhasil di tambah');
        } else {
          $this->session->set_flashdata('error', 'Server Data User Sedang sibuk, silahkan coba lagi');
        }
      } else {
        $this->session->set_flashdata('error', 'Server Data Jurusan Sedang sibuk, silahkan coba lagi');
      }
      redirect($this->redirect);
    }
  }

  public function update($id)
  {
    $decodeId   = decodeEncrypt($id);
    $major      = $this->Major->getDataBy(['id' => $decodeId])->row();
    if ($major) {
      $oldEmail   = $major->email;
      $this->_validation($oldEmail);
      if ($this->form_validation->run() === false) {
        $data = [
          'title'         => 'Ubah Data Jurusan',
          'desc'          => 'Berfungsi untuk mengubah data jurusan',
          'major'         => $major
        ];
        $page = '/admin/major/update';
        pageBackend($this->role, $page, $data);
      } else {
        $newEmail   = htmlspecialchars($this->input->post('email'));
        $dataUpdateMajor    = [
          'name'          => htmlspecialchars($this->input->post('name')),
          'email'         => $newEmail,
          'updated_at'    => date('Y-m-d H:i:s')
        ];
        $updateMajor        = $this->Major->update($dataUpdateMajor, ['id' => $decodeId]);
        if ($updateMajor > 0) {
          $cekUserByEmail = $this->Config->getDataUserBy(['username' => $oldEmail])->row();
          $dataUpdateUser     = [
            'username'         => $newEmail,
            'updated_at'    => date('Y-m-d H:i:s')
          ];
          $userId         = $cekUserByEmail->id;
          // var_dump($userId);
          // die;
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
    $major      = $this->Major->getDataBy(['id' => $decodeId])->row();
    if ($major) {
      $deleteMajor    = $this->Major->delete(['id' => $decodeId]);
      if ($deleteMajor > 0) {
        $deleteUser = $this->Config->deleteUser(['username' => $major->email]);
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
      'title'             => 'Data Jurusan',
      'allData'           => $this->Major->getAllData()->result()
    ];

    $this->load->view('admin/major/export/excel', $data);
  }

  private function _validation($oldValue = null)
  {
    $this->form_validation->set_rules(
      'name',
      'Nama Jurusan',
      'trim|required',
      [
        'required' => '%s wajib diisi',
      ]
    );

    if ($this->input->post('email') != $oldValue) {
      $is_unique  = '|is_unique[user.username]';
    } else {
      $is_unique  = '';
    }

    $this->form_validation->set_rules(
      'email',
      'Email',
      'trim|required' . $is_unique,
      [
        'required' => '%s wajib diisi',
      ]
    );
  }
}
