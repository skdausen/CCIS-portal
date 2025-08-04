<!-- download.php   -->
<!DOCTYPE html>
<html>
<head>
    <title>Student Grades</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 100%;
        }
        .content {
            display: flex;
            align-items: center;    /* vertical center */
            justify-content: center; /* horizontal center */
            height: 100vh;           /* full screen height */
            position: relative;
            z-index: 2;             
            padding: 1rem;
            margin: auto;
            padding: 2.5rem 3rem 2rem 3rem;
        }
        .table-container {
            width: 100%;
        }
        /* MAKE TABLE LAYOUT CONSISTENT */
        .student-grade-table {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
        }
        .student-grade-table th,
        .student-grade-table td {
            border-top: 1px solid #444;
            border-bottom: 1px solid #444;
            padding: 8px;
            text-align: center;
        }
        .student-grade-table tfoot td {
            text-align: right;
            font-weight: bold;
        }
        /* Remove left and right borders */
        .student-grade-table {
            border-left: none !important;
            border-right: none !important;
        }
        /* Left-align Subject Code and Subject Name */
        .student-grade-table th:nth-child(-n+2),
        .student-grade-table td:nth-child(-n+2) {
            text-align: left;
        }
        /* Narrower width for Subject Code, Grade, Units, and Earned columns */
        .student-grade-table th:nth-child(1),
        .student-grade-table td:nth-child(1),
        .student-grade-table th:nth-child(n+3):nth-child(-n+5),
        .student-grade-table td:nth-child(n+3):nth-child(-n+5) {
            width: 12%;
        }
        .student-grade-table th:nth-child(2),
        .student-grade-table td:nth-child(2) {
            width: 52%;
        }
        /* RIGHT-ALIGN GRADE SUMMARY */
        .grade-summary-box {
            width: 100%;
            text-align: right;           
            margin-top: 15px;        
            padding-right: 15px;         
            font-size: 14px;            
            font-weight: bold;         
            line-height: 1.4;
        }
        .grade-summary-box div {
            margin-bottom: 4px;
        }
    </style>

</head>
<body>
    <div class="content">

            <!-- Header with Logos and Text -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <!-- Left Logos -->
            <div style="display: flex; flex-direction: column; gap: 5px; align-items: center;">
                <img src="<?= $logoIS ?>" alt="IS Logo" style="height: 50px;">
                <img src="<?= $logoCC ?>" alt="CCIS Logo" style="height: 50px;">
            </div>

            <!-- Header Text -->
            <div style="flex: 1; text-align: center;">
                <div style="font-size: 13px;">Republic of the Philippines</div>
                <div style="font-size: 15px; font-weight: bold;">Ilocos Sur Polytechnic State College - Main Campus</div>
                <div style="font-size: 13px;">College of Computing and Information Sciences</div>
                <hr style="margin: 6px 0; border-top: 1px solid #000;">
                <div style="font-size: 13px; font-weight: bold;">Bachelor of Science in Computer Science</div>
                <div style="font-size: 12px;">CMO No. 25, Series 2015 · Board Resolution No. 2017</div>
            </div>

            <!-- Optional: Right Spacer (blank) -->
            <div style="width: 70px;"></div>
        </div>
        
        <h2>Student Grade Report</h2>
        <!-- Student Details -->
        <?php if (!empty($grades)): ?>
            <?php $student = $grades[0]; ?>
            <p><strong>Student ID:</strong> <?= $student->student_id ?></p>
            <p><strong>Name:</strong> <?= $student->lname ?>, <?= $student->fname ?> <?= $student->mname ?></p>
            <p><strong>Program:</strong> <?= $student->program_name ?? '-' ?></p>
            <?php if (!empty($currentSemester)): ?>
                <p><strong>Semester & School Year:</strong> <?= esc($currentSemester['semester'] ?? '-') ?> <?= esc($currentSemester['schoolyear'] ?? '-') ?></p>
            <?php endif; ?>
    
        <?php endif; ?>
        <br>
        <div class="table-container">
            <table class="student-grade-table">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Semestral Grade</th>
                        <th>Units</th>
                        <th>Units Earned</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grades as $g): ?>
                        <tr>
                            <td><?= $g->subject_code ?></td>
                            <td><?= $g->subject_name ?></td>
                            <td><?= $g->sem_grade ?? 'NE' ?></td>
                            <td><?= $g->total_units ?></td>
                            <td>
                                <?= (is_numeric($g->sem_grade) && $g->sem_grade != 0 && $g->sem_grade != 5.00) ? $g->total_units : '--' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <?php
                    $totalUnits = 0;
                    $totalUnitsEarned = 0;
                    $totalWeightedGrade = 0;
                    $hasNE = false;
    
                    foreach ($grades as $g) {
                        $units = (float) $g->total_units;
                        $grade = $g->sem_grade;
    
                        // Always add to totalUnits no matter what
                        $totalUnits += $units;
    
                        // Check for incomplete grades (null, 'NE', or 0)
                        if ($grade === null || strtoupper($grade) === 'NE' || $grade == 0) {
                            $hasNE = true;
                            continue; // don't add to GWA or earned
                        }
    
                        // Only process valid numeric grades
                        if (is_numeric($grade)) {
                            if ((float)$grade != 5.00) {
                                $totalUnitsEarned += $units;
                            }
    
                            // Include even 5.00 grades in GWA
                            $totalWeightedGrade += ((float)$grade * $units);
                        }
                    }
    
    
                    $gwa = ($totalUnits > 0 && !$hasNE) ? round($totalWeightedGrade / $totalUnits, 2) : null;
    
    
                ?>
            </table>
    
            <div style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%; margin-top: 15px; font-weight: bold;">
    
                <!-- GWA SUMMARY - RIGHT COLUMN -->
                <div style="text-align: right; border-bottom: 1px solid #000; padding-bottom: 10px; width: fit-content; margin-left: auto; margin-bottom: 15px;">
                        <div style="margin-bottom: 10px;">Total Units: <?= $totalUnits ?></div>
                    <?php if (!$hasNE): ?>
                        <div style="margin-bottom: 10px;">Total Units Earned: <?= $totalUnitsEarned ?></div>
                        <div>GWA: <?= number_format($gwa, 2) ?></div>
                    <?php else: ?>
                        <div>Total Units Earned: --</div>
                    <?php endif; ?>
                </div>
    
                <!-- GRADE SYSTEM GUIDE - LEFT COLUMN -->
                <div>
                    <div style="margin-bottom: 10px;">Grade System Guide</div>
                    <ul style="list-style-type: none; padding-left: 0; margin: 0;">
                        <li><strong>1.00</strong> – 97–100%</li>
                        <li><strong>1.25</strong> – 94–96%</li>
                        <li><strong>1.50</strong> – 91–93%</li>
                        <li><strong>1.75</strong> – 88–90%</li>
                        <li><strong>2.00</strong> – 85–87%</li>
                        <li><strong>2.25</strong> – 82–84%</li>
                        <li><strong>2.50</strong> – 79–81%</li>
                        <li><strong>2.75</strong> – 76–78%</li>
                        <li><strong>3.00</strong> – 75%</li>
                        <li><strong>5.00</strong> – Failed</li>
                        <li><strong>NE</strong> – No Entry</li>
                    </ul>
                </div>
            </div>
    </div>

    <div style="margin-top: 40px; text-align: center; font-size: 11px; font-style: italic; color: #444;">
     <p>This is <strong>not</strong> an official Certificate of Grades. For the official document, please request a Certificate of Grades (COG) from the Registrar's Office.</p>
    </div>

</body>
</html>
