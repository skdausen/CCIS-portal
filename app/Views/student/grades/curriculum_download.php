<!DOCTYPE html>
<html>
<head>
    <title><?= esc($curriculum_name ?? 'Curriculum') ?> PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h3, h4, h5 { margin: 0; padding: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }
        th, td { border: 1px solid #444; padding: 5px; text-align: center; vertical-align: middle; word-wrap: break-word; }

        .student-info-table {
            width: 100%;
            margin-bottom: 20px;
            border: none;
        }

        .student-info-table td {
            border: none;
            padding: 5px;
            font-size: 13px;
        }

        .curriculum-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 0;
        }

        .program-name {
            text-align: center;
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- Curriculum Title -->
    <h3 class="curriculum-title"><?= esc($curriculum_name ?? 'Curriculum') ?></h3>

    <!-- Program Name -->
    <p class="program-name"><?= esc($program_name ?? '') ?></p>

    <!-- Name and ID in Two Columns -->
    <table class="student-info-table">
        <tr>
            <td style="text-align: left;"><strong>Student Name:</strong> <?= esc($student_name ?? 'N/A') ?></td>
            <td style="text-align: right;"><strong>ID Number:</strong> <?= esc($student_id ?? 'N/A') ?></td>
        </tr>
    </table>

    <!-- Subject Table -->
    <?php if (!empty($groupedSubjects)): ?>
        <?php foreach ($groupedSubjects as $year => $semesters): ?>
            <h4><?= esc($year) ?></h4>
            <?php foreach ($semesters as $semester => $subjects): ?>
                <h5><?= esc($semester) ?></h5>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 15%;">Subject Code</th>
                            <th style="width: 30%;">Subject Name</th>
                            <th style="width: 10%;">LEC Units</th>
                            <th style="width: 10%;">LAB Units</th>
                            <th style="width: 10%;">Total Units</th>
                            <th style="width: 10%;">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $totalLec = 0;
                            $totalLab = 0;
                        ?>
                        <?php foreach ($subjects as $subject): ?>
                            <?php
                                $totalLec += $subject['lec_units'];
                                $totalLab += $subject['lab_units'];
                            ?>
                            <tr>
                                <td><?= esc($subject['subject_code']) ?></td>
                                <td><?= esc($subject['subject_name']) ?></td>
                                <td><?= esc($subject['lec_units']) ?></td>
                                <td><?= esc($subject['lab_units']) ?></td>
                                <td><?= esc($subject['total_units']) ?></td>
                                <td><?= $subject['grade'] !== null ? esc($subject['grade']) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2" style="text-align:right;"><strong>Total Units</strong></td>
                            <td><strong><?= $totalLec ?></strong></td>
                            <td><strong><?= $totalLab ?></strong></td>
                            <td><strong><?= $totalLec + $totalLab ?></strong></td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No subjects found in curriculum.</p>
    <?php endif; ?>

</body>
</html>
