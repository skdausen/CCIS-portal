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
        'lec_room',
        'lec_day',
        'lec_start',
        'lec_end',
        'lab_room',
        'lab_day',
        'lab_start',
        'lab_end',
    ];


    public $useTimestamps = false;

    // Get all classes with subject, semester, and schoolyear details
    public function getClassWithDetails()
    {
        return $this->select('classes.*, 
                    subjects.subject_code, 
                    subjects.subject_name, 
                    subjects.subject_type, 
                    semesters.semester, 
                    schoolyears.schoolyear')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->join('semesters', 'semesters.semester_id = classes.semester_id')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->findAll();
    }

    // Get all classes for a faculty in a semester
    public function getFacultyClasses($ftbId)
    {
        return $this->select('classes.*, 
                            subjects.subject_code, 
                            subjects.subject_name,
                            subjects.subject_type, 
                            semesters.semester, 
                            schoolyears.schoolyear')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->join('semesters', 'semesters.semester_id = classes.semester_id')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->where('classes.ftb_id', $ftbId)
            ->where('semesters.is_active', 1)
            ->findAll();
    }

    public function getSingleClassWithDetails($classId)
    {
        return $this->select('classes.*, 
                    subjects.subject_code, 
                    subjects.subject_name, 
                    subjects.subject_type, 
                    semesters.semester, 
                    schoolyears.schoolyear')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->join('semesters', 'semesters.semester_id = classes.semester_id')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->where('classes.class_id', $classId)
            ->first();
    }

}
