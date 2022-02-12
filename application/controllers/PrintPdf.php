<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PrintPdf extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Mahasiswa/Mahasiswa_krs_model', 'Krs');
    $this->load->model('Auth_model', 'Auth');
    $this->role = 'mahasiswa';
    cek_login('Mahasiswa');
  }

  public function krs()
  {
    $mpdf   = new \Mpdf\Mpdf(['mode' => 'utf-8', ['orientation' => 'P']]);
    $akademik = $this->Krs->getActiveAcademicYear();
    $student = $this->session->userdata('username');
    // check current krs
    $currentKrs = $this->Krs->getKrsCurrent($akademik->id, $student->id)->row();
    $latestkrs = $this->Krs->getKrsLatestSemester($student->id)->row();
    $getsumkrs = $this->Krs->getSumKrs($student->id);
    $data = $this->Krs->getKrsCurrent($akademik->id, $student->id)->row();
    $data->total_kredit = ($getsumkrs) ? $getsumkrs->total_kredit : 0;
    $data->ip_latest    = ($latestkrs) ? $latestkrs->ip : 0;
    $data->course_takens = $this->Krs->getKrsCurrentCourseTaken($akademik->id, $student->id)->result();
    // pretty_dump($data);
    // die;
    $data = [
      'student'  => $data,
    ];
    $view   = $this->load->view('mahasiswa/print/krs', $data, TRUE);
    $mpdf->SetProtection(array('print'));
    $mpdf->WriteHTML($view);
    $mpdf->Output('KRS Semester ' . $data['student']->semester . ' - ' . $data['student']->fullname . '.pdf', 'D');
  }

  public function khs()
  {
    $mpdf   = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-P']);
    $view   = $this->load->view('mahasiswa/print/khs', [], TRUE);
    $mpdf->SetProtection(array('print'));
    $mpdf->WriteHTML($view);
    $mpdf->Output('KHS Semester 1 - Agung Hardiyanto.pdf', 'D');
  }
}
