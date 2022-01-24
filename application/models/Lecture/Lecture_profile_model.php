<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lecture_profile_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'lecture';
    $this->tableUser = 'user';
  }

  public function getBy()
  {
    return $this->db->query("SELECT lecture.*, user.id as user_id,user.username, user.password FROM lecture JOIN user ON user.username = lecture.nip WHERE user.username = '".$this->session->userdata('user')."'")->row();
  }

  public function update($data)
  {
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update($this->table, $data, ['id' => $data['id']]);
    return $this->db->affected_rows();
  }
  
   public function updateUserPassword($data)
   {
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update($this->tableUser, $data, ['id' => $data['id']]);
    return $this->db->affected_rows();
   }
}
