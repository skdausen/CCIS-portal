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
        $facultyName = $faculty->fname . ' ' . $faculty->lname;

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
                'facultyName' => $facultyName,
                ])
            . view('templates/admin/admin_footer');
    }

    public function classes()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'faculty') {
        return redirect()->to('auth/login');
        }

        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $faculty = $db->table('faculty')->where('user_id', $userId)->get()->getRow();
        $ftbId = $faculty->ftb_id ?? null;

        $semesters = $db->table('semesters')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->select('semesters.*, schoolyears.schoolyear')
            ->orderBy('schoolyears.schoolyear', 'DESC')
            ->orderBy('semesters.semester', 'DESC')
            ->get()->getResult();

        $active = $db->table('semesters')->where('is_active', 1)->get()->getRow();

        return view('templates/faculty/faculty_header')
            . view('faculty/classes', [
                'semesters' => $semesters,
                'activeSemesterId' => $active->semester_id ?? null
            ])
            . view('templates/admin/admin_footer');
    }

    public function getClassesBySemester()
    {
        $semesterId = $this->request->getGet('semester_id');
        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        $faculty = $db->table('faculty')->where('user_id', $userId)->get()->getRow();
        $ftbId = $faculty->ftb_id ?? null;

        $classes = $db->table('classes')
            ->select('classes.*, subjects.subject_code, subjects.subject_name, subjects.subject_type, classes.section')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->where('classes.ftb_id', $ftbId)
            ->where('classes.semester_id', $semesterId)
            ->orderBy('classes.class_id', 'ASC')
            ->get()->getResult();

        return $this->response->setJSON($classes);
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