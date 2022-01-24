<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lecture_report_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->tableReportSupervision = 'supervision_report';
    $this->tableLecture = 'lecture';
    $this->tableMajor = 'major';
    $this->tableProdi = 'prodi';
    $this->tableCompany = 'company';
    $this->tableStudent = 'student';
    $this->tableRegistration = 'registration';
    $this->tableAcademicYear = 'academic_year';
    $this->tableReception = 'willingness_to_accept';
  }

  public function getSupervisionReport($period = null, $group_id = null)
  {
    $query = "SELECT `registration`.`group_id`, `registration`.`pushed`, `company`.`name` `company_name`, COUNT(registration.group_id) as studentcount, `supervision_report`.* FROM `supervision_report` RIGHT JOIN `registration` ON `registration`.`group_id` = `supervision_report`.`registration_group_id` JOIN `company` ON `company`.`id`=`registration`.`company_id` JOIN `academic_year` ON `academic_year`.`id`=`registration`.`academic_year_id` JOIN `lecture` ON `lecture`.`id`=`registration`.`lecture_id` WHERE `registration`.`period_pkl_id` = '$period' AND `lecture`.`nip` = '" . $this->session->userdata('user') . "'";
    if ($group_id) {
      $query .= " AND `registration`.`group_id` = '$group_id'";
    }
    $query .= " GROUP BY `registration`.`group_id`";
    return $this->db->query($query);
  }

  // laporan supervisi
  // public function getSupervisionReport($academic_year_id = null, $id = null)
  // {
  //   $this->_joinSupervisonReport();
  //   $this->db->where('lecture.nip', $this->session->userdata('user'));
  //   if ($academic_year_id && $id) {
  //     $this->db->where('academic_year.id', $academic_year_id);
  //     $this->db->where('supervision_report.id', $id);
  //   } else {
  //     $this->db->where('academic_year.status', 1);
  //   }
  //   $this->db->group_by('registration.group_id');
  //   return $this->db->get($this->tableReportSupervision);
  // }

  private function _joinSupervisonReport()
  {
    $this->db->select('registratiodn.group_id, registration.pushed, company.name company_name, COUNT(registration.group_id) as studentcount, supervision_report.*');
    $this->db->join($this->tableRegistration, 'registration.group_id = supervision_report.registration_group_id', 'RIGHT');
    $this->db->join($this->tableCompany, 'company.id=registration.company_id');
    $this->db->join($this->tableAcademicYear, 'academic_year.id=registration.academic_year_id');
    $this->db->join($this->tableLecture, 'lecture.id=registration.lecture_id');
  }

  public function insertSupervisonReport($data, $id = null)
  {
    if ($id) {
      $this->db->update($this->tableReportSupervision, $data, $id);
    } else {
      $this->db->insert($this->tableReportSupervision, $data);
    }
    return $this->db->affected_rows();
  }

  public function reportCheck()
  {
    $this->db->select('supervision_report.id, supervision_report.registration_group_id');
    $this->db->join($this->tableRegistration, 'registration.group_id=supervision_report.registration_group_id');
    $this->db->join($this->tableLecture, 'lecture.id=registration.lecture_id');
    return $this->db->get_where($this->tableReportSupervision, ['lecture.nip' => $this->session->userdata('user')])->row();
  }

  public function listReception()
  {
    return $this->db->query("SELECT willingness_to_accept.company_id,willingness_to_accept.year_accepted,company.name as company_name FROM willingness_to_accept 
    JOIN company ON company.id=willingness_to_accept.company_id
    JOIN registration on registration.company_id=company.id
    JOIN lecture on lecture.id=registration.lecture_id
    WHERE lecture.nip = '" . $this->session->userdata('user') . "'
    GROUP BY willingness_to_accept.company_id");
  }

  public function detailReception($company_id)
  {
    $this->db->select('company.name as company_name,company.address,company.pic,company.telp, willingness_to_accept.*, academic_year.name as academic_year, prodi.name as prodi_name');
    $this->db->join($this->tableCompany, 'company.id=willingness_to_accept.company_id');
    $this->db->join($this->tableAcademicYear, 'academic_year.id=willingness_to_accept.academic_year_id');
    $this->db->join($this->tableProdi, 'willingness_to_accept.prodi_id=prodi.id');
    $this->db->where('willingness_to_accept.company_id', $company_id);
    return $this->db->get_where($this->tableReception);
  }

  public function reportCheckByGroup($group)
  {
    $this->db->select('supervision_report.id, supervision_report.registration_group_id');
    $this->db->join($this->tableRegistration, 'registration.group_id=supervision_report.registration_group_id');
    $this->db->join($this->tableLecture, 'lecture.id=registration.lecture_id');
    return $this->db->get_where($this->tableReportSupervision, ['supervision_report.registration_group_id' => $group])->row();
  }
}
