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
                <?php if (!empty($groupedSubjects[$currentYearKey])): ?>
                    <?php
                        $semesterGroups = [];
                        foreach ($groupedSubjects[$currentYearKey] as $subject) {
                            $semesterGroups[$subject['yearlevel_sem']][] = $subject;
                        }
                        $semesterLabels = [
                            'Y1S1' => '1st Semester',
                            'Y1S2' => '2nd Semester',
                            'Y2S1' => '1st Semester',
                            'Y2S2' => '2nd Semester',
                            'Y3S1' => '1st Semester',
                            'Y3S2' => '2nd Semester',
                            'Y3S3' => 'Midyear',
                            'Y4S1' => '1st Semester',
                            'Y4S2' => '2nd Semester',
                        ];
                    ?>

                    <?php foreach ($semesterGroups as $semester => $subjects): ?>
                        <h6 class="mt-4"><?= $semesterLabels[$semester] ?? $semester ?></h6>
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
                                    foreach ($subjects as $subject):
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
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No subjects assigned to this year level.</p>
                <?php endif; ?>
            </div>
        </div>

        <nav aria-label="Year Level Pagination">
            <ul class="pagination justify-content-center my-4">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= site_url('admin/academics/curriculums/view/' . $curriculum['curriculum_id'] . '?page=' . ($page - 1)) ?>">Previous</a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <li class="page-item <?= ($page == $p) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= site_url('admin/academics/curriculums/view/' . $curriculum['curriculum_id'] . '?page=' . $p) ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= site_url('admin/academics/curriculums/view/' . $curriculum['curriculum_id'] . '?page=' . ($page + 1)) ?>">Next</a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled"><span class="page-link">Next</span></li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Edit Modal -->
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

    </div>
</div>


<script>
    document.getElementById('clearFilterBtn').addEventListener('click', function () {
        window.location.href = "<?= site_url('admin/academics/curriculums') ?>";
    });
</script>

