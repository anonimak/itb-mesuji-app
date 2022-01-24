<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lecture_planning_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'planning';
    $this->tableRegistration = 'registration';
    $this->tableStudent = 'student';
  }

  public function list($id)
  {
    $this->_join();
    return $this->db->get_where($this->table, $id);
  }

  private function _join()
  {
    $this->db->select('planning.id,planning.registration_id,planning.learning_achievement,planning.learning_achievement_sub,planning.time_qty,planning.approval,student.fullname,student.npm');
    $this->db->join($this->tableRegistration, 'registration.id=planning.registration_id');
    $this->db->join($this->tableStudent, 'student.id=registration.student_id');
  }

  public function update($data, $where)
  {
    // die(var_dump($where));
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }
}
