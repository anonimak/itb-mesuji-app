<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pkl_Prodi_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table            = 'prodi';
    $this->tablePeriode     = 'pkl_implementation_period';
    $this->tableAcademic    = 'academic_year';
    $this->tableFinalScore  = 'v_final_score_result_with_hm';
    $this->tableKaprodi     = 'head_of_study_program';
    $this->tableLecture     = 'lecture';
  }

  public function getByEmail($email)
  {
    $this->db->select('a.*,b.no_hp,c.name lecture,c.nip');
    $this->db->join($this->tableKaprodi . ' b', 'b.prodi_id=a.id');
    $this->db->join($this->tableLecture . ' c', 'b.lecture_id=c.id');
    return $this->db->get_where($this->table . ' a', ['a.email' => $email])->row();
  }

  public function getByEmailCheckStatusKaprodi($email)
  {
    $this->db->select('a.*,b.no_hp,c.name lecture,c.nip');
    $this->db->join($this->tableKaprodi . ' b', 'b.prodi_id=a.id');
    $this->db->join($this->tableLecture . ' c', 'b.lecture_id=c.id');
    $this->db->where('b.status', 1);
    return $this->db->get_where($this->table . ' a', ['a.email' => $email])->row();
  }

  public function getPeriode($data = null)
  {
    $this->_joinPeriode();
    if ($data) {
      $this->db->where($data);
    }
    return $this->db->get($this->tablePeriode . ' a');
  }

  public function getFinalScoreBy($data)
  {
    return $this->db->get_where($this->tableFinalScore, $data);
  }

  private function _joinPeriode()
  {
    $this->db->select('a.*,b.name academic');
    $this->db->join($this->tableAcademic . ' b', 'a.academic_year_id=b.id');
  }
}
