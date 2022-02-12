<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PrintPdf extends CI_Controller
{

  public function krs()
  {
    $mpdf   = new \Mpdf\Mpdf(['mode' => 'utf-8', ['orientation' => 'P']]);
    $view   = $this->load->view('mahasiswa/print/krs', [], TRUE);
    $mpdf->SetProtection(array('print'));
    $mpdf->WriteHTML($view);
    $mpdf->Output('KRS Semester 1 - Agung Hardiyanto.pdf', 'D');
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
