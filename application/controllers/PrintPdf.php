<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PrintPdf extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Mahasiswa/Mahasiswa_krs_model', 'Krs');
    $this->role = 'mahasiswa';
    cek_login('Mahasiswa');
  }

  public function krs()
  {
    $mpdf   = new \Mpdf\Mpdf(['mode' => 'utf-8', ['orientation' => 'P']]);
    $akademik = $this->Krs->getActiveAcademicYear();
    $student = $this->session->userdata('username');
    // check current krs
    $currentkrs = $this->Krs->getKrsCurrent($akademik->id, $student->id)->row();
    $latestkrs = $this->Krs->getKrsbySemester($student->id, $currentkrs->semester - 1)->row();
    $getsumkrs = $this->Krs->getSumKrs($student->id, $currentkrs->semester);
    $data = $currentkrs;
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

  public function khs($id)
  {
    $id   = decodeEncrypt($id);
    $student = $this->session->userdata('username');
    $akademik = $this->Krs->getActiveAcademicYear();
    $dataKhs = $this->Krs->getKhsbyId($id, $student->id)->row();
    $getsumkrs = $this->Krs->getSumKrs($student->id, $dataKhs->semester + 1);
    $dataKhs = $this->Krs->getKhsbyId($id, $student->id)->row();
    $dataKhs->total_kredit = ($getsumkrs) ? $getsumkrs->total_kredit : 0;
    $dataKhs->ipk = ($getsumkrs) ? $getsumkrs->ipk : 0;
    $detailKhs = $this->Krs->getKhsCourseTakenbyKhsId($dataKhs->id)->result();
    $score  = 0;
    foreach ($detailKhs as $detail) {
      $score += (int)$detail->score;
    }
    if ($score <= 0) {
      $this->session->set_flashdata('error', 'Data KHS Belum di inputkan oleh admin');
      redirect('mahasiswa/khs');
    } else {

      $data = [
        'dataKhs'       => $dataKhs,
        'detailKhs'     => $detailKhs,
        'score'         => $score
      ];
      $mpdf   = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-P']);
      $view   = $this->load->view('mahasiswa/print/khs', $data, TRUE);
      $mpdf->SetProtection(array('print'));
      $mpdf->WriteHTML($view);
      $mpdf->Output('KHS Semester ' . $dataKhs->semester . ' - ' . $dataKhs->fullname . '.pdf', 'D');
    }
  }
}
