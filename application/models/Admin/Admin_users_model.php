<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_users_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table            = 'user';
        $this->role             = 'role';
    }

    public function getAllData()
    {
        $this->_join();
        $this->db->order_by('b.name', 'ASC');
        $this->db->where('a.username !=', 'admin');
        return $this->db->get($this->table . ' a');
    }

    public function update($data, $where)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    private function _join()
    {
        $this->db->select('a.*,b.name as role_name');
        $this->db->join($this->role . ' b', 'a.role_id=b.id');
    }
}
