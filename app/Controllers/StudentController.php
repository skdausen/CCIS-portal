<?php

namespace App\Controllers;


use App\Models\AnnouncementModel;

class StudentController extends BaseController
{
    // Student Home
    public function studentHome()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['student'])) {
            return redirect()->to('auth/login');
        }

        $announcementModel = new AnnouncementModel();
        $announcements = $announcementModel->getAllWithUsernames();

        return view('templates/student/student_header')
            . view('student/home', ['announcements' => $announcements])
            . view('templates/admin/admin_footer');
    }
    public function studentSchedule()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['student'])) {
            return redirect()->to('auth/login');
        }
        $announcementModel = new AnnouncementModel();
        $announcements = $announcementModel->getAllWithUsernames();

        return view('templates/student/student_header')
            . view('student/schedule', ['announcements' => $announcements])
            . view('templates/admin/admin_footer');
    }
    public function studentGrades()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['student'])) {
            return redirect()->to('auth/login');
        }
        $announcementModel = new AnnouncementModel();
        $announcements = $announcementModel->getAllWithUsernames();

        return view('templates/student/student_header')
            . view('student/grades', ['announcements' => $announcements])
            . view('templates/admin/admin_footer');
    }

}