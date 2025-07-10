<?php

namespace App\Controllers;


use App\Models\AnnouncementModel;

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

}