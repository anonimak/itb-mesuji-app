<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kaprodi_Controller extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->role = 'prodi';
    $this->parentroute = 'prodi';
    $this->parentviewpath = $this->role;
    cek_login(ucfirst($this->role));
  }
}

class Custom_Controller extends CI_Controller
{
  // new custom controller
}
