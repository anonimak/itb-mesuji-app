<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Auth extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Auth_model', 'Auth');
  }

  public function index()
  {
    $session = $this->session->userdata('username');
    $sessionrole = $this->session->userdata('role');
    if ($session) {
      $password = "123456";
      if (password_verify($password, $session->password) && $sessionrole != 'Admin') {
        return redirect('/auth/changedefault');
      }
      redirect(strtolower($sessionrole)  . '/dashboard');
    }
    $page = '/auth/index';
    pageAuth($page);
  }

  public function indexchangepasswordfromdefault()
  {
    $page = '/auth/change_pass_default';
    $data = [
      '__url_form' => base_url('/auth/changedefault'),
    ];
    pageAuth($page, $data);
  }

  public function login()
  {
    $this->_validation();
    if ($this->form_validation->run() == FALSE) {
      $this->index();
    } else {
      $this->_login();
    }
  }

  public function changepasswordfromdefault()
  {
    cek_login();
    $this->_validationNewPass();
    if ($this->form_validation->run() == FALSE) {
      $this->indexchangepasswordfromdefault();
    } else {
      $this->_setnewpasswodfromdefault();
    }
  }

  public function logout()
  {
    $this->session->sess_destroy();
    redirect(base_url());
  }

  private function _validation()
  {
    $this->form_validation->set_rules(
      'username',
      'Email',
      'trim|required',
      [
        'required'      => 'Username wajib diisi',
      ]
    );

    $this->form_validation->set_rules(
      'password',
      'password',
      'trim|required',
      [
        'required' => 'Password wajib diisi',
      ]
    );
  }

  private function _validationNewPass()
  {
    $this->form_validation->set_rules(
      'new_password',
      'Sandi Baru',
      'trim|required|min_length[8]',
      [
        'required'      => 'Sandi wajib diisi',
        'min_length'      => 'Sandi minimal 8 karakter',
      ]
    );

    $this->form_validation->set_rules(
      'retype_new_password',
      'Ulang Sandi Baru',
      'trim|required|matches[new_password]',
      [
        'required' => 'Ulang Sandi wajib diisi',
        'matches' => 'Harus sama dengan kolom Sandi Baru',
      ]
    );
  }

  private function _login()
  {
    $username   = $this->input->post('username');
    $password   = $this->input->post('password');
    $ceKemail   = $this->Auth->adminCheck($username);
    if ($ceKemail->num_rows() > 0) {
      $dataUser    =  $ceKemail->row();
      if (password_verify($password, $dataUser->password)) {
        $this->session->set_userdata('username', $dataUser);
        $this->session->set_userdata('role', $dataUser->name);
        $this->session->set_userdata('user', $dataUser->username);
        return redirect('/admin/dashboard');
      } else {
        $this->session->set_flashdata('errorpassword', 'Password yang anda masukan salah');
        redirect('auth');
      }
    } else if ($ceKemail   = $this->Auth->userCheck($username)) {
      $dataUser    =  $ceKemail->row();
      if (password_verify($password, $dataUser->password)) {
        $this->session->set_userdata('username', $dataUser);
        $this->session->set_userdata('role', 'Mahasiswa');
        $this->session->set_userdata('user', $dataUser->npm);
        if ((string)$password == "123456" && @$dataUser->npm != '') {
          return redirect('/auth/changedefault');
        }
        return redirect('/mahasiswa/dashboard');
      } else {
        $this->session->set_flashdata('errorpassword', 'Password yang anda masukan salah');
        redirect('auth');
      }
    } else {
      $this->session->set_flashdata('errorusername', '' . $username . ' tidak ada di sistem');
      redirect('auth');
    }
  }

  private function _setnewpasswodfromdefault()
  {
    $session = $this->session->userdata('username');
    $newpassword   = $this->input->post('new_password');
    $result   = $this->Auth->updateStudent(['password' => password_hash($newpassword, PASSWORD_DEFAULT)], $session->id);
    if ($result) {
      redirect('/mahasiswa/dashboard');
    }
  }
}
