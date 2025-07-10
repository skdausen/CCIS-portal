<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'class';  // ✅ Your actual table name
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

    public $useTimestamps = false;

    // ✅ Get class with course and semester details
    public function getClassWithDetails()
    {
        return $this->select('class.*, courses.course_code, courses.course_description, semesters.semester, schoolyears.schoolyear')
                    ->join('courses', 'courses.course_id = class.course_id')
                    ->join('semesters', 'semesters.semester_id = class.semester_id')
                    ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
                    ->findAll();
    }
}
