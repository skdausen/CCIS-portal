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

    // Classes
    // public function classes()
    // {
    //     if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['faculty'])) {
    //         return redirect()->to('auth/login');
    //     }

    //     // Fetch classes from the database
    //     $classModel = new ClassModel();
    //     $facultyId = session()->get('faculty_id'); // Assuming faculty_id is stored in session
    //     $classes = $classModel->getClassesByFaculty($facultyId);

    //     // If no classes found, you can handle it accordingly
    //     if (empty($classes)) {
    //         $classes = []; // Ensure $classes is an array even if empty
    //     }

    //     return view('templates/faculty/faculty_header')
    //         . view('faculty/classes', ['classes' => $classes])
    //         . view('templates/admin/admin_footer');
    // }

    public function classes()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'faculty') {
            return redirect()->to('auth/login');
        }

        $facultyId = session()->get('username');

        $classModel = new ClassModel();
        $semesterModel = new SemesterModel();

        $activeSemester = $semesterModel->where('is_active', 1)->first();

        $classes = $classModel
            ->where('faculty_id', $facultyId)
            ->where('semester_id', $activeSemester['semester_id'])
            ->findAll();

        return view('templates/faculty/faculty_header')
            . view('faculty/classes', [
                'classes' => $classes,
                'semester' => $activeSemester
            ])
            . view('templates/admin/admin_footer');
    }
}