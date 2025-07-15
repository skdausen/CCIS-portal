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

        <!-- Curriculum and Subjects List -->
        <?php foreach ($curriculums as $curriculum): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <strong><?= esc($curriculum['curriculum_name']) ?></strong> (<?= esc($curriculum['program_name']) ?>)
                </div>
                <div class="card-body">
                    <?php if (!empty($curriculumSubjects[$curriculum['curriculum_id']])): ?>
                        <ul class="list-group">
                            <?php foreach ($curriculumSubjects[$curriculum['curriculum_id']] as $subject): ?>
                                <li class="list-group-item">
                                    <?= esc($subject['subject_code']) ?> - <?= esc($subject['subject_name']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No subjects assigned to this curriculum.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

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
