<!-- Main Container -->
<div class="main-container">

    <div class="container mt-5">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Curriculum Courses</h3>

        </div>

        <!-- Search Filter -->
        <div class="row g-2 align-items-center mb-4">
            <div class="col-md-4">
                <input type="text" id="curriculumSearch" class="form-control" placeholder="Search Curriculum Name...">
            </div>
            <div class="col-md-2">
                <button type="button" id="clearFilterBtn" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2 w-100">Clear</button>
            </div>
            <div class="col-md-6 mb-2 d-flex justify-content-end">
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add New Curriculum
                </button>        
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
                    <div class="card-body">
                        <h5 class="curriculum-title">
                            <a href="<?= site_url('admin/academics/curriculums/view/' . $curriculum['curriculum_id']) ?>">
                                <?= esc($curriculum['curriculum_name']) ?>
                            </a>
                        </h5>
                        <p class="curriculum-program mb-0"><?= esc($curriculum['program_name']) ?></p>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
