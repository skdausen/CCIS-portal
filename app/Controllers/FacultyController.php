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
        $studentModel = new StudentModel();

        $class = $classModel
            ->select('classes.*, course.course_code, course.course_description, s.semester, sy.schoolyear')
            ->join('course', 'course.course_id = classes.course_id')
            ->join('semesters s', 's.semester_id = classes.semester_id')
            ->join('schoolyears sy', 'sy.schoolyear_id = s.schoolyear_id')
            ->where('classes.class_id', $classId)
            ->first();

        if (!$class) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Class not found.');
        }
        // Get students enrolled in this class
        $students = $studentModel->getStudentsByClass($classId);
        if (!$students) {
            $students = []; // Ensure $students is always an array
        }
        // If you want to handle the case where no students are enrolled, you can add a message or handle it accordingly    

        return view('templates/faculty/faculty_header')
            . view('faculty/view_class', ['class' => $class, 'students' => $students])
            . view('templates/admin/admin_footer');

    }
    public function addStudentToClass()
    {
        $classId = $this->request->getPost('class_id');
        $username = $this->request->getPost('username');

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $studentModel = new StudentModel();
        $studentModel->addStudentToClass($classId, $user['user_id']);

        return redirect()->back()->with('success', 'Student added successfully.');
    }


}