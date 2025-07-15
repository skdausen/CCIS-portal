<?php

namespace App\Controllers;


use App\Models\AnnouncementModel;
use App\Models\ClassModel;
use App\Models\SemesterModel;
use App\Models\StudentModel;
use App\Models\UserModel;

class FacultyController extends BaseController
{
    // Faculty Home
    public function facultyHome()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['faculty'])) {
            return redirect()->to('auth/login');
        }

        $announcementModel = new AnnouncementModel();
        $announcements = $announcementModel->getAllWithUsernames();

        $userId = session()->get('user_id');

        // Get faculty's ftb_id
        $db = \Config\Database::connect();
        $faculty = $db->table('faculty')->where('user_id', $userId)->get()->getRow();
        $ftbId = $faculty->ftb_id ?? null;

        // Get current active semester
        $semester = $db->table('semesters')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->where('semesters.is_active', 1)
            ->select('semesters.*, schoolyears.schoolyear')
            ->get()->getRow();

        // Get classes
        $classes = $db->table('classes')
            ->select('classes.*, subjects.subject_code, subjects.subject_name, subjects.subject_type')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->where('classes.ftb_id', $ftbId)
            ->where('classes.semester_id', $semester->semester_id)
            ->get()->getResultArray();

        // Initialize empty schedule
        $schedule = [
            'Monday' => [],
            'Tuesday' => [],
            'Wednesday' => [],
            'Thursday' => [],
            'Friday' => []
        ];

        // Expand days and fill schedule
        foreach ($classes as $class) {
            // Add lecture schedule
            if (!empty($class['lec_day'])) {
                $lecDays = $this->expandDays($class['lec_day']);
                foreach ($lecDays as $day) {
                    $schedule[$day][] = [
                        'type' => 'Lecture',
                        'subject_code' => $class['subject_code'],
                        'subject_name' => $class['subject_name'],
                        'room' => $class['lec_room'],
                        'start' => $class['lec_start'],
                        'end' => $class['lec_end']
                    ];
                }
            }

            // Add lab schedule
            if ($class['subject_type'] === 'LEC with LAB' && !empty($class['lab_day'])) {
                $labDays = $this->expandDays($class['lab_day']);
                foreach ($labDays as $day) {
                    $schedule[$day][] = [
                        'type' => 'Laboratory',
                        'subject_code' => $class['subject_code'],
                        'subject_name' => $class['subject_name'],
                        'room' => $class['lab_room'],
                        'start' => $class['lab_start'],
                        'end' => $class['lab_end']
                    ];
                }
            }
        }

        // Sort each day's schedule by time
        foreach ($schedule as $day => &$entries) {
            usort($entries, function ($a, $b) {
                return strtotime($a['start']) - strtotime($b['start']);
            });
        }

        return view('templates/faculty/faculty_header')
            . view('faculty/home', [
                'announcements' => $announcements,
                'schedule' => $schedule,
                'semester' => $semester
                ])
            . view('templates/admin/admin_footer');
    }

    public function classes()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'faculty') {
            return redirect()->to('auth/login');
        }

        $userId = session()->get('user_id');

        // Get the faculty's ftb_id from the faculty table
        $db = \Config\Database::connect();
        $faculty = $db->table('faculty')->where('user_id', $userId)->get()->getRow();

        if (!$faculty) {
            return redirect()->back()->with('error', 'Faculty record not found.');
        }

        $ftbId = $faculty->ftb_id;

        $classModel = new \App\Models\ClassModel();
        $classes = $classModel->getFacultyClasses($ftbId); // now passing ftb_id

        $semesterModel = new \App\Models\SemesterModel();
        $activeSemester = $semesterModel
            ->select('semesters.*, schoolyears.schoolyear')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->where('semesters.is_active', 1)
            ->first();

        return view('templates/faculty/faculty_header')
            . view('faculty/classes', ['classes' => $classes, 'semester' => $activeSemester ])
            . view('templates/admin/admin_footer');
    }

    private function expandDays($days)
    {
        $dayMap = [
            'M' => 'Monday',
            'T' => 'Tuesday',
            'W' => 'Wednesday',
            'H' => 'Thursday',
            'F' => 'Friday'
        ];

        $days = strtoupper($days);
        $days = str_replace('TH', 'H', $days); // Normalize 'TH'

        $result = [];
        $chars = str_split($days);

        foreach ($chars as $char) {
            if (isset($dayMap[$char])) {
                $result[] = $dayMap[$char];
            }
        }

        return $result;
    }


}