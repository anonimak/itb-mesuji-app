<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_periode_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table          = 'periode';
    $this->tableAcademic  = 'academic_year';
  }

  public function getAllData($data)
  {
    $this->db->select('a.*,b.name as academic');
    $this->db->join($this->tableAcademic . ' b', 'a.academic_year_id=b.id');
    return $this->db->get_where($this->table . ' a', $data);
  }

  public function getDataBy($data, $type)
  {
    if ($data === 'active-periode') {
      $this->db->where('finish_time >', date('Y-m-d'));
    } else {
      $this->db->where($data);
    }
    return $this->db->get_where($this->table, $type);
  }

  public function getWhere($data)
  {
    return $this->db->get_where($this->table, $data);
  }
  public function insert($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->affected_rows();
  }
  public function update($data, $where)
  {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }
}
