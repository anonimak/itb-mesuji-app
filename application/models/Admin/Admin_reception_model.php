<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_reception_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'willingness_to_accept';
    $this->tableCompany = 'company';
    $this->tableAcademic = 'academic_year';
    $this->tableProdi = 'prodi';
  }

  public function list()
  {
    $this->db->select('*');
    $this->db->group_by('company.id');
    $this->db->join($this->tableCompany, 'company.id=willingness_to_accept.company_id');
    return $this->db->get($this->table);
  }

  public function detail($company_id)
  {
    $this->db->select('company.name as company_name,company.address,company.pic,company.telp, willingness_to_accept.*, academic_year.name as academic_year, prodi.name as prodi_name');
    $this->db->join($this->tableCompany, 'company.id=willingness_to_accept.company_id');
    $this->db->join($this->tableAcademic, 'academic_year.id=willingness_to_accept.academic_year_id');
    $this->db->join($this->tableProdi, 'willingness_to_accept.prodi_id=prodi.id');
    $this->db->where('willingness_to_accept.company_id', $company_id);
    return $this->db->get_where($this->table);
  }
}
