<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ref_User_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'user';
  }

  public function insert($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->affected_rows();
  }
}
