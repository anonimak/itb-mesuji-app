<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_planning_attachment_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'planning_attachment';
    $this->tableProdi = 'prodi';
    $this->tableMajor = 'major';
  }

  public function getAllData()
  {
    $this->db->select('planning_attachment.*, prodi.id as prodi_id,prodi.name as prodi_name, major.name as major_name');
    $this->db->join($this->tableProdi, 'prodi.id=planning_attachment.prodi_id');
    $this->db->join($this->tableMajor, 'major.id=prodi.major_id');
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
