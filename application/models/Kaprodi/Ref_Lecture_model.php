<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ref_Lecture_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'lecture';
  }

  public function getAll()
  {
    $this->db->select('id, name as text');
    return $this->db->get($this->table)->result();
  }

  public function getByProdiId($prodiId)
  {
    $this->db->select('id, name as text');
    $this->db->where('prodi_id', $prodiId);
    return $this->db->get($this->table)->result();
  }
}
