<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa_registration_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'registration';
    $this->studentTable = 'student';
    $this->t_responseLetter = 'response_letter';
    $this->tableCompany = 'company';
    $this->tableProdi = 'prodi';
    $this->tableMajor = 'major';
    $this->tablePeriode = 'periode';
    $this->tableHistory = 'history_registration';
    $this->tableAcademicYear = 'academic_year';
    $this->tableLecture = 'lecture';
    $this->tableResponseLetter = 'response_letter';
    $this->tableSupervisor = 'supervisor';
    $this->tableDatePeriod = 'pkl_implementation_period';
  }

  public function list()
  {
    $this->_joinTable();
    $this->db->order_by('registration.created_at', 'DESC');
    return $this->db->get_where($this->table, ['student.npm' => $this->session->userdata('user')]);
  }

  public function getAll($groupId, $leader = null)
  {
    $this->_joinTable();
    $this->db->where('registration.group_id', $groupId);
    if ($leader) {
      $this->db->where('registration.status', $leader);
    }
    return $this->db->get($this->table);
  }

  private function _joinTable()
  {
    $this->db->select('registration.*, company.name as company_name,supervisor.name as pic, pkl_implementation_period.title, pkl_implementation_period.start_date as start_time_pkl, pkl_implementation_period.finish_date as finish_time_pkl, student.npm,student.fullname, student.status as student_status, prodi.name as prodi_name, lecture.name as lecture_name, academic_year.name as academic_year, academic_year.status as academic_year_status');
    $this->db->join($this->tableCompany, 'company.id=registration.company_id');
    $this->db->join($this->tableDatePeriod, 'pkl_implementation_period.id=registration.period_pkl_id');
    $this->db->join($this->studentTable, 'student.id=registration.student_id');
    $this->db->join($this->tableProdi, 'prodi.id=registration.prodi_id');
    $this->db->join($this->tableLecture, 'lecture.id=registration.lecture_id', 'LEFT');
    $this->db->join($this->tableSupervisor, 'supervisor.id=registration.supervisor_id', 'LEFT');
    $this->db->join($this->tableAcademicYear, 'academic_year.id=registration.academic_year_id');
  }

  public function getCompanyList()
  {
    return $this->db->get_where('company', ['status' => 'verify'])->result();
  }

  public function getDataCompanyBy($data)
  {
    return $this->db->get_where('company', $data);
  }

  public function getDataCompanyInRegistration($data, $start, $finish)
  {
    $this->db->where($data);
    $this->db->where('created_at >=', $start);
    $this->db->where('created_at <=', $finish);
    $this->db->where('group_status !=', 'ditolak');
    $this->db->where('verify_member !=', 'Ditolak');
    return $this->db->get($this->table);
  }

  public function getDataAcademicYear($data)
  {
    return $this->db->get_where('academic_year', $data);
  }

  public function getStudentListByProdi()
  {
    return $this->db->get($this->studentTable)->result();
  }

  public function getDataStudentBy($data)
  {
    $this->db->select('student.*,major.id as major_id');
    $this->db->join($this->tableProdi, 'prodi.id=student.prodi_id');
    $this->db->join($this->tableMajor, 'major.id=prodi.major_id');
    return $this->db->get_where($this->studentTable, $data);
  }

  public function getStudentProdi()
  {
    return $this->db->get_where($this->studentTable, ['npm' => $this->session->userdata('user')])->row_array();
  }

  public function getDataPeriode()
  {
    $today  = date('Y-m-d');
    $this->db->where('start_time <=', $today);
    $this->db->where('finish_time >=', $today);
    $this->db->where('type =', 3);
    return $this->db->get('periode');
  }

  public function getDataPeriodeVerif()
  {
    $this->db->where('type =', 4);
    return $this->db->get('periode');
  }

  public function getPeriode()
  {
    $this->db->order_by('id', 'DESC');
    return $this->db->get_where('periode', ['type !=' => 2])->result();
  }

  public function getStudent($input = null, $prodiId = null, $leaderId = null)
  {
    $query = "SELECT `a`.*,`b`.`name` `prodi_name`,`c`.`name` `major_name`
                    FROM student `a` 
                    LEFT JOIN prodi `b` ON `a`.`prodi_id`=`b`.`id`
                    LEFT JOIN major `c` ON `b`.`major_id`=`c`.`id`";
    if ($input) {
      $query .=   "WHERE (`a`.`fullname` LIKE '%$input%' ESCAPE '!' OR `b`.`name` LIKE '%$input%' ESCAPE '!' OR `a`.`npm` LIKE '%$input%' ESCAPE '!')";
    }

    if ($leaderId) {
      $query .= "WHERE `a`.`id` != '" . $leaderId . "'";
    }
    $query .= 'AND `b`.`id` ="' . $prodiId . '" ';
    $query .=      "AND `a`.`id` NOT IN (SELECT `student_id` FROM `" . $this->table . "` WHERE `verify_member` != 'Ditolak' AND `group_status` != 'ditolak')";
    return $this->db->query($query);
  }

  public function getCompany($input, $prodi)
  {
    $query = "SELECT company.*,regency.name regency_name, province.name province_name
    FROM company
    JOIN regency ON company.regency_id= regency.id
    JOIN province ON company.province_id=province.id
    WHERE (company.name LIKE '%$input%' ESCAPE '!' OR regency.name LIKE '%$input%' ESCAPE '!' OR province.name LIKE '%$input%' ESCAPE '!') 
    AND (company.prodi_id = '$prodi' OR company.label = 'bersama')
    AND company.status = 'verify'
    AND company.id NOT IN (SELECT company_id FROM registration JOIN company ON company.id=registration.company_id WHERE company.label = 'prodi')
    LIMIT 10";
    return $this->db->query($query);
  }

  public function insert($data)
  {
    $this->db->set('created_at', date('Y-m-d H:i:s'));
    $this->db->insert($this->table, $data);
    return $this->db->affected_rows();
  }

  public function edit($data, $where)
  {
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update($this->table, $data, ['id' => $where]);
    return $this->db->affected_rows();
  }

  public function invited($data)
  {
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update($this->table, $data, ['student_id' => $data['student_id']]);
    return $this->db->affected_rows();
  }

  public function uploaded($data)
  {
    $this->db->set('id', 'UUID()', false);
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->insert($this->t_responseLetter, $data);
    return $this->db->affected_rows();
  }

  public function getDataLeaderId()
  {
    $this->db->select('a.*,b.id as userid, b.username, c.status,c.prodi_id,c.id leaderid');
    $this->db->join('user as b', 'a.npm=b.username');
    $this->db->join('registration as c', 'c.student_id=a.id');
    // $this->db->where('c.status =', 'Ketua');
    $this->db->where('c.group_status !=', 'ditolak');
    $this->db->where('b.id', $this->session->userdata()['username']->id);
    $dataRow = $this->db->get('student as a')->row();
    return $dataRow;
  }

  public function getDataBy($data, $where = null, $groupId = null)
  {
    $this->_join();
    if ($where && $groupId) {
      $this->db->where($where);
      $this->db->where('a.group_id !=', $groupId);
      $this->db->where('a.group_status !=', 'ditolak');
    }
    return $this->db->get_where($this->table . ' a', $data);
  }

  private function _join()
  {
    $this->db->select('a.*,b.id company_id, b.pic, b.telp, b.name company_name,c.fullname,c.npm,c.email student_email,d.id prodi_id,d.name prodi_name,d.code prodi_code,e.id as major_id, e.name major_name,f.name as lecture_name,g.username pl');
    $this->db->join($this->tableCompany . ' b', 'a.company_id=b.id', 'LEFT');
    $this->db->join($this->studentTable . ' c', 'a.student_id=c.id', 'LEFT');
    $this->db->join($this->tableProdi . ' d', 'c.prodi_id=d.id', 'LEFT');
    $this->db->join($this->tableMajor . ' e', 'd.major_id=e.id', 'LEFT');
    $this->db->join($this->tableLecture . ' f', 'a.lecture_id=f.id', 'LEFT');
    $this->db->join($this->tableSupervisor . ' g', 'a.supervisor_id=g.id', 'LEFT');
  }

  public function getDatePeriodPKL()
  {
    $this->db->select('pkl_implementation_period.*');
    $this->db->join($this->tableAcademicYear, 'academic_year.id=pkl_implementation_period.academic_year_id');
    return $this->db->get_where($this->tableDatePeriod, ['academic_year.status' => 1]);
  }
}
