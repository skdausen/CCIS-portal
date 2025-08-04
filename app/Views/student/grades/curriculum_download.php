<!DOCTYPE html>
<html>
<head>
    <title><?= esc($curriculum_name ?? 'Curriculum') ?> PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h3, h4, h5 { margin: 0; padding: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }
        th, td { border: 1px solid #444; padding: 5px; text-align: center; vertical-align: middle; word-wrap: break-word; }

        .student-info-table {
            /* width: 100%; */
            /* margin-bottom: 20px; */
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

        .header-table {
            width: 100%;
            border: none;
            margin-bottom: 20px;
        }

        .school-logo {
            height: 70px;
            margin-right: 10px;
        }

        .header-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .left-logos {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .school-info {
            text-align: center;
            flex: 1;
        }

        .school-info .school-name {
            font-size: 16px;
            font-weight: bold;
        }

        .school-info .college-name {
            font-size: 14px;
        }

        .school-info .program {
            font-size: 13px;
            margin-top: 4px;
        }

        .school-info .curriculum-title {
            font-size: 15px;
            font-weight: bold;
            margin-top: 6px;
        }
    </style>
</head>
<body>

    <!-- Header with Logos and Text -->
    <div class="header-wrapper">
        <div class="left-logos">
            <!-- <img src="<?= base_url('assets/transparentlogois.png') ?>" alt="ISPSC Logo" class="school-logo">
            <img src="<?= base_url('assets/ccislogo.jpg') ?>" alt="CCIS Logo" class="school-logo"> -->
        </div>
        <div class="school-info">
            <div class="school-name">Ilocos Sur Polytechnic State College - Main Campus</div>
            <div class="college-name">College of Computing and Information Sciences</div>
            <div class="program"><?= esc($program_name ?? '') ?></div>
            <div class="curriculum-title"><?= esc($curriculum_name ?? 'Curriculum') ?></div>
        </div>
    </div>

    <!-- Student Info -->
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
