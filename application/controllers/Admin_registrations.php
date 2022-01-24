<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_registrations extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin/Admin_registrations_model', 'Registrations');
        $this->load->model('Admin/Admin_config_model', 'Config');
        $this->role         = 'admin';
        $this->redirect     = 'admin/registrations';
        cek_login('Admin');
    }

    public function index()
    {
        $getAllData     = $this->Registrations->getAllData()->result();
        $dataPeriode    = $this->Registrations->getDataPeriode()->row();
        $data = [
            'title'                     => 'Data Pendaftaran PKL',
            'desc'                      => 'Berfungsi untuk melihat Data Pendaftaran PKL',
            'allRegistrationLeader'     => $getAllData, //Leader,
            'periode'                   => $dataPeriode
        ];

        $page = '/admin/registration/index';
        pageBackend($this->role, $page, $data);
    }

    public function create()
    {
        $this->_validation('leader');
        if ($this->form_validation->run() === false) {
            $data = [
                'title'         => 'Tambah Pendaftaran PKL',
                'desc'          => 'Berfungsi untuk menambah Pendaftaran PKL',
                // 'periode'       => $dataPeriode
            ];
            $page = '/admin/registration/create-new';
            pageBackend($this->role, $page, $data);
        } else {
            $leaderId    = $this->input->post('leader');
            $companyId   = $this->input->post('company');
            $prodiLeader = $this->Registrations->getDataStudentBy(['id' => $leaderId])->row();
            $prodiId     = $prodiLeader->prodi_id;
            $dataPeriode    = $this->Registrations->getDataPeriode()->row();
            $groupId        = strtotime($dataPeriode->start_time_pkl) . $leaderId;
            $academic       = $this->Config->getDataAcademicYear(['status' => 1])->row();
            $academicId     = $academic->id;
            $this->db->set('id', 'UUID()', FALSE);
            $dataInsertLeader   = [
                'group_id'          => $groupId,
                'company_id'        => $companyId,
                'start_date'        => $dataPeriode->start_time_pkl,
                'finish_date'       => $dataPeriode->finish_time_pkl,
                'student_id'        => $leaderId,
                'status'            => 'Ketua',
                'prodi_id'          => $prodiId,
                'group_status'      => 'diverifikasi',
                'academic_year_id'  => $academicId,
                'verify_member'     => 'Diterima'
            ];
            $insertLeader       = $this->Registrations->insert($dataInsertLeader);
            if ($insertLeader > 0) {
                $this->session->set_flashdata('success', 'Data Berhasil di simpan');
            } else {
                $this->session->set_flashdata('error', 'Server sedang sibuk, silahkan coba lagi');
            }
            redirect('config/historyadd');
        }
    }

    public function history()
    {
        $datahistory    = $this->Registrations->getDataHistory()->result();
        $data = [
            'title'         => 'Data History Pendaftaran PKL',
            'desc'          => 'Berfungsi untuk melihat history Data Pendaftaran PKL',
            'datahistory'   => $datahistory
        ];

        $page = '/admin/registration/history';
        pageBackend($this->role, $page, $data);
    }

    public function historydetail()
    {
        $groupId    = $this->input->post('groupId');
        $getData    = $this->Registrations->getDataBy(['a.group_id' => $groupId])->result();
        if ($getData != null) {
            $result['status'] = 'ok';
            $output     = "";
            foreach ($getData as $student) {
                $output     .= "
                <tr>
                    <td>#</td>
                    <td>" . $student->company_name . "</td>
                    <td>" . $student->start_date . " - " . $student->finish_date . "</td>
                    <td>" . $student->fullname . " - " . $student->status . "</td>
                    <td>" . $student->prodi_name . "</td>
                    <td>" . $student->verify_member . "</td>
                </tr>
            ";
            }
            $result['data']   = $output;
        } else {
            $result['status'] = 'bad';
            $result['data']   = null;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }



    private function _validation($type)
    {
        if ($type === 'leader') {
            $this->form_validation->set_rules(
                'prodi',
                'Program studi',
                'trim|required',
                [
                    'required' => '%s wajib diisi'
                ]
            );

            $this->form_validation->set_rules(
                'leader',
                'Ketua Grup',
                'trim|required',
                [
                    'required' => '%s wajib diisi'
                ]
            );

            $this->form_validation->set_rules(
                'company',
                'Perusahaan',
                'trim|required|callback_company_check',
                [
                    'required' => '%s wajib diisi'
                ]
            );
        }
    }

    //VALIDATION COMPANY LABEL PRODI
    public function company_check()
    {
        $companyId  = $this->input->post('company');
        $cekCompany = $this->Registrations->getDataCompanyBy(['id' => $companyId])->row();
        if ($cekCompany->label === 'prodi') {
            $cekPeriode                 = $this->Registrations->getDataPeriode()->row();
            $cekCompanyInRegistration   = $this->Registrations->getDataCompanyInRegistration(['company_id' => $companyId], $cekPeriode->start_time, $cekPeriode->finish_time);
            if ($cekCompanyInRegistration->num_rows() > 0) {
                $this->form_validation->set_message(__FUNCTION__, 'Perusahaan ini sudah terdaftar di kelompok PKL sebelumnya, karena label Prodi');
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }


    public function detail($id)
    {
        $decode         = decodeEncrypt($id);
        $leader         = $this->Registrations->getDataBy(['a.id' => $decode])->row();
        $anotherGroup   = $this->Registrations->getDataBy(['a.prodi_id' => $leader->prodi_id], ['a.status' => 'Ketua'], $leader->group_id)->result();

        $responLetter   = $this->Registrations->getResponseLetter(['registration_group_id' => $leader->group_id]);
        if ($leader) {
            $getData        = $this->Registrations->getDataBy(['a.group_id' => $leader->group_id]);
            $lecture        = $this->Registrations->getLecture()->result();
            $dataPeriode    = $this->Registrations->getDataPeriode()->row();
            $members        = $this->Config->getStudent(null, $leader->prodi_id, $leader->id)->result();
            $data = [
                'title'         => 'Detail Pendaftaran PKL',
                'desc'          => 'Berfungsi untuk melihat detail Pendaftaran PKL',
                'leader'        => $leader,
                'periode'       => $dataPeriode,
                'datamember'    => $members,
                'getdata'       => $getData,
                'lectures'      => $lecture,
                'anothergroup'  => $anotherGroup,
            ];
            if ($responLetter->num_rows() > 0) {
                $data['letter']    = $responLetter->row();
            }
            $page = '/admin/registration/detail';
            pageBackend($this->role, $page, $data);
        } else {
            $this->session->set_flashdata('error', 'Maaf data yang dipilih tidak ada di server kami, silahkan coba lagi');
            redirect($this->redirect);
        }
    }


    public function addnewmember($uri)
    {
        $id         = $this->input->post('id');
        $leader     = $this->Registrations->getDataBy(['a.id' => $id])->row();
        $memberId   = $this->input->post('member');
        if ($memberId !== null) {
            $insert         = 0;
            foreach ($memberId as $member) {
                $this->db->set('id', 'UUID()', FALSE);
                $dataInsertMember   = [
                    'group_id'          => $leader->group_id,
                    'company_id'        => $leader->company_id,
                    'start_date'        => $leader->start_date,
                    'finish_date'       => $leader->finish_date,
                    'student_id'        => $member,
                    'status'            => 'Anggota',
                    'prodi_id'          => $leader->prodi_id,
                    'group_status'      => $leader->group_status,
                    'academic_year_id'  => $leader->academic_year_id,
                    'verify_member'     => 'Diterima'
                ];
                $this->Registrations->insert($dataInsertMember);
                $insert++;
            }
            if ($insert > 0) {
                $this->session->set_flashdata('success', 'Data berhasil disimpan');
            } else {
                $this->session->set_flashdata('error', 'Server sedang sibuk, silahkan coba lagi');
            }
        } else {
            $this->session->set_flashdata('error', 'Tidak ada anggota group yang di pilih');
        }

        redirect('admin/registrations/detail/' . $uri);
    }




    public function upload($uri)
    {

        $config['upload_path'] = './assets/uploads/';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 3000;

        $this->upload->initialize($config);
        $data = $this->input->post();
        if ($this->upload->do_upload('file')) {
            $fileData = $this->upload->data();
            $upload = [
                'letter_number'         => $data['letter_number'],
                'file'                  => $fileData['file_name'],
                'registration_group_id' => $data['registration_group_id'],
            ];
            if ($this->Registrations->uploaded($upload)) {
                $this->session->set_flashdata('success', '<p>File <strong>' . $fileData['file_name'] . '</strong> berhasil di simpan!</p>');
            } else {
                $this->session->set_flashdata('error', '<p>Gagal! File ' . $fileData['file_name'] . ' tidak berhasil tersimpan!</p>');
            }
            redirect('admin/registrations/detail/' . $uri);
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('admin/registrations/detail/' . $uri);
        }
    }


    public function verification($stringUrl, $status)
    {
        $explode    = explode(":", $stringUrl);
        $id         = $explode[0];
        $uri        = $explode[1];
        if ($status === '1') {
            $groupStatus    = 'diterima';
        } else {
            $groupStatus    = 'ditolak';
        }
        $dataUpdate = [
            'group_status'  => $groupStatus,
            'updated_at'    => date('Y-m-d H:i:s')
        ];
        $where      = [
            'id'    => $id,
        ];

        $update     = $this->Registrations->update($dataUpdate, $where);
        if ($update > 0) {
            $this->session->set_flashdata('success', 'Data berhasil di update');
        } else {
            $this->session->set_flashdata('error', 'Server sedang sibuk, silahkan coba lagi');
        }
        redirect('admin/registrations/detail/' . $uri);
    }


    public function updatesupervisor($uri)
    {
        $lecture    = $this->input->post('lecture');
        $id         = $this->input->post('registration-id');
        $rowData    = $this->Registrations->getDataBy(['a.id' => $id])->row();
        if ($rowData->supervisor_id === null) {
            $lastSuperVisor = $this->Registrations->lastSupervisorData($rowData->prodi_code);
            $lastSuperVisor = $this->Registrations->lastSupervisorData($rowData->prodi_code);
            if ($lastSuperVisor) {
                $arrayusername = explode('_', $lastSuperVisor->username);
                $index = (int) $arrayusername[2];
                $arrayusername[2] = $index + 1;
                $strnewsupervisor = implode('_', $arrayusername);
            } else {
                $strnewsupervisor = 'pl_' . $rowData->prodi_code . '_1';
            }

            //Insert Supervisor
            $this->db->set('id', 'UUID()', FALSE);
            $dataInsertSupervisor   = [
                'username'      => $strnewsupervisor,
                'company_id'    => $rowData->company_id,
            ];
            $this->db->insert('supervisor', $dataInsertSupervisor);
            //Insert user pl
            $this->db->set('id', 'UUID()', FALSE);
            $dataInsertUserPL   = [
                'username'      => $strnewsupervisor,
                'password'      => password_hash('123456', PASSWORD_DEFAULT),
                'role_id'       => '775b0fa8-b7a8-11eb-a91e-0cc47abcfaa6',
            ];
            $this->db->insert('user', $dataInsertUserPL);

            $cekSuperVisor  = $this->db->get_where('supervisor', ['username' => $strnewsupervisor])->row();


            $updateSuperVisorId = [
                'supervisor_id' => $cekSuperVisor->id,
                'updated_at'    => date('Y-m-d H:i:s'),
            ];

            $where  = [
                'group_id'      => $rowData->group_id
            ];

            $this->db->update('registration', $updateSuperVisorId, $where);
        }




        if ($lecture) {
            $dataUpdate = [
                'lecture_id'    => $lecture,
            ];
            $where  = [
                'id'        => $id
            ];
            $update     = $this->Registrations->update($dataUpdate, $where);
            if ($update > 0) {
                $this->session->set_flashdata('success', 'Data berhasil di update');
            } else {
                $this->session->set_flashdata('error', 'Server sedang sibuk, silahkan coba lagi');
            }
            redirect('admin/registrations/detail/' . $uri);
        } else {
            $this->session->set_flashdata('error', 'Tidak ada data yang di pilih');
        }
        redirect('admin/registrations/detail/' . $uri);
    }

    public function updateanothergroup($uri)
    {
        $academic       = $this->Config->getDataAcademicYear(['status' => 1])->row();
        $academicId     = $academic->id;
        $leader         = htmlspecialchars($this->input->post('leadergroup'));
        $studentId      = htmlspecialchars($this->input->post('id'));
        $getData        = $this->Registrations->getDataBy(['a.group_id' => $leader])->row();
        $this->db->set('id', 'UUID()', FALSE);
        $dataMember   = [
            'group_id'          => htmlspecialchars($this->input->post('leadergroup')),
            'company_id'        => $getData->company_id,
            'start_date'        => $getData->start_date,
            'finish_date'       => $getData->finish_date,
            'student_id'        => $studentId,
            'status'            => 'Anggota',
            'prodi_id'          => $getData->prodi_id,
            'group_status'      => $getData->group_status,
            'academic_year_id'  => $academicId,
            'verify_member'     => 'Diterima'
        ];
        $insertLeader       = $this->Registrations->insert($dataMember);
        if ($insertLeader > 0) {
            $this->session->set_flashdata('success', 'Data Berhasil di simpan');
        } else {
            $this->session->set_flashdata('error', 'Server sedang sibuk, silahkan coba lagi');
        }
        redirect('admin/registrations/detail/' . $uri);
    }


    //Sementara ga kepake
    public function creategroup()
    {
        $leaderId   = $this->input->post('leaderId');
        $companyId  = $this->input->post('companyId');
        $memberId   = $this->input->post('memberId');
        $startDate  = $this->input->post('startDate');
        $finishDate = $this->input->post('finishDate');
        $cekCompany = $this->Registrations->getDataCompanyBy(['id' => $companyId])->row();

        if ($cekCompany->label === 'prodi') {
            $cekPeriode                 = $this->Registrations->getDataPeriode()->row();
            $cekCompanyInRegistration   = $this->Registrations->getDataCompanyInRegistration(['company_id' => $companyId], $cekPeriode->start_time, $cekPeriode->finish_time);
            if ($cekCompanyInRegistration->num_rows() > 0) {
                $status['message'] = 'failedcompany';
            } else {
                $status                   = $this->_insertRegistration($leaderId, $startDate, $finishDate, $companyId, $memberId);
            }
        } else {
            $status = $this->_insertRegistration($leaderId, $startDate, $finishDate, $companyId, $memberId);
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }
    private function _insertRegistration($leaderId, $startDate, $finishDate, $companyId, $memberId)
    {
        $prodiLeader    = $this->Registrations->getDataStudentBy(['id' => $leaderId])->row();
        $academic       = $this->Config->getDataAcademicYear(['status' => 1])->row();
        $academicId     = $academic->id;
        $groupId        = strtotime($startDate) . $leaderId;
        $this->db->set('id', 'UUID()', FALSE);
        $dataInsertLeader   = [
            'group_id'          => $groupId,
            'company_id'        => $companyId,
            'start_date'        => $startDate,
            'finish_date'       => $finishDate,
            'student_id'        => $leaderId,
            'status'            => 'Ketua',
            'prodi_id'          => $prodiLeader->prodi_id,
            'academic_year_id'  => $academicId,
            'verify_member'     => 'Diterima'
        ];
        $insertLeader       = $this->Registrations->insert($dataInsertLeader);
        $status             = [];
        if ($insertLeader > 0) {
            //insert member
            if ($memberId !== null) {
                $insert         = 0;
                foreach ($memberId as $member) {
                    $prodiMember    = $this->Registrations->getDataStudentBy(['id' => $member])->row();
                    $this->db->set('id', 'UUID()', FALSE);
                    $dataInsertMember   = [
                        'group_id'          => $groupId,
                        'company_id'        => $companyId,
                        'start_date'        => $startDate,
                        'finish_date'       => $finishDate,
                        'student_id'        => $member,
                        'status'            => 'Anggota',
                        'prodi_id'          => $prodiMember->prodi_id,
                        'group_status'      => 'belum_terverifikasi',
                        'academic_year_id'  => $academicId,
                    ];
                    $this->Registrations->insert($dataInsertMember);
                    $insert++;
                }
                if ($insert > 0) {
                    $status['message']  = 'success';
                } else {
                    $status['message']  = 'failedmember';
                }
            } else {
                $status['message']  = 'success';
            }
        } else {
            $status['message']  = 'failed';
        }
        return $status;
    }

    public function getmember()
    {
        $leaderId   = $this->input->post("leaderId");
        $prodiId    = $this->input->post("prodiId");
        $data       = $this->Config->getStudent(null, $prodiId, $leaderId)->result();
        $output     = "";
        foreach ($data as $student) {
            $output     .= "
                <tr>
                    <td><input type='checkbox' class='member-checkbox' name='member[]' value='" . $student->id . "'></td>
                    <td>" . $student->fullname . "</td>
                    <td>" . $student->prodi_name . "</td>
                </tr>
            ";
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }
    //Akhir ga kepake

}
