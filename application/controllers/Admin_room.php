<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_room extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Admin/Admin_room_model', 'Room');
    $this->role         = 'admin';
    $this->redirect     = 'admin/master/room';
    cek_login('Admin');
  }

  public function index()
  {
    $rooms         = $this->Room->getAllData()->result();
    $data = [
      'title' => 'Data Daftar Ruangan',
      'desc'  => 'Berfungsi untuk melihat Data Daftar Ruangan',
      'rooms' => $rooms
    ];
    $page = '/admin/room/index';
    pageBackend($this->role, $page, $data);
  }

  public function create()
  {
    $this->_validation();
    if ($this->form_validation->run() === FALSE) {
      $data = [
        'title'         => 'Tambah Data Ruangan',
        'desc'          => 'Berfungsi untuk menambah Data Ruangan',
      ];
      $page = '/admin/room/create';
      pageBackend($this->role, $page, $data);
    } else {
      $this->db->set('id', 'UUID()', FALSE);
      $dataInsert = [
        'name'         => htmlspecialchars($this->input->post('name')),
      ];
      $insert     = $this->Room->insert($dataInsert);
      if ($insert > 0) {
        $this->session->set_flashdata('success', 'Data berhasil di tambah');
      } else {
        $this->session->set_flashdata('error', 'Server Data User Sedang sibuk, silahkan coba lagi');
      }
      redirect($this->redirect);
    }
  }

  public function update($id)
  {
    $decodeId   = decodeEncrypt($id);
    $room       = $this->Room->getDataBy(['id' => $decodeId])->row();
    if ($room) {
      $oldName   = $room->name;
      $this->_validation($oldName);
      if ($this->form_validation->run() === false) {
        $data = [
          'title'         => 'Ubah Data Ruangan',
          'desc'          => 'Berfungsi untuk mengubah data ruangan',
          'room'          => $room
        ];
        $page = '/admin/room/update';
        pageBackend($this->role, $page, $data);
      } else {
        $dataUpdate = [
          'name'          => htmlspecialchars($this->input->post('name')),
          'updated_at'    => date('Y-m-d H:i:s')
        ];
        $update     = $this->Room->update($dataUpdate, ['id' => $decodeId]);
        if ($update > 0) {
          $this->session->set_flashdata('success', 'Data berhasil di update');
        } else {
          $this->session->set_flashdata('error', 'Server Sedang sibuk, silahkan coba lagi');
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
    $room       = $this->Room->getDataBy(['id' => $decodeId])->row();
    if ($room) {
      $deleteRoom    = $this->Room->delete(['id' => $decodeId]);
      if ($deleteRoom > 0) {
        $this->session->set_flashdata('success', 'Data berhasil di hapus');
      } else {
        $this->session->set_flashdata('error', 'Server sedang sibuk, silahkan coba lagi');
      }
    } else {
      $this->session->set_flashdata('error', 'Data yang anda masukan tidak ada');
    }
    redirect($this->redirect);
  }

  private function _validation($oldname = null)
  {
    if ($this->input->post('name') !== $oldname) {
      $is_unique = '|is_unique[room.name]';
    } else {
      $is_unique = '';
    }
    $this->form_validation->set_rules(
      'name',
      'Nama ruangan',
      'trim|required' . $is_unique,
      [
        'required'  => '%s wajib diisi',
      ]
    );
  }
}
