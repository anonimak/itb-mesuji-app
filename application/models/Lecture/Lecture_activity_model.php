<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lecture_activity_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->tableDailyLog = 'daily_log';
    $this->tableAttendance = 'check_point';
    $this->tableRegistration = 'registration';
    $this->tableStudent = 'student';
    $this->tableLecture = 'lecture';
    $this->tableAcademicYear = 'academic_year';
  }

  public function getListDailyLog($id)
  {
    $this->db->select('daily_log.*,student.npm,student.fullname');
    $this->db->join($this->tableRegistration, 'registration.id=daily_log.registration_id');
    $this->db->join($this->tableStudent, 'student.id=registration.student_id');
    $this->db->join($this->tableLecture, 'lecture.id=registration.lecture_id');
    $this->db->join($this->tableAcademicYear, 'academic_year.id=registration.academic_year_id');
    return $this->db->get_where($this->tableDailyLog, $id);
  }

  public function getListAttendance($id)
  {
    $this->db->select('check_point.*,student.npm,student.fullname');
    $this->db->join($this->tableRegistration, 'registration.id=check_point.registration_id');
    $this->db->join($this->tableStudent, 'student.id=registration.student_id');
    $this->db->join($this->tableLecture, 'lecture.id=registration.lecture_id');
    $this->db->join($this->tableAcademicYear, 'academic_year.id=registration.academic_year_id');
    return $this->db->get_where($this->tableAttendance, $id)->result();
  }
}
