<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_config extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Admin/Admin_config_model', 'Config');
    $this->role               = 'admin';
    $this->tableVerification  = 'list_check_validation';
  }

  public function getprodi()
  {

    $input      = $this->input->post('search');
    if ($this->session->userdata()['username']->name === 'Sekjur') {
      $results = $this->Config->getProdiBy($input, $this->session->userdata()['username']->username)->result();
    } else {
      $results    = $this->Config->getProdiBy($input)->result();
    }
    $selectAjax = array();
    foreach ($results as $row) {
      $selectAjax[]   = [
        'id'    => $row->prodi_id,
        'text'  => $row->major_name . ' - ' . $row->prodi_name
      ];
    }
    $this->output->set_content_type('application/json')->set_output(json_encode($selectAjax));
  }

  public function getregency()
  {
    $input      = $this->input->post('search');
    $results    = $this->Config->getRegencyBy($input)->result();
    $selectAjax = array();
    foreach ($results as $row) {
      $selectAjax[]   = [
        'id'    => $row->regency_id,
        'text'  => $row->province_name . ' - ' . $row->regency_name
      ];
    }
    $this->output->set_content_type('application/json')->set_output(json_encode($selectAjax));
  }

  public function getleader()
  {
    $input      = $this->input->post('search');
    $prodiId    = $this->input->post('prodiId');
    $results    = $this->Config->getStudent($input, $prodiId)->result();
    $selectAjax = array();
    foreach ($results as $row) {
      $selectAjax[]   = [
        'id'    => $row->id,
        'text'  => strtoupper($row->npm)  . ' - ' . strtoupper($row->fullname)  . ' - ' . strtoupper($row->prodi_name)
      ];
    }
    $this->output->set_content_type('application/json')->set_output(json_encode($selectAjax));
  }

  public function getcompanies()
  {
    $input      = $this->input->post('search');
    $results    = $this->Config->getCompany($input)->result();
    $selectAjax = array();
    foreach ($results as $row) {
      $selectAjax[]   = [
        'id'    => $row->id,
        'text'  => strtoupper($row->name)  . ' - ' . strtoupper($row->regency_name)  . ' - ' . strtoupper($row->province_name)
      ];
    }
    $this->output->set_content_type('application/json')->set_output(json_encode($selectAjax));
  }

  public function historyAdd()
  {
    $cekLastInsert  = $this->Config->getLastIdRegistration()->row();
    $this->db->set('id', 'UUID()', FALSE);
    $dataInsert     = [
      'user_id'       => $this->session->userdata()['username']->id,
      'description'   => 'Menambahkan mahasiswa pkl pada tanggal ' . $cekLastInsert->created_at,
      'group_id'      => $cekLastInsert->group_id,
      'created_at'    => date('Y-m-d H:i:s')
    ];
    $insert         = $this->Config->insertHistory($dataInsert);
    if ($insert > 0) {
      $this->session->set_flashdata('success', 'Data berhasil di simpan');
    } else {
      $this->session->set_flashdata('error', 'Server History Registrasi gangguan, data tidak terekam');
    }
    redirect('admin/registrations');
  }

  public function historyupdate($paramId, $status)
  {
    $getData    = $this->Config->getLastIdRegistration(['student_id' => $paramId])->row();
    $this->db->set('id', 'UUID()', FALSE);
    $dataInsert     = [
      'user_id'       => $this->session->userdata()['username']->id,
      'description'   => 'Undangan PKL ' . $status . ' pada tanggal ' . $getData->updated_at,
      'group_id'      => $getData->group_id,
      'created_at'    => date('Y-m-d H:i:s')
    ];
    $insert         = $this->Config->insertHistory($dataInsert);
    if ($insert < 0) {
      $this->session->set_flashdata('error', 'Server History Registrasi gangguan, data tidak terekam');
      redirect('admin/registrations');
    }
    redirect('admin/registrations');
  }

  public function historyVerfication($idRegistration)
  {

    $getData    = $this->Config->getLastIdRegistration(['id' => $idRegistration])->row();

    $this->db->set('id', 'UUID()', FALSE);
    $dataInsert     = [
      'user_id'       => $this->session->userdata()['username']->id,
      'description'   => 'Group Status ' . $getData->group_status . ' pada tanggal ' . $getData->updated_at,
      'group_id'      => $getData->group_id,
      'created_at'    => date('Y-m-d H:i:s')
    ];
    $insert         = $this->Config->insertHistory($dataInsert);
    if ($insert > 0) {
      $this->session->set_flashdata('success', '<b>Verifikasi data berhasil</b>');
    } else {
      $this->session->set_flashdata('error', '<b>Server sedang sibuk, silahkan coba lagi</b>');
    }
    redirect('prodi/pkl_registrasi');
  }

  public function historyapproval($igGroup)
  {
    $getData    = $this->Config->getLastIdRegistration(['group_id' => $igGroup])->row();
    $this->db->set('id', 'UUID()', FALSE);
    $dataInsert     = [
      'user_id'       => $this->session->userdata()['username']->id,
      'description'   => 'Group Status <b>' . $getData->group_status . '</b> pada tanggal ' . $getData->updated_at,
      'group_id'      => $getData->group_id,
      'created_at'    => date('Y-m-d H:i:s')
    ];
    $insert         = $this->Config->insertHistory($dataInsert);
    if ($insert > 0) {
      $this->session->set_flashdata('success', '<b>Approval data berhasil</b>');
    } else {
      $this->session->set_flashdata('error', '<b>Server sedang sibuk, silahkan coba lagi</b>');
    }
    redirect('prodi/pkl_registrasi');
  }

  //MAJOR && ADMIN VERIFICATION
  public function data()
  {
    $studentId          = $this->input->post('studentId');
    $kompensasi         = $this->input->post('kompensasi');
    $bebastanggungan    = $this->input->post('bebastanggungan');
    $kelulusan          = $this->input->post('kelulusan');
    $kehadiran          = $this->input->post('kehadiran');
    $kehadrianadmin             = $this->input->post('kehadiranadmin');

    $status     = [];
    $query      = null;
    if ($kompensasi === '1') {
      $this->db->set('id', 'UUID()', FALSE);
      $insert     = [
        'student_id'    => $studentId,
        'v_kompensasi'  => $kompensasi,
      ];
      $query = $this->Config->insert($this->tableVerification, $insert);
    } else if ($kompensasi === '0') {
      $query = $this->Config->delete($this->tableVerification, ['student_id' => $studentId]);
    } else {
      if ($bebastanggungan) {
        $dataUpdate = [
          'v_bebastanggungan' => $bebastanggungan,
          'updated_at'        => date('Y-m-d H:i:s')
        ];

        $where  = [
          'student_id'        => $studentId,
        ];
        $query = $this->Config->update($this->tableVerification, $dataUpdate, $where);
      }

      if ($kelulusan) {
        $dataUpdate = [
          'v_kelulusan'       => $kelulusan,
          'updated_at'        => date('Y-m-d H:i:s')
        ];
        $where  = [
          'student_id'        => $studentId,
        ];
        $query = $this->Config->update($this->tableVerification, $dataUpdate, $where);
      }

      if ($kehadiran) {
        $dataUpdate = [
          'v_kehadiran'       => $kehadiran,
          'updated_at'        => date('Y-m-d H:i:s')
        ];
        $where  = [
          'student_id'        => $studentId,
        ];
        $query = $this->Config->update($this->tableVerification, $dataUpdate, $where);
      }
    }

    if ($kehadrianadmin) {
      $dataUpdate = [
        'v_kehadiran_admin' => $kehadrianadmin,
        'updated_at'        => date('Y-m-d H:i:s')
      ];

      $where  = [
        'student_id'        => $studentId,
      ];
      $query = $this->Config->update($this->tableVerification, $dataUpdate, $where);
    }

    if ($query > 0) {
      $status['message']  = 'success';
    } else {
      $status['message']  = 'failed';
    }
    $this->output->set_content_type('application/json')->set_output(json_encode($status));
  }
}
