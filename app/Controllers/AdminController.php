<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\SemesterModel;
use App\Models\SchoolYearModel;
use App\Models\CourseModel; // ✅ Add this
use App\Models\ClassModel;
use App\Models\FacultyModel;

class AdminController extends BaseController
{
    // Admin Home
    public function adminHome()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        return view('templates/admin/admin_header')
            . view('admin/home')
            . view('templates/admin/admin_footer');
    }

    // Users List
    public function users()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $model = new LoginModel();
        $data['users'] = $model->findAll();

        return view('templates/admin/admin_header')
            . view('admin/users', $data)
            . view('templates/admin/admin_footer');
    }

    // Add User Form
    public function addUserForm()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        return view('templates/admin/admin_header')
            . view('admin/add_users')
            . view('templates/admin/admin_footer');
    }

    // Create New User
    public function createUser()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $model = new LoginModel();

        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $role = $this->request->getPost('role');

        $defaultPassword = 'ccis1234';
        $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);

        if ($model->where('username', $username)->first()) {
            return redirect()->back()->with('error', 'Username already exists.');
        }

        if ($model->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Email already exists.');
        }

        $model->insert([
            'username' => $username,
            'email' => $email,
            'userpassword' => $hashedPassword,
            'role' => $role,
            'status' => 'inactive',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('admin/users')->with('success', 'Account created successfully.');
    }

    // View Single User
    public function viewUser($id)
    {
        $model = new LoginModel();
        $user = $model->find($id);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User not found.');
        }

        return view('templates/admin/admin_header')
            . view('admin/view_user', ['user' => $user])
            . view('templates/admin/admin_footer');
    }

    // Academics Main Page
    public function index()
    {
        $data['title'] = 'Academics';

        return view('templates/admin/admin_header', $data)
            . view('admin/academics')
            . view('templates/admin/admin_footer');
    }

    // Semesters List
    public function view_semesters()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $semesterModel = new SemesterModel();

        $data['semesters'] = $semesterModel
            ->select('semesters.semester_id, semesters.semester, schoolyears.schoolyear')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id', 'left')
            ->findAll();

        return view('templates/admin/admin_header')
            . view('admin/academics/semesters', $data)
            . view('templates/admin/admin_footer');
    }

    public function createSemester()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $semester = $this->request->getPost('semester');
        $schoolyearText = $this->request->getPost('schoolyear');

        if (!$semester || !$schoolyearText) {
            return redirect()->back()->with('error', 'Please fill all fields.');
        }

        $schoolYearModel = new SchoolYearModel();
        $existing = $schoolYearModel->where('schoolyear', $schoolyearText)->first();

        $schoolyearId = $existing ? $existing['schoolyear_id'] : $schoolYearModel->insert(['schoolyear' => $schoolyearText], true);

        $semesterModel = new SemesterModel();
        $semesterModel->insert([
            'semester' => $semester,
            'schoolyear_id' => $schoolyearId,
        ]);

        return redirect()->to('admin/academics/semesters')->with('success', 'Semester added successfully.');
    }

   // ✅ View all courses (unchanged)
public function view_courses()
{
    $courseModel = new CourseModel();
    $data['courses'] = $courseModel->findAll();

    return view('templates/admin/admin_header')
        . view('admin/academics/courses', $data)
        . view('templates/admin/admin_footer');
}

// ✅ Create a new course (unchanged)
public function createCourse()
{
    $courseModel = new CourseModel();
    $courseModel->insert([
        'course_code' => $this->request->getPost('course_code'),
        'course_description' => $this->request->getPost('course_description'),
        'lec_units' => $this->request->getPost('lec_units'),
        'lab_units' => $this->request->getPost('lab_units'),
    ]);

    return redirect()->to('admin/academics/courses')->with('success', 'Course added successfully.');
}

// ✅ Show edit form
public function editCourse($id)
{
    $courseModel = new CourseModel();
    $course = $courseModel->find($id);

    if (!$course) {
        return redirect()->to('admin/academics/courses')->with('error', 'Course not found.');
    }

    return view('templates/admin/admin_header')
        . view('admin/academics/edit_course', ['course' => $course])
        . view('templates/admin/admin_footer');
}

// ✅ Update the course
public function updateCourse($id)
{
    $courseModel = new CourseModel();
    $courseModel->update($id, [
        'course_code' => $this->request->getPost('course_code'),
        'course_description' => $this->request->getPost('course_description'),
        'lec_units' => $this->request->getPost('lec_units'),
        'lab_units' => $this->request->getPost('lab_units'),
    ]);

    return redirect()->to('admin/academics/courses')->with('success', 'Course updated successfully.');
}

// ✅ Delete a course
public function deleteCourse($course_id)
{
    $courseModel = new CourseModel();
    $classModel = new ClassModel();

    // Check if there are related classes first
    $relatedClasses = $classModel->where('course_id', $course_id)->countAllResults();

    if ($relatedClasses > 0) {
        return redirect()->back()->with('error', '❌ Cannot delete. This course has classes assigned to it.');
    }

    // Delete course
    $courseModel->delete($course_id);

    return redirect()->back()->with('success', 'Course deleted successfully.');
}


    // Other academics views
 public function view_classes()
{
    $classModel = new ClassModel();
    $facultyModel = new FacultyModel();
    $userModel = new LoginModel();
    $courseModel = new CourseModel();

    // Get classes with course description
    $classes = $classModel->select('class.*, course.course_description, faculty.faculty_id, users.fname, users.lname')
        ->join('course', 'course.course_id = class.course_id', 'left')
        ->join('faculty', 'faculty.faculty_id = class.faculty_id', 'left')
        ->join('users', 'users.user_id = faculty.user_id', 'left')
        ->findAll();

    // Prepare instructors list
    $faculty = $facultyModel->findAll();
    $instructors = [];
    foreach ($faculty as $f) {
        $user = $userModel->find($f['user_id']);
        if ($user) {
            $instructors[$f['faculty_id']] = $user['fname'] . ' ' . $user['lname'];
        }
    }

    $courses = $courseModel->findAll();

    return view('templates/admin/admin_header')
        . view('admin/academics/classes', [
            'classes' => $classes,
            'instructors' => $instructors,
            'courses' => $courses,
        ])
        . view('templates/admin/admin_footer');
}



    public function createClass()
{
    $classModel = new ClassModel();

    $classModel->insert([
        'faculty_id' => $this->request->getPost('faculty_id'),
        'course_id' => $this->request->getPost('course_id'),
        'class_day' => $this->request->getPost('class_day'),
        'class_start' => $this->request->getPost('class_start'),
        'class_end' => $this->request->getPost('class_end'),
        'class_room' => $this->request->getPost('class_room'),
    ]);

    return redirect()->to('admin/academics/classes')->with('success', 'Class created successfully.');
}
public function updateClass($class_id)
{
    $classModel = new ClassModel();

    $data = [
        'course_id'   => $this->request->getPost('course_id'),
        'class_day'   => $this->request->getPost('class_day'),
        'class_start' => $this->request->getPost('class_start'),
        'class_end'   => $this->request->getPost('class_end'),
        'class_room'  => $this->request->getPost('class_room'),
        'faculty_id'  => $this->request->getPost('faculty_id'),
    ];

    $classModel->update($class_id, $data);

    return redirect()->to('admin/academics/classes')->with('success', 'Class updated successfully.');

}

public function deleteClass($class_id)
{
    $classModel = new ClassModel();
    $classModel->delete($class_id);

   return redirect()->to('admin/academics/classes')->with('success', 'Class deleted successfully.');

}


    public function view_curriculums()
    {
        return view('templates/admin/admin_header')
            . view('admin/academics/curriculums')
            . view('templates/admin/admin_footer');
    }

    public function view_teaching_loads()
    {
        return view('templates/admin/admin_header')
            . view('admin/academics/teaching_loads')
            . view('templates/admin/admin_footer');
    }

}
