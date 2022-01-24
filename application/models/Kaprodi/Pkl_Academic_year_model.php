<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pkl_Academic_year_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'academic_year';
    }

    public function getAll()
    {
        $this->db->select('id, name as text');
        return $this->db->get($this->table)->result();
    }
}
