<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa_profile extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Mahasiswa/Mahasiswa_profile_model', 'Profile');
    $this->load->model('Auth_model', 'Auth');
    $this->role = 'mahasiswa';
    cek_login('Mahasiswa');
    $this->redirectUrl = 'mahasiswa/profile';
  }

  public function index()
  {
    $profile  = $this->Profile->getBy();
    $this->_validation($profile->email);
    if ($this->form_validation->run() == false) {
      $data = [
        'title'         => 'Update Profile',
        'desc'          => 'Berfungsi untuk merubah data profil',
        'profile'       => $profile,
      ];
      $page       = '/mahasiswa/profile/index';
      pageBackend($this->role, $page, $data);
    } else {
      $this->output->set_content_type('application/json')->set_output(json_encode($profile));
      $data = $this->input->post();
      $profile = [
        'npm'           => $data['npm'],
        'fullname'      => htmlspecialchars($data['fullname']),
        'email'         => htmlspecialchars($data['email']),
        'address'       => htmlspecialchars($data['address']),
        'birth_date'    => htmlspecialchars($data['birth_date']),
        'no_hp'         => htmlspecialchars($data['no_hp'])
      ];
      $update = $this->Profile->update($profile);
      if ($update > 0) {
        $this->session->set_flashdata('success', '<b>Ubah data berhasil</b>');
      } else {
        $this->session->set_flashdata('error', '<b>Server sedang sibuk, silahkan coba lagi</b>');
      }
      redirect('mahasiswa/profile');
    }
  }

  private function _validation($email = null)
  {
    $this->form_validation->set_rules(
      'fullname',
      'Nama Lengkap',
      'trim|required',
      [
        'required' => '%s wajib di isi',
      ]
    );

    if ($this->input->post('email') !== $email) {
      $is_unique  = '|is_unique[student.email]';
    } else {
      $is_unique  = '';
    }
    $this->form_validation->set_rules(
      'email',
      'Email',
      'trim|valid_email' . $is_unique,
      [
        'required'      => '%s wajib di isi',
        'valid_email'   => 'Format %s salah'
      ]
    );

    $this->form_validation->set_rules(
      'address',
      'Alamat',
      'trim|required',
      [
        'required' => '%s wajib di isi',
      ]
    );

    $this->form_validation->set_rules(
      'birth_date',
      'Tanggal lahir',
      'trim|required',
      [
        'required' => '%s wajib di isi',
      ]
    );

    $this->form_validation->set_rules(
      'no_hp',
      'No handphone',
      'trim|required',
      [
        'required' => '%s wajib di isi',
      ]
    );
  }
}
