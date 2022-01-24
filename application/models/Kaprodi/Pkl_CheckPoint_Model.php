<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pkl_CheckPoint_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'check_point';
    }

    public function getByRegId($reg_id)
    {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get_where($this->table, ['registration_id' => $reg_id])->result();
    }
}
