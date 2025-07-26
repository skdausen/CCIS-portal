<div class="container mt-5">
    <div class="position-relative text-center mb-4 w-100">
        
        <!-- Back Arrow - positioned at the start -->
        <a href="<?= site_url('student/grades/grades') ?>" 
        class="position-absolute start-0 top-50 translate-middle-y btn btn-link text-muted text-decoration-none d-flex align-items-center gap-2 px-0">
            <i class="fas fa-arrow-left fs-3"></i>
        </a>

        <!-- Centered Title -->
        <h3 class="m-0">My Curriculum Plan View</h3>

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
                            <td class="text-center"><?= $totalLec ?></td>
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

<div class="container" style="max-width: 1300px;">
    <div class="text-end mt-4 mb-2">
        <a href="<?= site_url('student/grades/curriculum_download') ?>" class="btn btn-outline-success">
            Download PDF
        </a>
    </div>
</div>


<div class="container px-4" style="max-width: 900px;">  <!-- Match your table width -->
    <div class="row gx-5 mt-5">
        <!-- Left Column: Legend -->
        <div class="col-md-6">
            <div class="p-4 rounded shadow-sm bg-white h-100">
                <h5 class="fw-bold mb-3 text-purple">Legend for Latin Honors</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span class="fw-bold">Summa Cum Laude:</span> 1.0 – 1.25 <br>
                        <small class="text-muted">No grade below 2.0</small>
                    </li>
                    <li class="list-group-item">
                        <span class="fw-bold">Magna Cum Laude:</span> 1.26 – 1.5 <br>
                        <small class="text-muted">No grade below 2.25</small>
                    </li>
                    <li class="list-group-item">
                        <span class="fw-bold">Cum Laude:</span> 1.51 – 1.75 <br>
                        <small class="text-muted">No grade below 2.5</small>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Column: Computed GWA and Honors -->
        <div class="col-md-6">
            <div class="p-4 rounded shadow-sm bg-white h-100">
                <h5 class="fw-bold mb-3 text-purple">Automated GWA Result</h5>
                <div class="alert gwa-box">
                    <p class="mb-2 fs-5">Your Computed GWA: <strong><?= $gwa !== null ? $gwa : 'N/A' ?></strong></p>

                    <?php if ($honor): ?>
                        <p class="mb-2 text-success fs-6">You are qualified as <strong><?= esc($honor) ?></strong></p>
                    <?php else: ?>
                        <p class="mb-2 text-muted fs-6">You do not qualify for any Latin honor based on computed GWA.</p>
                    <?php endif; ?>

                    <p class="text-muted small fst-italic mt-3">
                        This is an <strong>automated calculation only.</strong> Final awarding of honors is subject to deliberation by the Academic Committee.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
