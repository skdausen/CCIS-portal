<?php

// app/Models/SemesterModel.php
namespace App\Models;

use CodeIgniter\Model;

class SemesterModel extends Model
{
    protected $table = 'semesters';
    protected $primaryKey = 'semester_id';
    protected $allowedFields = ['semester', 'schoolyear_id'];

    public function getSemWithDetails()
    {
        return $this->select('classes.*, schoolyears.schoolyear')
                    ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
                    ->findAll();
    }
}
