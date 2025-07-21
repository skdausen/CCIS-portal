<?php

namespace App\Controllers;

use Config\Database;
use App\Models\AnnouncementModel;
use App\Models\ProgramModel;
use App\Models\StudentModel;
use App\Models\GradeModel;
use App\Models\SemesterModel;
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
        if ($student) {
            session()->set([
                'stb_id' => $student['stb_id'],
                'curriculum_id' => $student['curriculum_id']
            ]);
        }

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

        $db = Database::connect();
        $userId = session()->get('user_id');

        $student = $db->table('students')->where('user_id', $userId)->get()->getRow();
        if (!$student) {
            return redirect()->to('auth/login');
        }

        $stbId = $student->stb_id;

        $selectedSemester = $this->request->getGet('semester_id');

        // Fetch all semesters for dropdown
        $semesters = $db->table('semesters')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->select('semesters.*, schoolyears.schoolyear')
            ->orderBy('schoolyears.schoolyear', 'DESC')
            ->orderBy('semesters.semester', 'DESC')
            ->get()->getResult();

        // ✅ Automatically select the active semester if not selected
        if (!$selectedSemester) {
            $activeSemester = $db->table('semesters')
                ->where('is_active', 1)
                ->select('semester_id')
                ->get()
                ->getRow();
            if ($activeSemester) {
                $selectedSemester = $activeSemester->semester_id;
            }
        }

        // Fetch grades filtered by semester
        $builder = $db->table('student_schedules ss')
            ->select('s.subject_code, s.subject_name, g.mt_grade, g.fn_grade, g.sem_grade')
            ->join('classes c', 'c.class_id = ss.class_id')
            ->join('subjects s', 's.subject_id = c.subject_id')
            ->join('grades g', 'g.class_id = c.class_id AND g.stb_id = ss.stb_id', 'left')
            ->where('ss.stb_id', $stbId);

        if ($selectedSemester) {
            $builder->where('c.semester_id', $selectedSemester);
        }

        $grades = $builder->get()->getResult();

        // Build grades query
            $gradesQuery = $db->table('student_schedules ss')
                ->select('s.subject_code, s.subject_name, g.mt_grade, g.fn_grade, g.sem_grade, s.total_units')
                ->join('classes c', 'c.class_id = ss.class_id')
                ->join('subjects s', 's.subject_id = c.subject_id')
                ->join('grades g', 'g.class_id = c.class_id AND g.stb_id = ss.stb_id', 'left')
                ->where('ss.stb_id', $stbId);

            if ($selectedSemester) {
                $gradesQuery->where('c.semester_id', $selectedSemester);
            }

            $grades = $gradesQuery->get()->getResult();

            $curriculumId = $student->curriculum_id;

            $yearlevelSems = ['Y1S1','Y1S2','Y2S1','Y2S2','Y3S1','Y3S2','Y3S3','Y4S1','Y4S2'];
            $deansListFlags = [];

            foreach ($yearlevelSems as $yls) {
                $deansListFlags[$yls] = $this->isDeansLister($stbId, $curriculumId, $yls);
            }

            $totalUnits = 0;
            $weightedSum = 0;
            $isDeanLister = true;

        // Determine selectedYLS from grades if possible
        $selectedYLS = '';
        foreach ($grades as $g) {
            if (isset($g->yearlevel_sem)) {
                $selectedYLS = $g->yearlevel_sem;
                break;
            }
        }

        // Compute GWA from current grades
        $totalUnits = 0;
        $weightedSum = 0;
        $gwa = null;

        foreach ($grades as $g) {
            if (!is_numeric($g->sem_grade)) continue;
            $totalUnits += $g->total_units;
            $weightedSum += ($g->sem_grade * $g->total_units);
        }

        $gwa = $totalUnits > 0 ? round($weightedSum / $totalUnits, 2) : null;

        // Check if Dean’s Lister for selected semester
        $isDeanLister = false;
        if ($selectedYLS && isset($deansListFlags[$selectedYLS])) {
            $isDeanLister = $deansListFlags[$selectedYLS];
        }

        return view('templates/student/student_header')
            . view('student/grades/grades', [
                'grades' => $grades,
                'semesters' => $semesters,
                'selectedSemester' => $selectedSemester,
                'deansListFlags' => $deansListFlags,
                'isDeanLister' => $isDeanLister,
                'gwa' => $gwa
            ])
            . view('templates/admin/admin_footer');
    }

    public function downloadPDF()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            return redirect()->to('auth/login');
        }

        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        $student = $db->table('students')->where('user_id', $userId)->get()->getRow();
        if (!$student) {
            return redirect()->to('auth/login');
        }

        $semesterModel = new SemesterModel();
        $currentSemester = $semesterModel->getActiveSemester();

        $stbId = $student->stb_id;

        $selectedSemester = $this->request->getGet('semester_id'); // ✅ make sure this is passed in your href

        // Grades query (same as in getGrades)
        $gradesQuery = $db->table('student_schedules ss')
            ->select('s.subject_code, s.subject_name, s.total_units, g.sem_grade, st.lname, st.fname, st.mname, st.student_id, p.program_name')
            ->join('classes c', 'c.class_id = ss.class_id')
            ->join('subjects s', 's.subject_id = c.subject_id')
            ->join('grades g', 'g.class_id = c.class_id AND g.stb_id = ss.stb_id', 'left')
            ->join('students st', 'st.stb_id = ss.stb_id') // Join students table
            ->join('programs p', 'p.program_id = st.program_id', 'left') // Optional: join program info
            ->where('ss.stb_id', $stbId);

        if ($selectedSemester) {
            $gradesQuery->where('c.semester_id', $selectedSemester);
        }

        $grades = $gradesQuery->get()->getResult();

        if (empty($grades)) {
            return redirect()->back()->with('error', 'No grades found to export.');
        }

        $html = view('student/grades/download', [
            'grades' => $grades,
            'currentSemester' => $currentSemester
        ]);
    

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('grades.pdf', ['Attachment' => true]);
    }

    public function checkDeansListerStatus($stbId, $curriculumId, $studentYearLevel, $studentSemester)
    {
        $yearlevelSem = $this->mapYearLevelAndSemester($studentYearLevel, $studentSemester);

        if ($yearlevelSem !== null) {
            $isLister = $this->isDeansLister($stbId, $curriculumId, $yearlevelSem);
            echo $isLister ? "Dean's Lister ✅" : "You're not a Dean's Lister ❌";
        } else {
            echo "Invalid year level or semester mapping!";
        }
    }

    // ✅ Converts year + semester to Y1S1, Y3S3, etc.
    private function mapYearLevelAndSemester($yearLevel, $semester)
    {
        $yearMap = [
            'First Year' => 'Y1',
            'Second Year' => 'Y2',
            'Third Year' => 'Y3',
            'Fourth Year' => 'Y4',
        ];

        $semMap = [
            'First Semester' => 'S1',
            'Second Semester' => 'S2',
            'Midyear' => 'S3',
        ];

        $year = $yearMap[$yearLevel] ?? null;
        $sem = $semMap[$semester] ?? null;

        return ($year && $sem) ? "{$year}{$sem}" : null;
    }

    // ✅ Main checker for Dean’s Lister
    private function isDeansLister($stbId, $curriculumId, $yearlevelSem)
    {
        $db = Database::connect();

        // 1. Get all required subjects for this curriculum and year/sem
        $currSubjects = $db->table('subjects')
            ->select('subject_id')
            ->where('curriculum_id', $curriculumId)
            ->where('yearlevel_sem', $yearlevelSem)
            ->get()
            ->getResultArray();

        $currSubjectIds = array_column($currSubjects, 'subject_id');



        if (empty($currSubjectIds)) {
            return false; // No required subjects for this yearlevel/sem
        }

        // 2. Get subjects the student enrolled in with grades
        $studentSubjects = $db->table('student_schedules ss')
            ->select('s.subject_id, g.sem_grade')
            ->join('classes c', 'c.class_id = ss.class_id')
            ->join('subjects s', 's.subject_id = c.subject_id')
            ->join('grades g', 'g.class_id = c.class_id AND g.stb_id = ss.stb_id', 'left')
            ->where('ss.stb_id', $stbId)
            ->whereIn('s.subject_id', $currSubjectIds)
            ->get()
            ->getResult();

        // Debug: Count curriculum subjects
        log_message('debug', '📘 Curriculum subjects count: ' . count($currSubjectIds));

        // Debug: Count student matched subjects
        log_message('debug', '📘 Student enrolled subjects matching: ' . count($studentSubjects));

        // Debug: Log all subject_ids
        log_message('debug', '✅ Curriculum subject_ids: ' . json_encode($currSubjectIds));
        log_message('debug', '✅ Student subject_ids: ' . json_encode(array_map(fn($s) => $s->subject_id, $studentSubjects)));

        // 3. Check if all curriculum subjects are enrolled
        if (count($studentSubjects) !== count($currSubjectIds)) {
            return false; // Not a full load
        }

        // 4. Check all grades (must be numeric and ≤ 2.25)
        // foreach ($studentSubjects as $subj) {
        //     $grade = $subj->sem_grade;
        //     if (!is_numeric($grade) || $grade > 2.25) {
        //         return false; // Not qualified
        //     }
        // }

        return true; // Qualified 🎉



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

public function curriculumPlanView()
{
    $db = Database::connect();

    $student_id = session('stb_id');
    if (!$student_id) {
        return redirect()->back()->with('error', 'No student found in session.');
    }

    $student = $db->table('students')->where('stb_id', $student_id)->get()->getRow();
    if (!$student) {
        return redirect()->back()->with('error', 'Student not found.');
    }

    $curriculum_id = $student->curriculum_id;

    $subjects = $db->table('subjects')
        ->select('subject_id, subject_code, subject_name, lec_units, lab_units, total_units, yearlevel_sem')
        ->where('curriculum_id', $curriculum_id)
        ->orderBy('yearlevel_sem', 'ASC')
        ->get()
        ->getResultArray();

    // Attach Grade
    foreach ($subjects as &$subject) {
        $gradeRow = $db->table('grades g')
            ->select('g.sem_grade')
            ->join('classes c', 'c.class_id = g.class_id')
            ->where('g.stb_id', $student_id)
            ->where('c.subject_id', $subject['subject_id'])
            ->get()
            ->getRow();

        $subject['grade'] = $gradeRow ? $gradeRow->sem_grade : null;
    }

    // Group by Year and Semester (Readable)
    $groupedSubjects = [];
    foreach ($subjects as $subject) {
        $yearRaw = substr($subject['yearlevel_sem'], 0, 2); // Y1
        $semRaw = substr($subject['yearlevel_sem'], 2);     // S1

        $year = match ($yearRaw) {
            'Y1' => '1st Year',
            'Y2' => '2nd Year',
            'Y3' => '3rd Year',
            'Y4' => '4th Year',
            default => 'Other Year',
        };

        $semester = match ($semRaw) {
            'S1' => '1st Semester',
            'S2' => '2nd Semester',
            'S3' => 'Midyear',
            default => 'Other Semester',
        };

        $groupedSubjects[$year][$semester][] = $subject;
    }

    $data = [
        'groupedSubjects' => $groupedSubjects,
        'curriculum_id' => $curriculum_id
    ];

    return view('student/grades/curriculum_planview', $data);
}
}