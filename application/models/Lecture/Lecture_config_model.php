<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lecture_config_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->tableAcademicYear = 'academic_year';
  }

  public function getAcademicYears($searchTerm = null)
  {
    $this->db->select('*');
    $this->db->where("name like '%" . $searchTerm . "%' ");
    $fetched_records = $this->db->get($this->tableAcademicYear);
    $users = $fetched_records->result_array();
    $data = array();
    foreach ($users as $user) {
      $data[] = array("id" => $user['id'], "text" => $user['name']);
    }
    return $data;
  }
}
