<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_students_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table        = 'student';
    $this->tableProdi   = 'prodi';
    $this->tableMajor   = 'major';
    $this->tableKrs     = 'krs';
    $this->tableTa      = 'academic_year';
  }

  public function getAllData()
  {
    $this->_join();
    // $this->_query_serverside();

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
    $this->db->select('a.*,b.name prodi_name,c.id as major_id,c.name major_name');
    $this->db->join($this->tableProdi . ' as b', 'a.prodi_id=b.id');
    $this->db->join($this->tableMajor . ' as c', 'b.major_id=c.id');
    if (@$this->input->post('search')['value']) {
      $this->db->like('a.npm', $this->input->post('search')['value']);
    }
    if ($this->input->post('order')) {
      $this->db->order_by('a.npm', $this->input->post('order')['0']['dir']);
    } else {
      $this->db->order_by('a.npm', 'DESC');
    }
  }


  public function countRecordsTotal()
  {
    $this->db->select('*');
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }


  public function countRecordsFiltered()
  {
    $this->_join();
    $query = $this->db->get($this->table . ' as a');
    return $query->num_rows();
  }

  public function deleteMultiple($data)
  {
    $where = explode(",", $data);
    $this->db->where_in('npm', $where);
    $this->db->delete($this->table);
    sleep(2);
    $this->db->where_in('username', $where);
    $this->db->delete('user');
    return $this->db->affected_rows();
  }

  //KRS
  public function getDataKRSBy($data)
  {
    $this->db->select('a.*,b.name as ta');
    $this->db->join($this->tableTa . ' b', 'a.academic_year_id=b.id');
    return $this->db->get_where($this->tableKrs . ' a', $data);
  }
}
