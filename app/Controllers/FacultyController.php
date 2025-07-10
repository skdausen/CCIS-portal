<?php

namespace App\Controllers;


use App\Models\AnnouncementModel;
use App\Models\ClassModel;
use App\Models\SemesterModel;

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

        $classModel = new \App\Models\ClassModel();
        $semesterModel = new \App\Models\SemesterModel();

        $activeSemester = $semesterModel->where('is_active', 1)->first();

        // Get faculty classes
        $facultyId = session()->get('user_id');
        $classes = $classModel
            ->select('class.*, course.course_description')
            ->join('course', 'course.course_id = class.course_id')
            ->where('class.user_id', $facultyId)
            ->where('class.semester_id', $activeSemester['semester_id'])
            ->findAll();

        // Step 1: Map day codes to full names
        $dayMap = [
            'M' => 'Monday',
            'T' => 'Tuesday',
            'W' => 'Wednesday',
            'Th' => 'Thursday',
            'F' => 'Friday',
            'S' => 'Saturday',
        ];

        // Step 2: Prepare schedule array
        $schedule = [
            'Monday' => [],
            'Tuesday' => [],
            'Wednesday' => [],
            'Thursday' => [],
            'Friday' => [],
            'Saturday' => [],
        ];

        foreach ($classes as $class) {
            $classDays = strtoupper($class['class_day']);

            // Step 3: Handle day combinations like "MWF", "TTh"
            $days = [];
            $i = 0;
            while ($i < strlen($classDays)) {
                if ($classDays[$i] === 'T' && isset($classDays[$i+1]) && $classDays[$i+1] === 'H') {
                    $days[] = 'Th';
                    $i += 2;
                } else {
                    $days[] = $classDays[$i];
                    $i++;
                }
            }

            // Step 4: Map to schedule
            foreach ($days as $dayCode) {
                $dayName = $dayMap[$dayCode] ?? null;
                if ($dayName) {
                    $schedule[$dayName][] = $class;
                }
            }
        }

        // Step 5: Sort classes in each day by start time
        foreach ($schedule as $day => &$dayClasses) {
            usort($dayClasses, function ($a, $b) {
                return strtotime($a['class_start']) <=> strtotime($b['class_start']);
            });
        }

        return view('templates/faculty/faculty_header')
            . view('faculty/home', [
                'announcements' => $announcements,
                'schedule' => $schedule,])
            . view('templates/admin/admin_footer');
    }

    public function classes()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'faculty') {
            return redirect()->to('auth/login');
        }

        $facultyId = session()->get('user_id');

        $classModel = new ClassModel();
        $semesterModel = new SemesterModel();

        $activeSemester = $semesterModel->where('is_active', 1)->first();

        $semesterWithYear = $semesterModel
            ->select('semesters.*, schoolyears.schoolyear')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->where('semesters.is_active', 1)
            ->first();

        // dd(get_class_methods($classModel));
        $classes = $classModel->getFacultyClasses($facultyId, $activeSemester['semester_id']);

        return view('templates/faculty/faculty_header')
            . view('faculty/classes', [
                'classes' => $classes,
                'semester' => $semesterWithYear
            ])
            . view('templates/admin/admin_footer');
    }

    public function viewClass($classId)
    {
        $classModel = new ClassModel();

        $class = $classModel
            ->select('class.*, course.course_code, course.course_description, s.semester, sy.schoolyear')
            ->join('course', 'course.course_id = class.course_id')
            ->join('semesters s', 's.semester_id = class.semester_id')
            ->join('schoolyears sy', 'sy.schoolyear_id = s.schoolyear_id')
            ->where('class.class_id', $classId)
            ->first();

        if (!$class) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Class not found.');
        }

        return view('templates/faculty/faculty_header')
            . view('faculty/view_class', ['class' => $class])
            . view('templates/admin/admin_footer');

    }
}