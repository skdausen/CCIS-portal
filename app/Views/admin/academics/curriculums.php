<!-- Main Container -->
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

      <!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Curriculum Courses</h3>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
        Add New Curriculum
    </button>
</div>


      <!-- Filters -->
<form method="get" class="mb-4">
    <div class="row g-2">
        <div class="col-md-4">
            <select name="curriculum_id" class="form-select" onchange="this.form.submit()">
                <option value="">Filter by Curriculum</option>
                <?php foreach ($curriculums as $curriculumOption): ?>
                    <option value="<?= $curriculumOption['curriculum_id'] ?>"
                        <?= $selectedCurriculum == $curriculumOption['curriculum_id'] ? 'selected' : '' ?>>
                        <?= esc($curriculumOption['curriculum_name']) ?> (<?= esc($curriculumOption['program_name']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

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
            <button type="button" id="clearFilterBtn" class="btn btn-secondary">Clear</button>
        </div>
    </div>
</form>

<?php foreach ($curriculumsToDisplay as $curriculum): ?>
    <div class="card mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div>
                <strong><?= esc($curriculum['curriculum_name']) ?></strong> (<?= esc($curriculum['program_name']) ?>)
            </div>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $curriculum['curriculum_id'] ?>">
                Edit
            </button>
        </div>
        <div class="card-body">
            <?php if (!empty($curriculumSubjects[$curriculum['curriculum_id']])): ?>
                <ul class="list-group">
                    <?php foreach ($curriculumSubjects[$curriculum['curriculum_id']] as $subject): ?>
                        <li class="list-group-item"><?= esc($subject['subject_code']) ?> - <?= esc($subject['subject_name']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">No subjects assigned to this curriculum.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 👇 Edit Modal (inside the same loop) -->
    <div class="modal fade" id="editModal<?= $curriculum['curriculum_id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $curriculum['curriculum_id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="<?= site_url('admin/academics/curriculums/update/' . $curriculum['curriculum_id']) ?>">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Curriculum</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Curriculum Name</label>
                            <input type="text" name="curriculum_name" class="form-control" value="<?= esc($curriculum['curriculum_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Program</label>
                            <select name="program_id" class="form-select" required>
                                <option value="" disabled>Select Program</option>
                                <?php foreach ($programs as $program): ?>
                                    <option value="<?= $program['program_id'] ?>" <?= $program['program_id'] == $curriculum['program_id'] ? 'selected' : '' ?>>
                                        <?= esc($program['program_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>


<!-- Add Curriculum Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="<?= site_url('admin/academics/curriculums/create') ?>">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add New Curriculum</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="curriculum_name" class="form-label">Curriculum Name</label>
                        <input type="text" name="curriculum_name" id="curriculum_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="program_id" class="form-label">Program</label>
                        <select name="program_id" id="program_id" class="form-select" required>
                            <option value="" selected disabled>Select Program</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?= $program['program_id'] ?>">
                                    <?= esc($program['program_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.getElementById('clearFilterBtn').addEventListener('click', function() {
    window.location.href = "<?= site_url('admin/academics/curriculums') ?>";
});
</script>
