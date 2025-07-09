<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'class';
    protected $primaryKey = 'class_id';
    protected $allowedFields = [
    'faculty_id',
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
        return $this->select('classes.*, courses.course_code, courses.course_description, semesters.semester, semesters.schoolyear')
                    ->join('courses', 'courses.course_id = classes.course_id')
                    ->join('semesters', 'semesters.semester_id = classes.semester_id')
                    ->findAll();
    }

    // Get classes by faculty ID
    public function getClassesByFaculty($facultyId)
    {
        return $this->where('faculty_id', $facultyId)
                    ->findAll();
    }
}
