<?php

namespace App\Controllers;


use App\Models\AnnouncementModel;
use App\Models\ProgramModel;
use App\Models\StudentModel;
use App\Models\GradeModel;
use Dompdf\Dompdf;
use Dompdf\Options;


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

        // --- BEGIN: Load schedule like studentSchedule() ---
        $db = \Config\Database::connect();

        $semester = $db->table('semesters')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->where('semesters.is_active', 1)
            ->select('semesters.*, schoolyears.schoolyear')
            ->get()->getRow();

        $classes = $db->table('student_schedules')
            ->select('
                classes.*, 
                subjects.subject_code, 
                subjects.subject_name, 
                subjects.subject_type, 
                CONCAT(faculty.fname, " ", faculty.lname) AS instructor_name
            ')
            ->join('classes', 'classes.class_id = student_schedules.class_id')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->join('faculty', 'faculty.ftb_id = classes.ftb_id') // ✅ Add this join
            ->where('student_schedules.stb_id', $student['stb_id'])
            ->where('classes.semester_id', $semester->semester_id)
            ->get()->getResultArray();


        $schedule = [
            'Monday' => [],
            'Tuesday' => [],
            'Wednesday' => [],
            'Thursday' => [],
            'Friday' => []
        ];

        foreach ($classes as $class) {
            // Lecture schedule
            if (!empty($class['lec_day'])) {
                $lecDays = $this->expandDays($class['lec_day']);
                foreach ($lecDays as $day) {
                    $schedule[$day][] = [
                        'type' => 'Lecture',
                        'subject_code' => $class['subject_code'],
                        'subject_name' => $class['subject_name'],
                        'room' => $class['lec_room'],
                        'start' => $class['lec_start'],
                        'end' => $class['lec_end'],
                        'instructor' => $class['instructor_name'] ?? 'N/A'
                    ];
                }
            }

            // Lab schedule
            if ($class['subject_type'] === 'LEC with LAB' && !empty($class['lab_day'])) {
                $labDays = $this->expandDays($class['lab_day']);
                foreach ($labDays as $day) {
                    $schedule[$day][] = [
                        'type' => 'Laboratory',
                        'subject_code' => $class['subject_code'],
                        'subject_name' => $class['subject_name'],
                        'room' => $class['lab_room'],
                        'start' => $class['lab_start'],
                        'end' => $class['lab_end'],
                        'instructor' => $class['instructor_name'] ?? 'N/A'
                    ];
                }
            }
        }

        foreach ($schedule as $day => &$entries) {
            usort($entries, function ($a, $b) {
                return strtotime($a['start']) - strtotime($b['start']);
            });
        }


        return view('templates/student/student_header')
            . view('student/home', [
                'announcements' => $announcements,
                'programs' => $programs,
                'student' => $student,
                'schedule' => $schedule
            ])
            . view('templates/admin/admin_footer');
    }

    public function studentCurriculum()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['student'])) {
            return redirect()->to('auth/login');
        }

        $db = \Config\Database::connect();
        $student = $db->table('students')->where('user_id', session()->get('user_id'))->get()->getRow();

        $subjects = $db->table('subjects')
            ->where('curriculum_id', $student->curriculum_id)
            ->orderBy('yearlevel_sem')
            ->get()
            ->getResultArray();

        $groupedSubjects = [
            '1st Year' => ['1st Semester' => [], '2nd Semester' => []],
            '2nd Year' => ['1st Semester' => [], '2nd Semester' => []],
            '3rd Year' => ['1st Semester' => [], '2nd Semester' => [], 'Midyear' => []],
            '4th Year' => ['1st Semester' => [], '2nd Semester' => []],
        ];

        foreach ($subjects as $subject) {
            switch ($subject['yearlevel_sem']) {
                case 'Y1S1':
                    $groupedSubjects['1st Year']['1st Semester'][] = $subject;
                    break;
                case 'Y1S2':
                    $groupedSubjects['1st Year']['2nd Semester'][] = $subject;
                    break;
                case 'Y2S1':
                    $groupedSubjects['2nd Year']['1st Semester'][] = $subject;
                    break;
                case 'Y2S2':
                    $groupedSubjects['2nd Year']['2nd Semester'][] = $subject;
                    break;
                case 'Y3S1':
                    $groupedSubjects['3rd Year']['1st Semester'][] = $subject;
                    break;
                case 'Y3S2':
                    $groupedSubjects['3rd Year']['2nd Semester'][] = $subject;
                    break;
                case 'Y3S3':
                    $groupedSubjects['3rd Year']['Midyear'][] = $subject;
                    break;
                case 'Y4S1':
                    $groupedSubjects['4th Year']['1st Semester'][] = $subject;
                    break;
                case 'Y4S2':
                    $groupedSubjects['4th Year']['2nd Semester'][] = $subject;
                    break;
            }
        }

        $yearKeys = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        $page = (int)$this->request->getGet('page') ?: 1;
        $totalPages = count($yearKeys);
        $currentYearKey = $yearKeys[$page - 1] ?? null;

        return view('templates/student/student_header')
            . view('student/curriculum', [
                'groupedSubjects' => $groupedSubjects,
                'currentYearKey' => $currentYearKey,
                'page' => $page,
                'totalPages' => $totalPages
            ])
            . view('templates/admin/admin_footer');
    }
    public function studentGrades()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            return redirect()->to('auth/login');
        }

        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // Get student record
        $student = $db->table('students')->where('user_id', $userId)->get()->getRow();
        if (!$student) {
            return redirect()->to('auth/login');
        }

        $stbId = $student->stb_id;

        // Get filters from GET
        $selectedSemester = $this->request->getGet('semester_id');
        $selectedYear = $this->request->getGet('year_level');

        // Get all semesters
        $semesters = $db->table('semesters')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->select('semesters.*, schoolyears.schoolyear')
            ->orderBy('schoolyears.schoolyear', 'DESC')
            ->orderBy('semesters.semester', 'DESC')
            ->get()->getResult();

        // Build grade query
        $builder = $db->table('student_schedules ss')
            ->select('s.subject_code, s.subject_name, g.mt_grade, g.fn_grade, g.sem_grade')
            ->join('classes c', 'c.class_id = ss.class_id')
            ->join('subjects s', 's.subject_id = c.subject_id')
            ->join('grades g', 'g.class_id = c.class_id AND g.stb_id = ss.stb_id', 'left')
            ->where('ss.stb_id', $stbId);

        if (!empty($selectedSemester)) {
            $builder->where('c.semester_id', $selectedSemester);
        }

        if (!empty($selectedYear)) {
            $builder->where('c.year_level', $selectedYear); // assuming `classes` table has `year_level`
        }

        $grades = $builder->get()->getResult();

        return view('templates/student/student_header')
            . view('student/grades/grades', [
                'grades' => $grades,
                'semesters' => $semesters,
                'selectedSemester' => $selectedSemester,
                'selectedYear' => $selectedYear,
            ])
            . view('templates/admin/admin_footer');
    }

    public function getGrades()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            return redirect()->to('auth/login');
        }

        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        // Get current student
        $student = $db->table('students')->where('user_id', $userId)->get()->getRow();
        if (!$student) {
            return redirect()->to('auth/login');
        }

        $stbId = $student->stb_id;

        // Get selected semester from GET
        $selectedSemester = $this->request->getGet('semester_id');

        // Get list of all semesters for the filter dropdown
        $semesters = $db->table('semesters')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->select('semesters.*, schoolyears.schoolyear')
            ->orderBy('schoolyears.schoolyear', 'DESC')
            ->orderBy('semesters.semester', 'DESC')
            ->get()->getResult();

        // Build grades query
        $gradesQuery = $db->table('student_schedules ss')
            ->select('s.subject_code, s.subject_name, g.mt_grade, g.fn_grade, g.sem_grade')
            ->join('classes c', 'c.class_id = ss.class_id')
            ->join('subjects s', 's.subject_id = c.subject_id')
            ->join('grades g', 'g.class_id = c.class_id AND g.stb_id = ss.stb_id', 'left')
            ->where('ss.stb_id', $stbId);

        if ($selectedSemester) {
            $gradesQuery->where('c.semester_id', $selectedSemester);
        }

        $grades = $gradesQuery->get()->getResult();

        return view('templates/student/student_header')
            . view('student/grades/grades', [
                'grades' => $grades,
                'semesters' => $semesters,
                'selectedSemester' => $selectedSemester
            ])
            . view('templates/admin/admin_footer');
    }

    public function downloadPDF()
    {
        $gradesModel = new GradeModel(); // adjust model if needed
        $userId = session()->get('user_id');
        $semesterId = $this->request->getGet('semester_id');

        $grades = $gradesModel->getGradesByUserAndSemester($userId, $semesterId); // use your actual function
        $semester = $this->getSemesterName($semesterId); // optional

        $data = [
            'grades' => $grades,
            'semester' => $semester,
        ];

        $html = view('grades/pdf_template', $data); // we’ll make this view

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream("grades.pdf", ["Attachment" => true]);
    }


    
    private function expandDays($days)
    {
        $dayMap = [
            'M' => 'Monday',
            'T' => 'Tuesday',
            'W' => 'Wednesday',
            'H' => 'Thursday', // we use 'H' to represent Thursday after replacing 'TH'
            'F' => 'Friday'
        ];

        // Convert to uppercase for consistency and normalize 'TH' to 'H'
        $days = strtoupper($days);
        $days = str_replace('TH', 'H', $days); // IMPORTANT: Replace 'TH' first

        $result = [];
        $chars = str_split($days); // Split remaining single letters

        foreach ($chars as $char) {
            if (isset($dayMap[$char])) {
                $result[] = $dayMap[$char];
            }
        }

        return $result;
    }



}