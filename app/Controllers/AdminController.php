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
use App\Models\ProgramModel;


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

        $userModel = new UserModel();
        $curriculumModel = new CurriculumModel(); 

        $usersPerPage = 10;
        $page = (int) ($this->request->getGet('page') ?? 1);
        $page = max($page, 1);
        $offset = ($page - 1) * $usersPerPage;

        $users = $userModel->findAll($usersPerPage, $offset);
        $totalUsers = $userModel->countAll();
        $totalPages = ceil($totalUsers / $usersPerPage);

        $data = [
            'users'       => $users,
            'curriculums' => $curriculumModel->findAll() ,
            'page' => $page,
            'totalPages' => $totalPages
        ];

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
        $role     = strtolower($this->request->getPost('role'));
        $curriculumId = $this->request->getPost('curriculum_id'); 


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

        // DEFAULT PROFILE IMAGE
        $defaultImg = 'default.png';

        // INSERT INTO RELATED TABLES BASED ON ROLE
        if ($role === 'student') {
            if (!$studentModel->insert([
                'student_id'     => $username,
                'user_id'        => $userId,
                'curriculum_id'  => $curriculumId,
                'profimg'        => $defaultImg
            ])) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Failed to create student account.');
            }
        }


        if ($role === 'faculty') {
            if (!$facultyModel->insert([
                'faculty_id' => $username,
                'user_id'    => $userId,
                'profimg'    => $defaultImg
            ])) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Failed to create faculty account.');
            }
        }

        if ($role === 'admin') {
            if (!$adminModel->insert([
                'admin_id'   => $username,
                'user_id'    => $userId,
                'profimg'    => $defaultImg
            ])) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Failed to create admin account.');
            }
        }

        // COMPLETE TRANSACTION
        $db->transComplete();

        // CHECK TRANSACTION STATUS
        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Failed to create user. Please try again.');
        }
        return redirect()->to('admin/users')->with('success', 'Account created successfully.');
    }


    // Controller method to get users with joined profile info
    public function viewUsers()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('users u');
        $builder->select('u.*, 
                        COALESCE(s.fname, f.fname, a.fname) as fname,
                        COALESCE(s.mname, f.mname, a.mname) as mname,
                        COALESCE(s.lname, f.lname, a.lname) as lname,
                        COALESCE(s.sex, f.sex, a.sex) as sex,
                        COALESCE(s.birthdate, f.birthdate, a.birthdate) as birthday,
                        COALESCE(s.address, f.address, a.address) as address,
                        COALESCE(s.contactnum, f.contactnum, a.contactnum) as contact_number,
                        COALESCE(s.profimg, f.profimg, a.profimg) as profimg');

        $builder->join('students s', 'u.user_id = s.user_id', 'left');
        $builder->join('faculty f',  'u.user_id = f.user_id',  'left');
        $builder->join('admin a',    'u.user_id = a.user_id',  'left');

        $query = $builder->get();
        $data['users'] = $query->getResultArray();

        return view('admin/users', $data);
    }

    public function getUser($id)
    {
        $userModel    = new UserModel();
        $studentModel = new StudentModel();
        $facultyModel = new FacultyModel();
        $adminModel   = new AdminModel();

        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('#')->with('error', 'User not found');
        }

        // Get extra info based on role
        switch ($user['role']) {
            case 'student':
                $extra = $studentModel->where('user_id', $id)->first();
                break;
            case 'faculty':
                $extra = $facultyModel->where('user_id', $id)->first();
                break;
            case 'admin':
            case 'superadmin':
                $extra = $adminModel->where('user_id', $id)->first();
                break;
            default:
                $extra = [];
        }

        return $this->response->setJSON(array_merge($user, [
            'contactnum'  => $extra['contactnum'] ?? null,
            'fname'       => $extra['fname'] ?? null,
            'mname'       => $extra['mname'] ?? null,
            'lname'       => $extra['lname'] ?? null,
            'sex'         => $extra['sex'] ?? null,
            'birthdate'   => $extra['birthdate'] ?? null,
            'address'     => $extra['address'] ?? null,
            'profimg'     => $extra['profimg'] ?? null,
        ]));
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
            . view('templates/admin/sidebar')
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
            . view('templates/admin/sidebar')
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
        $schoolyearText = str_replace(['–', '—'], '-', $this->request->getPost('schoolyear'));
        $schoolyearText = preg_replace('/\s+/', '', trim($schoolyearText));
        $status = $this->request->getPost('status');
        $isActive = $status == '1' ? 1 : 0;


        $schoolYearModel = new SchoolYearModel();
        $semesterModel = new SemesterModel();

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
            return redirect()->back()->with('error', 'That semester & school year already exists.');
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
        $schoolyearText = str_replace(['–', '—'], '-', $this->request->getPost('schoolyear'));
        $schoolyearText = preg_replace('/\s+/', '', trim($schoolyearText));
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
    $curriculumModel = new CurriculumModel();

    $perPage = 10;
    $page = $this->request->getGet('page') ?? 1;

    $allSubjects = $subjectModel->orderBy('subject_code')->findAll();

    $totalSubjects = count($allSubjects);
    $totalPages = ceil($totalSubjects / $perPage);

    $offset = ($page - 1) * $perPage;
    $subjects = array_slice($allSubjects, $offset, $perPage);

    $data = [
        'subjects' => $subjects,
        'curriculums' => $curriculumModel->findAll(),
        'page' => $page,
        'totalPages' => $totalPages,
    ];

    return view('templates/admin/admin_header')
        . view('templates/admin/sidebar')
        . view('admin/academics/subjects', $data)
        . view('templates/admin/admin_footer');
}


    // Create a new subject
    public function createSubject()
    {
        $subjectModel = new SubjectModel();

        $lec_units = $this->request->getPost('lec_units');
        $lab_units = $this->request->getPost('lab_units');

        $data = [
            'subject_code'   => $this->request->getPost('subject_code'),
            'subject_name'   => $this->request->getPost('subject_name'),
            'subject_type'   => $this->request->getPost('subject_type'),
            'lec_units'      => $lec_units,
            'lab_units'      => $lab_units,
            'total_units'    => $lec_units + $lab_units,
            'curriculum_id'  => $this->request->getPost('curriculum_id'),
            'yearlevel_sem'  => $this->request->getPost('yearlevel_sem'),
        ];

        $success = $subjectModel->insert($data);

        if (!$success) {
            dd($subjectModel->errors());
        }

        return redirect()->to('admin/academics/subjects')->with('success', 'Subject added.');
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
            . view('templates/admin/sidebar')
            . view('admin/academics/edit_subject', ['subject' => $subject])
            . view('templates/admin/admin_footer');
    }

    //update
    public function updateSubject($id)
    {
        $subjectModel = new SubjectModel();

        $lec_units = $this->request->getPost('lec_units');
        $lab_units = $this->request->getPost('lab_units');

        $data = [
            'subject_code'   => $this->request->getPost('subject_code'),
            'subject_name'   => $this->request->getPost('subject_name'),
            'subject_type'   => $this->request->getPost('subject_type'),
            'lec_units'      => $lec_units,
            'lab_units'      => $lab_units,
            'total_units'    => $lec_units + $lab_units,
            'curriculum_id'  => $this->request->getPost('curriculum_id'),
            'yearlevel_sem'  => $this->request->getPost('yearlevel_sem'), // ✅ This was missing
        ];

        $subjectModel->update($id, $data);

        return redirect()->to('admin/academics/subjects')->with('success', 'Subject updated successfully.');
    }

    // Delete a subject
    public function deleteSubject($subject_id)
    {
        $subjectModel = new SubjectModel();
        $classModel = new ClassModel();

        // Check if there are related classes first
        $relatedClasses = $classModel->where('subject_id', $subject_id)->countAllResults();

        if ($relatedClasses > 0) {
            return redirect()->back()->with('error', 'Cannot delete. This subject has classes assigned to it.');
        }

        // Delete subject
        $subjectModel->delete($subject_id);

        return redirect()->back()->with('success', 'Subject deleted successfully.');
    }

    /********************************************** 
        CLASSES MANAGEMENT
     ***********************************************/
    
public function view_classes()
{
    $classModel = new ClassModel();
    $facultyModel = new FacultyModel();
    $subjectModel = new SubjectModel();
    $semesterModel = new SemesterModel();

    $activeSemester = $semesterModel->getActiveSemester();
    $selectedSemesterId = $this->request->getGet('semester_id');

    $instructorSearch = $this->request->getGet('instructor');
    $subjectSearch = $this->request->getGet('subject');
    $sectionFilter = $this->request->getGet('section');

    $semesterToShow = !empty($selectedSemesterId)
        ? $selectedSemesterId
        : (!empty($activeSemester) ? $activeSemester['semester_id'] : null);

    $builder = $classModel
        ->select('
            classes.*, 
            subjects.subject_code, subjects.subject_name, subjects.subject_type,  
            semesters.semester, semesters.semester_id, schoolyears.schoolyear,
            faculty.ftb_id, faculty.fname, faculty.lname
        ')
        ->join('subjects', 'subjects.subject_id = classes.subject_id', 'left')
        ->join('semesters', 'semesters.semester_id = classes.semester_id', 'left')
        ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id', 'left')
        ->join('faculty', 'faculty.ftb_id = classes.ftb_id', 'left');

    if (!empty($semesterToShow)) {
        $builder->where('classes.semester_id', $semesterToShow);
    }

    if (!empty($instructorSearch)) {
        $builder->groupStart()
            ->like('faculty.fname', $instructorSearch)
            ->orLike('faculty.lname', $instructorSearch)
            ->groupEnd();
    }

    if (!empty($subjectSearch)) {
        $builder->groupStart()
            ->like('subjects.subject_code', $subjectSearch)
            ->orLike('subjects.subject_name', $subjectSearch)
            ->groupEnd();
    }

    if (!empty($sectionFilter)) {
        $builder->where('classes.section', $sectionFilter);
    }

    $classes = $builder->findAll();

    $facultyList = $facultyModel->findAll();
    $instructors = [];
    foreach ($facultyList as $faculty) {
        $instructors[$faculty['ftb_id']] = $faculty['fname'] . ' ' . $faculty['lname'];
    }

    $semesters = $semesterModel
        ->select('semesters.semester_id, semesters.semester, schoolyears.schoolyear')
        ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id', 'left')
        ->orderBy('semesters.is_active', 'DESC')
        ->orderBy('schoolyears.schoolyear', 'DESC')
        ->orderBy('semesters.semester', 'ASC')
        ->findAll();

    // For Section Dropdown - only distinct sections for the current semester
        $sections = $classModel
            ->distinct()
            ->select('section')
            ->where('semester_id', $semesterToShow) // optional if you want per semester
            ->findAll();


    return view('templates/admin/admin_header')
        . view('templates/admin/sidebar')
        . view('admin/academics/classes', [
            'classes' => $classes,
            'instructors' => $instructors,
            'courses' => $subjectModel->findAll(),
            'semesters' => $semesters,
            'activeSemester' => $activeSemester,
            'sections' => $sections,
        ])
        . view('templates/admin/admin_footer');
}



// Create a new class
public function createClass()
{
    $classModel = new ClassModel();

    try {
        $subjectId = $this->request->getPost('subject_id');
        $ftbId = $this->request->getPost('ftb_id');
        $semesterId = $this->request->getPost('semester_id');
        $section = strtoupper($this->request->getPost('section'));
        $subjectType = $this->request->getPost('subject_type');

        // TIME VALIDATION
        $lec_start = $this->request->getPost('lec_start');
        $lec_end   = $this->request->getPost('lec_end');
        $lab_start = $this->request->getPost('lab_start');
        $lab_end   = $this->request->getPost('lab_end');

        if (strtotime($lec_start) >= strtotime($lec_end)) {
            return redirect()->back()->withInput()->with('error', 'Lecture end time must be after start time.');
        }

        if ($subjectType === 'LEC with LAB') {
            if (strtotime($lab_start) >= strtotime($lab_end)) {
                return redirect()->back()->withInput()->with('error', 'Lab end time must be after start time.');
            }
        }

        $data = [
            'ftb_id'      => $ftbId,
            'subject_id'  => $subjectId,
            'semester_id' => $semesterId,
            'section'     => $section,
            'lec_day'     => strtoupper($this->request->getPost('lec_day')),
            'lec_start'   => $this->request->getPost('lec_start'),
            'lec_end'     => $this->request->getPost('lec_end'),
            'lec_room'    => strtoupper($this->request->getPost('lec_room')),
        ];

        if ($subjectType === 'LEC with LAB') {
            $data['lab_day']   = strtoupper($this->request->getPost('lab_day'));
            $data['lab_start'] = $this->request->getPost('lab_start');
            $data['lab_end']   = $this->request->getPost('lab_end');
            $data['lab_room']  = strtoupper($this->request->getPost('lab_room'));
        }

        $classModel->insert($data);

        return redirect()->to('admin/academics/classes')->with('success', 'Class added successfully.');
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}


// Update an existing class
public function updateClass($id)
{
    $classModel = new ClassModel();

    try {
        $subjectType = $this->request->getPost('subject_type');
        $lec_start = $this->request->getPost('lec_start');
        $lec_end = $this->request->getPost('lec_end');
        $lab_start = $this->request->getPost('lab_start');
        $lab_end = $this->request->getPost('lab_end');

        // Time validation
        if (strtotime($lec_start) >= strtotime($lec_end)) {
            return redirect()->back()->withInput()->with('error', 'Lecture end time must be after start time.');
        }

        if ($subjectType === 'LEC with LAB') {
            if (strtotime($lab_start) >= strtotime($lab_end)) {
                return redirect()->back()->withInput()->with('error', 'Lab end time must be after start time.');
            }
        }

        $data = [
            'ftb_id'      => $this->request->getPost('ftb_id'),
            'subject_id'  => $this->request->getPost('subject_id'),
            'semester_id' => $this->request->getPost('semester_id'),
            'section' => strtoupper($this->request->getPost('section')),
            'lec_day'     => strtoupper($this->request->getPost('lec_day')),
            'lec_start'   => $this->request->getPost('lec_start'),
            'lec_end'     => $this->request->getPost('lec_end'),
            'lec_room'    => strtoupper($this->request->getPost('lec_room')),
        ];

        if ($subjectType === 'LEC with LAB') {
            $data['lab_day']   = strtoupper($this->request->getPost('lab_day'));
            $data['lab_start'] = $this->request->getPost('lab_start');
            $data['lab_end']   = $this->request->getPost('lab_end');
            $data['lab_room']  = strtoupper($this->request->getPost('lab_room'));
        } else {
            $data['lab_day']   = null;
            $data['lab_start'] = null;
            $data['lab_end']   = null;
            $data['lab_room']  = null;
        }

        $classModel->update($id, $data);

        return redirect()->to('admin/academics/classes')->with('success', 'Class updated successfully.');
    } catch (\Exception $e) {
        return redirect()->to('admin/academics/classes')->with('error', 'An unexpected error occurred while updating the class.');
    }
}


// Delete a class
public function deleteClass($id)
{


    $classModel = new ClassModel();

    try {
        $classModel->delete($id);

        return redirect()->to('admin/academics/classes')->with('success', 'Class deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->to('admin/academics/classes')->with('error', 'An unexpected error occurred while deleting the class.');
    }
}




    /********************************************** 
        CURRICULUM MANAGEMENT
     ***********************************************/

        //View Curriculums
public function view_curriculums()
{
    $curriculumModel = new CurriculumModel();
    $programModel = new ProgramModel();
    $subjectModel = new SubjectModel();

    $yearlevel_sem = $this->request->getGet('yearlevel_sem');
    $selectedCurriculum = $this->request->getGet('curriculum_id');
    $search = $this->request->getGet('search'); //  Get the search input

    $curriculums = $curriculumModel->getCurriculumsWithProgramName(); // For dropdown
    $programs = $programModel->findAll();

    // Get all subjects first
    if (!empty($yearlevel_sem)) {
        $subjects = $subjectModel->where('yearlevel_sem', $yearlevel_sem)->findAll();
    } else {
        $subjects = $subjectModel->findAll();
    }

    $curriculumSubjects = [];
    foreach ($subjects as $subject) {
        $curriculumId = $subject['curriculum_id'];
        if (!isset($curriculumSubjects[$curriculumId])) {
            $curriculumSubjects[$curriculumId] = [];
        }
        $curriculumSubjects[$curriculumId][] = $subject;
    }

    // Only show the selected curriculum in cards
    $curriculumsToDisplay = $curriculums;

    if (!empty($selectedCurriculum)) {
        $curriculumsToDisplay = array_filter($curriculums, function ($curriculum) use ($selectedCurriculum) {
            return $curriculum['curriculum_id'] == $selectedCurriculum;
        });
    }

    // If search is used, filter by name (ignore selectedCurriculum if search is active)
    if (!empty($search)) {
        $curriculumsToDisplay = array_filter($curriculums, function ($curriculum) use ($search) {
            return stripos($curriculum['curriculum_name'], $search) !== false;
        });
    }

    return view('templates/admin/admin_header')
        . view('templates/admin/sidebar')
        . view('admin/academics/curriculums', [
            'curriculums' => $curriculums, // for dropdown
            'curriculumsToDisplay' => $curriculumsToDisplay, // for cards
            'programs' => $programs,
            'curriculumSubjects' => $curriculumSubjects,
            'selectedFilter' => $yearlevel_sem,
            'selectedCurriculum' => $selectedCurriculum,
            'search' => $search, // 
        ])
        . view('templates/admin/admin_footer');
}


//create curriculum
        public function create()
{
    $curriculumModel = new CurriculumModel();

    $data = [
        'curriculum_name' => $this->request->getPost('curriculum_name'),
        'program_id' => $this->request->getPost('program_id'),
    ];

    $curriculumModel->insert($data);

    return redirect()->to(site_url('admin/academics/curriculums'))->with('success', 'Curriculum added successfully.');
}

//update curriculum
public function update_curriculum($curriculum_id)
{
    $curriculumModel = new CurriculumModel();

    $data = [
        'curriculum_name' => $this->request->getPost('curriculum_name'),
        'program_id' => $this->request->getPost('program_id'),
    ];

    $curriculumModel->update($curriculum_id, $data);

    return redirect()->to(site_url('admin/academics/curriculums'))->with('success', 'Curriculum updated successfully.');
}


public function view_curriculum_detail($curriculum_id)
{
    $curriculumModel = new CurriculumModel();
    $subjectModel = new SubjectModel();
    $programModel = new ProgramModel();

    $curriculum = $curriculumModel->find($curriculum_id);
    if (!$curriculum) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Curriculum not found');
    }

    $program = $programModel->find($curriculum['program_id']);
    $curriculum['program_name'] = $program['program_name'] ?? 'N/A';

    $subjects = $subjectModel->where('curriculum_id', $curriculum_id)->orderBy('yearlevel_sem')->findAll();

    $groupedSubjects = [
        '1st Year' => ['1st Semester' => [], '2nd Semester' => []],
        '2nd Year' => ['1st Semester' => [], '2nd Semester' => []],
        '3rd Year' => ['1st Semester' => [], '2nd Semester' => [], 'Midyear' => []],
        '4th Year' => ['1st Semester' => [], '2nd Semester' => []],
    ];

    foreach ($subjects as $subject) {
        switch ($subject['yearlevel_sem']) {
            case 'Y1S1': $groupedSubjects['1st Year']['1st Semester'][] = $subject; break;
            case 'Y1S2': $groupedSubjects['1st Year']['2nd Semester'][] = $subject; break;
            case 'Y2S1': $groupedSubjects['2nd Year']['1st Semester'][] = $subject; break;
            case 'Y2S2': $groupedSubjects['2nd Year']['2nd Semester'][] = $subject; break;
            case 'Y3S1': $groupedSubjects['3rd Year']['1st Semester'][] = $subject; break;
            case 'Y3S2': $groupedSubjects['3rd Year']['2nd Semester'][] = $subject; break;
            case 'Y3S3': $groupedSubjects['3rd Year']['Midyear'][] = $subject; break;
            case 'Y4S1': $groupedSubjects['4th Year']['1st Semester'][] = $subject; break;
            case 'Y4S2': $groupedSubjects['4th Year']['2nd Semester'][] = $subject; break;
        }
    }

    $yearKeys = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
    $totalPages = count($yearKeys);
    $page = (int)$this->request->getGet('page') ?: 1;
    $page = max(1, min($page, $totalPages));
    $currentYearKey = $yearKeys[$page - 1] ?? null;

    return view('templates/admin/admin_header')
        . view('templates/admin/sidebar')
        . view('admin/academics/curriculum_detail', [
            'curriculum_id' => $curriculum_id,
            'curriculum' => $curriculum,
            'program' => $program,
            'groupedSubjects' => $groupedSubjects,
            'currentYearKey' => $currentYearKey,
            'page' => $page,
            'totalPages' => $totalPages
        ])
        . view('templates/admin/admin_footer');
}
}