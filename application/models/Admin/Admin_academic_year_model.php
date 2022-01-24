<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_academic_year_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'academic_year';
  }

  public function getAllData()
  {
    $this->db->order_by('created_at', 'DESC');
    return $this->db->get($this->table);
  }

  public function update($data, $where = null)
  {
    if ($where) {
      $this->db->where($where);
    }
    $this->db->update($this->table, $data);
    return $this->db->affected_rows();
  }

  public function insert($data)
  {
    $this->db->insert('academic_year', $data);
    return $this->db->affected_rows();
  }

  public function getFilterData($data)
  {
    return $this->db->get_where($this->table, $data);
  }

  public function delete($where)
  {
    $this->db->delete($this->table, $where);
    return $this->db->affected_rows();
  }
}
