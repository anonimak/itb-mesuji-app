<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pkl_Supervisor_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'supervisor';
  }

  public function getLatest($prodicode)
  {
    $this->db->select('*');
    $this->db->like('username', 'pl-' . $prodicode);
    $this->db->order_by('created_at', 'DESC');
    $this->db->limit(1);
    return $this->db->get($this->table)->row();
  }

  public function insert($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->affected_rows();
  }
}
