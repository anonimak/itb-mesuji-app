<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_company_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table            = 'company';
    $this->tableRegency     = 'regency';
    $this->tableProvince    = 'province';
    $this->tableProdi       = 'prodi';
  }

  public function getAllData()
  {
    $this->_join();
    if ((int)$this->input->post('length') !== -1) {
      $this->db->limit($this->input->post('length'), $this->input->post('start'));
    }
    return $this->db->get($this->table . ' as a');
  }

  public function insert($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->affected_rows();
  }

  public function getDataBy($data)
  {
    $this->_join();
    return $this->db->get_where($this->table . ' as a', $data);
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


  public function importData($data = array())
  {
    $jumlah = count($data);
    if ($jumlah > 0) {
      $this->db->set('id', 'UUID()', FALSE);
      $this->db->replace($this->table, $data);
    }
  }

  private function _join()
  {
    $this->db->select('a.*,b.name regency_name,c.name province_name,d.name as prodi_name');
    $this->db->join($this->tableRegency  . ' as b', 'a.regency_id=b.id');
    $this->db->join($this->tableProvince . ' as c', 'a.province_id=c.id');
    $this->db->join($this->tableProdi . ' as d', 'a.prodi_id=d.id');
    if (@$this->input->post('search')['value']) {
      $this->db->like('a.name', $this->input->post('search')['value']);
    }
    if ($this->input->post('order')) {
      $this->db->order_by('a.name', $this->input->post('order')['0']['dir']);
    } else {
      $this->db->order_by('a.name', 'DESC');
    }
  }

  public function countRecordsFiltered()
  {
    $this->_join();
    $query = $this->db->get($this->table . ' as a');
    return $query->num_rows();
  }

  public function countRecordsTotal()
  {
    $this->db->select('*');
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  public function deleteMultiple($data)
  {
    $where = explode(",", $data);
    $this->db->where_in('id', $where);
    $this->db->delete($this->table);
    return $this->db->affected_rows();
  }
}
