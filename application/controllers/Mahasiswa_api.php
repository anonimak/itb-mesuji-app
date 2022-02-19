<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa_api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mahasiswa/Mahasiswa_krs_model', 'Krs');
        $this->load->model('Auth_model', 'Auth');
        $this->role = 'mahasiswa';
        cek_login('Mahasiswa');
        $this->redirectUrl = 'mahasiswa/krs';
    }

    public function getKrsInfo()
    {
        $active_academic = $this->Krs->getActiveAcademicYear();
        $student = $this->session->userdata('username');

        // check current krs
        $currentkrs = $this->Krs->getKrsCurrent($active_academic->id, $student->id)->row();
        // pretty_dump($currentkrs);
        if (!$currentkrs) {
            $latestkrs = $this->Krs->getlatestKrsbyStudentId($student->id)->row();
            // insert ke database
            $dataInsert = [
                "academic_year_id"  => $active_academic->id,
                "student_id"        => $student->id,
                "semester"          => ($latestkrs) ? $latestkrs->semester + 1 : 1,
                "kredit"            => 0,
                "ip"                => 0,
                "status"            => "edit",
            ];
            $this->Krs->insert($dataInsert);
            $currentkrs = $this->Krs->getKrsCurrent($active_academic->id, $student->id)->row();
        } else {
            $latestkrs = $this->Krs->getKrsbySemester($student->id, $currentkrs->semester - 1)->row();
        }
        $data = $currentkrs;
        $getsumkrs = $this->Krs->getSumKrs($student->id, $currentkrs->semester);
        $data->total_kredit = ($getsumkrs) ? $getsumkrs->total_kredit : 0;
        $data->ip_latest    = ($latestkrs) ? $latestkrs->ip : 0;
        $data->course_takens = $this->Krs->getKrsCurrentCourseTaken($active_academic->id, $student->id)->result();

        $response = array(
            'status' => 'OK',
            'data'  => $data
        );

        outputJson($response);
    }

    public function getAcademicYear()
    {
        $data = $this->Krs->getAcademicYear();

        $response = array(
            'status' => 'OK',
            'data'  => $data
        );

        outputJson($response);
    }

    public function getKrsCourse()
    {
        $active_academic = $this->Krs->getActiveAcademicYear();
        $student = $this->session->userdata('username');
        $currenkrs = $this->Krs->getKrsCurrent($active_academic->id, $student->id)->row();
        $getsumkrs = $this->Krs->getSumKrs($student->id, $currenkrs->semester);
        $data = $this->Krs->getKrsCoursesbySemester($student->prodi_id, $student->id, $currenkrs->semester, ($getsumkrs && $getsumkrs->ipk >= 3.6) ? true : false)->result();
        $limitCredit = 24;
        if ($currenkrs->semester > 1) {
            $limitCredit = ($getsumkrs->ipk < 3) ? 21 : 24;
        }

        $response = array(
            'status' => 'OK',
            'data'  => $data,
            'limitCredit' => $limitCredit - $currenkrs->kredit
        );

        outputJson($response);
    }

    public function insertToCurrentCourse()
    {
        $active_academic = $this->Krs->getActiveAcademicYear();
        $student = $this->session->userdata('username');
        $currenkrs = $this->Krs->getKrsCurrent($active_academic->id, $student->id)->row();
        $course_ids = json_decode($this->input->post('course_ids'));
        $kredit = $currenkrs->kredit;
        foreach ($course_ids as $value) {
            $course = $this->Krs->getCoursebyId($value)->row();
            $affectedrow = $this->Krs->insertDetail(
                array(
                    'krs_id'    => $currenkrs->id,
                    'course_id' => $value
                )
            );
            if ($affectedrow > 0) {
                $kredit = $kredit + $course->sks;
            }
        }

        $this->Krs->update(array(
            'id' => $currenkrs->id,
            'kredit' => $kredit
        ));

        $response = array(
            'status' => 'OK',
        );

        outputJson($response);
    }

    public function removeCurrentCourse($id)
    {
        $active_academic = $this->Krs->getActiveAcademicYear();
        $student = $this->session->userdata('username');
        $currenkrs = $this->Krs->getKrsCurrent($active_academic->id, $student->id)->row();
        $course = $this->Krs->getKrsCourseTakenbyId($id)->row();
        $affectedrow = $this->Krs->deleteDetail($id);
        if ($affectedrow > 0) {
            $status = 'OK';

            $this->Krs->update(array(
                'id' => $currenkrs->id,
                'kredit' => $currenkrs->kredit - $course->sks
            ));
        } else {
            $status = 'NO';
        }

        $response = array(
            'status' => $status,
        );

        outputJson($response);
    }

    public function submitKrs($id)
    {
        $affectedrow = $this->Krs->update(array(
            'id' => $id,
            'status' => 'unverified'
        ));

        if ($affectedrow > 0) {
            $status = 'OK';
        } else {
            $status = 'NO';
        }

        $response = array(
            'status' => $status,
        );

        outputJson($response);
    }
}
