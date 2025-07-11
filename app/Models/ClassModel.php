<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'class_id';
    protected $allowedFields = [
        'ftb_id',
        'subject_id',
        'semester_id',
        'section',
        'class_room',
        'class_day',
        'class_start',
        'class_end',
        'class_type',
    ];

    public $useTimestamps = false;

    // Get all classes with subject, semester, and schoolyear details
    public function getClassWithDetails()
    {
        return $this->select('classes.*, 
                subjects.subject_code, 
                subjects.subject_name, 
                semesters.semester, 
                schoolyears.schoolyear')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->join('semesters', 'semesters.semester_id = classes.semester_id')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->findAll();
    }

    // Get all classes for a faculty in a semester
    public function getFacultyClasses($facultyId, $semesterId)
    {
        return $this->select('classes.*, 
                subjects.subject_code, 
                subjects.subject_name, 
                semesters.semester, 
                schoolyears.schoolyear')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->join('semesters', 'semesters.semester_id = classes.semester_id')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->where('classes.ftb_id', $facultyId)
            ->where('classes.semester_id', $semesterId)
            ->findAll();
    }

    // Get faculty schedule ordered by day and time
    public function getFacultyScheduleByDay($facultyId, $semesterId)
    {
        return $this->select('classes.*, subjects.subject_code, subjects.subject_name')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->where('classes.ftb_id', $facultyId)
            ->where('classes.semester_id', $semesterId)
            ->orderBy('FIELD(classes.class_day, "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")')
            ->orderBy('classes.class_start')
            ->findAll();
    }
}
