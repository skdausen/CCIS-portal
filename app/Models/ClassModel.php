<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'class';
    protected $primaryKey = 'class_id';
    protected $allowedFields = [
    'user_id',
    'course_id',
    'semester_id',
    'class_day',
    'class_start',
    'class_end',
    'class_room',
    'class_type',
    ];

    

    // Optional: add timestamp support
    public $useTimestamps = false;

    // JOIN courses and semesters to display names instead of IDs
    public function getClassWithDetails()
    {
        return $this->select('class.*, 
                course.course_code, 
                course.course_description, 
                semesters.semester, 
                semesters.schoolyear')
            ->join('course', 'course.course_id = class.course_id')
            ->join('semesters', 'semesters.semester_id = class.semester_id')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->findAll();
    }

    public function getFacultyClasses($facultyId, $semesterId)
    {
        return $this->select('
                class.*, 
                course.course_code,
                course.course_description, 
                semesters.semester, 
                schoolyears.schoolyear
            ')
            ->join('course', 'course.course_id = class.course_id')
            ->join('semesters', 'semesters.semester_id = class.semester_id')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->where('class.user_id', $facultyId)
            ->where('class.semester_id', $semesterId)
            ->findAll();
    }

    
}
