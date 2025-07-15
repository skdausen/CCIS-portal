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

        $classModel = new ClassModel();
        $semesterModel = new SemesterModel();

        return view('templates/faculty/faculty_header')
            . view('faculty/home', [
                'announcements' => $announcements,
                // 'schedule' => $schedule,
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


}