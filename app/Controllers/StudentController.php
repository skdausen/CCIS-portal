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
        $studentName = ucwords($student['fname'] . ' ' . $student['mname'] . ' ' . $student['lname']);
        if ($student) {
            session()->set([
                'stb_id' => $student['stb_id'],
                'curriculum_id' => $student['curriculum_id']
            ]);
        }

        $announcementModel = new AnnouncementModel();
        $announcements = $announcementModel->getAllWithUsernames();

        // --- BEGIN: Load schedule like studentSchedule() ---
        $db = Database::connect();

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
            ->join('faculty', 'faculty.ftb_id = classes.ftb_id') 
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
                'schedule' => $schedule,
                'studentName' => $studentName
            ])
            . view('templates/footer');
    }
    public function studentCurriculum()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['student'])) {
            return redirect()->to('auth/login');
        }

        $db = Database::connect();
        $student = $db->table('students')->where('user_id', session()->get('user_id'))->get()->getRow();

        $programModel = new ProgramModel();
        $programs = $programModel->findAll();
        $studentModel = new StudentModel();
        $studentProfile = $studentModel->where('user_id', session('user_id'))->first();

        $subjects = $db->table('subjects')
            ->where('curriculum_id', $student->curriculum_id)
            ->orderBy('yearlevel_sem')
            ->get()
            ->getResultArray();

        $groupedSubjects = [
            'First Year' => ['First Semester' => [], 'Second Semester' => []],
            'Second Year' => ['First Semester' => [], 'Second Semester' => []],
            'Third Year' => ['First Semester' => [], 'Second Semester' => [], 'Midyear' => []],
            'Fourth Year' => ['First Semester' => [], 'Second Semester' => []],
        ];


        foreach ($subjects as $subject) {
            switch ($subject['yearlevel_sem']) {
                case 'Y1S1':
                    $groupedSubjects['First Year']['First Semester'][] = $subject;
                    break;
                case 'Y1S2':
                    $groupedSubjects['First Year']['Second Semester'][] = $subject;
                    break;
                case 'Y2S1':
                    $groupedSubjects['Second Year']['First Semester'][] = $subject;
                    break;
                case 'Y2S2':
                    $groupedSubjects['Second Year']['Second Semester'][] = $subject;
                    break;
                case 'Y3S1':
                    $groupedSubjects['Third Year']['First Semester'][] = $subject;
                    break;
                case 'Y3S2':
                    $groupedSubjects['Third Year']['Second Semester'][] = $subject;
                    break;
                case 'Y3S3':
                    $groupedSubjects['Third Year']['Midyear'][] = $subject;
                    break;
                case 'Y4S1':
                    $groupedSubjects['Fourth Year']['First Semester'][] = $subject;
                    break;
                case 'Y4S2':
                    $groupedSubjects['Fourth Year']['Second Semester'][] = $subject;
                    break;
            }
        }

        $yearKeys = ['First Year', 'Second Year', 'Third Year', 'Fourth Year'];
        $page = (int)$this->request->getGet('page') ?: 1;
        $totalPages = count($yearKeys);
        $currentYearKey = $yearKeys[$page - 1] ?? null;

        return view('templates/student/student_header')
            . view('student/curriculum', [
                'groupedSubjects' => $groupedSubjects,
                'currentYearKey' => $currentYearKey,
                'page' => $page,
                'totalPages' => $totalPages,     
                'programs' => $programs,
                'student' => $studentProfile
            ])
            . view('templates/footer');
    }
    public function studentGrades()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            return redirect()->to('auth/login');
        }

        $db = Database::connect();
        $userId = session()->get('user_id');
        
        $programModel = new ProgramModel();
        $programs = $programModel->findAll();
        $studentModel = new StudentModel();
        $studentProfile = $studentModel->where('user_id', session('user_id'))->first();

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

        if (!$selectedSemester) {
            // Get the most recent semester where the student has enrolled
            $latestSemester = $db->table('student_schedules ss')
                ->join('classes c', 'c.class_id = ss.class_id')
                ->join('semesters sem', 'sem.semester_id = c.semester_id')
                ->where('ss.stb_id', $stbId)
                ->select('sem.semester_id')
                ->orderBy('sem.schoolyear_id', 'DESC')
                ->orderBy('sem.semester', 'DESC')
                ->limit(1)
                ->get()
                ->getRow();

            if ($latestSemester) {
                $selectedSemester = $latestSemester->semester_id;
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
            ->select('s.subject_code, s.subject_name, g.mt_grade, g.fn_grade, g.sem_grade, s.total_units, s.yearlevel_sem')
            ->join('classes c', 'c.class_id = ss.class_id')
            ->join('subjects s', 's.subject_id = c.subject_id')
            ->join('grades g', 'g.class_id = c.class_id AND g.stb_id = ss.stb_id', 'left')
            ->where('ss.stb_id', $stbId);

        if ($selectedSemester) {
            $gradesQuery->where('c.semester_id', $selectedSemester);
        }

        $grades = $gradesQuery->get()->getResult();

        $grades_data = $this->prepareGradesData($grades, $student, $selectedSemester, $stbId, $semesters);

        return view('templates/student/student_header')
            . view('student/grades/grades', array_merge($grades_data, ['programs' => $programs, 'student' => $studentProfile]))
            . view('templates/footer');
    }
    public function downloadPDF()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            return redirect()->to('auth/login');
        }

        $userId = session()->get('user_id');
        $db     = Database::connect();

        $student = $db->table('students')->where('user_id', $userId)->get()->getRow();
        if (!$student) {
            return redirect()->to('auth/login');
        }

        $semesterModel   = new SemesterModel();
        $selectedSemesterInfo = null;

        $stbId            = $student->stb_id;
        $selectedSemester = $this->request->getGet('semester_id');
        
        if ($selectedSemester) {
            $selectedSemesterInfo = $db->table('semesters')
                ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
                ->select('semesters.semester, schoolyears.schoolyear')
                ->where('semesters.semester_id', $selectedSemester)
                ->get()
                ->getRowArray();
        }

        $currentSemester = $selectedSemesterInfo ?? $semesterModel->getActiveSemester();

        // Fetch all semesters
        $semesters = $db->table('semesters')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->select('semesters.*, schoolyears.schoolyear')
            ->orderBy('schoolyears.schoolyear', 'DESC')
            ->orderBy('semesters.semester', 'DESC')
            ->get()
            ->getResult();

        // Query for PDF export (includes student/program info)
        $gradesQuery = $db->table('student_schedules ss')
            ->select('s.subject_code, s.subject_name, s.total_units, s.yearlevel_sem, g.sem_grade, st.lname, st.fname, st.mname, st.student_id, p.program_name')
            ->join('classes c', 'c.class_id = ss.class_id')
            ->join('subjects s', 's.subject_id = c.subject_id')
            ->join('grades g', 'g.class_id = c.class_id AND g.stb_id = ss.stb_id', 'left')
            ->join('students st', 'st.stb_id = ss.stb_id')
            ->join('programs p', 'p.program_id = st.program_id', 'left')
            ->where('ss.stb_id', $stbId);

        if ($selectedSemester) {
            $gradesQuery->where('c.semester_id', $selectedSemester);
        }

        $grades = $gradesQuery->get()->getResult();

        if (empty($grades)) {
            return redirect()->back()->with('error', 'No grades found to export.');
        }

        $grades_data = $this->prepareGradesData($grades, $student, $selectedSemester, $stbId, $semesters);

        // Prepare logo path for PDF
        $ISData = base64_encode(file_get_contents(FCPATH . 'rsc/assets/transparentlogois.png'));
        $CCData = base64_encode(file_get_contents(FCPATH . 'rsc/assets/cs-logo.png'));

        $ISData = 'data:image/png;base64,' . $ISData;
        $CCData = 'data:image/png;base64,' . $CCData;

        // Build the PDF HTML
        $html = view('student/grades/download', array_merge([
            'grades'          => $grades,
            'currentSemester' => $currentSemester,
            'logoIS'           => $ISData,
            'logoCC'           => $CCData
        ], $grades_data));

        // Render PDF
        $dompdf = new Dompdf();
        $dompdf->getOptions()->set('isRemoteEnabled', true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Format filename parts
        $fullName = ucwords(strtolower("{$student->fname} {$student->mname} {$student->lname}"));
        $semesterText = $currentSemester['semester'] ?? 'Semester';
        $schoolYear   = $currentSemester['schoolyear'] ?? 'SchoolYear';

        // Clean file name (remove spaces, special chars if needed)
        $filename = "{$fullName} {$semesterText} {$schoolYear} Grades_Report";
        $filename = preg_replace('/[^\w\s\-]/', '', $filename);  
        $filename = str_replace(' ', '_', $filename);            

        return $dompdf->stream($filename, ['Attachment' => true]);
    }
    
    private function prepareGradesData($grades, $student, $selectedSemester, $stbId, $semesters)
    {
        $db = Database::connect();

        $semesters = $db->table('student_schedules') // use your actual enrollment mapping table
            ->join('classes', 'classes.class_id = student_schedules.class_id')
            ->join('semesters', 'semesters.semester_id = classes.semester_id')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->select('semesters.semester_id, semesters.semester, schoolyears.schoolyear')
            ->where('student_schedules.stb_id', $stbId) // filter by logged-in student
            ->groupBy('semesters.semester_id')
            ->orderBy('schoolyears.schoolyear', 'DESC')
            ->orderBy('semesters.semester', 'DESC')
            ->get()
            ->getResult();


        // For Dean’s List checks
        $curriculumId   = $student->curriculum_id;
        $yearlevelSems  = ['Y1S1','Y1S2','Y2S1','Y2S2','Y3S1','Y3S2','Y3S3','Y4S1','Y4S2'];
        $deansListFlags = [];
        foreach ($yearlevelSems as $yls) {
            $deansListFlags[$yls] = $this->isDeansLister($stbId, $curriculumId, $yls);
        }

        // Try to detect which YLS this set of grades belongs to
        $selectedYLS = '';
        foreach ($grades as $g) {
            if (isset($g->yearlevel_sem) && $g->yearlevel_sem !== '') {
                $selectedYLS = $g->yearlevel_sem;
                break;
            }
        }

        $totalUnits    = 0; // all subjects in the result
        $unitsEarned   = 0; // only passed
        $weightedSum   = 0; // for GWA
        $unitsForGwa   = 0; // denom for GWA
        $hasIncomplete = false;

        foreach ($grades as $g) {
            // Sum all units for display
            $totalUnits += (float) $g->total_units;

            $sem = $g->sem_grade;

            // Incomplete detection
            if ($sem === null || $sem === '' || strtoupper((string)$sem) === 'NE' || $sem == 0) {
                $hasIncomplete = true;
                continue; // skip incomplete in both GWA and earned units
            }

            if (is_numeric($sem)) {
                // Earned units only for passing grades
                if ((float)$sem != 5.00) {
                    $unitsEarned += (float)$g->total_units;
                }

                // GWA includes all numeric grades including 5.00
                $weightedSum += ((float)$sem * (float)$g->total_units);
                $unitsForGwa += (float)$g->total_units;
            }
        }


        // If any incomplete grade exists in the set, GWA must be null.
        // Else compute weighted average of passed subjects.
        $gwa = (!$hasIncomplete && $unitsForGwa > 0)
            ? round($weightedSum / $unitsForGwa, 3)
            : null;

        // Check for any failed grades
        $hasFailingGrade = false;
        foreach ($grades as $g) {
            if (is_numeric($g->sem_grade) && (float)$g->sem_grade == 5.00) {
                $hasFailingGrade = true;
                break;
            }
        }

        // Determine whether student qualifies as Dean’s Lister *for that YLS*
        $isDeanLister = false;
        if ($selectedYLS && isset($deansListFlags[$selectedYLS])) {
            $isDeanLister = $deansListFlags[$selectedYLS];

            // Apply final condition: must have no failing grade and GWA must be ≤ 1.75
            if ($hasFailingGrade || $gwa === null || $gwa > 1.75) {
                $isDeanLister = false;
            }
        }

        return [
            'grades'           => $grades,
            'semesters'        => $semesters,
            'selectedSemester' => $selectedSemester,
            'deansListFlags'   => $deansListFlags,
            'isDeanLister'     => $isDeanLister,
            'unitsEarned'      => $unitsEarned,
            'totalUnits'       => $totalUnits,
            'hasIncomplete'    => $hasIncomplete,
            'gwa'              => $gwa,
        ];
    }
    public function checkDeansListerStatus($stbId, $curriculumId, $studentYearLevel, $studentSemester)
    {
        $yearlevelSem = $this->mapYearLevelAndSemester($studentYearLevel, $studentSemester);

        if ($yearlevelSem !== null) {
            $isLister = $this->isDeansLister($stbId, $curriculumId, $yearlevelSem);
            echo $isLister ? "Dean's Lister " : "You're not a Dean's Lister ";
        } else {
            echo "Invalid year level or semester mapping!";
        }
    }
    //  Converts year + semester to Y1S1, Y3S3, etc.
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

    // Main checker for Dean’s Lister
    private function isDeansLister($stbId, $curriculumId, $yearlevelSem)
    {
        $db = Database::connect();

        // 1. Get required subjects
        $currSubjects = $db->table('subjects')
            ->select('subject_id')
            ->where('curriculum_id', $curriculumId)
            ->where('yearlevel_sem', $yearlevelSem)
            ->get()
            ->getResultArray();

        $currSubjectIds = array_column($currSubjects, 'subject_id');

        if (empty($currSubjectIds)) return false;

        // 2. Get student enrolled subjects WITH total_units
        $studentSubjects = $db->table('student_schedules ss')
            ->select('s.subject_id, g.sem_grade, s.total_units')
            ->join('classes c', 'c.class_id = ss.class_id')
            ->join('subjects s', 's.subject_id = c.subject_id')
            ->join('grades g', 'g.class_id = c.class_id AND g.stb_id = ss.stb_id', 'left')
            ->where('ss.stb_id', $stbId)
            ->whereIn('s.subject_id', $currSubjectIds)
            ->get()
            ->getResult();

        if (count($studentSubjects) !== count($currSubjectIds)) return false;

        $totalUnits = 0;
        $weightedSum = 0;

        foreach ($studentSubjects as $subj) {
            if (!is_numeric($subj->sem_grade)) return false;
            if ($subj->sem_grade > 2.25) return false;

            $weightedSum += $subj->sem_grade * $subj->total_units;
            $totalUnits += $subj->total_units;
        }

        $gwa = $totalUnits > 0 ? round($weightedSum / $totalUnits, 2) : null;

        if ($gwa === null || $gwa > 1.75) return false;

        return true; // Qualified Dean's Lister
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
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            return redirect()->to('auth/login');
        }

        $db = Database::connect();
        $programModel = new ProgramModel();
        $programs = $programModel->findAll();
        $studentModel = new StudentModel();
        $studentProfile = $studentModel->where('user_id', session('user_id'))->first();

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

        // Attach Grades
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
        unset($subject); // Good practice

        // Remove duplicates properly via subject_id
        $uniqueSubjects = [];
        foreach ($subjects as $subject) {
            $key = $subject['subject_id'];
            if (!isset($uniqueSubjects[$key])) {
                $uniqueSubjects[$key] = $subject;
            }
        }
        $subjects = array_values($uniqueSubjects);

        // Group by Year and Semester
        $groupedSubjects = [];
        foreach ($subjects as $subject) {
            $yearRaw = substr($subject['yearlevel_sem'], 0, 2);
            $semRaw = substr($subject['yearlevel_sem'], 2);

            $year = match ($yearRaw) {
                'Y1' => 'First Year',
                'Y2' => 'Second Year',
                'Y3' => 'Third Year',
                'Y4' => 'Fourth Year',
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

        // --- Compute GWA and Honor ---
        $totalUnits = 0;
        $totalGradePoints = 0;
        $lowestGrade = 0;

        foreach ($subjects as $subject) {
            // Skip NSTP
            if (stripos($subject['subject_code'], 'NSTP') !== false) {
                continue;
            }

            if ($subject['grade'] !== null && is_numeric($subject['grade']) && $subject['grade'] != 0) {
                $units = $subject['total_units'];
                $grade = $subject['grade'];

                $totalUnits += $units;
                $totalGradePoints += $units * $grade;

                if ($grade > $lowestGrade) {
                    $lowestGrade = $grade;
                }
            }
        }

        $gwa = $totalUnits > 0 ? round($totalGradePoints / $totalUnits, 2) : null;
        $honor = null;

        if ($gwa !== null) {
            if ($gwa >= 1.0 && $gwa <= 1.25 && $lowestGrade <= 2.0) {
                $honor = 'Summa Cum Laude';
            } elseif ($gwa > 1.25 && $gwa <= 1.5 && $lowestGrade <= 2.25) {
                $honor = 'Magna Cum Laude';
            } elseif ($gwa > 1.5 && $gwa <= 1.75 && $lowestGrade <= 2.5) {
                $honor = 'Cum Laude';
            }
        }

        return view('templates/student/student_header')
            . view('student/grades/curriculum_planview', [
            'groupedSubjects' => $groupedSubjects,
            'curriculum_id' => $curriculum_id,
            'gwa' => $gwa,
            'honor' => $honor,
            'programs' => $programs,
            'student' => $studentProfile])
            . view('templates/footer');
    }

    public function downloadCurriculumPdf()
    {
        $student_id = session('stb_id');
        if (!$student_id) {
            return redirect()->back()->with('error', 'Student not logged in.');
        }

        $db = Database::connect();

        // Fetch student data with program and curriculum
        $student = $db->table('students s')
            ->select('s.student_id, s.fname, s.mname, s.lname, p.program_name, c.curriculum_name')
            ->join('programs p', 'p.program_id = s.program_id')
            ->join('curriculums c', 'c.curriculum_id = s.curriculum_id')
            ->where('s.stb_id', $student_id)
            ->get()
            ->getRow();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        // Full name
        $fullName = $student->fname . ' ' . $student->mname . ' ' . $student->lname;

        // Fetch subjects and compute grouped data
        $subjects = $this->getStudentCurriculumData($student_id);
        $gwa = $this->computeGWA($subjects); 
        $honor = $this->getHonorTitle($gwa, $subjects);
        $groupedSubjects = $this->groupSubjects($subjects);

        // Generate PDF
        $dompdf = new Dompdf(['isRemoteEnabled' => true]);
        $logo1Data = base64_encode(file_get_contents(FCPATH . 'rsc/assets/transparentlogois.png'));
        $logo2Data = base64_encode(file_get_contents(FCPATH . 'rsc/assets/cs-logo.png'));

        $logo1 = 'data:image/png;base64,' . $logo1Data;
        $logo2 = 'data:image/png;base64,' . $logo2Data;

        $html = view('student/grades/curriculum_download', [
            'groupedSubjects'   => $groupedSubjects,
            'gwa'               => $gwa,
            'honor'             => $honor,
            'student_name'      => $fullName,
            'student_id'        => $student->student_id,
            'program_name'      => $student->program_name,
            'curriculum_name'   => $student->curriculum_name,
            'logo1'             => $logo1,
            'logo2'             => $logo2
        ]);
        

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Format filename parts
        $fullName = ucwords(strtolower("{$student->fname} {$student->mname} {$student->lname}"));

        // Clean file name (remove spaces, special chars if needed)
        $filename = "{$fullName} Curriculum_Plan";
        $filename = preg_replace('/[^\w\s\-]/', '', $filename);  
        $filename = str_replace(' ', '_', $filename);     
        $dompdf->stream($filename, ['Attachment' => 1]); 
    }

    private function getStudentCurriculumData($student_id)
    {
        $db = Database::connect();

        $student = $db->table('students')->where('stb_id', $student_id)->get()->getRow();
        if (!$student) return [];

        $curriculum_id = $student->curriculum_id;

        $subjects = $db->table('subjects')
            ->select('subject_id, subject_code, subject_name, lec_units, lab_units, total_units, yearlevel_sem')
            ->where('curriculum_id', $curriculum_id)
            ->orderBy('yearlevel_sem', 'ASC')
            ->get()
            ->getResultArray();

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
        unset($subject);

        return $subjects;
    }

    private function computeGWA($subjects)
    {
        $totalUnits = 0;
        $totalGradePoints = 0;

        foreach ($subjects as $subject) {
            if (stripos($subject['subject_code'], 'NSTP') !== false) continue;

            if (!empty($subject['grade']) && is_numeric($subject['grade'])) {
                $totalUnits += $subject['total_units'];
                $totalGradePoints += $subject['total_units'] * $subject['grade'];
            }
        }

        return $totalUnits > 0 ? round($totalGradePoints / $totalUnits, 2) : null;
    }

    private function getHonorTitle($gwa, $subjects)
    {
        if ($gwa === null) return null;

        $lowestGrade = 0;
        foreach ($subjects as $subject) {
            if (!empty($subject['grade']) && is_numeric($subject['grade']) && $subject['grade'] > $lowestGrade) {
                $lowestGrade = $subject['grade'];
            }
        }

        if ($gwa <= 1.25 && $lowestGrade <= 2.0) return 'Summa Cum Laude';
        if ($gwa <= 1.5 && $lowestGrade <= 2.25) return 'Magna Cum Laude';
        if ($gwa <= 1.75 && $lowestGrade <= 2.5) return 'Cum Laude';
        return null;
    }

    private function groupSubjects($subjects)
    {
        $grouped = [];
        foreach ($subjects as $subject) {
            $yearRaw = substr($subject['yearlevel_sem'], 0, 2);
            $semRaw = substr($subject['yearlevel_sem'], 2);

            $year = match ($yearRaw) {
                'Y1' => 'First Year',
                'Y2' => 'Second Year',
                'Y3' => 'Third Year',
                'Y4' => 'Fourth Year',
                default => 'Other Year',
            };

            $semester = match ($semRaw) {
                'S1' => '1st Semester',
                'S2' => '2nd Semester',
                'S3' => 'Midyear',
                default => 'Other Semester',
            };

            $grouped[$year][$semester][] = $subject;
        }

        return $grouped;


    }

    }