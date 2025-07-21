<!-- download.php   -->
<!DOCTYPE html>
<html>
<head>
    <title>Student Grades</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h2>Student Grade Report</h2>
    <!-- Student Details -->
    <?php if (!empty($grades)): ?>
        <?php $student = $grades[0]; ?>
        <p><strong>Student ID:</strong> <?= $student->student_id ?></p>
        <p><strong>Name:</strong> <?= $student->lname ?>, <?= $student->fname ?> <?= $student->mname ?></p>
        <p><strong>Program:</strong> <?= $student->program_name ?? '-' ?></p>
        <?php if (!empty($currentSemester)): ?>
            <p><strong>Semester & School Year:</strong> <?= esc($currentSemester['semester']) ?> <?= esc($currentSemester['schoolyear']) ?></p>
        <?php endif; ?>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Semestral Grade</th>
                <th>Units</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($grades as $g): ?>
                <tr>
                    <td><?= $g->subject_code ?></td>
                    <td><?= $g->subject_name ?></td>
                    <td><?= $g->sem_grade ?? 'NE' ?></td>
                    <td><?= ($g->sem_grade ?? 'NE') === 'NE' ? '-' : ($g->total_units ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <?php
            $totalUnits = 0;
            foreach ($grades as $g) {
                if (($g->sem_grade ?? 'NE') !== 'NE') {
                    $totalUnits += (float) $g->total_units;
                }
            }
        ?>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total Units</strong></td>
                <td><strong><?= $totalUnits ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <br>
    <!-- Grade Card -->
    <div class="card mt-4 border-0 shadow-sm col-md-3 scale-down">
        <div class="card-header">
            Grade System Guide
        </div>
        <div class="card-body">
            <ul class="mb-0" style="list-style-type: none; padding-left: 0;">
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
</body>
</html>
