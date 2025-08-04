<!DOCTYPE html>
<html>
<head>
    <title><?= esc($curriculum_name ?? 'Curriculum') ?> PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h3, h4, h5 { margin: 0; padding: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }
        th, td { border: 1px solid #444; padding: 5px; text-align: center; vertical-align: middle; word-wrap: break-word; }

        .student-info-table, .heading-table {
            /* width: 100%; */
            /* margin-bottom: 20px; */
            border: none;
        }
        .student-info-table td, .heading-table td {
            border: none;
            padding: 5px;
            font-size: 13px;
        }
        .curriculum-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 0 !important;
            margin-top: 1rem;
        }
        .program-name {
            text-align: center;
            font-size: 13px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

        <!-- Heading -->
        <table class="heading-table" width="100%" style="margin-bottom: 20px; border: none;">
            <tr>
                <!-- Left Logo -->
                <td style="width: 20%; text-align: left;">
                <img src="<?= $logo1 ?>" alt="IS Logo" style="height: 75px;">
                </td>

                <!-- Center Text -->
                <td style="width: 60%; text-align: center;">
                <div style="font-size: 13px;">Republic of the Philippines</div>
                <div style="font-size: 15px; font-weight: bold;">
                    Ilocos Sur Polytechnic State College - Main Campus
                </div>
                <div style="font-size: 13px;">College of Computing and Information Sciences</div>
                </td>

                <!-- Right Logo -->
                <td style="width: 20%; text-align: right;">
                <img src="<?= $logo2 ?>" alt="CCIS Logo" style="height: 75px;">
                </td>
            </tr>
        </table>

        <!-- Horizontal line -->
        <hr style="margin: 6px auto; border-top: 1px solid #000; width: 100%;">

        <!-- Curriculum Title -->
        <h3 class="curriculum-title"><?= esc($curriculum_name ?? 'Curriculum') ?></h3>

        <!-- Program Name -->
        <p class="program-name"><?= esc($program_name ?? '') ?></p>

        <!-- Student Info -->
        <table class="student-info-table" style="width: 100%; margin-bottom: 10px;">
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
