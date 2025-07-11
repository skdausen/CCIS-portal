<?php
// AdminController.php 
namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\SemesterModel;
use App\Models\SchoolYearModel;
use App\Models\CourseModel; 
use App\Models\ClassModel;
use App\Models\FacultyModel;
use App\Models\AnnouncementModel;
use App\Models\CurriculumModel;

class AdminController extends BaseController
{
    // Admin Home
    public function adminHome()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $announcementModel = new AnnouncementModel();
        $announcements = $announcementModel->getAllWithUsernames();

        return view('templates/admin/admin_header')
            . view('admin/home', ['announcements' => $announcements])
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

    // Create New User
    public function createUser()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $model = new LoginModel();

        $username = $this->request->getPost('username');
        $fname = $this->request->getPost('fname');
        $mname = $this->request->getPost('mname');
        $lname = $this->request->getPost('lname');
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
            'fname'         => $fname,
            'mname'         => $mname,
            'lname'         => $lname,
            'profile_img'   => 'default.png', // Default profile image
        ]);

        return redirect()->to('admin/users')->with('success', 'Account created successfully.');
    }

    //Adding Announcement
    public function saveAnnouncement()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $announcementModel = new AnnouncementModel();

        $announcementModel->insert([
            'title'       => $this->request->getPost('title'),
            'content'     => $this->request->getPost('content'),
            'audience'    => $this->request->getPost('audience'),
            'event_datetime' => $this->request->getPost('event_datetime'),
            'created_by'  => session()->get('user_id'),
            'created_at'  => date('Y-m-d H:i:s')
            
        ]);

        return redirect()->to('admin/home')->with('success', 'Announcement added!');
    }

    public function updateAnnouncement()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $id = $this->request->getPost('announcement_id');
        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'audience' => $this->request->getPost('audience'),
            'event_datetime' => $this->request->getPost('event_datetime')
        ];

        $model = new AnnouncementModel();
        $model->update($id, $data);

        return redirect()->to('admin/home')->with('success', 'Announcement updated successfully.');
    }


    public function deleteAnnouncement()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $id = $this->request->getPost('announcement_id');

        $model = new AnnouncementModel();
        $model->delete($id);

        return redirect()->to('admin/home')->with('success', 'Announcement deleted successfully.');
    }


    // Academics Main Page
   // App\Controllers\AdminController.php

    public function index()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $schoolYearModel = new SchoolYearModel();
        $semesterModel = new SemesterModel();
        $courseModel = new CourseModel();
        $classModel = new ClassModel();
        $facultyModel = new FacultyModel();

        // Get counts
        $data = [
            'title' => 'Academics',
            'schoolYearsCount' => $schoolYearModel->countAllResults(),
            'semestersCount' => $semesterModel->countAllResults(),
            'coursesCount' => $courseModel->countAllResults(),
            'classesCount' => $classModel->countAllResults(),
            'facultyCount' => $facultyModel->countAllResults(),
            // Get 5 most recent courses
            'recentCourses' => $courseModel->orderBy('course_id', 'DESC')->findAll(5),
        ];

        return view('templates/admin/admin_header', $data)
            . view('admin/academics', $data)
            . view('templates/admin/admin_footer');
    }

        // Semesters List
        // SEMESTERS LIST
  public function view_semesters()
{
    if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
        return redirect()->to('auth/login');
    }

    $semesterModel = new SemesterModel();

    //  Call the method from the model that has the ORDER BY
    $data['semesters'] = $semesterModel->getSemWithDetails();

    return view('templates/admin/admin_header')
        . view('admin/academics/semesters', $data)
        . view('templates/admin/admin_footer');
}

// CREATE SEMESTER
public function createSemester()
{
    if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
        return redirect()->to('auth/login');
    }

    $semester = $this->request->getPost('semester');
    $schoolyearText = $this->request->getPost('schoolyear');
    $status = $this->request->getPost('status'); // "1" or "0"

    if (!$semester || !$schoolyearText || $status === null) {
        return redirect()->back()->with('error', 'Please fill all fields.');
    }

    $isActive = ($status == '1') ? 1 : 0;

    $schoolYearModel = new SchoolYearModel();
    $existingSchoolYear = $schoolYearModel->where('schoolyear', $schoolyearText)->first();
    $schoolyearId = $existingSchoolYear
        ? $existingSchoolYear['schoolyear_id']
        : $schoolYearModel->insert(['schoolyear' => $schoolyearText], true);

    $semesterModel = new SemesterModel();

    // Check for duplicate semester-schoolyear
    $duplicate = $semesterModel
        ->where('semester', $semester)
        ->where('schoolyear_id', $schoolyearId)
        ->first();

    if ($duplicate) {
        return redirect()->back()->with('error', 'Semester and school year combination already exists.');
    }

    // Deactivate others if new semester is active
    if ($isActive === 1) {
        $semesterModel->where('is_active', 1)->set('is_active', 0)->update();
    }

    // Save new semester
    $semesterModel->insert([
        'semester' => $semester,
        'schoolyear_id' => $schoolyearId,
        'is_active' => $isActive
    ]);

    return redirect()->to('admin/academics/semesters')->with('success', 'Semester added successfully.');
}





public function updateSemester($id)
{
    if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
        return redirect()->to('auth/login');
    }

    $semester = $this->request->getPost('semester');
    $schoolyearText = $this->request->getPost('schoolyear');
    $status = $this->request->getPost('status');

    if (!$semester || !$schoolyearText || $status === null) {
        return redirect()->back()->with('error', 'Please fill all fields.');
    }

    $isActive = ($status == '1') ? 1 : 0;

    $schoolYearModel = new SchoolYearModel();
    $existing = $schoolYearModel->where('schoolyear', $schoolyearText)->first();
    $schoolyearId = $existing ? $existing['schoolyear_id'] : $schoolYearModel->insert(['schoolyear' => $schoolyearText], true);

    $semesterModel = new SemesterModel();

    // Check for duplicates excluding this ID
    $duplicate = $semesterModel
        ->where('semester', $semester)
        ->where('schoolyear_id', $schoolyearId)
        ->where('semester_id !=', $id)
        ->first();

    if ($duplicate) {
        return redirect()->back()->with('error', 'Semester and school year combination already exists.');
    }

    // ✔️ Prevent multiple active semesters
    if ($isActive === 1) {
        $activeSemester = $semesterModel
            ->where('is_active', 1)
            ->where('semester_id !=', $id) // exclude this semester
            ->first();
        if ($activeSemester) {
            return redirect()->back()->with('error', 'Another active semester already exists. Please deactivate it first.');
        }
    }

    $semesterModel->update($id, [
        'semester' => $semester,
        'schoolyear_id' => $schoolyearId,
        'is_active' => $isActive
    ]);

    return redirect()->to('admin/academics/semesters')->with('success', 'Semester updated successfully.');
}

    // DELETE SEMESTER
    public function deleteSemester($id)
{
    if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
        return redirect()->to('auth/login');
    }

    $semesterModel = new SemesterModel();
    $semester = $semesterModel->find($id);

    if (!$semester) {
        return redirect()->back()->with('error', 'Semester not found.');
    }

    if ($semester['is_active']) {
        return redirect()->back()->with('error', 'Cannot delete an active semester.');
    }

    $semesterModel->delete($id);

    return redirect()->to('admin/academics/semesters')->with('success', 'Semester deleted successfully.');
}

    // View all courses (unchanged)
    public function view_courses()
    {
        $courseModel = new CourseModel();
        $data['courses'] = $courseModel->findAll();

        return view('templates/admin/admin_header')
            . view('admin/academics/courses', $data)
            . view('templates/admin/admin_footer');
    }

    // Create a new course (unchanged)
   public function createCourse()
{
    $courseModel = new CourseModel();

    try {
        $courseModel->insert([
            'course_code' => $this->request->getPost('course_code'),
            'course_description' => $this->request->getPost('course_description'),
            'lec_units' => $this->request->getPost('lec_units'),
            'lab_units' => $this->request->getPost('lab_units'),
        ]);

        return redirect()->to('admin/academics/courses')->with('success', 'Course added successfully.');
    } catch (\Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate') !== false || $e->getCode() == 1062) {
            // MySQL duplicate entry error
            return redirect()->to('admin/academics/courses')->with('error', 'Duplicate entry: Course code already exists.');
        }

        return redirect()->to('admin/academics/courses')->with('error', 'An unexpected error occurred.');
    }
}


    // Show edit form
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

    // Update the course
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

    // Delete a course
    public function deleteCourse($course_id)
    {
        $courseModel = new CourseModel();
        $classModel = new ClassModel();

        // Check if there are related classes first
        $relatedClasses = $classModel->where('course_id', $course_id)->countAllResults();

        if ($relatedClasses > 0) {
            return redirect()->back()->with('error', 'Cannot delete. This course has classes assigned to it.');
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
    $semesterModel = new SemesterModel();

    $activeSemester = $semesterModel->getActiveSemester();

    // ✅ Get the selected semester_id from query string
    $selectedSemesterId = $this->request->getGet('semester_id');

    // ✅ Decide what semester to show
    if (!empty($selectedSemesterId)) {
        $semesterToShow = $selectedSemesterId;
    } elseif (!empty($activeSemester)) {
        $semesterToShow = $activeSemester['semester_id'];
    } else {
        $semesterToShow = null; // No semester to show
    }

    // ✅ Start the query
    $builder = $classModel
        ->select('class.*, 
                  course.course_code, course.course_description, 
                  semesters.semester, semesters.semester_id, schoolyears.schoolyear,
                  users.user_id, users.fname, users.lname')
        ->join('course', 'course.course_id = class.course_id', 'left')
        ->join('semesters', 'semesters.semester_id = class.semester_id', 'left')
        ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id', 'left')
        ->join('users', 'users.user_id = class.user_id', 'left');

    // ✅ If a semester to show is available, filter by it
    if (!empty($semesterToShow)) {
        $builder->where('class.semester_id', $semesterToShow);
        $classes = $builder->findAll();
    } else {
        // ✅ If no semester is available, return an empty array
        $classes = [];
    }

    // Instructors list
    $instructors = [];
    $facultyUsers = $userModel->where('role', 'faculty')->findAll();
    foreach ($facultyUsers as $user) {
        $instructors[$user['user_id']] = $user['fname'] . ' ' . $user['lname'];
    }

    // All semesters for the dropdown
    $semesters = $semesterModel
        ->select('semesters.semester_id, semesters.semester, schoolyears.schoolyear')
        ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id', 'left')
        ->orderBy('semesters.is_active', 'DESC')
        ->orderBy('schoolyears.schoolyear', 'DESC')
        ->orderBy('semesters.semester', 'ASC')
        ->findAll();

    return view('templates/admin/admin_header')
        . view('admin/academics/classes', [
            'classes' => $classes,
            'instructors' => $instructors,
            'courses' => $courseModel->findAll(),
            'semesters' => $semesters,
            'activeSemester' => $activeSemester,
        ])
        . view('templates/admin/admin_footer');
}




    public function createClass()
    {
        $classModel = new ClassModel();

        $classModel->insert([
            // New
            'user_id' => $this->request->getPost('user_id'),
            'course_id' => $this->request->getPost('course_id'),
            'semester_id' => $this->request->getPost('semester_id'), 
            'class_day' => $this->request->getPost('class_day'),
            'class_start' => $this->request->getPost('class_start'),
            'class_end' => $this->request->getPost('class_end'),
            'class_room' => $this->request->getPost('class_room'),
            'class_type' => $this->request->getPost('class_type'),
    ]);

        return redirect()->to('admin/academics/classes')->with('success', 'Class created successfully.');
    }

    public function updateClass($class_id)
    {
        $classModel = new ClassModel();

        $data = [
            // New
            'user_id' => $this->request->getPost('user_id'),
            'course_id'   => $this->request->getPost('course_id'),
            'semester_id' => $this->request->getPost('semester_id'), 
            'class_day'   => $this->request->getPost('class_day'),
            'class_start' => $this->request->getPost('class_start'),
            'class_end'   => $this->request->getPost('class_end'),
            'class_room'  => $this->request->getPost('class_room'),
            'class_type'  => $this->request->getPost('class_type'),
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


//CURRICULUM
public function view_curriculums()
{
    $yearLevel = $this->request->getGet('year_level');
    $semester = $this->request->getGet('semester');

    $curriculumModel = new CurriculumModel();
    $courses = $curriculumModel->getCourses($yearLevel, $semester);

    $semesterOptions = ['1st Sem', '2nd Sem', 'Midyear']; // adjust this if needed

    return view('templates/admin/admin_header')
        . view('admin/academics/curriculums', [
            'courses' => $courses,
            'yearLevel' => $yearLevel,
            'semester' => $semester,
            'semesterOptions' => $semesterOptions,
        ])
        . view('templates/admin/admin_footer');
}

public function curriculum_old()
{
    $yearLevel = $this->request->getGet('year_level');
    $semester = $this->request->getGet('semester');

    $curriculumModel = new CurriculumModel();
    $courses = $curriculumModel->getCourses($yearLevel, $semester);

    $semesterOptions = ['1st Sem', '2nd Sem', 'Midyear']; // adjust this if needed

    return view('templates/admin/admin_header')
        . view('admin/academics/curriculum_old', [
            'courses' => $courses,
            'yearLevel' => $yearLevel,
            'semester' => $semester,
            'semesterOptions' => $semesterOptions,
        ])
        . view('templates/admin/admin_footer');
}

public function curriculum_new()
{
    $yearLevel = $this->request->getGet('year_level');
    $semester = $this->request->getGet('semester');

    $curriculumModel = new CurriculumModel();
    $courses = $curriculumModel->getCourses($yearLevel, $semester);

    $semesterOptions = ['1st Sem', '2nd Sem', 'Midyear']; // adjust this if needed

    return view('templates/admin/admin_header')
        . view('admin/academics/curriculum_new', [
            'courses' => $courses,
            'yearLevel' => $yearLevel,
            'semester' => $semester,
            'semesterOptions' => $semesterOptions,
        ])
        . view('templates/admin/admin_footer');
}
}

