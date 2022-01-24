<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Major_persentasepkl_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableProdi   = 'prodi';
    }

    public function getDataBy($data)
    {
        return $this->db->get_where($this->tableProdi, $data);
    }
}
