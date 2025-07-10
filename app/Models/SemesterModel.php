<?php

namespace App\Models;

use CodeIgniter\Model;

class SemesterModel extends Model
{
    protected $table = 'semesters';
    protected $primaryKey = 'semester_id';
    protected $allowedFields = ['semester', 'schoolyear_id', 'is_active'];

    public function getSemWithDetails()
    {
        return $this->select('semesters.semester_id, semesters.semester, semesters.is_active, schoolyears.schoolyear')
                    ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
                    ->orderBy('semesters.is_active', 'DESC')
                    ->orderBy('schoolyears.schoolyear', 'DESC')
                    ->orderBy('semesters.semester', 'ASC')
                    ->findAll();
    }

    public function getSemestersBySchoolYear($schoolyearId)
    {
        return $this->where('schoolyear_id', $schoolyearId)->findAll();
    }

    public function getActiveSemester()
    {
        return $this->select('semester_id, semester, schoolyear_id, is_active')
                    ->where('is_active', 1)
                    ->first();
    }
}
