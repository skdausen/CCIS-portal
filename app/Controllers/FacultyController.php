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

        return view('templates/faculty/faculty_header')
            . view('faculty/home', ['announcements' => $announcements])
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
}