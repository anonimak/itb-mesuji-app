<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pkl_Registration_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'registration';
  }

  public function getRegistrationId($id)
  {
    return $this->db->get_where($this->table, ['id' => $id])->row();
  }

  public function getRegistrationById($prodi_id, $id)
  {
    $this->db->select('a.id, a.group_id, a.status,b.name as company_name,g.start_date,g.finish_date,a.group_status,c.fullname as member_name, d.name as lecture_name, e.file, f.username as supervisor_name');
    $this->db->join('company b', 'b.id=a.company_id');
    $this->db->join('student c', 'c.id=a.student_id');
    $this->db->join('lecture d', 'd.id=a.lecture_id', 'left');
    $this->db->join('response_letter e', 'e.registration_group_id=a.group_id', 'left');
    $this->db->join('supervisor f', 'f.id=a.supervisor_id', 'left');
    $this->db->join('pkl_implementation_period g', 'g.id=a.period_pkl_id', 'left');
    return $this->db->get_where($this->table . ' a', ['a.id' => $id, 'a.prodi_id' => $prodi_id])->row();
  }

  public function getRegistrationByProdiAndGrpStatus($prodi_id, $status = 'belum_terverifikasi')
  {
    $this->db->select('a.id, a.status,b.name as company_name,a.start_date,a.finish_date,a.group_status,c.fullname as member_name');
    $this->db->join('company b', 'b.id=a.company_id');
    $this->db->join('student c', 'c.id=a.student_id');
    $this->db->order_by('a.created_at', 'DESC');
    return $this->db->get_where($this->table . ' a', ['a.prodi_id' => $prodi_id, 'a.group_status' => $status, 'a.verify_member' => 'Diterima'])->result();
  }

  public function getRegistrationMemberByGrpId($group_id)
  {
    $this->db->select('a.status, a.id, c.fullname as member_name, c.npm, d.HM, a.group_status');
    $this->db->join('student c', 'c.id=a.student_id');
    $this->db->join('v_final_score_result_with_hm d', 'd.registration_id=a.id', 'left');
    $this->db->order_by('a.status', 'ASC');
    return $this->db->get_where($this->table . ' a', ['a.group_id' => $group_id, 'a.verify_member' => 'Diterima'])->result();
  }

  public function getRegistrationByProdiAndStatus($prodi_id, $academic_year_id, $terverifikasi = true)
  {
    $this->db->select('a.id, a.group_id, a.status,b.name as company_name,g.start_date,g.finish_date,a.group_status,c.fullname as member_name, d.name as lecture_name, e.file, f.username as supervisor_name');
    $this->db->join('company b', 'b.id=a.company_id');
    $this->db->join('student c', 'c.id=a.student_id');
    $this->db->join('lecture d', 'd.id=a.lecture_id', 'left');
    $this->db->join('response_letter e', 'e.registration_group_id=a.group_id', 'left');
    $this->db->join('supervisor f', 'f.id=a.supervisor_id', 'left');
    $this->db->join('pkl_implementation_period g', 'g.id=a.period_pkl_id', 'left');
    $this->db->where("(select count(id) from `registration` where `registration`.group_id = a.group_id and `registration`.verify_member = 'Diterima') >= (select `periode`.quantity from `periode` where `periode`.academic_year_id = a.academic_year_id and (date_format(`a`.`created_at`, '%Y-%m-%d') BETWEEN `periode`.start_time and `periode`.finish_time) and `periode`.type = 3)");
    if ($academic_year_id) {
      $this->db->where('a.academic_year_id', $academic_year_id);
    } else {
      $this->db->where("a.academic_year_id IN (select id from `academic_year` where status = 1)");
    }
    $this->db->order_by('a.group_id', 'ASC');
    $this->db->order_by('a.status', 'ASC');
    $group_status =  ($terverifikasi) ? 'a.group_status' : 'a.group_status !=';
    return $this->db->get_where($this->table . ' a', ['a.prodi_id' => $prodi_id, $group_status => 'belum_terverifikasi', 'a.verify_member' => 'Diterima'])->result();
  }

  public function getOneRegistrationMemberByGrpId($group_id)
  {
    $this->db->where('lecture_id !=', NULL);
    $this->db->where('supervisor_id !=', NULL);
    $this->db->limit(1);
    return $this->db->get_where($this->table . ' a', ['a.group_id' => $group_id, 'a.verify_member' => 'Diterima'])->row();
  }

  public function getCompanyById($prodi_id, $id)
  {
    $this->db->select('b.id company_id, b.name company_name');
    $this->db->join('company b', 'b.id=a.company_id');
    return $this->db->get_where($this->table . ' a', ['a.id' => $id, 'a.prodi_id' => $prodi_id])->row();
  }

  public function getOnlyKetuaByProdi($prodi_id)
  {
    $this->db->select('a.id, a.group_id, b.name as company_name,g.start_date,g.finish_date,a.group_status, d.name as lecture_name, f.username as supervisor_name');
    $this->db->join('company b', 'b.id=a.company_id');
    $this->db->join('lecture d', 'd.id=a.lecture_id', 'left');
    $this->db->join('supervisor f', 'f.id=a.supervisor_id', 'left');
    $this->db->join('pkl_implementation_period g', 'g.id=a.period_pkl_id', 'left');
    $this->db->where("a.academic_year_id IN (select id from `academic_year` where status = 1)");
    $this->db->order_by('a.group_id', 'ASC');
    $this->db->order_by('a.status', 'ASC');

    return $this->db->get_where($this->table . ' a', ['a.prodi_id' => $prodi_id, 'a.status' => 'Ketua',  'a.group_status' => 'diterima', 'a.verify_member' => 'Diterima'])->result();
  }

  public function getJoinStudentById($id)
  {
    $this->db->select('a.id as registration_id, b.*');
    $this->db->join('student b', 'b.id=a.student_id');
    return $this->db->get_where($this->table . ' a', ['a.id' => $id])->row();
  }

  public function getFinalScoreByRegId($id)
  {
    return $this->db->query("SELECT supervision_value.nilai_total as supervision_value, v_final_score.supervisor_value,v_final_score.lecture_value,v_final_score.final_score_value, v_final_score_result_with_hm.*, CASE
    WHEN v_final_score_result_with_hm.HM = 'E' THEN 'Tidak Lulus'
    ELSE 'Lulus'
    END as student_status, prodi.degree
    FROM v_final_score 
    JOIN v_final_score_result_with_hm ON v_final_score_result_with_hm.registration_id=v_final_score.registration_id
    JOIN supervision_value ON supervision_value.registration_id=v_final_score.registration_id
    JOIN prodi ON prodi.id=v_final_score_result_with_hm.prodi_id WHERE supervision_value.registration_id = '$id'")->row();
  }

  public function update($data, $id)
  {
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update($this->table, $data, ['id' => $id]);
    return $this->db->affected_rows();
  }

  public function updatebyGroupId($data, $id)
  {
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update($this->table, $data, ['group_id' => $id]);
    return $this->db->affected_rows();
  }

  public function getDataPeriode()
  {
    $today  = date('Y-m-d');
    $this->db->where('start_time <=', $today);
    $this->db->where('finish_time >=', $today);
    $this->db->where('type =', 4);
    return $this->db->get('periode');
  }
}
