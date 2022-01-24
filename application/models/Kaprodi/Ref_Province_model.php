<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ref_Province_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'province';
  }

  public function getAll()
  {
    return $this->db->get($this->table)->result();
  }
}
