<div class="main-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-title">Academics</div>
        <ul class="sidebar-nav">
            <li><a href="<?= site_url('admin/academics/semesters') ?>">Semesters</a></li>
            <li><a href="<?= site_url('admin/academics/subjects') ?>">Subjects</a></li>
            <li><a href="<?= site_url('admin/academics/curriculums') ?>">Curriculum</a></li>
            <li><a href="<?= site_url('admin/academics/classes') ?>">Classes</a></li>
        </ul>
    </div>

    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Curriculum Courses</h3>
        </div>

        <form method="get" class="mb-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <select name="yearlevel_sem" class="form-select" onchange="this.form.submit()">
                        <option value="">Filter by Year Level & Semester</option>
                        <option value="Y1S1" <?= $selectedFilter == 'Y1S1' ? 'selected' : '' ?>>1st Year - 1st Semester</option>
                        <option value="Y1S2" <?= $selectedFilter == 'Y1S2' ? 'selected' : '' ?>>1st Year - 2nd Semester</option>
                        <option value="Y2S1" <?= $selectedFilter == 'Y2S1' ? 'selected' : '' ?>>2nd Year - 1st Semester</option>
                        <option value="Y2S2" <?= $selectedFilter == 'Y2S2' ? 'selected' : '' ?>>2nd Year - 2nd Semester</option>
                        <option value="Y3S1" <?= $selectedFilter == 'Y3S1' ? 'selected' : '' ?>>3rd Year - 1st Semester</option>
                        <option value="Y3S2" <?= $selectedFilter == 'Y3S2' ? 'selected' : '' ?>>3rd Year - 2nd Semester</option>
                        <option value="Y3S3" <?= $selectedFilter == 'Y3S3' ? 'selected' : '' ?>>3rd Year - Midyear</option>
                        <option value="Y4S1" <?= $selectedFilter == 'Y4S1' ? 'selected' : '' ?>>4th Year - 1st Semester</option>
                        <option value="Y4S2" <?= $selectedFilter == 'Y4S2' ? 'selected' : '' ?>>4th Year - 2nd Semester</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" id="clearFilterBtn" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2">Clear</button>
                </div>
            </div>
        </form>

        <div class="row g-4">
            <?php foreach ($curriculumsToDisplay as $curriculum): ?>
                <div class="col-12">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div class="curriculum-name">
                                <?= esc($curriculum['curriculum_name']) ?> (<?= esc($curriculum['program_name']) ?>)
                            </div>
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $curriculum['curriculum_id'] ?>">
                                Edit
                            </button>
                        </div>

                        <div class="card-body">
                            <?php if (!empty($curriculumSubjects[$curriculum['curriculum_id']])): ?>
                                <?php
                                    $groupedSubjects = [];
                                    foreach ($curriculumSubjects[$curriculum['curriculum_id']] as $subject) {
                                        $groupedSubjects[$subject['yearlevel_sem']][] = $subject;
                                    }
                                    $yearLevelLabels = [
                                        'Y1S1' => '1st Year - 1st Semester',
                                        'Y1S2' => '1st Year - 2nd Semester',
                                        'Y2S1' => '2nd Year - 1st Semester',
                                        'Y2S2' => '2nd Year - 2nd Semester',
                                        'Y3S1' => '3rd Year - 1st Semester',
                                        'Y3S2' => '3rd Year - 2nd Semester',
                                        'Y3S3' => '3rd Year - Midyear',
                                        'Y4S1' => '4th Year - 1st Semester',
                                        'Y4S2' => '4th Year - 2nd Semester',
                                    ];
                                ?>

                                <?php foreach ($yearLevelLabels as $key => $label): ?>
                                    <?php if (!empty($groupedSubjects[$key])): ?>
                                        <h6 class="mt-4 semester-heading"><?= $label ?></h6>
                                        <table class="table table-bordered fixed-columns">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Subject Code</th>
                                                    <th>Subject Name</th>
                                                    <th>LEC Units</th>
                                                    <th>LAB Units</th>
                                                    <th>Total Units</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $totalLec = 0;
                                                    $totalLab = 0;
                                                    foreach ($groupedSubjects[$key] as $subject):
                                                        $totalLec += $subject['lec_units'];
                                                        $totalLab += $subject['lab_units'];
                                                ?>
                                                    <tr>
                                                        <td><?= esc($subject['subject_code']) ?></td>
                                                        <td><?= esc($subject['subject_name']) ?></td>
                                                        <td><?= esc($subject['lec_units']) ?></td>
                                                        <td><?= esc($subject['lab_units']) ?></td>
                                                        <td><?= $subject['lec_units'] + $subject['lab_units'] ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <tr class="fw-bold bg-light">
                                                    <td colspan="2" class="text-end">Total:</td>
                                                    <td><?= $totalLec ?></td>
                                                    <td><?= $totalLab ?></td>
                                                    <td><?= $totalLec + $totalLab ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No subjects assigned to this curriculum.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal per Curriculum -->
                <div class="modal fade" id="editModal<?= $curriculum['curriculum_id'] ?>" tabindex="-1"
                    aria-labelledby="editModalLabel<?= $curriculum['curriculum_id'] ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="post"
                                action="<?= site_url('admin/academics/curriculums/update/' . $curriculum['curriculum_id']) ?>">
                                <?= csrf_field() ?>
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Curriculum</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Curriculum Name</label>
                                        <input type="text" name="curriculum_name" class="form-control"
                                            value="<?= esc($curriculum['curriculum_name']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Program</label>
                                        <select name="program_id" class="form-select" required>
                                            <option value="" disabled>Select Program</option>
                                            <?php foreach ($programs as $program): ?>
                                                <option value="<?= $program['program_id'] ?>"
                                                    <?= $program['program_id'] == $curriculum['program_id'] ? 'selected' : '' ?>>
                                                    <?= esc($program['program_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-outline-success">Update</button>
                                    <button type="button" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    document.getElementById('clearFilterBtn').addEventListener('click', function () {
        window.location.href = "<?= site_url('admin/academics/curriculums') ?>";
    });
</script>
