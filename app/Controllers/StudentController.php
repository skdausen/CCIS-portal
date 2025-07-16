<?php

namespace App\Controllers;


use App\Models\AnnouncementModel;
use App\Models\ProgramModel;
use App\Models\StudentModel;

class StudentController extends BaseController
{
    // Student Home
    public function studentHome()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['student'])) {
            return redirect()->to('auth/login');
        }

        $programModel = new ProgramModel();
        $programs = $programModel->findAll();

        $studentModel = new StudentModel();
        $student = $studentModel->where('user_id', session('user_id'))->first();

        $announcementModel = new AnnouncementModel();
        $announcements = $announcementModel->getAllWithUsernames();

        // --- BEGIN: Load schedule like studentSchedule() ---
        $db = \Config\Database::connect();

        $semester = $db->table('semesters')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->where('semesters.is_active', 1)
            ->select('semesters.*, schoolyears.schoolyear')
            ->get()->getRow();

        $classes = $db->table('student_schedules')
            ->select('
                classes.*, 
                subjects.subject_code, 
                subjects.subject_name, 
                subjects.subject_type, 
                CONCAT(faculty.fname, " ", faculty.lname) AS instructor_name
            ')
            ->join('classes', 'classes.class_id = student_schedules.class_id')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->join('faculty', 'faculty.ftb_id = classes.ftb_id') // ✅ Add this join
            ->where('student_schedules.stb_id', $student['stb_id'])
            ->where('classes.semester_id', $semester->semester_id)
            ->get()->getResultArray();


        $schedule = [
            'Monday' => [],
            'Tuesday' => [],
            'Wednesday' => [],
            'Thursday' => [],
            'Friday' => []
        ];

        foreach ($classes as $class) {
            // Lecture schedule
            if (!empty($class['lec_day'])) {
                $lecDays = $this->expandDays($class['lec_day']);
                foreach ($lecDays as $day) {
                    $schedule[$day][] = [
                        'type' => 'Lecture',
                        'subject_code' => $class['subject_code'],
                        'subject_name' => $class['subject_name'],
                        'room' => $class['lec_room'],
                        'start' => $class['lec_start'],
                        'end' => $class['lec_end'],
                        'instructor' => $class['instructor_name'] ?? 'N/A'
                    ];
                }
            }

            // Lab schedule
            if ($class['subject_type'] === 'LEC with LAB' && !empty($class['lab_day'])) {
                $labDays = $this->expandDays($class['lab_day']);
                foreach ($labDays as $day) {
                    $schedule[$day][] = [
                        'type' => 'Laboratory',
                        'subject_code' => $class['subject_code'],
                        'subject_name' => $class['subject_name'],
                        'room' => $class['lab_room'],
                        'start' => $class['lab_start'],
                        'end' => $class['lab_end'],
                        'instructor' => $class['instructor_name'] ?? 'N/A'
                    ];
                }
            }
        }

        foreach ($schedule as $day => &$entries) {
            usort($entries, function ($a, $b) {
                return strtotime($a['start']) - strtotime($b['start']);
            });
        }


        return view('templates/student/student_header')
            . view('student/home', [
                'announcements' => $announcements,
                'programs' => $programs,
                'student' => $student,
                'schedule' => $schedule
            ])
            . view('templates/admin/admin_footer');
    }

    public function studentCurriculum()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['student'])) {
            return redirect()->to('auth/login');
        }

        return view('templates/student/student_header')
            . view('student/curriculum')
            . view('templates/admin/admin_footer');
    }
    public function studentGrades()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['student'])) {
            return redirect()->to('auth/login');
        }
        return view('templates/student/student_header')
            . view('student/grades')
            . view('templates/admin/admin_footer');
        
    }   
    
    private function expandDays($days)
    {
        $dayMap = [
            'M' => 'Monday',
            'T' => 'Tuesday',
            'W' => 'Wednesday',
            'H' => 'Thursday', // we use 'H' to represent Thursday after replacing 'TH'
            'F' => 'Friday'
        ];

        // Convert to uppercase for consistency and normalize 'TH' to 'H'
        $days = strtoupper($days);
        $days = str_replace('TH', 'H', $days); // IMPORTANT: Replace 'TH' first

        $result = [];
        $chars = str_split($days); // Split remaining single letters

        foreach ($chars as $char) {
            if (isset($dayMap[$char])) {
                $result[] = $dayMap[$char];
            }
        }

        return $result;
    }

}