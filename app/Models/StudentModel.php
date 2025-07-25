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
        'year_level',
        'curriculum_id'
    ];

    public function getStudentsByClass($classId)
    {
        return $this->select('students.stb_id, students.student_id, students.fname, students.lname, students.mname, students.year_level, programs.program_name')
            ->join('student_schedules', 'student_schedules.stb_id = students.stb_id')
            ->join('programs', 'programs.program_id = students.program_id')
            ->where('student_schedules.class_id', $classId)
            ->findAll();
    }

    public function getEnrolledStudentsWithGrades($classId)
    {
        return $this->select('students.stb_id, students.student_id, students.fname, students.mname, students.lname, grades.mt_grade, grades.fn_grade, grades.sem_grade, grades.grade_id')
            ->join('student_schedules', 'student_schedules.stb_id = students.stb_id')
            ->join('grades', 'grades.stb_id = students.stb_id AND grades.class_id = student_schedules.class_id', 'left')
            ->where('student_schedules.class_id', $classId)
            ->findAll();
    }


}

