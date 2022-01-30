<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_course_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'course';
    $this->tableProdi   = 'prodi';
  }

  public function getAllData()
  {
    $this->db->select('a.*, b.name as prodi_name');
    $this->db->join($this->tableProdi . ' as b', 'a.prodi_id=b.id');
    return $this->db->get($this->table . ' as a');
  }

  public function insert($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->affected_rows();
  }

  public function getDataBy($data)
  {
    return $this->db->get_where($this->table, $data);
  }

  public function update($data, $where)
  {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }

  public function delete($data)
  {
    $this->db->delete($this->table, $data);
    return $this->db->affected_rows();
  }
}
