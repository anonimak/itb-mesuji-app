<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_registrations_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table                = 'registration';
    $this->tableCompany         = 'company';
    $this->tableStudent         = 'student';
    $this->tableProdi           = 'prodi';
    $this->tableMajor           = 'major';
    $this->tablePeriode         = 'periode';
    $this->tableHistory         = 'history_registration';
    $this->tableAcademicYear    = 'academic_year';
    $this->tableLecture         = 'lecture';
    $this->tableResponseLetter  = 'response_letter';
    $this->tableSupervisor      = 'supervisor';
    $this->tableDatePeriod      = 'pkl_implementation_period';
  }

  public function getAllData()
  {
    $this->_join();
    $this->db->where('registration.status =', 'Ketua');
    $this->db->order_by('registration.created_at', 'DESC');
    // $this->db->group_by('registration.company_id');
    if ((int)$this->input->post('length') !== -1) {
      $this->db->limit($this->input->post('length'), $this->input->post('start'));
    }
    return $this->db->get($this->table);
  }

  public function insert($data)
  {
    $this->db->set('created_at', date('Y-m-d H:i:s'));
    $this->db->insert($this->table, $data);
    return $this->db->affected_rows();
  }

  public function update($data, $where)
  {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }

  public function getDataBy($data, $where = null, $groupId = null)
  {
    $this->_join();
    if ($where && $groupId) {
      $this->db->where($where);
      $this->db->where('registration.group_id !=', $groupId);
      $this->db->where('registration.group_status !=', 'ditolak');
    }
    return $this->db->get_where($this->table, $data);
  }

  public function getLecture()
  {
    $this->db->select('lecture.*,prodi.name as prodi_name');
    $this->db->join($this->tableProdi, 'prodi.id=lecture.prodi_id');
    return $this->db->get_where($this->tableLecture);
  }

  public function getDataStudentBy($data)
  {
    $this->db->select('student.*,major.id as major_id');
    $this->db->join($this->tableProdi, 'prodi.id=student.prodi_id');
    $this->db->join($this->tableMajor, 'major.id=prodi.major_id');
    return $this->db->get_where($this->tableStudent, $data);
  }

  public function getDataPeriode()
  {
    $today  = date('Y-m-d');
    $this->db->where('finish_time >=', $today);
    $this->db->where('type =', 3);
    $this->db->limit(1);
    $this->db->order_by('finish_time', 'ASC');
    return $this->db->get($this->tablePeriode);
  }

  private function _join()
  {
    $this->db->select('registration.*, company.name as company_name,supervisor.name as pic, supervisor.telp, pkl_implementation_period.title, pkl_implementation_period.start_date as start_time_pkl, pkl_implementation_period.finish_date as finish_time_pkl, student.npm,student.fullname,student.email,student.status as student_status,prodi.name as prodi_name,prodi.code as prodi_code, lecture.name as lecture_name, academic_year.name as academic_year, academic_year.status as academic_year_status, supervisor.username');
    $this->db->join($this->tableCompany, 'company.id=registration.company_id', 'LEFT');
    $this->db->join($this->tableDatePeriod, 'pkl_implementation_period.id=registration.period_pkl_id');
    $this->db->join($this->tableProdi, 'prodi.id=registration.prodi_id', 'LEFT');
    $this->db->join($this->tableMajor, 'major.id=prodi.major_id', 'LEFT');
    $this->db->join($this->tableStudent, 'student.id=registration.student_id', 'LEFT');
    $this->db->join($this->tableLecture, 'lecture.id=registration.lecture_id', 'LEFT');
    $this->db->join($this->tableSupervisor, 'supervisor.id=registration.supervisor_id', 'LEFT');
    $this->db->join($this->tableAcademicYear, 'academic_year.id=registration.academic_year_id');
    if (@$this->input->post('search')['value']) {
      $this->db->like('company.name', $this->input->post('search')['value']);
    }
    if ($this->input->post('order')) {
      $this->db->order_by('registration.created_at', $this->input->post('order')['0']['dir']);
    } else {
      $this->db->order_by('registration.created_at', 'DESC');
    }
  }


  public function countRecordsFiltered()
  {
    $this->_join();
    $this->db->where('registration.status =', 'Ketua');
    $this->db->order_by('registration.created_at', 'DESC');
    $query = $this->db->get($this->table);
    return $query->num_rows();
  }

  public function countRecordsTotal()
  {
    $this->db->select('*');
    $this->db->where('registration.status =', 'Ketua');
    $this->db->order_by('registration.created_at', 'DESC');
    $this->db->get($this->table);
    return $this->db->count_all_results();
  }


  public function getDataHistory()
  {
    $this->db->select('a.*,b.username');
    $this->db->join('user b', 'a.user_id=b.id');
    $this->db->order_by('a.created_at', 'DESC');
    return $this->db->get($this->tableHistory . ' a');
  }

  public function getDataCompanyBy($data)
  {
    return $this->db->get_where($this->tableCompany, $data);
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

  public function uploaded($data)
  {
    $this->db->set('id', 'UUID()', false);
    $this->db->insert($this->tableResponseLetter, $data);
    return $this->db->affected_rows();
  }

  public function getResponseLetter($data)
  {
    return $this->db->get_where($this->tableResponseLetter, $data);
  }

  public function lastSupervisorData($prodicode)
  {
    $this->db->select('*');
    $this->db->like('username', 'pl-' . $prodicode);
    $this->db->order_by('created_at', 'DESC');
    $this->db->limit(1);
    return $this->db->get($this->tableSupervisor)->row();
  }

  public function getDatePeriodPKL()
  {
    $this->db->select('pkl_implementation_period.*');
    $this->db->join($this->tableAcademicYear, 'academic_year.id=pkl_implementation_period.academic_year_id');
    return $this->db->get_where($this->tableDatePeriod, ['academic_year.status' => 1]);
  }

  public function deleteMultiple($data)
  {
    $where = explode(",", $data);
    $this->db->select('a.*,b.id as supervisor_id,b.username as supervisor,c.username as user');
    $this->db->join($this->tableSupervisor . ' b', 'a.supervisor_id=b.id', 'LEFT');
    $this->db->join('user as c', 'b.username=c.username', 'LEFT');
    $this->db->where_in('a.group_id', $where);
    $results = $this->db->get('registration as a')->result();

    $user       = [];
    $supervisor = [];
    foreach ($results as $row) {
      $user[] = $row->user;
      $supervisor[] = $row->supervisor_id;
    }


    $this->db->where_in('username', $user);
    $this->db->delete('user');
    sleep(3);
    $this->db->where_in('id', $supervisor);
    $this->db->delete($this->tableSupervisor);
    sleep(3);

    $this->db->where_in('group_id', $where);
    $this->db->delete($this->table);

    return $this->db->affected_rows();
  }


  public function getStudentMember($prodi)
  {
    $this->db->where('verify_member !=', 'Ditolak');
    $this->db->where('group_status !=', 'ditolak');
    $query = $this->db->get($this->table)->result();
    $studentId = [];
    foreach ($query as $row) {
      $studentId[] = $row->student_id;
    }
    $this->_joinMember();
    if ((int)$this->input->post('length') !== -1) {
      $this->db->limit($this->input->post('length'), $this->input->post('start'));
    }
    $this->db->where_not_in('a.id', $studentId);
    $this->db->where('a.prodi_id =', $prodi);
    return $this->db->get($this->tableStudent . ' a');
  }

  private function _joinMember()
  {
    $this->db->select('a.*,b.name prodi_name');
    $this->db->join($this->tableProdi . ' b', 'a.prodi_id=b.id');
    if (@$this->input->post('search')['value']) {
      $this->db->like('a.fullname', $this->input->post('search')['value']);
    }
    if ($this->input->post('order')) {
      $this->db->order_by('a.npm', $this->input->post('order')['0']['dir']);
    } else {
      $this->db->order_by('a.created_at', 'DESC');
    }
  }

  public function countRecordsTotalMember($prodi)
  {
    $this->db->where('verify_member !=', 'Ditolak');
    $this->db->where('group_status !=', 'ditolak');
    $query = $this->db->get($this->table)->result();
    $studentId = [];
    foreach ($query as $row) {
      $studentId[] = $row->student_id;
    }
    $this->db->select('*');
    $this->db->where_not_in('a.id', $studentId);
    $this->db->where('a.prodi_id =', $prodi);
    $this->db->from($this->tableStudent . ' as a');
    return $this->db->count_all_results();
  }


  public function countRecordsFilteredMember($prodi)
  {
    $this->db->where('verify_member !=', 'Ditolak');
    $this->db->where('group_status !=', 'ditolak');
    $query = $this->db->get($this->table)->result();
    $studentId = [];
    foreach ($query as $row) {
      $studentId[] = $row->student_id;
    }
    $this->_joinMember();
    $this->db->where_not_in('a.id', $studentId);
    $this->db->where('a.prodi_id =', $prodi);
    $query = $this->db->get($this->tableStudent . ' as a');
    return $query->num_rows();
  }
}
