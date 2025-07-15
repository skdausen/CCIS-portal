<?php

namespace App\Models;
use CodeIgniter\Model;

class StudentScheduleModel extends Model
{
    protected $table = 'student_schedules';
    protected $primaryKey = 'schedule_id';

    protected $allowedFields = ['class_id', 'stb_id',];

    // Enroll students (insert if not already enrolled)
    public function enrollStudents($classId, $studentIds = [])
    {
        $data = [];

        foreach ($studentIds as $studentId) {
            if (!$this->where(['class_id' => $classId, 'stb_id' => $studentId])->first()) {
                $data[] = [
                    'class_id' => $classId,
                    'stb_id' => $studentId,
                ];
            }
        }

        if (!empty($data)) {
            $this->insertBatch($data);
            return true;
        }

        return false;
    }

    // Get students enrolled in a class
    public function getEnrolledStudents($classId)
    {
        return $this->select('students.*')
            ->join('students', 'students.stb_id = student_schedules.stb_id')
            ->where('student_schedules.class_id', $classId)
            ->findAll();
    }
}