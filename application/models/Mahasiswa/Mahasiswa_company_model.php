<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa_company_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'company';
    $this->provinceTable = 'province';
    $this->regencyTable = 'regency';
    $this->prodiTable = 'prodi';
  }

  public function list($prodi = null)
  {
    $this->_join();
    $this->db->where('company.prodi_id', $prodi); // FALSE dianggap sebagai kolom
    $this->db->or_where('company.label', 'bersama');
    $this->db->group_by('company.id');
    return $this->db->get($this->table);
  }

  private function _join()
  {
    $this->db->select('company.*, province.name as province_name, regency.name as regency_name');
    $this->db->join($this->provinceTable, 'province.id=company.province_id');
    $this->db->join($this->regencyTable, 'regency.id=company.regency_id');
    $this->db->join($this->prodiTable, 'prodi.id=company.prodi_id ');
    $this->db->order_by('company.created_at', 'DESC');
  }

  public function getDataPeriode()
  {
    $today  = date('Y-m-d');
    $this->db->where('start_time <=', $today);
    $this->db->where('finish_time >=', $today);
    $this->db->where('type =', 1);
    return $this->db->get('periode');
  }

  public function getDataPeriodeVerif()
  {
    $today  = date('Y-m-d');
    $this->db->where('start_time <=', $today);
    $this->db->where('finish_time >=', $today);
    $this->db->where('type =', 2);
    return $this->db->get('periode');
  }

  public function getCompanyById($id)
  {
    return $this->db->get_where($this->table, ['id' => $id])->row();
  }

  public function getProdiId()
  {
    return $this->db->select('prodi_id')
      ->from('student')
      ->where('npm', $this->session->userdata('user'))
      ->get()->row();
  }

  public function insert($data)
  {
    $this->db->set('id', 'UUID()', FALSE);
    $this->db->insert($this->table, $data);
  }

  public function edit($data, $where)
  {
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update($this->table, $data, ['id' => $where]);
    return $this->db->affected_rows();
  }

  public function getAllProvince()
  {
    return $this->db->get($this->provinceTable)->result();
  }

  public function getAllRegency($province_id = null)
  {
    if ($province_id) {
      $this->db->where(['province_id' => $province_id]);
    }
    return $this->db->get($this->regencyTable)->result();
  }
}
