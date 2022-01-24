<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_pkl_period_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'pkl_implementation_period';
    $this->tableAcamdemicYear = 'academic_year';
  }

  public function list()
  {
    $this->_join();
    return $this->db->get($this->table);
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

  public function check()
  {
    $this->_join();
    return $this->db->get_where($this->table, ['academic_year.status' => 1]);
  }

  private function _join()
  {
    $this->db->select('pkl_implementation_period.*, academic_year.name as academic_year,academic_year.status as academic_year_status');
    $this->db->join($this->tableAcamdemicYear, 'academic_year.id=pkl_implementation_period.academic_year_id');
  }
}
