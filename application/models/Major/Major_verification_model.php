<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Major_verification_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table        = 'list_check_validation';
    $this->tableStudent = 'student';
  }

  public function getDataStudent($data)
  {
    return $this->db->get_where($this->tableStudent, $data);
  }

  public function insert($data)
  {
    $this->db->set('id', 'UUID()', false);
    $this->db->insert($this->table, $data);
    return $this->db->affected_rows();
  }

  public function update($data, $where)
  {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }
}
