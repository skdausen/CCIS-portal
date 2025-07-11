<?php
// AdminController.php 
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SemesterModel;
use App\Models\SchoolYearModel;
use App\Models\SubjectModel; 
use App\Models\ClassModel;
use App\Models\FacultyModel;
use App\Models\AdminModel;
use App\Models\StudentModel;
use App\Models\AnnouncementModel;
use App\Models\CurriculumModel;


class AdminController extends BaseController
{
    /********************************************** 
        ADMIN HOME 
     ***********************************************/
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


    /********************************************** 
        USER MANAGEMENT 
     ***********************************************/

    // Display all users
    public function users()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $model = new UserModel();
        $data['users'] = $model->findAll();

        return view('templates/admin/admin_header')
            . view('admin/users', $data)
            . view('templates/admin/admin_footer');
    }


    // Display form to add a new user
    public function createUser()
    {
        // ALLOW ONLY admin or superadmin
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        // LOAD MODELS
        $userModel = new UserModel();
        $studentModel = new StudentModel();
        $facultyModel = new FacultyModel();
        $adminModel = new AdminModel();

        // GET POST INPUTS
        $username = strtoupper($this->request->getPost('username'));
        $email    = $this->request->getPost('email');
        $role     = $this->request->getPost('role');

        // DEFAULT PASSWORD
        $defaultPassword = 'ccis1234';
        $hashedPassword  = password_hash($defaultPassword, PASSWORD_DEFAULT);

        // VALIDATE: USERNAME
        if ($userModel->where('username', $username)->first()) {
            return redirect()->back()->with('error', 'Username already exists.');
        }

        // VALIDATE: EMAIL
        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Email already exists.');
        }

        // START TRANSACTION
        $db = \Config\Database::connect();
        $db->transStart();

        // INSERT INTO `users` TABLE
        $userId = $userModel->insert([
            'username'     => $username,
            'email'        => $email,
            'userpassword' => $hashedPassword,
            'role'         => $role,
            'status'       => 'inactive',
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        // INSERT INTO RELATED TABLES BASED ON ROLE
        if ($role === 'student') {
            $studentModel->insert([
                'student_id' => $username,
                'user_id'    => $userId // optional: if linked by user ID
            ]);
        }

        if ($role === 'faculty') {
            $facultyModel->insert([
                'faculty_id' => $username,
                'user_id'    => $userId  // optional: if linked by user ID
            ]);
        }

        if ($role === 'admin') {
            $adminModel->insert([
                'admin_id' => $username,
                'user_id'    => $userId // optional: if linked by user ID
            ]);
        }

        // COMPLETE TRANSACTION
        $db->transComplete();

        // CHECK TRANSACTION STATUS
        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Failed to create user. Please try again.');
        }

        return redirect()->to('admin/users')->with('success', 'Account created successfully.');
    }


    /********************************************** 
        ANNOUNCEMENT MANAGEMENT
     ***********************************************/

    // Display form to add a new announcement
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

    // Update an existing announcement
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

    // Delete an existing announcement
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


    /********************************************** 
        ACADEMICS PAGE
     ***********************************************/

    public function index()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $schoolYearModel = new SchoolYearModel();
        $semesterModel = new SemesterModel();
        $subjectModel = new SubjectModel();
        $classModel = new ClassModel();
        $facultyModel = new FacultyModel();

        $data = [
            'title' => 'Academics',
            'schoolYearsCount' => $schoolYearModel->countAllResults(),
            'semestersCount' => $semesterModel->countAllResults(),
            'subjectsCount' => $subjectModel->countAllResults(), // This is now subjects count
            'classesCount' => $classModel->countAllResults(),
            'facultyCount' => $facultyModel->countAllResults(),
            'recentSubjects' => $subjectModel->orderBy('subject_id', 'DESC')->findAll(5), // Corrected variable name
        ];

        return view('templates/admin/admin_header', $data)
            . view('admin/academics', $data)
            . view('templates/admin/admin_footer');
    }


    /********************************************** 
        SEMESTERS MANAGEMENT
     ***********************************************/

    // View all semesters with details
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

        $semester = $this->request->getPost('semester'); // e.g., "1st"
        $schoolyearText = $this->request->getPost('schoolyear'); // e.g., "2025-2026"
        $status = $this->request->getPost('status'); // "1" or "0"
        $isActive = $status == '1' ? 1 : 0;

        $schoolYearModel = new \App\Models\SchoolYearModel();
        $semesterModel = new \App\Models\SemesterModel();

        $existingSchoolYear = $schoolYearModel->where('schoolyear', $schoolyearText)->first();
        $schoolyearId = $existingSchoolYear
            ? $existingSchoolYear['schoolyear_id']
            : $schoolYearModel->insert(['schoolyear' => $schoolyearText], true);

        // Debug schoolyearId
        // dd($schoolyearId);

        $duplicate = $semesterModel
            ->where('semester', $semester)
            ->where('schoolyear_id', $schoolyearId)
            ->first();

        if ($duplicate) {
            return redirect()->back()->with('error', 'That semester + school year already exists.');
        }

        if ($isActive) {
            $semesterModel->where('is_active', 1)->set('is_active', 0)->update();
        }

        $result = $semesterModel->insert([
            'semester' => $semester,
            'schoolyear_id' => $schoolyearId,
            'is_active' => $isActive
        ]);

        if (!$result) {
            dd('Insert failed', $semesterModel->errors());
        }

        return redirect()->to('admin/academics/semesters')->with('success', 'Semester added!');
    }

    // UPDATE SEMESTER
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

        // Prevent multiple active semesters
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

    /********************************************** 
        SUBJECTS MANAGEMENT
     ***********************************************/

    // View all subjects
    public function view_subjects()
    {
        $subjectModel = new SubjectModel();
        $data['subjects'] = $subjectModel->findAll();

        return view('templates/admin/admin_header')
            . view('admin/academics/subjects', $data)
            . view('templates/admin/admin_footer');
    }

    // Create a new subject
    public function createSubject()
    {
        $subjectModel = new SubjectModel();

        try {
            $subjectModel->insert([
                'subject_code' => $this->request->getPost('subject_code'),
                'subject_name' => $this->request->getPost('subject_name'),
                'lec_units' => $this->request->getPost('lec_units'),
                'lab_units' => $this->request->getPost('lab_units'),
                'total_units' => $this->request->getPost('lec_units') + $this->request->getPost('lab_units'),
            ]);

            return redirect()->to('admin/academics/subjects')->with('success', 'Subject added successfully.');
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate') !== false || $e->getCode() == 1062) {
                return redirect()->to('admin/academics/subjects')->with('error', 'Duplicate entry: Subject code already exists.');
            }

<<<<<<< HEAD

//CLASSES
public function view_classes()
{
    $classModel = new ClassModel();
    $facultyModel = new FacultyModel();
    $subjectModel = new SubjectModel();
    $semesterModel = new SemesterModel();

    $activeSemester = $semesterModel->getActiveSemester();

    // Get selected semester from query string
    $selectedSemesterId = $this->request->getGet('semester_id');

    // Determine which semester to show
    if (!empty($selectedSemesterId)) {
        $semesterToShow = $selectedSemesterId;
    } elseif (!empty($activeSemester)) {
        $semesterToShow = $activeSemester['semester_id'];
    } else {
        $semesterToShow = null;
    }

    // Build the query for classes with joined data
    $builder = $classModel
        ->select('classes.*, 
                  subjects.subject_code, subjects.subject_name, 
                  semesters.semester, semesters.semester_id, schoolyears.schoolyear,
                  faculty.ftb_id, faculty.faculty_fname, faculty.faculty_lname')
        ->join('subjects', 'subjects.subject_id = classes.subject_id', 'left')
        ->join('semesters', 'semesters.semester_id = classes.semester_id', 'left')
        ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id', 'left')
        ->join('faculty', 'faculty.ftb_id = classes.ftb_id', 'left');

    // Apply semester filter
    if (!empty($semesterToShow)) {
        $builder->where('classes.semester_id', $semesterToShow);
        $classes = $builder->findAll();
    } else {
        $classes = [];
    }

    // Instructors list from faculty table
    $instructors = [];
    $facultyList = $facultyModel->findAll();
    foreach ($facultyList as $faculty) {
        $instructors[$faculty['ftb_id']] = $faculty['faculty_fname'] . ' ' . $faculty['faculty_lname'];
    }


    // Get all semesters for the dropdown
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
            'courses' => $subjectModel->findAll(), // ✅ This is now your subjects
            'semesters' => $semesters,
            'activeSemester' => $activeSemester,
        ])
        . view('templates/admin/admin_footer');
}
=======
            return redirect()->to('admin/academics/subjects')->with('error', 'An unexpected error occurred.');
        }
    }

    // Show edit form
    public function editSubject($id)
    {
        $subjectModel = new SubjectModel();
        $subject = $subjectModel->find($id);

        if (!$subject) {
            return redirect()->to('admin/academics/subjects')->with('error', 'Subject not found.');
        }

        return view('templates/admin/admin_header')
            . view('admin/academics/edit_subject', ['subject' => $subject])
            . view('templates/admin/admin_footer');
    }

    // Update the subject
    public function updateSubject($id)
    {
        $subjectModel = new SubjectModel();

        $lec_units = $this->request->getPost('lec_units');
        $lab_units = $this->request->getPost('lab_units');

        $subjectModel->update($id, [
            'subject_code' => $this->request->getPost('subject_code'),
            'subject_name' => $this->request->getPost('subject_name'),
            'lec_units' => $lec_units,
            'lab_units' => $lab_units,
            'total_units' => $lec_units + $lab_units,
        ]);

        return redirect()->to('admin/academics/subjects')->with('success', 'Subject updated successfully.');
    }

    // Delete a subject
    public function deleteSubject($subject_id)
    {
        $subjectModel = new SubjectModel();
        $classModel = new ClassModel();

        // Check if there are related classes first
        $relatedClasses = $classModel->where('subject_id', $subject_id)->countAllResults();
>>>>>>> b3a9ff7e8cfd0abe2f95863324f14c828534b4af

        if ($relatedClasses > 0) {
            return redirect()->back()->with('error', 'Cannot delete. This subject has classes assigned to it.');
        }

        // Delete subject
        $subjectModel->delete($subject_id);

<<<<<<< HEAD
        // CREATE CLASS
        public function createClass()
        {
            $classModel = new ClassModel();
=======
        return redirect()->back()->with('success', 'Subject deleted successfully.');
    }

    /********************************************** 
        CLASSES MANAGEMENT
     ***********************************************/
    
    // View all classes with details
    public function view_classes()
    {
        $classModel = new ClassModel();
        $facultyModel = new FacultyModel();
        $userModel = new UserModel();
        $courseModel = new SubjectModel();
        $semesterModel = new SemesterModel();

        $activeSemester = $semesterModel->getActiveSemester();

        // Get the selected semester_id from query string
        $selectedSemesterId = $this->request->getGet('semester_id');

        // Decide what semester to show
        if (!empty($selectedSemesterId)) {
            $semesterToShow = $selectedSemesterId;
        } elseif (!empty($activeSemester)) {
            $semesterToShow = $activeSemester['semester_id'];
        } else {
            $semesterToShow = null; // No semester to show
        }

        // Start the query
        $builder = $classModel
            ->select('class.*, 
                    course.course_code, course.course_description, 
                    semesters.semester, semesters.semester_id, schoolyears.schoolyear,
                    users.user_id, users.fname, users.lname')
            ->join('course', 'course.course_id = class.course_id', 'left')
            ->join('semesters', 'semesters.semester_id = class.semester_id', 'left')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id', 'left')
            ->join('users', 'users.user_id = class.user_id', 'left');

        // If a semester to show is available, filter by it
        if (!empty($semesterToShow)) {
            $builder->where('class.semester_id', $semesterToShow);
            $classes = $builder->findAll();
        } else {
            // If no semester is available, return an empty array
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

    //CREATE CLASS
    public function createClass()
    {
        $classModel = new ClassModel();
>>>>>>> b3a9ff7e8cfd0abe2f95863324f14c828534b4af

            $classModel->insert([
                'ftb_id'       => $this->request->getPost('ftb_id'),  // ✅ was user_id
                'subject_id'   => $this->request->getPost('subject_id'),  // ✅ was course_id
                'semester_id'  => $this->request->getPost('semester_id'),
                'class_day'    => $this->request->getPost('class_day'),
                'class_start'  => $this->request->getPost('class_start'),
                'class_end'    => $this->request->getPost('class_end'),
                'class_room'   => $this->request->getPost('class_room'),
                'class_type'   => $this->request->getPost('class_type'),
            ]);

            return redirect()->to('admin/academics/classes')->with('success', 'Class created successfully.');
        }

        // UPDATE CLASS
        public function updateClass($class_id)
        {
            $classModel = new ClassModel();

            $data = [
                'ftb_id'       => $this->request->getPost('ftb_id'),  // ✅ was user_id
                'subject_id'   => $this->request->getPost('subject_id'),  // ✅ was course_id
                'semester_id'  => $this->request->getPost('semester_id'),
                'class_day'    => $this->request->getPost('class_day'),
                'class_start'  => $this->request->getPost('class_start'),
                'class_end'    => $this->request->getPost('class_end'),
                'class_room'   => $this->request->getPost('class_room'),
                'class_type'   => $this->request->getPost('class_type'),
            ];

            $classModel->update($class_id, $data);

            return redirect()->to('admin/academics/classes')->with('success', 'Class updated successfully.');
        }


<<<<<<< HEAD
        //DELETE CLASS
            public function deleteClass($class_id)
            {
                $classModel = new ClassModel();
                $classModel->delete($class_id);
=======
    //UPDATE CLASS
    public function updateClass($class_id)
    {
        $classModel = new ClassModel();
>>>>>>> b3a9ff7e8cfd0abe2f95863324f14c828534b4af

                return redirect()->to('admin/academics/classes')->with('success', 'Class deleted successfully.');

<<<<<<< HEAD
            }
=======
        $classModel->update($class_id, $data);

        return redirect()->to('admin/academics/classes')->with('success', 'Class updated successfully.');
    }


    //DELETE CLASS
    public function deleteClass($class_id)
    {
        $classModel = new ClassModel();
        $classModel->delete($class_id);

        return redirect()->to('admin/academics/classes')->with('success', 'Class deleted successfully.');

    }
>>>>>>> b3a9ff7e8cfd0abe2f95863324f14c828534b4af


    /********************************************** 
        CURRICULUM MANAGEMENT
     ***********************************************/

    // View all curriculums
    public function view_curriculums()
    {
        $yearLevel = $this->request->getGet('year_level');
        $semester = $this->request->getGet('semester');

        $curriculumModel = new CurriculumModel();
        $courses = $curriculumModel->getCourses($yearLevel, $semester);

<<<<<<< HEAD
    $curriculumModel = new CurriculumModel();
    $courses = $curriculumModel->getSubjects($yearLevel, $semester); //  UPDATED HERE
=======
        $semesterOptions = ['1st Sem', '2nd Sem', 'Midyear']; // adjust this if needed
>>>>>>> b3a9ff7e8cfd0abe2f95863324f14c828534b4af

        return view('templates/admin/admin_header')
            . view('admin/academics/curriculums', [
                'courses' => $courses,
                'yearLevel' => $yearLevel,
                'semester' => $semester,
                'semesterOptions' => $semesterOptions,
            ])
            . view('templates/admin/admin_footer');
    }

<<<<<<< HEAD
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
    $courses = $curriculumModel->getSubjects($yearLevel, $semester); // ✅ UPDATED HERE

    $semesterOptions = ['1st Sem', '2nd Sem', 'Midyear'];

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
    $courses = $curriculumModel->getSubjects($yearLevel, $semester); // ✅ UPDATED HERE

    $semesterOptions = ['1st Sem', '2nd Sem', 'Midyear'];

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
=======
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
>>>>>>> b3a9ff7e8cfd0abe2f95863324f14c828534b4af
