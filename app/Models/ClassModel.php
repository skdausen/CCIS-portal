<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'class_id';
    protected $allowedFields = [
    'user_id',
    'subject_id',
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
        return $this->select('classes .*, 
                subjects.subject_code, 
                course.course_description, 
                semesters.semester, 
                semesters.schoolyear')
            ->join('course', 'course.course_id = classes.course_id')
            ->join('semesters', 'semesters.semester_id = classes.semester_id')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->findAll();
    }

    public function getFacultyClasses($facultyId, $semesterId)
    {
        return $this->select('
                classes.*, 
                course.course_code,
                course.course_description, 
                semesters.semester, 
                schoolyears.schoolyear
            ')
            ->join('course', 'course.course_id = class.course_id')
            ->join('semesters', 'semesters.semester_id = classes.semester_id')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->where('classes.user_id', $facultyId)
            ->where('classes.semester_id', $semesterId)
            ->findAll();
    }

    public function getFacultyScheduleByDay($facultyId, $semesterId)
    {
        return $this->select('classes.*, course.course_code, course.course_description')
            ->join('course', 'course.course_id = classes.course_id')
            ->where('classes.faculty_id', $facultyId)
            ->where('classes.semester_id', $semesterId)
            ->orderBy('FIELD(classes.class_day, "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")')
            ->orderBy('classes.class_start')
            ->findAll();
    }


    
}
