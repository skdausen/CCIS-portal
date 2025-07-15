<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'stb_id';
        protected $allowedFields    = [
        'student_id',
        'user_id',
        'email',
        'profimg',
        'lname',
        'fname',
        'mname',
        'sex',
        'address',
        'birthdate',
        'contactnum',
        'program_id',
        'year_level'
    ];

    public function getStudentsByClass($classId)
    {
        return $this->select('students.student_id, students.fname, students.lname, students.mname, students.year_level, programs.program_name')
            ->join('student_schedules', 'student_schedules.stb_id = students.stb_id')
            ->join('programs', 'programs.program_id = students.program_id')
            ->where('student_schedules.class_id', $classId)
            ->findAll();
    }



}

