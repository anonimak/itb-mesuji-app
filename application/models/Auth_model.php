<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
  public function adminCheck($username)
  {
    $this->db->select('a.*,b.id role_id,b.name');
    $this->db->join('role as b', 'a.role_id=b.id');
    $query = $this->db->get_where('user as a', ['a.username' => $username, 'a.is_active' => 1]);
    return $query;
  }

  public function userCheck($username)
  {
    $this->db->select('*');
    $query = $this->db->get_where('student', ['npm' => $username, 'status' => 1]);
    return $query;
  }

  function cek_login()
  {
    if (empty($this->session->userdata('is_login'))) {
      redirect('auth');
    }
  }

  function showNameLogin()
  {
    switch ($this->session->userdata('role')) {
      case 'Mahasiswa':
        $query = "SELECT student.fullname FROM student WHERE email = '" . $this->session->userdata('user') . "'";
        return $this->db->query($query)->row_array();
        break;
      default:
        echo 'NOT USER FOUND!';
    }
  }

  public function update($data, $id)
  {
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update('user', $data, ['id' => $id]);
    return $this->db->affected_rows();
  }

  public function updateStudent($data, $id)
  {
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->update('student', $data, ['id' => $id]);
    return $this->db->affected_rows();
  }
}
