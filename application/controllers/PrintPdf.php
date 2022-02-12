<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PrintPdf extends CI_Controller
{
  public function __construct()
  {
  }

  public function index()
  {
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML('<h1>Hello world!</h1>');
    $mpdf->Output();
  }
}
