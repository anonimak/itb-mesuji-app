<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_guidebook_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'guidebook';
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
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update($this->table, $data);
    return $this->db->affected_rows();
  }

  public function insert($data)
  {
    $this->db->set('created_at', date('Y-m-d H:i:s'));
    $this->db->insert($this->table, $data);
    return $this->db->affected_rows();
  }

  public function getFilterData($data)
  {
    return $this->db->get_where($this->table, $data);
  }
}
