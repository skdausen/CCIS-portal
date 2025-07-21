<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('/rsc/custom_css/style.css') ?>">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>My Curriculum Plan View</h3>
    </div>

    <?php if (!empty($groupedSubjects)): ?>
        <?php foreach ($groupedSubjects as $year => $semesters): ?>
            <h4 class="fw-bold"><?= esc($year) ?></h4>

            <?php foreach ($semesters as $semester => $subjects): ?>
                <h6 class="fw-bold"><?= esc($semester) ?></h6>
                <table class="curriculum-planview-table">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                        <col style="width: 10%;">
                        <col style="width: 10%;">
                        <col style="width: 10%;">
                        <col style="width: 10%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>LEC Units</th>
                            <th>LAB Units</th>
                            <th>Total Units</th>
                            <th>Grade</th>
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
                        <tr class="fw-bold bg-light">
                            <td colspan="2" class="text-end">Total Units:</td>
                            <td><?= $totalLec ?></td>
                            <td><?= $totalLab ?></td>
                            <td><?= $totalLec + $totalLab ?></td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">No subjects in your curriculum.</p>
    <?php endif; ?>
</div>

