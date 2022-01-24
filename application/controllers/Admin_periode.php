<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_periode extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin/Admin_periode_model', 'Periode');
        $this->load->model('Admin/Admin_config_model', 'Config');
        $this->role         = 'admin';
        $this->redirect     = 'admin/config';
        cek_login('Admin');
    }

    //REGISTRASI
    public function index($type)
    {
        if ($type === 'registration_period') {
            $type   = ['type' => '3'];
            $title  = 'Data Periode Pendaftaran PKL';
            $desc   = 'Berfungsi untuk melihat Data Periode Pendaftaran PKL';
        } else if ($type === 'location_period') {
            $type   = ['type' => '1'];
            $title  = 'Data Periode Pendaftaran Lokasi PKL';
            $desc   = 'Berfungsi untuk melihat Data Periode Pendaftaran Lokasi PKL';
        } else {
            //location verification
            $type   = ['type' => '2'];
            $title  = 'Data Periode Verifikasi Pendaftaran Lokasi PKL';
            $desc   = 'Berfungsi untuk melihat Data Periode Verifikasi Pendaftaran Lokasi PKL';
        }
        $allPeriode         = $this->Periode->getAllData($type)->result();
        $activePeriode      = $this->Periode->getDataBy('active-periode', $type)->row();

        $data = [
            'title'         => $title,
            'desc'          => $desc,
            'allPeriode'    => $allPeriode,
            'activePeriode' => $activePeriode
        ];
        $page = '/admin/periode/index';
        pageBackend($this->role, $page, $data);
    }

    public function create($type)
    {
        $this->_validation();
        if ($this->form_validation->run() === FALSE) {
            if ($type === 'registration_period') {
                $type   = ['type' => '3'];
                $title  = 'Tambah Data Periode Pendaftaran PKL';
                $desc   = 'Berfungsi untuk menambah Data Periode Pendaftaran PKL';
            } else if ($type === 'location_period') {
                $type   = ['type' => '1'];
                $title  = 'Tambah Data Periode Pendaftaran Lokasi PKL';
                $desc   = 'Berfungsi untuk menambah Data Periode Pendaftaran Lokasi PKL';
            } else {
                //location verification
                $type   = ['type' => '2'];
                $title  = 'Data Periode Verifikasi Pendaftaran Lokasi PKL';
                $desc   = 'Berfungsi untuk menambah Data Periode Verifikasi Pendaftaran Lokasi PKL';
            }
            $data = [
                'title'         => $title,
                'desc'          => $desc,
            ];
            $page = '/admin/periode/create';
            pageBackend($this->role, $page, $data);
        } else {
            $this->db->set('id', 'UUID()', FALSE);
            if ($type === 'registration_period') {
                $quantity       = htmlspecialchars($this->input->post('quantity'));
                if ($quantity) {
                    $quantity   = $quantity;
                } else {
                    $quantity   = '3';
                }
                $typePeriode    = '3';
                $startPkl       = htmlspecialchars($this->input->post('starttimepkl'));
                $finishPkl      = htmlspecialchars($this->input->post('finishtimepkl'));
            } else if ($type === 'location_period') {
                $quantity   = '0';
                $typePeriode    = '1';
            } else {
                $quantity   = '0';
                $typePeriode    = '2';
            }
            $academic       = $this->Config->getDataAcademicYear(['status' => 1])->row();
            $academicId     = $academic->id;
            $dataInsert = [
                'title'             => htmlspecialchars($this->input->post('title')),
                'quantity'          => $quantity,
                'type'              => $typePeriode,
                'start_time'        => htmlspecialchars($this->input->post('starttime')),
                'finish_time'       => htmlspecialchars($this->input->post('finishtime')),
                'start_time_pkl'    => $startPkl,
                'finish_time_pkl'   => $finishPkl,
                'academic_year_id'  => htmlspecialchars($academicId)
            ];
            $insert     = $this->Periode->insert($dataInsert);
            if ($insert > 0) {
                $this->session->set_flashdata('success', 'Data berhasil di tambah');
            } else {
                $this->session->set_flashdata('error', 'Server Data User Sedang sibuk, silahkan coba lagi');
            }
            redirect('admin/config/' . $type);
        }
    }

    public function update($type, $id)
    {
        $this->_validation();
        $decodeId   = decodeEncrypt($id);
        if ($this->form_validation->run() === false) {
            $rowData    = $this->Periode->getWhere(['id' => $decodeId])->row();
            if ($type === 'registration_period') {
                $type   = ['type' => '3'];
                $title  = 'Ubah Data Periode Pendaftaran PKL';
                $desc   = 'Berfungsi untuk mengubah Data Periode Pendaftaran PKL';
            } else if ($type === 'location_period') {
                $type   = ['type' => '1'];
                $title  = 'Ubah Data Periode Pendaftaran Lokasi PKL';
                $desc   = 'Berfungsi untuk mengubah Data Periode Pendaftaran Lokasi PKL';
            } else {
                //location verification
                $type   = ['type' => '2'];
                $title  = 'Ubah Data Periode Verifikasi Pendaftaran Lokasi PKL';
                $desc   = 'Berfungsi untuk mengubah Data Periode Verifikasi Pendaftaran Lokasi PKL';
            }
            $data = [
                'title'         => $title,
                'desc'          => $desc,
                'data'          => $rowData,
            ];
            $page = '/admin/periode/update';
            pageBackend($this->role, $page, $data);
        } else {
            $dataUpdate = [
                'title'             => htmlspecialchars($this->input->post('title')),
                'quantity'          => htmlspecialchars($this->input->post('quantity')),
                'start_time'        => htmlspecialchars($this->input->post('starttime')),
                'finish_time'       => htmlspecialchars($this->input->post('finishtime')),
                'start_time_pkl'    => htmlspecialchars($this->input->post('starttimepkl')),
                'finish_time_pkl'   => htmlspecialchars($this->input->post('finishtimepkl')),
                'updated_at'        => date('Y-m-d H:i:s')
            ];
            $update = $this->Periode->update($dataUpdate, ['id' => $decodeId]);
            if ($update > 0) {
                $this->session->set_flashdata('success', 'Data berhasil di update');
            } else {
                $this->session->set_flashdata('error', 'Server Data User Sedang sibuk, silahkan coba lagi');
            }
            redirect('admin/config/' . $type);
        }
    }

    private function _validation()
    {
        $this->form_validation->set_rules(
            'title',
            'Judul',
            'trim|required',
            [
                'required'  => '%s wajib diisi',
            ]
        );

        if ($this->uri->segment(4) === 'registration_period') {
            $this->form_validation->set_rules(
                'starttimepkl',
                'Tanggal mulai PKL',
                'trim|required',
                [
                    'required'  => '%s wajib diisi',
                ]
            );

            $this->form_validation->set_rules(
                'finishtimepkl',
                'Tanggal berakhir PKL',
                'trim|required',
                [
                    'required'  => '%s wajib diisi',
                ]
            );
        }

        $this->form_validation->set_rules(
            'starttime',
            'Tanggal Mulai',
            'trim|required',
            [
                'required'  => '%s wajib diisi',
            ]
        );

        $this->form_validation->set_rules(
            'finishtime',
            'Tanggal Selesai',
            'trim|required|callback_endtime_check',
            [
                'required'  => '%s wajib diisi',
            ]
        );
    }

    public function endtime_check()
    {
        if (strtotime($this->input->post('finishtime')) <= strtotime($this->input->post('starttime'))) {
            $this->form_validation->set_message(__FUNCTION__, 'Tanggal Selesai lebih kecil atau sama dengan dari tanggal mulai');
            return false;
        } else {
            return true;
        }
    }
}
