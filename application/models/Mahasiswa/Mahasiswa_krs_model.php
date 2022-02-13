  <?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Mahasiswa_krs_model extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
            $this->table = 'krs';
            $this->tableDetail = 'detail_krs';
            $this->tableStudent = 'student';
            $this->tableCourse = 'course';
            $this->tableProdi = 'prodi';
            $this->tableAcademicYear = 'academic_year';
            //TAMBAHAN DARI AGUNG
            $this->tableLecture = 'lecture';
        }

        public function getActiveAcademicYear()
        {
            $this->db->select('*');
            return $this->db->get_where($this->tableAcademicYear, ['status' => 1])->row();
        }

        public function getAcademicYear()
        {
            $this->db->select('*');
            return $this->db->get($this->tableAcademicYear)->result();
        }

        public function getKrsCurrentbyId($id)
        {
            $this->db->select('a.*, b.fullname, b.npm, d.name prodi_name, d.degree, c.name academic_year_name, c.semester academic_year_semester,e.name lecture');
            $this->db->join($this->tableStudent . ' b', 'a.student_id=b.id', 'LEFT');
            $this->db->join($this->tableAcademicYear . ' c', 'a.academic_year_id=c.id', 'LEFT');
            $this->db->join($this->tableProdi . ' d', 'b.prodi_id=d.id', 'LEFT');
            $this->db->join($this->tableLecture . ' e', ' b.lecture_id=e.id', 'LEFT');
            return $this->db->get_where($this->table . ' a', ['a.id' => $id]);
        }

        public function getKrsCurrent($academicYearId, $studentId)
        {
            $this->db->select('a.*, b.fullname, b.npm, d.name prodi_name, d.degree, c.name academic_year_name, c.semester academic_year_semester,e.name lecture');
            $this->db->join($this->tableStudent . ' b', 'a.student_id=b.id', 'LEFT');
            $this->db->join($this->tableAcademicYear . ' c', 'a.academic_year_id=c.id', 'LEFT');
            $this->db->join($this->tableProdi . ' d', 'b.prodi_id=d.id', 'LEFT');
            $this->db->join($this->tableLecture . ' e', ' b.lecture_id=e.id', 'LEFT');
            $this->db->where('a.academic_year_id', $academicYearId);
            return $this->db->get_where($this->table . ' a', ['a.student_id' => $studentId]);
        }

        public function getKrsCurrentCourseTaken($academicYearId, $studentId)
        {
            $this->db->select('a.id, c.code, c.name, c.semester, c.sks');
            $this->db->join($this->table . ' b', 'a.krs_id=b.id', 'LEFT');
            $this->db->join($this->tableCourse . ' c', 'a.course_id=c.id', 'LEFT');
            $this->db->where('b.student_id', $studentId);
            $this->db->where('b.academic_year_id', $academicYearId);
            $this->db->order_by('c.semester', 'ASC');
            return $this->db->get($this->tableDetail . ' a');
        }

        public function getCoursebyId($id)
        {
            return $this->db->get_where($this->tableCourse, ['id' => $id]);
        }

        public function getKrsCourseTakenbyId($id)
        {
            $this->db->select('a.*, b.sks');
            $this->db->join($this->tableCourse . ' b', 'a.course_id=b.id', 'LEFT');
            return $this->db->get_where($this->tableDetail . ' a', ['a.id' => $id]);
        }

        public function getKrsAllCourseTaken($studentId)
        {
            $this->db->select('a.*');
            $this->db->join($this->table . ' b', 'a.krs_id=b.id', 'LEFT');
            $this->db->where('b.student_id', $studentId);
            return $this->db->get($this->tableDetail . ' a');
        }

        public function getKrsCoursesOddEven($prodiId, $semesteracademic, $studentId)
        {

            $strOddEven = ($semesteracademic == 'Genap') ? "(semester % 2) = 0" : "(semester % 2) > 0";
            return $this->db->query("SELECT * FROM `course` 
                                WHERE `prodi_id` = '$prodiId'
                                AND `id` 
                                NOT IN(
                                    SELECT `a`.course_id FROM `detail_krs` `a` 
                                    LEFT JOIN `krs` `b` ON `a`.`krs_id`=`b`.`id` 
                                    WHERE `b`.`student_id` = '$studentId'
                                ) 
                                AND $strOddEven");
        }

        public function getlatestKrsbyStudentId($studentId)
        {
            $this->db->select('a.*, b.fullname, b.npm, d.name prodi_name, d.degree, c.name academic_year_name, c.semester academic_year_semester');
            $this->db->join($this->tableStudent . ' b', 'a.student_id=b.id', 'LEFT');
            $this->db->join($this->tableAcademicYear . ' c', 'a.academic_year_id=c.id', 'LEFT');
            $this->db->join($this->tableProdi . ' d', 'b.prodi_id=d.id', 'LEFT');
            $this->db->where('a.status', 'verified');
            $this->db->limit(1);
            $this->db->order_by('a.semester', 'DESC');
            return $this->db->get_where($this->table . ' a', ['a.student_id' => $studentId]);
        }

        public function getKrsbySemester($studentId, $semester)
        {
            $this->db->select('a.*, b.fullname, b.npm, d.name prodi_name, d.degree, c.name academic_year_name, c.semester academic_year_semester');
            $this->db->join($this->tableStudent . ' b', 'a.student_id=b.id', 'LEFT');
            $this->db->join($this->tableAcademicYear . ' c', 'a.academic_year_id=c.id', 'LEFT');
            $this->db->join($this->tableProdi . ' d', 'b.prodi_id=d.id', 'LEFT');
            $this->db->where('a.status', 'verified');
            $this->db->where('a.semester', $semester);
            $this->db->limit(1);
            $this->db->order_by('a.semester', 'DESC');
            return $this->db->get_where($this->table . ' a', ['a.student_id' => $studentId]);
        }

        public function getSumKrs($studentId, $semester = null)
        {
            $this->db->select('sum(kredit) total_kredit, ROUND(sum(ip)/ count(ip),2) ipk');
            $this->db->where('status', 'verified');
            if ($semester)
                $this->db->where('semester <', $semester);
            $this->db->group_by('student_id');
            $query = $this->db->get_where($this->table, ['student_id' => $studentId]);
            return $query->row();
        }

        public function getKhs($studentId)
        {
            $this->db->select('a.id, a.semester, c.name academic_year_name, c.semester academic_year_semester');
            $this->db->join($this->tableAcademicYear . ' c', 'a.academic_year_id=c.id', 'LEFT');
            $this->db->where('a.status', 'verified');
            $this->db->order_by('a.semester', 'ASC');
            return $this->db->get_where($this->table . ' a', ['a.student_id' => $studentId]);
        }

        public function getKhsbyId($id, $studentId)
        {
            $this->db->select('a.*, b.fullname, b.npm, d.name prodi_name, d.degree, c.name academic_year_name, c.semester academic_year_semester, e.name lecture');
            $this->db->join($this->tableStudent . ' b', 'a.student_id=b.id', 'LEFT');
            $this->db->join($this->tableAcademicYear . ' c', 'a.academic_year_id=c.id', 'LEFT');
            $this->db->join($this->tableProdi . ' d', 'b.prodi_id=d.id', 'LEFT');
            $this->db->join($this->tableLecture . ' e', 'b.lecture_id=e.id', 'LEFT');
            $this->db->where('a.student_id', $studentId);
            return $this->db->get_where($this->table . ' a', ['a.id' => $id]);
        }

        public function getKhsCourseTakenbyKhsId($khsId)
        {
            $this->db->select('a.id, a.grade, a.score, a.description, c.code, c.name, c.sks');
            $this->db->join($this->tableCourse . ' c', 'a.course_id=c.id', 'LEFT');
            $this->db->where('a.krs_id', $khsId);
            $this->db->order_by('c.semester', 'ASC');
            return $this->db->get($this->tableDetail . ' a');
        }

        public function insert($data)
        {
            $this->db->set('id', 'UUID()', FALSE);
            $this->db->insert($this->table, $data);
            return $this->db->affected_rows();
        }

        public function insertDetail($data)
        {
            $this->db->set('id', 'UUID()', FALSE);
            $this->db->insert($this->tableDetail, $data);
            return $this->db->affected_rows();
        }

        public function update($data)
        {
            $this->db->set('updated_at', date('Y-m-d H:i:s'));
            $this->db->update($this->table, $data, ['id' => $data['id']]);
            return $this->db->affected_rows();
        }

        public function updateDetail($data)
        {
            $this->db->set('updated_at', date('Y-m-d H:i:s'));
            $this->db->update($this->tableDetail, $data, ['id' => $data['id']]);
            return $this->db->affected_rows();
        }

        public function deleteDetail($id)
        {
            $this->db->delete($this->tableDetail, ['id' => $id]);
            return $this->db->affected_rows();
        }
    }
