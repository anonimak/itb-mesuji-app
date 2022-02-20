<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Mpdf\Tag\P;
use Mpdf\Tag\Th;

class Admin_students extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin/Admin_students_model', 'Student');
		$this->load->model('Admin/Admin_config_model', 'Config');
		$this->load->model('Admin/Admin_lecture_model', 'Lecture');
		$this->role         = 'admin';
		$this->redirect     = 'admin/master/student';
		cek_login('Admin');
	}

	public function index()
	{
		$data = [
			'title'         => 'Data Mahasiswa',
			'desc'          => 'Berfungsi untuk melihat Data Mahasiswa',
			'students'      => $this->Student->getAllData()->result()
		];

		$page = '/admin/student/index';
		pageBackend($this->role, $page, $data);
	}

	public function create()
	{
		$this->_validation();
		if ($this->form_validation->run() === false) {
			$data = [
				'allprodi'			=> $this->Student->getProdi()->result(),
				'title'         => 'Tambah Data Mahasiswa',
				'desc'          => 'Berfungsi untuk menambah Data Mahasiswa',
			];

			$page = '/admin/student/create';
			pageBackend($this->role, $page, $data);
		} else {
			$npm            = htmlspecialchars($this->input->post('npm'));
			$academic       = $this->Config->getDataAcademicYear(['status' => 1])->row();
			$academicId     = $academic->id;
			$this->db->set('id', 'UUID()', FALSE);
			$dataInputStudent = [
				'fullname'          => htmlspecialchars($this->input->post('fullname')),
				'npm'               => $npm,
				'email'             => htmlspecialchars($this->input->post('email')),
				'password'					=> password_hash('123456', PASSWORD_DEFAULT),
				'prodi_id'          => htmlspecialchars($this->input->post('prodi')),
				'lecture_id'				=> htmlspecialchars($this->input->post('dosen-pembimbing')),
				'address'           => htmlspecialchars($this->input->post('address')),
				'birth_date'        => htmlspecialchars($this->input->post('birthdate')),
				'no_hp'             => htmlspecialchars($this->input->post('nohp')),
				'academic_year_id'  => $academicId,
				'status'            => htmlspecialchars('active'),
			];
			$insertStudent      = $this->Student->insert($dataInputStudent);
			if ($insertStudent > 0) {
				$this->session->set_flashdata('success', 'Data berhasil di tambah');
			} else {
				$this->session->set_flashdata('error', 'Server Data Prodi sedang sibuk, silahkan coba lagi');
			}
			redirect($this->redirect);
		}
	}

	public function update($id)
	{
		$decode     = decodeEncrypt($id);
		$student    = $this->Student->getDataBy(['a.id' => $decode])->row();
		if ($student) {
			$oldNpm     = $student->npm;
			$this->_validation($oldNpm, $student->email);
			if ($this->form_validation->run() === false) {
				$data = [
					'allprodi'			=> $this->Student->getProdi()->result(),
					'title'         => 'Ubah Data Mahasiswa',
					'desc'          => 'Berfungsi untuk mengubah Data Mahasiswa',
					'student'       => $student,
				];
				$page = '/admin/student/update';
				pageBackend($this->role, $page, $data);
			} else {
				$npm          = htmlspecialchars($this->input->post('npm'));
				$dataUpdateStudent = [
					'fullname'      => htmlspecialchars($this->input->post('fullname')),
					'npm'           => $npm,
					'email'         => htmlspecialchars($this->input->post('email')),
					'prodi_id'      => htmlspecialchars($this->input->post('prodi')),
					'lecture_id'		=> htmlspecialchars($this->input->post('dosen-pembimbing')),
					'address'       => htmlspecialchars($this->input->post('address')),
					'birth_date'    => htmlspecialchars($this->input->post('birthdate')),
					'no_hp'         => htmlspecialchars($this->input->post('nohp')),
					'status'        => htmlspecialchars($this->input->post('status')),
					'updated_at'    => date('Y-m-d H:i:s')
				];
				$updateStudent      = $this->Student->update($dataUpdateStudent, ['id' => $decode]);
				if ($updateStudent > 0) {
					$this->session->set_flashdata('success', 'Data berhasil di ubah');
				} else {
					$this->session->set_flashdata('error', 'Server Data Dosen sedang sibuk, silahkan coba lagi');
				}
				redirect($this->redirect);
			}
		} else {
			$this->session->set_flashdata('error', 'Data yang anda masukan tidak ada');
			redirect($this->redirect);
		}
	}

	public function delete($id)
	{
		$decodeId   = decodeEncrypt($id);
		$student    = $this->Student->getDataBy(['a.id' => $decodeId])->row();
		if ($student) {
			$deleteStudent    = $this->Student->delete(['id' => $decodeId]);
			if ($deleteStudent > 0) {
				$this->session->set_flashdata('success', 'Data berhasil di hapus');
			} else {
				$this->session->set_flashdata('error', 'Server data mahasiswa sedang sibuk, silahkan coba lagi');
			}
		} else {
			$this->session->set_flashdata('error', 'Data yang anda masukan tidak ada');
		}
		redirect($this->redirect);
	}

	public function resetpassword($id)
	{
		$decode     = decodeEncrypt($id);
		$student    = $this->Student->getDataBy(['a.id' => $decode])->row();
		if ($student) {
			$dataUpdate	= [
				'password'					=> password_hash('123456', PASSWORD_DEFAULT),
				'updated_at'				=> date('Y-m-d H:i:s')
			];
			$updateStudent      = $this->Student->update($dataUpdate, ['id' => $decode]);
			if ($updateStudent > 0) {
				$this->session->set_flashdata('success', 'Password berhasil di reset');
			} else {
				$this->session->set_flashdata('error', 'Server Data Dosen sedang sibuk, silahkan coba lagi');
			}
			redirect($this->redirect);
		} else {
			$this->session->set_flashdata('error', 'Data yang anda masukan tidak ada');
			redirect($this->redirect);
		}
	}

	public function krs($id)
	{
		$decode     = decodeEncrypt($id);
		$student    = $this->Student->getDataBy(['a.id' => $decode])->row();
		if ($student) {
			$krs			= $this->Student->getDataKRSBy(['a.student_id' => $decode])->result();
			$data = [
				'title'         => 'KRS Mahasiswa',
				'desc'          => 'Berfungsi untuk Melihat Data KRS Mahasiswa',
				'student'       => $student,
				'datakrs'				=> $krs,
			];
			$page = '/admin/student/krs';
			pageBackend($this->role, $page, $data);
		} else {
			$this->session->set_flashdata('error', 'Data yang anda masukan tidak ada');
			redirect($this->redirect);
		}
	}

	public function detailkrs($krsId)
	{
		$decode     = decodeEncrypt($krsId);
		$krs    = $this->Student->getDataKRSBy(['a.id' => $decode])->row();
		if ($krs) {
			$detailKrs						= $this->Student->getDataDetailKRSBy(['a.krs_id' => $decode])->result();
			$semeterLalu					= $krs->semester - 1;
			$ipSemesterLalu				= $this->Student->getDataKRSBy(['a.student_id =' => $krs->student_id, 'a.semester' => $semeterLalu]);
			$totalKreditTercapai	= $this->Student->getDataKRSBy(['a.student_id =' => $krs->student_id]);
			$totalKredit					= 0;
			if ($totalKreditTercapai->num_rows() > 0) {
				foreach ($totalKreditTercapai->result() as $skstercapai) {
					if ($skstercapai->semester < $krs->semester) {
						$totalKredit		+= $skstercapai->kredit;
					}
				}
			}
			if ($ipSemesterLalu->num_rows() > 0) {
				$ipSebelumnya 	= $ipSemesterLalu->row()->ip;
			} else {
				$ipSebelumnya	= 0;
			}
			$data = [
				'title'         => 'KRS Mahasiswa',
				'desc'          => 'Berfungsi untuk Melihat Data KRS Mahasiswa',
				'krs'						=> $krs,
				'detailKrs'			=> $detailKrs,
				'ipSebelumnya'	=> $ipSebelumnya,
				'totalKreditTercapai'	=> $totalKredit,
				'__backurl'		=> base_url('admin/master/student/krs/' . encodeEncrypt($krs->student_id))
			];
			$page = '/admin/student/detailkrs';
			pageBackend($this->role, $page, $data);
		} else {
			$this->session->set_flashdata('error', 'Data yang anda masukan tidak ada');
			redirect($this->redirect);
		}
	}


	public function import()
	{
		$data = [
			'title'         => 'Import Data Mahasiswa',
			'desc'          => 'Berfungsi untuk menambah banyak Data Mahasiswa',
		];

		$page = '/admin/student/import';
		pageBackend($this->role, $page, $data);
	}

	public function importstudent()
	{
		$config = [
			'upload_path'       => './assets/uploads/',
			'allowed_types'     => 'xlsx|xls',
			'file_name'         => 'doc' . time(),
		];
		$this->upload->initialize($config);
		if ($this->upload->do_upload('importstudent')) {
			$file   = $this->upload->data();
			$reader = ReaderEntityFactory::createXLSXReader();
			$reader->open('assets/uploads/' . $file['file_name']);

			foreach ($reader->getSheetIterator() as $sheet) {
				$numRow = 1;
				foreach ($sheet->getRowIterator() as $row) {
					if ($numRow > 1) {
						// $email      = $this->Student->getDataBy(['a.email' => $row->getCellAtIndex(2)])->num_rows();
						$npm            = $this->Student->getDataBy(['a.npm' => $row->getCellAtIndex(1)])->num_rows();
						$academic       = $this->Config->getDataAcademicYear(['status' => 1])->row();
						$academicId     = $academic->id;

						if ($npm < 1) {
							$dataInputStudent = array(
								'fullname'          => $row->getCellAtIndex(0),
								'npm'               => $row->getCellAtIndex(1),
								'email'             => $row->getCellAtIndex(2),
								'password'          => password_hash('123456', PASSWORD_DEFAULT),
								'prodi_id'          => $row->getCellAtIndex(3),
								'address'           => $row->getCellAtIndex(4),
								'birth_date'        => $row->getCellAtIndex(5),
								'no_hp'             => $row->getCellAtIndex(6),
								'academic_year_id'  => $academicId,
								'status'            => htmlspecialchars('active'),
							);
							$this->Student->importData($dataInputStudent);
						}
					}
					$numRow++;
				}
				$reader->close();
				unlink(FCPATH . 'assets/uploads/' . $file['file_name']);
				$this->session->set_flashdata('success', 'Import Data Berhasil');
				redirect($this->redirect);
			}
		} else {
			echo "Error :" . $this->upload->display_errors();
		}
	}

	public function export()
	{
		$data   =   [
			'title'             => 'Data Mahasiswa',
			'allData'           => $this->Student->getAllData()->result()
		];

		$this->load->view('admin/student/export/excel', $data);
	}

	public function getDosenPembimbing()
	{
		$prodiId = $this->input->post('prodiId');
		$lectureId	= $this->input->post('lectureId');
		$data = $this->Lecture->getDataBy(['prodi_id' => $prodiId])->result();
		$output = '<option value=""></option>';
		foreach ($data as $row) {
			if ($lectureId === $row->id) {
				$output .= '<option value="' . $row->id . '" selected> ' . $row->name . '</option>';
			} else {
				$output .= '<option value="' . $row->id . '"> ' . $row->name . '</option>';
			}
			// $output .= '<option value="' . $row->id . '"> ' . $row->name . '</option>';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	public function byid($studentId)
	{
		$student    = $this->Student->getDataBy(['a.id' => $studentId])->row();
		outputJson($student);
	}

	private function _validation($npm = null, $email = null)
	{
		if ($this->input->post('npm') !== $npm) {
			$is_unique  = '|is_unique[student.npm]';
		} else {
			$is_unique  = '';
		}
		$this->form_validation->set_rules(
			'npm',
			'NPM',
			'trim|required|max_length[8]|min_length[8]' . $is_unique,
			[
				'required' => '%s wajib di isi',
				'min_length'	=> '%s minimal 8 karakter',
				'max_length'	=> '%s maksimal 8 karekter'
			]
		);

		$this->form_validation->set_rules(
			'fullname',
			'Nama Lengkap',
			'trim|required',
			[
				'required' => '%s wajib di isi',
			]
		);

		if ($this->input->post('email') !== $email) {
			$is_unique  = '|is_unique[student.email]';
		} else {
			$is_unique  = '';
		}
		$this->form_validation->set_rules(
			'email',
			'Email',
			'trim|valid_email' . $is_unique,
			[
				'required'      => '%s wajib di isi',
				'valid_email'   => 'Format %s salah'
			]
		);

		// $this->form_validation->set_rules(
		//     'birthdate',
		//     'Tanggal Lahir',
		//     'trim|required',
		//     [
		//         'required' => '%s wajib di isi',
		//     ]
		// );

		// $this->form_validation->set_rules(
		//     'address',
		//     'Alamat',
		//     'trim|required',
		//     [
		//         'required' => '%s wajib di isi',
		//     ]
		// );

		// $this->form_validation->set_rules(
		//     'nohp',
		//     'Nomor Handphone',
		//     'trim|required|numeric|min_length[10]|max_length[14]',
		//     [
		//         'required'      => '%s wajib di isi',
		//         'numeric'       => '%s wajib berisi angka',
		//     ]
		// );

		if ($this->input->post('prodi') === "") {
			$this->form_validation->set_rules(
				'prodi',
				'Program Studi',
				'trim|required',
				[
					'required' => '%s wajib di isi',
				]
			);
		}

		if ($this->input->post('dosen-pembimbing') === "") {
			$this->form_validation->set_rules(
				'dosen-pembimbing',
				'Dosen Pembimbing',
				'trim|required',
				[
					'required' => '%s wajib di isi',
				]
			);
		}
	}

	public function verifedkrs($krsId)
	{
		$decodeId   = decodeEncrypt($krsId);
		$student    = $this->Student->getDataKRSBy(['a.id' => $decodeId])->row();
		if ($student) {
			$updateKrs    = $this->Student->UpdateKrs(['status' => 'verified', 'updated_at' => date('Y-m-d H:i:s')], ['id' => $decodeId]);
			if ($updateKrs > 0) {
				$this->session->set_flashdata('success', 'Data berhasil di update');
			} else {
				$this->session->set_flashdata('error', 'Server data mahasiswa sedang sibuk, silahkan coba lagi');
			}
		} else {
			$this->session->set_flashdata('error', 'Data yang anda masukan tidak ada');
		}
		redirect('admin/master/student/krsdetail/' . $krsId);
	}

	public function krsreset($krsId)
	{
		$decodeId   = decodeEncrypt($krsId);
		$student    = $this->Student->getDataKRSBy(['a.id' => $decodeId])->row();
		if ($student) {
			$cekDetailKrs = $this->Student->getDataDetailKRSBy(['a.krs_id' => $decodeId, 'score !=' => 0]);
			if ($cekDetailKrs->num_rows() > 0) {
				//Tidak bisa di reset
				$this->session->set_flashdata('error', 'KRS sudah ada nilainya tidak bisa di reset');
			} else {
				$updateKrs    = $this->Student->UpdateKrs(['status' => 'edit', 'updated_at' => date('Y-m-d H:i:s')], ['id' => $decodeId]);
				if ($updateKrs > 0) {
					$this->session->set_flashdata('success', 'Data berhasil di update');
				} else {
					$this->session->set_flashdata('error', 'Server data mahasiswa sedang sibuk, silahkan coba lagi');
				}
			}
		} else {
			$this->session->set_flashdata('error', 'Data yang anda masukan tidak ada');
		}
		redirect('admin/master/student/krsdetail/' . $krsId);
	}

	public function updatekhs($detailKrsId)
	{
		$krsId	= $this->input->post('krsId');
		$grade 	= $this->input->post('grade');
		if ($grade === 'A') {
			$value	= 4;
		}
		if ($grade === 'B') {
			$value	= 3;
		}
		if ($grade === 'C') {
			$value	= 2;
		}
		if ($grade === 'D') {
			$value	= 1;
		}
		if ($grade === 'E') {
			$value	= 0;
		}
		$kredit	= $this->input->post('credit');
		$score	= $value * (int)$kredit;
		$desc 	= $this->input->post('description');
		$dataUpdate	= [
			'grade'				=> $grade,
			'score'				=> $score,
			'description'	=> $desc,
			'updated_at'	=> date('Y-m-d H:i:s')
		];
		$where	= [
			'id'	=> $detailKrsId
		];
		$updateKhs	= $this->Student->updateDetailKrs($dataUpdate, $where);
		if ($updateKhs > 0) {
			$detailKrs						= $this->Student->getDataDetailKRSBy(['a.krs_id' => $krsId])->result();
			$totalKredit	= 0;
			$totalScore		= 0;
			foreach ($detailKrs as $updateIp) {
				$totalKredit	+= $updateIp->sks;
				$totalScore		+= $updateIp->score;
			}
			$ipSemester	= round(($totalScore / $totalKredit), 2);
			$updateIpKhs	= $this->Student->UpdateKrs(['ip' => $ipSemester, 'updated_at' => date('Y-m-d H:i:s')], ['id' => $krsId]);
			if ($updateIpKhs > 0) {
				$this->session->set_flashdata('success', 'Data berhasil di update');
			} else {
				$this->session->set_flashdata('error', 'Server data KRS sedang sibuk, silahkan coba lagi');
			}
		} else {
			$this->session->set_flashdata('error', 'Server data Detail KRS sedang sibuk, silahkan coba lagi');
		}
		redirect('admin/master/student/krsdetail/' . encodeEncrypt($krsId));
	}
}
