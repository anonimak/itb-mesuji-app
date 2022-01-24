<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa_data_pkl_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'registration';
    $this->tableStudent = 'student';
  }

  public function getDetailValue()
  {
    $query = "SELECT supervision_value.nilai_total as supervision_value, v_final_score.supervisor_value,v_final_score.lecture_value,v_final_score.final_score_value, v_final_score_result_with_hm.*, CASE
    WHEN v_final_score_result_with_hm.HM = 'E' THEN 'Tidak Lulus'
    ELSE 'Lulus'
    END as student_status, prodi.degree
    FROM v_final_score 
    JOIN v_final_score_result_with_hm ON v_final_score_result_with_hm.registration_id=v_final_score.registration_id
    JOIN supervision_value ON supervision_value.registration_id=v_final_score.registration_id
    JOIN prodi ON prodi.id=v_final_score_result_with_hm.prodi_id WHERE npm =" . $this->session->userdata('user');
    return $this->db->query($query)->row();
  }

  public function upload($data, $id)
  {
    $this->db->update($this->table, $data, $id);
    return $this->db->affected_rows();
  }

  public function getFile()
  {
    $this->db->select('registration.id, registration.file, registration.updated_at');
    $this->db->join($this->tableStudent, 'student.id=registration.student_id');
    return $this->db->get_where($this->table, ['student.npm' => $this->session->userdata('user')]);
  }

  public function getBy($id)
  {
    return $this->db->get_where($this->table, ['id' => $id]);
  }
}
