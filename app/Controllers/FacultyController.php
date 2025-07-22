<?php

namespace App\Controllers;


use App\Models\AnnouncementModel;
use App\Models\StudentScheduleModel;
use App\Models\ClassModel;
use App\Models\GradeModel;
use App\Models\SemesterModel;
use App\Models\StudentModel;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use CodeIgniter\HTTP\Response;

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

        $userId = session()->get('user_id');

        // Get faculty's ftb_id
        $db = \Config\Database::connect();
        $faculty = $db->table('faculty')->where('user_id', $userId)->get()->getRow();
        $ftbId = $faculty->ftb_id ?? null;
        $facultyName = $faculty->fname . ' ' . $faculty->lname;

        // Get current active semester
        $semester = $db->table('semesters')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->where('semesters.is_active', 1)
            ->select('semesters.*, schoolyears.schoolyear')
            ->get()->getRow();

        // Get classes
        $classes = $db->table('classes')
            ->select('classes.*, subjects.subject_code, subjects.subject_name, subjects.subject_type')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->where('classes.ftb_id', $ftbId)
            ->where('classes.semester_id', $semester->semester_id)
            ->get()->getResultArray();

        // Initialize empty schedule
        $schedule = [
            'Monday' => [],
            'Tuesday' => [],
            'Wednesday' => [],
            'Thursday' => [],
            'Friday' => []
        ];

        // Expand days and fill schedule
        foreach ($classes as $class) {
            // Add lecture schedule
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
                        'section' => $class['section']
                    ];
                }
            }

            // Add lab schedule
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
                        'section' => $class['section']
                    ];
                }
            }
        }

        // Sort each day's schedule by time
        foreach ($schedule as $day => &$entries) {
            usort($entries, function ($a, $b) {
                return strtotime($a['start']) - strtotime($b['start']);
            });
        }

        return view('templates/faculty/faculty_header')
            . view('faculty/home', [
                'announcements' => $announcements,
                'schedule' => $schedule,
                'facultyName' => $facultyName,
                ])
            . view('templates/admin/admin_footer');
    }

    public function classes()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'faculty') {
        return redirect()->to('auth/login');
        }

        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $faculty = $db->table('faculty')->where('user_id', $userId)->get()->getRow();
        $ftbId = $faculty->ftb_id ?? null;

        $semesters = $db->table('semesters')
            ->join('schoolyears', 'schoolyears.schoolyear_id = semesters.schoolyear_id')
            ->select('semesters.*, schoolyears.schoolyear')
            ->orderBy('schoolyears.schoolyear', 'DESC')
            ->orderBy('semesters.semester', 'DESC')
            ->get()->getResult();

        $active = $db->table('semesters')->where('is_active', 1)->get()->getRow();

        return view('templates/faculty/faculty_header')
            . view('faculty/classes', [
                'semesters' => $semesters,
                'activeSemesterId' => $active->semester_id ?? null
            ])
            . view('templates/admin/admin_footer');
    }

    public function getClassesBySemester()
    {
        $semesterId = $this->request->getGet('semester_id');
        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        $faculty = $db->table('faculty')->where('user_id', $userId)->get()->getRow();
        $ftbId = $faculty->ftb_id ?? null;

        $classes = $db->table('classes')
            ->select('classes.*, subjects.subject_code, subjects.subject_name, subjects.subject_type, classes.section')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->where('classes.ftb_id', $ftbId)
            ->where('classes.semester_id', $semesterId)
            ->orderBy('classes.class_id', 'ASC')
            ->get()->getResult();

        return $this->response->setJSON($classes);
    }

    public function viewClass($classId)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'faculty') {
            return redirect()->to('auth/login');
        }

        $classModel = new ClassModel();
        $studentModel = new StudentModel();
        $studentScheduleModel = new StudentScheduleModel();

        $class = $classModel->find($classId);

        $class = $classModel
            ->select('classes.*, subjects.subject_code, subjects.subject_name, subjects.subject_type, s.semester, sy.schoolyear')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->join('semesters s', 's.semester_id = classes.semester_id')
            ->join('schoolyears sy', 'sy.schoolyear_id = s.schoolyear_id')
            ->where('classes.class_id', $classId)
            ->first();

        if (!$class) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Class not found.');
        }

        // Get enrolled students
        $students = $studentModel->getStudentsByClass($classId);
        
        // Already enrolled student IDs
        $enrolledIds = $studentScheduleModel
            ->where('class_id', $classId)
            ->select('stb_id')
            ->findColumn('stb_id'); // returns array of stb_ids

        // Fetch students NOT yet enrolled
        $allStudents = $studentModel
            ->select('students.*, programs.program_name')
            ->join('programs', 'programs.program_id = students.program_id')
            ->whereNotIn('students.stb_id', $enrolledIds ?: [0]) // prevents null in whereNotIn
            ->findAll();

        return view('templates/faculty/faculty_header')
            . view('faculty/view_class', [
                'class' => $class,
                'students' => $students ?? [],
                'allStudents' => $allStudents
            ])
            . view('templates/admin/admin_footer');
    }

    public function enrollStudents($classId)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'faculty') {
            return redirect()->to('auth/login');
        }

        $studentIds = $this->request->getPost('student_ids');
        $scheduleModel = new StudentScheduleModel();

        if ($scheduleModel->enrollStudents($classId, $studentIds)) {
            return redirect()->back()->with('success', 'Students successfully enrolled to class.');
        } else {
            return redirect()->back()->with('error', 'No new students were enrolled.');
        }
    }

    public function removeStudent($classId, $stbId)
    {
        $studentScheduleModel = new StudentScheduleModel();

        $studentScheduleModel
            ->where('class_id', $classId)
            ->where('stb_id', $stbId)
            ->delete();

        return redirect()->to('faculty/class/' . $classId)->with('success', 'Student removed successfully');
    }

    public function manageGrades($classId)
    {
        $classModel = new ClassModel();
        $studentScheduleModel = new StudentScheduleModel();
        $studentModel = new StudentModel();
        $gradeModel = new GradeModel();

        // Get class details
        $class = $classModel
            ->select('classes.*, subjects.subject_code, subjects.subject_name')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->find($classId);

        // Get enrolled students with existing grades (if any)
        $students = $studentModel->select('students.*, grades.mt_grade, grades.fn_grade, grades.sem_grade, grades.mt_numgrade, grades.fn_numgrade, grades.sem_numgrade')
            ->join('student_schedules', 'student_schedules.stb_id = students.stb_id')
            ->join('grades', 'grades.stb_id = students.stb_id AND grades.class_id = student_schedules.class_id', 'left')
            ->where('student_schedules.class_id', $classId)
            ->findAll();

        return view('templates/faculty/faculty_header')
            . view('faculty/manage_grades', [
                    'class' => $class,
                    'students' => $students,
                    ])
            . view('templates/admin/admin_footer');
    }

    public function saveGrades($classId)
    {
        $gradeModel = new GradeModel();

        $gradesData = $this->request->getPost('grades');

        foreach ($gradesData as $stb_id => $grade) {
            // Safely get the numerical grades (null if not set or empty)
            $mtNum = isset($grade['mt_numgrade']) && $grade['mt_numgrade'] !== '' ? floatval($grade['mt_numgrade']) : null;
            $fnNum = isset($grade['fn_numgrade']) && $grade['fn_numgrade'] !== '' ? floatval($grade['fn_numgrade']) : null;

            // ❌ If both are empty, skip this student
            if ($mtNum === null && $fnNum === null) {
                continue;
            }

            // Transmute
            $mtGrade = $mtNum !== null ? $this->transmute($mtNum) : null;
            $fnGrade = $fnNum !== null ? $this->transmute($fnNum) : null;

            // Average for semestral
            $semNum = ($mtNum !== null && $fnNum !== null) ? round(($mtNum + $fnNum) / 2, 2) : null;
            $semGrade = $semNum !== null ? $this->transmute($semNum) : null;

            $data = [
                'stb_id'        => $stb_id,
                'class_id'      => $classId,
                'mt_numgrade'   => $mtNum,
                'mt_grade'      => $mtGrade,
                'fn_numgrade'   => $fnNum,
                'fn_grade'      => $fnGrade,
                'sem_numgrade'  => $semNum,
                'sem_grade'     => $semGrade,
            ];

            $existing = $gradeModel->where('stb_id', $stb_id)
                                ->where('class_id', $classId)
                                ->first();

            if ($existing) {
                $gradeModel->update($existing['grade_id'], $data);
            } else {
                $gradeModel->insert($data);
            }
        }


        return redirect()->back()->with('success', 'Grades saved successfully!');
    }

    private function transmute($numGrade)
    {
        if ($numGrade >= 96.5) return '1.00';
        if ($numGrade >= 93.5) return '1.25';
        if ($numGrade >= 90.5) return '1.50';
        if ($numGrade >= 87.5) return '1.75';
        if ($numGrade >= 84.5) return '2.00';
        if ($numGrade >= 81.5) return '2.25';
        if ($numGrade >= 78.5) return '2.50';
        if ($numGrade >= 75.5) return '2.75';
        if ($numGrade >= 74.5) return '3.00';
        return '5.00';
    }

    public function uploadGrades($classId)
    {
        helper('text');

        $file = $this->request->getFile('grades_file');

        if (!$file->isValid()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Upload failed: ' . $file->getErrorString()
            ]);
        }

        $ext = strtolower($file->getExtension());
        if (!in_array($ext, ['xlsx', 'xls'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid file type. Only .xlsx or .xls files are allowed.'
            ]);
        }

        $studentModel = new StudentModel();
        $gradeModel = new GradeModel();

        $spreadsheet = IOFactory::load($file->getTempName());
        $sheet = $spreadsheet->getActiveSheet();
        
        $rows = $sheet->toArray(null, true, true, true);

        if (empty($rows)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Excel sheet is empty.']);
        }

        // Step 1: Identify MG and TFG column letters from the first row
        $headerRow = $rows[1]; // First row = header
        $mgCol = null;
        $tfgCol = null;

        foreach ($headerRow as $colLetter => $value) {
            foreach ($headerRow as $colLetter => $value) {
                if (!$value) continue;

                $val = strtoupper(trim(preg_replace('/\s+/', '', $value))); // Remove spaces and normalize
                if ($val === 'MG') $mgCol = $colLetter;
                if ($val === 'TFG') $tfgCol = $colLetter;
            }
        }

        if (!$mgCol && !$tfgCol) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'MG or TFG column not found. Make sure the column headers are labeled "MG" and/or "TFG".'
            ]);
        }

        unset($rows[1]); // remove header

        $changedGrades = [];
        $extraStudents = []; 
        $studentsWithNoGrades = [];

        foreach ($rows as $row) {
            $studentId = $row['A'] ?? null;

            $mtNum = $mgCol && isset($row[$mgCol]) && is_numeric($row[$mgCol]) ? round(floatval($row[$mgCol]), 2) : null;
            $fnNum = $tfgCol && isset($row[$tfgCol]) && is_numeric($row[$tfgCol]) ? round(floatval($row[$tfgCol]), 2) : null;


            if (!$studentId) continue;

            $student = $studentModel->where('student_id', $studentId)->first();
            if (!$student) {
                $extraStudents[] = $studentId;
                continue;
            }

            if ($mtNum === null && $fnNum === null) {
                $studentsWithNoGrades[] = $studentId;
                continue;
            }

            $stbId = $student['stb_id'];
            $existing = $gradeModel->where('stb_id', $stbId)->where('class_id', $classId)->first();

            $changes = [];

            if ($mtNum !== null && (!isset($existing['mt_numgrade']) || round($existing['mt_numgrade'], 2) !== $mtNum)) {
                $changes['mt_numgrade'] = ['old' => $existing['mt_numgrade'] ?? '-', 'new' => $mtNum];
            }

            if ($fnNum !== null && (!isset($existing['fn_numgrade']) || round($existing['fn_numgrade'], 2) !== $fnNum)) {
                $changes['fn_numgrade'] = ['old' => $existing['fn_numgrade'] ?? '-', 'new' => $fnNum];
            }

            if (!empty($changes)) {
                $semNum = ($mtNum !== null && $fnNum !== null) ? round(($mtNum + $fnNum) / 2, 2) : null;
                $semGrade = $semNum !== null ? $this->transmute($semNum) : null;

                $changedGrades[] = [
                    'student_id' => $studentId,
                    'fullname'   => "{$student['lname']}, {$student['fname']} {$student['mname']}",
                    'changes'    => $changes,
                    'data'       => [
                        'stb_id'       => $stbId,
                        'class_id'     => $classId,
                        'mt_numgrade'  => $mtNum,
                        'mt_grade'     => $mtNum !== null ? $this->transmute($mtNum) : null,
                        'fn_numgrade'  => $fnNum,
                        'fn_grade'     => $fnNum !== null ? $this->transmute($fnNum) : null,
                        'sem_numgrade' => $semNum,
                        'sem_grade'    => $semGrade,
                    ]
                ];
            }
        }

        if (empty($changedGrades) && empty($extraStudents) && empty($studentsWithNoGrades)) {
            return $this->response->setJSON(['status' => 'no_changes']);
        }
        if (empty($changedGrades) && !empty($extraStudents) && empty($studentsWithNoGrades)) {
            return $this->response->setJSON([
                'status' => 'changes_detected',
                'extra_students' => $extraStudents,
            ]);
        }
        // if (empty($changedGrades) && empty($extraStudents) && !empty($studentsWithNoGrades)) {
        //     return $this->response->setJSON([
        //         'status' => 'changes_detected',
        //         'students_with_no_grades' => $studentsWithNoGrades
        //     ]);
        // }

        // Store changes in session
        session()->set('grade_upload_changes', $changedGrades);

        return $this->response->setJSON([
            'status' => 'changes_detected',
            'changes' => $changedGrades,
            'extra_students' => $extraStudents,
            'students_with_no_grades' => $studentsWithNoGrades
        ]);
    }


    public function confirmUpload($classId)
    {
        $gradeModel = new GradeModel();
        $changes = session()->get('grade_upload_changes') ?? [];

        if (empty($changes)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No changes to save.']);
        }

        $successCount = 0;

        foreach ($changes as $entry) {
            $data = $entry['data'];

            // Prevent null overwrite (optional improvement)
            $data = array_filter($data, function($val) {
                return $val !== null;
            });

            $existing = $gradeModel
                ->where('stb_id', $data['stb_id'])
                ->where('class_id', $classId)
                ->first();

            if ($existing) {
                $gradeModel->update($existing['grade_id'], $data);
            } else {
                $gradeModel->insert($data);
            }

            $successCount++;
        }

        session()->remove('grade_upload_changes');

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "$successCount grade(s) successfully uploaded."
        ]);
    }



    public function downloadGradeTemplate($classId)
    {
        $classModel = new ClassModel();
        $studentModel = new StudentModel();
        $gradeModel = new GradeModel();

        // Get class info
        $class = $classModel
            ->select('classes.*, subjects.subject_code, subjects.subject_name')
            ->join('subjects', 'subjects.subject_id = classes.subject_id')
            ->find($classId);

        if (!$class) {
            return redirect()->back()->with('error', 'Class not found.');
        }

        // Get students enrolled in this class
        $students = $studentModel
            ->select('students.student_id, students.fname, students.mname, students.lname, students.stb_id')
            ->join('student_schedules', 'student_schedules.stb_id = students.stb_id')
            ->where('student_schedules.class_id', $classId)
            ->findAll();

        // Map grades by stb_id
        $grades = $gradeModel->where('class_id', $classId)->findAll();
        $gradeMap = [];
        foreach ($grades as $g) {
            $gradeMap[$g['stb_id']] = $g;
        }

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'student_id');
        $sheet->setCellValue('B1', 'full_name');
        $sheet->setCellValue('C1', 'MG');
        $sheet->setCellValue('D1', 'TFG');

        // Fill data
        $row = 2;
        foreach ($students as $student) {
            $fullName = "{$student['lname']}, {$student['fname']} {$student['mname']}";
            $midterm = $gradeMap[$student['stb_id']]['mt_numgrade'] ?? '';
            $final   = $gradeMap[$student['stb_id']]['fn_numgrade'] ?? '';

            $sheet->setCellValue("A{$row}", $student['student_id']);
            $sheet->setCellValue("B{$row}", $fullName);
            $sheet->setCellValue("C{$row}", $midterm);
            $sheet->setCellValue("D{$row}", $final);
            $row++;
        }

        // Generate filename
        $safeCode = preg_replace('/[^a-zA-Z0-9]/', '_', $class['subject_code']);
        $safeName = preg_replace('/[^a-zA-Z0-9]/', '_', $class['subject_name']);
        $filename = "{$safeCode}_{$safeName}_Template.xlsx";

        // Output file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // Helper to capture spreadsheet output
    private function spreadsheetToOutput($writer)
    {
        ob_start();
        $writer->save('php://output');
        return ob_get_clean();
    }

    private function expandDays($days)
    {
        $dayMap = [
            'M' => 'Monday',
            'T' => 'Tuesday',
            'W' => 'Wednesday',
            'H' => 'Thursday',
            'F' => 'Friday'
        ];

        $days = strtoupper($days);
        $days = str_replace('TH', 'H', $days); // Normalize 'TH'

        $result = [];
        $chars = str_split($days);

        foreach ($chars as $char) {
            if (isset($dayMap[$char])) {
                $result[] = $dayMap[$char];
            }
        }

        return $result;
    }


}