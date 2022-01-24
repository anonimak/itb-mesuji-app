<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pkl_Location_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'company';
  }

  public function getAll($prodi = null)
  {
    $this->db->select('a.id,a.name,a.address,a.email,a.telp,a.pic,a.label,a.status,a.created_at,b.name as province_name,c.name as regency_name');
    $this->db->join('province b', 'b.id=a.province_id');
    $this->db->join('regency c', 'c.id=a.regency_id');
    $this->db->where('a.prodi_id', $prodi);
    $this->db->or_where('a.label', 'bersama');
    $this->db->order_by('a.created_at', 'DESC');
    return $this->db->get($this->table . ' a')->result();
  }

  public function getById($id)
  {
    return $this->db->get_where($this->table, ['id' => $id])->row();
  }

  public function getPklLocationRegistrationNotGroupId($prodi_id, $group_id)
  {
    $this->db->select('b.id,CONCAT(a.name," - ",a.label) as text, d.name as prodiname');
    $this->db->join('registration b', 'b.company_id=a.id', 'left');
    $this->db->join('academic_year c', 'b.academic_year_id=c.id', 'left');
    $this->db->join('prodi d', 'b.prodi_id=d.id', 'left');
    $this->db->where('c.status', '1');
    $this->db->where_not_in('b.group_status', ['belum_terverifikasi', 'ditolak']);
    $this->db->where("(b.prodi_id = '$prodi_id' AND b.group_id!='$group_id' AND b.status='Ketua')");
    $this->db->or_where("(a.label = 'bersama' AND b.prodi_id = '$prodi_id' AND b.group_id!='$group_id' AND b.status='Ketua')");
    return $this->db->get($this->table . ' a')->result();
  }

  public function checkEmail($email, $id)
  {
    $query  = $this->db->query("SELECT * FROM `$this->table` WHERE email = '$email' AND id != '$id'");
    if ($query->num_rows() > 0) {
      return false;
    } else {
      return true;
    }
  }

  public function getDataPeriode()
  {
    $today  = date('Y-m-d');
    $this->db->where('start_time <=', $today);
    $this->db->where('finish_time >=', $today);
    $this->db->where('type =', 2);
    return $this->db->get('periode');
  }

  public function update($data, $id)
  {
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update($this->table, $data, ['id' => $id]);
    return $this->db->affected_rows();
  }

  public function insert($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->affected_rows();
  }

  public function delete($where)
  {
    $this->db->delete($this->table, $where);
    return $this->db->affected_rows();
  }
}
