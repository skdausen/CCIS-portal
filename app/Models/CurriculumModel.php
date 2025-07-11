<?php
namespace App\Models;

use CodeIgniter\Model;

class CurriculumModel extends Model
{
    protected $table = 'curriculums';
    protected $primaryKey = 'curriculum_id';
    protected $allowedFields = ['curriculum_name', 'subject_id', 'program_id', 'year_level', 'course_semester'];

    public function getSubjects($yearLevel = null, $semester = null)
    {
        return $this->db->table('subjects AS s')
            ->select('s.subject_code, s.subject_name, s.lec_units, s.lab_units, cu.year_level, cu.course_semester')
            ->join('curriculums AS cu', 'cu.subject_id = s.subject_id', 'left')
            ->when($yearLevel, function ($query) use ($yearLevel) {
                return $query->where('cu.year_level', $yearLevel);
            })
            ->when($semester, function ($query) use ($semester) {
                return $query->where('cu.course_semester', $semester);
            })
            ->orderBy('s.subject_code', 'ASC')
            ->get()
            ->getResult();
    }
}
