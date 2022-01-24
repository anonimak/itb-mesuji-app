<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_head_program_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table            = 'head_of_department';
        $this->tableMajor       = 'major';
        $this->tableLecture     = 'lecture';
    }

    public function getAllData()
    {
        $this->db->select('a.*,b.name as lecture,c.name as major');
        $this->db->join($this->tableLecture . ' as b', 'a.lecture_id=b.id');
        $this->db->join($this->tableMajor . ' as c', 'a.major_id=c.id');
        return $this->db->get($this->table . ' as a');
    }

    public function getLecture()
    {
        $query = 'SELECT `a`.*, `b`.`id` as `major_id`,`b`.`name` as `major` 
                    FROM `lecture` as `a` 
                    JOIN `major` as `b` ON `a`.`major_id`=`b`.`id` 
                    WHERE `a`.`id` NOT IN(SELECT `lecture_id` FROM `head_of_department` WHERE `status` = "1")
                    AND `b`.`id` NOT IN(SELECT `major_id` FROM `head_of_department`  WHERE `status` = "1")
                    ';
        return $this->db->query($query);
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->affected_rows();
    }

    public function update($data, $id)
    {
        $this->db->update($this->table, $data, $id);
        return $this->db->affected_rows();
    }
}
