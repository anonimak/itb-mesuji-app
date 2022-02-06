<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_head_program_study_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table            = 'head_of_study_program';
        $this->tableProdi       = 'prodi';
        $this->tableLecture     = 'lecture';
    }

    public function getAllData()
    {
        $this->db->select('a.*,b.name as lecture,c.name as prodi');
        $this->db->join($this->tableLecture . ' as b', 'a.lecture_id=b.id');
        $this->db->join($this->tableProdi . ' as c', 'a.prodi_id=c.id');
        return $this->db->get($this->table . ' as a');
    }

    public function getLecture()
    {
        $query = 'SELECT `a`.*, `b`.`id` as `major_id`,`b`.`name` as `major`,`c`.`id` as `prodi_id`,`c`.`name` as `prodi` 
                    FROM `lecture` as `a` 
                    JOIN `major` as `b` ON `a`.`major_id`=`b`.`id`
                    JOIN `prodi` as `c` ON `a`.`prodi_id`=`c`.`id` 
                    WHERE `a`.`id` NOT IN(SELECT `lecture_id` FROM `head_of_study_program` WHERE `status` = "1")
                    AND `c`.`id` NOT IN(SELECT `prodi_id` FROM `head_of_study_program`  WHERE `status` = "1")';
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
