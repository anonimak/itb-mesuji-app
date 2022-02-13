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
    $this->tableLecture = 'lecture';
    $this->tableCourse  = 'course';
    $this->tableDetailKrs = 'detail_krs';
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
    $this->db->select('a.*,b.name ta, b.semester ta_semester,c.fullname,c.npm,c.email,d.name lecture,e.name prodi, e.degree,f.name major');
    $this->db->join($this->tableTa . ' b', 'a.academic_year_id=b.id');
    $this->db->join($this->table . ' c', 'a.student_id=c.id');
    $this->db->join($this->tableLecture . ' d', 'c.lecture_id=d.id');
    $this->db->join($this->tableProdi . ' e', 'c.prodi_id=e.id');
    $this->db->join($this->tableMajor . ' f', 'e.major_id=f.id');
    return $this->db->get_where($this->tableKrs . ' a', $data);
  }

  public function getDataDetailKRSBy($data)
  {
    $this->db->select('a.*,b.name matkul,b.sks,b.code,b.semester');
    $this->db->join($this->tableCourse . ' b', 'a.course_id=b.id', 'LEFT');
    return $this->db->get_where($this->tableDetailKrs . ' a', $data);
  }

  //GET PRODI
  public function getProdi()
  {
    $this->db->select('a.*, b.name as major');
    $this->db->join($this->tableMajor . ' b', 'a.major_id=b.id', 'LEFT');
    return $this->db->get($this->tableProdi . ' a');
  }
}
