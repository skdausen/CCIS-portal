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
    $curriculumModel = new CurriculumModel();

    $data['subjects'] = $subjectModel->findAll();
    $data['curriculums'] = $curriculumModel->findAll(); 


    return view('templates/admin/admin_header')
        . view('admin/academics/subjects', $data)
        . view('templates/admin/admin_footer');
}

    // Create a new subject
   public function createSubject()
{
    $subjectModel = new SubjectModel();

    $data = [
        'subject_code' => $this->request->getPost('subject_code'),
        'subject_name' => $this->request->getPost('subject_name'),
        'subject_type' => $this->request->getPost('subject_type'),
        'lec_units'    => $this->request->getPost('lec_units'),
        'lab_units'    => $this->request->getPost('lab_units'),
        'total_units'  => $this->request->getPost('lec_units') + $this->request->getPost('lab_units'),
        'curriculum_id'  => $this->request->getPost('curriculum_id'),
    ];

    $success = $subjectModel->insert($data);

    if (!$success) {
        // Show errors from the model
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
            'subject_type'   => $this->request->getPost('subject_type'),
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
    
    // View all classes with details
    public function view_classes()
{
    $classModel = new ClassModel();
    $facultyModel = new FacultyModel();
    $userModel = new UserModel();
    $subjectModel = new SubjectModel();
    $semesterModel = new SemesterModel();

    $activeSemester = $semesterModel->getActiveSemester();

    $selectedSemesterId = $this->request->getGet('semester_id');

    $semesterToShow = !empty($selectedSemesterId)
        ? $selectedSemesterId
        : (!empty($activeSemester) ? $activeSemester['semester_id'] : null);

    // Start the query - ✅ updated table & column names
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
        $classes = $builder->findAll();
    } else {
        $classes = [];
    }

    // Instructors list -
    $facultyList = $facultyModel->findAll();
    $instructors = [];
    foreach ($facultyList as $faculty) {
        $instructors[$faculty['ftb_id']] = $faculty['fname'] . ' ' . $faculty['lname'];
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
            'courses' => $subjectModel->findAll(),   //
            'semesters' => $semesters,
            'activeSemester' => $activeSemester,
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
        $section = $this->request->getPost('section');

        // Insert Lecture
        $classData = [
            'ftb_id'      => $ftbId,
            'subject_id'  => $subjectId,
            'semester_id' => $semesterId,
            'section'     => $section,
            'class_day'   => $this->request->getPost('class_day'),
            'class_start' => $this->request->getPost('class_start'),
            'class_end'   => $this->request->getPost('class_end'),
            'class_room'  => $this->request->getPost('class_room'),
        ];

        if (!empty($classData['class_day']) && !empty($classData['class_start']) && !empty($classData['class_end'])) {
            $classModel->insert($classData);
        }

        // Insert Lab if exists
        if ($this->request->getPost('subject_type') === 'LEC with LAB') {
            $labData = [
                'ftb_id'      => $ftbId,
                'subject_id'  => $subjectId,
                'semester_id' => $semesterId,
                'section'     => $section,
                'class_day'   => $this->request->getPost('lab_day'),
                'class_start' => $this->request->getPost('lab_start'),
                'class_end'   => $this->request->getPost('lab_end'),
                'class_room'  => $this->request->getPost('lab_room'),
            ];

            if (!empty($labData['class_day']) && !empty($labData['class_start']) && !empty($labData['class_end'])) {
                $classModel->insert($labData);
            }
        }

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
        $classModel->update($id, [
            'ftb_id'      => $this->request->getPost('ftb_id'),
            'subject_id'  => $this->request->getPost('subject_id'),
            'semester_id' => $this->request->getPost('semester_id'),
            'class_day'   => $this->request->getPost('class_day'),
            'class_start' => $this->request->getPost('class_start'),
            'class_end'   => $this->request->getPost('class_end'),
            'class_room'  => $this->request->getPost('class_room'),
            'section'     => $this->request->getPost('section')
        ]);

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

    // View all curriculums
public function view_curriculums()
{
    $curriculumModel = new CurriculumModel();
    $programModel = new ProgramModel();
    $subjectModel = new SubjectModel();

    // Get all curriculums with program names
    $curriculums = $curriculumModel->getCurriculumsWithProgramName();
    $programs = $programModel->findAll();

    // Get all subjects grouped by curriculum_id
    $subjects = $subjectModel->findAll();
    $curriculumSubjects = [];

    foreach ($subjects as $subject) {
        $curriculumId = $subject['curriculum_id'];
        if (!isset($curriculumSubjects[$curriculumId])) {
            $curriculumSubjects[$curriculumId] = [];
        }
        $curriculumSubjects[$curriculumId][] = $subject;
    }

    return view('templates/admin/admin_header')
        . view('admin/academics/curriculums', [
            'curriculums' => $curriculums,
            'programs' => $programs,
            'curriculumSubjects' => $curriculumSubjects,
        ])
        . view('templates/admin/admin_footer');
}

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
}