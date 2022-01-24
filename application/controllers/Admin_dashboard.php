<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_dashboard extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->role = 'admin';
    cek_login('Admin');
  }

  public function index()
  {
    $page = '/dashboard/admin_dashboard';
    pageBackend($this->role, $page);
  }
}

/* End of file Dashboard.php */