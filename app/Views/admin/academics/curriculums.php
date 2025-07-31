<!-- Main Container -->
<div class="main-container">
    <div class="container mt-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Curriculum Courses</h3>
        </div>

        <!-- Search Filter -->
        <div class="row align-items-center mb-4">
            <div class="col-md-5 d-flex gap-2">
                <input type="text" id="curriculumSearch" class="form-control" placeholder="Search Curriculum Name...">
                <button type="button" id="clearFilterBtn" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2">Clear</button>
            </div>
            <div class="col-md-7 mb-2 d-flex justify-content-end">
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add New Curriculum
                </button>        
            </div>
        </div>

        <!-- Add Curriculum Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="post" action="<?= site_url('admin/academics/curriculums/create') ?>">
                        <?= csrf_field() ?>
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add New Curriculum</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                            <button type="submit" class="btn btn-outline-success">Add</button>
                            <button type="button" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Curriculum Cards -->
        <div id="curriculumList">
            <?php foreach ($curriculumsToDisplay as $curriculum): ?>
                <div class="card mb-3 curriculum-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="curriculum-title mb-1">
                                <a href="<?= site_url('admin/academics/curriculums/view/' . $curriculum['curriculum_id']) ?>">
                                    <?= esc($curriculum['curriculum_name']) ?>
                                </a>
                            </h5>
                            <p class="curriculum-program mb-0"><?= esc($curriculum['program_name']) ?></p>
                        </div>
                        <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $curriculum['curriculum_id'] ?>">
                            Edit
                        </button>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?= $curriculum['curriculum_id'] ?>" tabindex="-1" aria-hidden="true">
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
                                        <label for="curriculum_name_<?= $curriculum['curriculum_id'] ?>" class="form-label">Curriculum Name</label>
                                        <input type="text" name="curriculum_name"
                                            id="curriculum_name_<?= $curriculum['curriculum_id'] ?>"
                                            class="form-control"
                                            value="<?= esc($curriculum['curriculum_name']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="program_id_<?= $curriculum['curriculum_id'] ?>" class="form-label">Program</label>
                                        <select name="program_id" id="program_id_<?= $curriculum['curriculum_id'] ?>" class="form-select" required>
                                            <?php foreach ($programs as $program): ?>
                                                <option value="<?= $program['program_id'] ?>" <?= $program['program_id'] == $curriculum['program_id'] ? 'selected' : '' ?>>
                                                    <?= esc($program['program_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-outline-success">Update</button>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    const searchInput = document.getElementById('curriculumSearch');
    const curriculumCards = document.querySelectorAll('.curriculum-card');

    searchInput.addEventListener('input', function() {
        const keyword = this.value.toLowerCase();
        curriculumCards.forEach(card => {
            const title = card.querySelector('.curriculum-title').textContent.toLowerCase();
            const program = card.querySelector('.curriculum-program').textContent.toLowerCase();
            const combined = title + " " + program;
            card.style.display = combined.includes(keyword) ? '' : 'none';
        });
    });

    document.getElementById('clearFilterBtn').addEventListener('click', function() {
        searchInput.value = '';
        curriculumCards.forEach(card => card.style.display = '');
    });
</script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
