<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_academic_year extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Admin/Admin_academic_year_model', 'TA');
    $this->role         = 'admin';
    $this->redirect     = 'admin/config/academic_year';
    cek_login('Admin');
  }

  public function index()
  {
    $data = [
      'title'         => 'Data Tahun Akademik',
      'desc'          => 'Berfungsi untuk melihat data tahun akademik',
      'academic_ta'   => $this->TA->getAllData()->result()
    ];

    $page = '/admin/academic/index';
    pageBackend($this->role, $page, $data);
  }

  public function create()
  {
    $this->form_validation->set_rules(
      'name',
      'Tahun Akademik',
      'trim|required',
      [
        'required' => '%s wajib di isi',
      ]
    );
    if ($this->form_validation->run() === FALSE) {
      $data = [
        'title'         => 'Tambah Data Tahun Akademik',
        'desc'          => 'Berfungsi untuk menambah data tahun akademik',
      ];
      $page = '/admin/academic/create';
      pageBackend($this->role, $page, $data);
    } else {
      //UPDATE status menjadi 0
      $cekData    = $this->TA->getAllData();
      if ($cekData->num_rows() > 0) {
        $update = $this->TA->update(['status' => '0']);
        if ($update > 0) {
          $insert = $this->_insert();
          if ($insert > 0) {
            $this->session->set_flashdata('success', 'Data Berhasil di simpan');
          } else {
            $this->session->set_flashdata('error', 'Server Sedang gangguan, silahkan coba lagi');
          }
        } else {
          $this->session->set_flashdata('error', 'Server Sedang gangguan, silahkan coba lagi');
        }
      } else {
        $insert = $this->_insert();
        if ($insert > 0) {
          $this->session->set_flashdata('success', 'Data Berhasil di simpan');
        } else {
          $this->session->set_flashdata('error', 'Server Sedang gangguan, silahkan coba lagi');
        }
      }
      redirect($this->redirect);
    }
  }

  private function _insert()
  {
    $this->db->set('id', 'UUID()', FALSE);
    $datainsert = [
      'name'      => htmlspecialchars($this->input->post('name')),
    ];
    $insert     = $this->TA->insert($datainsert);
    return $insert;
  }

  public function update($id)
  {
    $decodeId   = decodeEncrypt($id);
    $dataRow    = $this->TA->getFilterData(['id' => $decodeId])->row();
    if ($dataRow) {
      $this->_validation();
      if ($this->form_validation->run() === false) {
        $data = [
          'title'         => 'Data Tahun Akademik',
          'desc'          => 'Berfungsi untuk melihat data tahun akademik',
          'academic_row'  => $dataRow
        ];
        $page = '/admin/academic/update';
        pageBackend($this->role, $page, $data);
      } else {
        $ta     = htmlspecialchars($this->input->post('name'));
        $status = htmlspecialchars($this->input->post('status'));
        $update = $this->TA->update(['status' => '0', 'updated_at' => date('Y-m-d H:i:s')]);
        if ($update > 0) {
          $dataUpdate = [
            'name'          => $ta,
            'status'        => $status,
            'updated_at'    => date('Y-m-d H:i:s')
          ];
          // var_dump($dataUpdate);
          // die;
          $updateTa     = $this->TA->update($dataUpdate, ['id' => $decodeId]);
          if ($updateTa > 0) {
            $this->session->set_flashdata('success', 'Data Berhasil di update');
          } else {
            $this->session->set_flashdata('error', 'Server Sedang gangguan, silahkan coba lagi');
          }
        } else {
          $this->session->set_flashdata('error', 'Server Sedang gangguan, silahkan coba lagi');
        }
        redirect($this->redirect);
      }
    } else {
      $this->session->set_flashdata('error', 'Data yang anda masukan salah');
      redirect($this->redirect);
    }
  }

  public function delete($id)
  {
    $decodeId   = decodeEncrypt($id);
    $dataRow    = $this->TA->getFilterData(['id' => $decodeId])->row();
    if ($dataRow) {
      $deleteData = $this->TA->delete(['id' => $decodeId]);
      if ($deleteData > 0) {
        $this->session->set_flashdata('success', 'Data Berhasil di hapus');
      } else {
        $this->session->set_flashdata('error', 'Server sedang gangguan, silahkan coba lagi');
      }
    } else {
      $this->session->set_flashdata('error', 'Data yang anda masukan salah');
    }
    redirect($this->redirect);
  }

  private function _validation()
  {
    $this->form_validation->set_rules(
      'name',
      'Tahun Ajaran',
      'trim|required',
      [
        'required' => '%s wajib di isi',
      ]
    );
  }
}
