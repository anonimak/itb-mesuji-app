<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kaprodi_profile_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'prodi';
    $this->tableKaprodi = 'head_of_study_program';
    $this->tableUser = 'user';
    $this->tableLecture = 'lecture';
  }

  public function getBy()
  {
    $this->db->select('a.*,b.no_hp,c.name lecture,c.nip, d.id as user_id,d.username, d.password');
    $this->db->join($this->tableKaprodi . ' b', 'b.prodi_id=a.id');
    $this->db->join($this->tableLecture . ' c', 'b.lecture_id=c.id');
    $this->db->join($this->tableUser . ' d', 'd.username=a.email');
    $this->db->where('b.status', 1);
    return $this->db->get_where($this->table . ' a', ['a.email' => $this->session->userdata('user')])->row();
  }

  public function updateUserPassword($data)
  {
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update($this->tableUser, $data, ['id' => $data['id']]);
    return $this->db->affected_rows();
  }
}
