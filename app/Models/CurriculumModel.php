<?php
namespace App\Models;

use CodeIgniter\Model;

class CurriculumModel extends Model
{
    protected $table = 'curriculums';
    protected $primaryKey = 'curriculum_id';
    protected $allowedFields = ['curriculum_name', 'course_id', 'program_id', 'year_level', 'course_semester'];

    // ✅ Add this method here
    public function getCourses($yearLevel = null, $semester = null)
    {
        return $this->db->table('course AS co')
            ->select('co.course_code, co.course_description, co.lec_units, co.lab_units, cu.year_level, cu.course_semester')
            ->join('curriculums AS cu', 'cu.course_id = co.course_id', 'left')
            ->when($yearLevel, function ($query) use ($yearLevel) {
                return $query->where('cu.year_level', $yearLevel);
            })
            ->when($semester, function ($query) use ($semester) {
                return $query->where('cu.course_semester', $semester);
            })
            ->orderBy('co.course_code', 'ASC')
            ->get()
            ->getResult();
    }
}
