<?php

namespace App\Controllers;


use App\Models\AnnouncementModel;
use App\Models\StudentScheduleModel;
use App\Models\ClassModel;
use App\Models\GradeModel;
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
                        'end' => $class['lec_end']
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
                        'end' => $class['lab_end']
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
        $scheduleModel = new \App\Models\StudentScheduleModel();

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
        $students = $studentModel->select('students.*, grades.mt_grade, grades.fn_grade, grades.sem_grade')
            ->join('student_schedules', 'student_schedules.stb_id = students.stb_id')
            ->join('grades', 'grades.stb_id = students.stb_id AND grades.class_id = student_schedules.class_id', 'left')
            ->where('student_schedules.class_id', $classId)
            ->findAll();

        return view('faculty/manage_grades', [
            'class' => $class,
            'students' => $students,
        ]);
    }

    public function saveGrades($classId)
    {
        $gradeModel = new GradeModel();

        $gradesInput = $this->request->getPost('grades');

        foreach ($gradesInput as $stb_id => $gradeData) {
            $mt = isset($gradeData['mt_grade']) ? $gradeData['mt_grade'] : null;
            $fn = isset($gradeData['fn_grade']) ? $gradeData['fn_grade'] : null;

            $data = [
                'stb_id' => $stb_id,
                'class_id' => $classId,
                'mt_grade' => $mt,
                'fn_grade' => $fn,
                'sem_grade' => (isset($mt, $fn) && is_numeric($mt) && is_numeric($fn)) 
                                ? number_format(($mt + $fn) / 2, 2) 
                                : null
            ];

            // Check if grade already exists
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