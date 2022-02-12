<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa_dashboard extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Auth_model', 'Auth');
    $this->role = 'mahasiswa';
    cek_login($this->role);
  }

  public function index()
  {
    $data = [
      'title'         => 'Dashboard',
      'desc'          => 'Sistem Informasi KRS/KHS ITB Mesuji',
      'showName'      => $this->Auth->showNameLogin()
    ];
    $page = '/dashboard/mahasiswa_dashboard';
    pageBackend($this->role, $page, $data);
  }
}
