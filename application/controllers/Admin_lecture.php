<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Admin_lecture extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Admin/Admin_lecture_model', 'Lecture');
    $this->load->model('Admin/Admin_config_model', 'Config');
    $this->role         = 'admin';
    $this->redirect     = 'admin/master/lecture';


    cek_login('Admin');
  }

  public function index()
  {
    $data = [
      'title'         => 'Data Dosen',
      'desc'          => 'Berfungsi untuk melihat Data Dosen',
      'lectures'      => $this->Lecture->getAllData()->result()
    ];

    $page = '/admin/lecture/index';
    pageBackend($this->role, $page, $data);
  }

  public function create()
  {

    $this->_validation();
    if ($this->form_validation->run() === false) {
      $data = [
        'title'         => 'Tambah Data Dosen',
        'desc'          => 'Berfungsi untuk menambah Data Dosen',
        'getmajor'      => $this->Config->getAllMajor()->result()
      ];
      $page = '/admin/lecture/create';
      pageBackend($this->role, $page, $data);
    } else {
      $this->db->set('id', 'UUID()', FALSE);
      $nip          = htmlspecialchars($this->input->post('nip'));
      $postMajor    = $this->input->post('major');
      $explode      = explode(":", $postMajor);
      $majorId      = $explode[0];
      $prodiId      = $explode[1];


      $dataInputLecture = [
        'name'          => htmlspecialchars($this->input->post('name')),
        'nip'           => $nip,
        'email'         => htmlspecialchars($this->input->post('email')),
        'major_id'      => $majorId,
        'prodi_id'      => $prodiId
      ];
      $insertLecture      = $this->Lecture->insert($dataInputLecture);
      if ($insertLecture > 0) {
        $this->db->set('id', 'UUID()', FALSE);
        $dataInsertUser = [
          'username'  => $nip,
          'password'  => password_hash('123456', PASSWORD_DEFAULT),
          'role_id'   => '775b0f58-b7a8-11eb-a91e-0cc47abcfaa6',
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
    $decode     = decodeEncrypt($id);
    $lecture    = $this->Lecture->getDataBy(['a.id' => $decode])->row();
    if ($lecture) {
      $oldNip     = $lecture->nip;
      $this->_validation($oldNip, $lecture->email);
      if ($this->form_validation->run() === false) {
        $data = [
          'title'         => 'Ubah Data Dosen',
          'desc'          => 'Berfungsi untuk mengubah Data Dosen',
          'lecture'       => $lecture,
          'getmajor'      => $this->Config->getAllMajor()->result()
        ];
        $page = '/admin/lecture/update';
        pageBackend($this->role, $page, $data);
      } else {
        $nip          = htmlspecialchars($this->input->post('nip'));
        $postMajor    = $this->input->post('major');
        $explode      = explode(":", $postMajor);
        $majorId      = $explode[0];
        $prodiId      = $explode[1];
        $dataUpdateLecture = [
          'name'          => htmlspecialchars($this->input->post('name')),
          'nip'           => $nip,
          'email'         => htmlspecialchars($this->input->post('email')),
          'major_id'      => $majorId,
          'prodi_id'      => $prodiId,
          'updated_at'    => date('Y-m-d H:i:s')
        ];
        $updateLecture      = $this->Lecture->update($dataUpdateLecture, ['id' => $decode]);
        if ($updateLecture > 0) {
          $cekUserByNip   = $this->Config->getDataUserBy(['username' => $oldNip])->row();
          $dataUpdateUser = [
            'username'      => $nip,
            'updated_at'    => date('Y-m-d H:i:s')
          ];
          $userId         = $cekUserByNip->id;
          $updateUser     = $this->Config->updateUser($dataUpdateUser, ['id' => $userId]);
          if ($updateUser > 0) {
            $this->session->set_flashdata('success', 'Data berhasil di ubah');
          } else {
            $this->session->set_flashdata('error', 'Server Data User Sedang sibuk, silahkan coba lagi');
          }
        } else {
          $this->session->set_flashdata('error', 'Server Data Dosen sedang sibuk, silahkan coba lagi');
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
    $lecture    = $this->Lecture->getDataBy(['a.id' => $decodeId])->row();
    if ($lecture) {
      $deleteprodi    = $this->Lecture->delete(['id' => $decodeId]);
      if ($deleteprodi > 0) {
        $deleteUser = $this->Config->deleteUser(['username' => $lecture->nip]);
        if ($deleteUser > 0) {
          $this->session->set_flashdata('success', 'Data berhasil di hapus');
        } else {
          $this->session->set_flashdata('error', 'Server data user sedang sibuk, silahkan coba lagi');
        }
      } else {
        $this->session->set_flashdata('error', 'Server data dosen sedang sibuk, silahkan coba lagi');
      }
    } else {
      $this->session->set_flashdata('error', 'Data yang anda masukan tidak ada');
    }
    redirect($this->redirect);
  }

  public function import()
  {
    $data = [
      'title'         => 'Import Data Dosen',
      'desc'          => 'Berfungsi untuk menambah banyak Data Dosen',
    ];

    $page = '/admin/lecture/import';
    pageBackend($this->role, $page, $data);
  }

  public function importlecture()
  {
    $config = [
      'upload_path'       => './assets/uploads/',
      'allowed_types'     => 'xlsx|xls',
      'file_name'         => 'doc' . time(),
    ];
    $this->upload->initialize($config);
    if ($this->upload->do_upload('importlecture')) {
      $file   = $this->upload->data();
      $reader = ReaderEntityFactory::createXLSXReader();
      $reader->open('assets/uploads/' . $file['file_name']);
      foreach ($reader->getSheetIterator() as $sheet) {
        $numRow = 1;
        foreach ($sheet->getRowIterator() as $row) {
          if ($numRow > 1) {
            $email      = $this->Lecture->getDataBy(['a.email' => $row->getCellAtIndex(2)])->num_rows();
            $nip        = $this->Lecture->getDataBy(['a.nip' => $row->getCellAtIndex(1)])->num_rows();

            if ($email < 1 && $nip < 1) {
              $dataInputLecture = array(
                'name'          => $row->getCellAtIndex(0),
                'nip'           => $row->getCellAtIndex(1),
                'email'         => $row->getCellAtIndex(2),
                'major_id'      => $row->getCellAtIndex(3),
              );
              $this->Lecture->importData($dataInputLecture);
              $dataInputUser  =   [
                'username'  => $row->getCellAtIndex(1),
                'password'  => password_hash('123456', PASSWORD_DEFAULT),
                'role_id'   => '775b1040-b7a8-11eb-a91e-0cc47abcfaa6',
              ];

              $this->Config->importData($dataInputUser);
            }
          }
          $numRow++;
        }
        $reader->close();
        unlink(FCPATH . 'assets/uploads/' . $file['file_name']);
        $this->session->set_flashdata('success', 'Import Data Berhasil');
        redirect($this->redirect);
      }
    } else {
      echo "Error :" . $this->upload->display_errors();
    }
  }

  private function _validation($nip = null, $email = null)
  {

    if ($this->input->post('nip') !== $nip) {
      $is_unique  = '|is_unique[user.username]';
    } else {
      $is_unique  = '';
    }

    $this->form_validation->set_rules(
      'nip',
      'NIP',
      'trim|required|max_length[18]|min_length[18]' . $is_unique,
      [
        'required' => '%s wajib di isi',
      ]
    );

    $this->form_validation->set_rules(
      'name',
      'Nama Lengkap',
      'trim|required',
      [
        'required' => '%s wajib di isi',
      ]
    );

    if ($this->input->post('email') !== $email) {
      $is_unique  = '|is_unique[lecture.email]';
    } else {
      $is_unique  = '';
    }
    $this->form_validation->set_rules(
      'email',
      'Email',
      'trim|required|valid_email' . $is_unique,
      [
        'required'      => '%s wajib di isi',
        'valid_email'   => 'Format %s salah'
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

/* End of file Dashboard.php */