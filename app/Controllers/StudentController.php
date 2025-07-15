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

        return view('templates/student/student_header')
            . view('student/home',
            ['announcements' => $announcements,    
                    'programs' => $programs,
                    'student' => $student])
            . view('templates/admin/admin_footer');
    }
    public function studentSchedule()
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

        return view('templates/student/student_header')
            . view('student/schedule', 
            ['announcements' => $announcements,    
                    'programs' => $programs,
                    'student' => $student])
            . view('templates/admin/admin_footer');
    }
    public function studentGrades()
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

        return view('templates/student/student_header')
            . view('student/grades', 
                    ['announcements' => $announcements,    
                    'programs' => $programs,
                    'student' => $student])
            . view('templates/admin/admin_footer');
    }

}