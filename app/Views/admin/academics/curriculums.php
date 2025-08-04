<!-- Main Container -->
<div class="main-container">
    <div class="container mt-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Curriculum Courses</h3>
        </div>

        <!-- Search Filter -->
        <div class="row align-items-center mb-4">
            <div class="col-md-5 mb-2 d-flex gap-2">
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
                    <div class="card-body d-flex justify-content-between flex-column flex-md-row">
                        <div class="mb-3 mb-md-0">
                            <h5 class="curriculum-title mb-1">
                                <a href="<?= site_url('admin/academics/curriculums/view/' . $curriculum['curriculum_id']) ?>">
                                    <?= esc($curriculum['curriculum_name']) ?>
                                </a>
                            </h5>
                            <p class="curriculum-program mb-0"><?= esc($curriculum['program_name']) ?></p>
                        </div>
                        <button class="btn btn-sm btn-outline-primary ms-auto ms-md-0 align-self-md-center"
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
           <?php if ($total > 0 || $totalPages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center gap-2">

                        <!-- Previous Button -->
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                            href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                            Prev
                            </a>
                        </li>

                       <!-- Page Numbers -->
                        <?php if ($total > 0): ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link"
                                    href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        <?php endif; ?>


                        <!-- Next Button -->
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link"
                            href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                            Next
                            </a>
                        </li>

                    </ul>
                </nav>
            <?php endif; ?>


        </div>
    </div>
</div>

<!-- Scripts -->
<!-- <script>
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
</script> -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterSelect = document.getElementById('curriculumFilter');
    const searchInput = document.getElementById('curriculumSearch');
    const clearBtn = document.getElementById('clearFilterBtn'); // ✅ this is your actual button ID
    const curriculumCards = document.querySelectorAll('.curriculum-card');

    const urlParams = new URLSearchParams(window.location.search);
    const currentFilter = urlParams.get('curriculum_id') || '';
    const currentSearch = urlParams.get('search') || '';

    if (filterSelect) filterSelect.value = currentFilter;
    if (searchInput) searchInput.value = currentSearch;

    if (searchInput && currentSearch) {
        searchInput.focus();
        const val = searchInput.value;
        searchInput.value = '';
        searchInput.value = val;
    }

    if (filterSelect) {
        filterSelect.addEventListener('change', () => {
            urlParams.set('curriculum_id', filterSelect.value);
            urlParams.set('page', 1);
            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        });
    }

    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                urlParams.set('search', searchInput.value.trim());
                urlParams.set('page', 1);
                window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
            }, 500);
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            curriculumCards.forEach(card => card.style.display = ''); // show all cards again
            window.location.href = window.location.pathname; // clears all URL filters
        });
    }
});
</script>


<script>
document.addEventListener('keydown', function(event) {
    const isModalOpen = document.querySelector('.modal.show');
    if (isModalOpen) return;

    const currentPage = <?= $page ?>;
    const totalPages = <?= $totalPages ?>;
    const urlParams = new URLSearchParams(window.location.search);

    if (event.key === 'ArrowRight') {
        let nextPage = currentPage + 1;
        if (nextPage > totalPages) nextPage = 1;
        urlParams.set('page', nextPage);
        window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
    }

    if (event.key === 'ArrowLeft') {
        let prevPage = currentPage - 1;
        if (prevPage < 1) prevPage = totalPages;
        urlParams.set('page', prevPage);
        window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
    }
});
</script>

