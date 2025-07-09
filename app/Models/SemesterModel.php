<?php

// app/Models/SemesterModel.php
namespace App\Models;

use CodeIgniter\Model;

class SemesterModel extends Model
{
    protected $table = 'semesters';
    protected $primaryKey = 'semester_id';
    protected $allowedFields = ['semester', 'schoolyear_id', 'is_active'];

// SemesterModel.php
    public function getSemWithDetails()
{
    return $this->select('semesters.semester_id, semesters.semester, semesters.is_active, schoolyears.schoolyear')
                ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
                ->findAll();
}

    public function getSemestersBySchoolYear($schoolyearId)
    {
        return $this->where('schoolyear_id', $schoolyearId)->findAll();
    }
}
