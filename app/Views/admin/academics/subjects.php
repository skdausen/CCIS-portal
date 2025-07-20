<!-- Main Container -->
<div class="main-container">

    <div class="container mt-5">
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Subjects Management</h3>
        </div>


        <!-- FILTERS & SEARCH -->
    <div class="row mb-3">
        <div class="col-md-3 mb-2 d-flex">
            <select id="categoryFilter" class="form-select">
                <option value="">Filter by Category</option>
                <option value="cs">CS</option>
                <option value="ge">GE</option>
                <option value="pe">PE</option>
            </select>
            <button type="button" id="clearFilterBtn" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2 ms-2">Clear</button>
        </div>
        <div class="col-md-5 mb-2">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by code or description...">
        </div>
        
        <div class="col-md-4 mb-2 d-flex justify-content-end">
            <!-- Add Subject Button -->
            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addModal">Add New Subject</button>
        </div>
    </div>


        <!-- Subjects Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover custom-padding" id="subjectsTable">
                <thead class="table-light">
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Subject Type</th>
                        <th class="text-center">Lecture Units</th>
                        <th class="text-center">Lab Units</th>
                        <th class="text-center">Total Units</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject): ?>
                    <tr>
                        <td><?= esc($subject['subject_code']) ?></td>
                        <?php
                            $fullTitle = trim($subject['subject_name']);
                            $shortTitle = (strlen($fullTitle) > 52)
                                ? mb_substr($fullTitle, 0, 52) . '...'
                                : $fullTitle;
                        ?>
                        <td title="<?= esc($fullTitle) ?>">
                            <?= esc($shortTitle) ?>
                        </td>
                        <td><?= esc($subject['subject_type']) ?></td>
                        <td class="text-center"><?= esc($subject['lec_units']) ?></td>
                        <td class="text-center"><?= esc($subject['lab_units']) ?></td>
                        <td class="text-center"><?= esc($subject['total_units']) ?></td>
                        <td>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $subject['subject_id'] ?>">Edit</button>
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $subject['subject_id'] ?>">Delete</button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $subject['subject_id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="post" action="<?= site_url('admin/academics/subjects/update/' . $subject['subject_id']) ?>">
                                    <?= csrf_field() ?>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Subject</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label>Curriculum</label>
                                            <select name="curriculum_id" class="form-select" required>
                                                <option value="">Select Curriculum</option>
                                                <?php foreach ($curriculums as $curr): ?>
                                                    <option value="<?= $curr['curriculum_id'] ?>" <?= $curr['curriculum_id'] == $subject['curriculum_id'] ? 'selected' : '' ?>>
                                                        <?= esc($curr['curriculum_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Year Level & Semester</label>
                                            <select name="yearlevel_sem" class="form-select" required>
                                                <option value="">Select Year Level & Semester</option>
                                                <option value="Y1S1" <?= $subject['yearlevel_sem'] == 'Y1S1' ? 'selected' : '' ?>>1st Year - 1st Semester</option>
                                                <option value="Y1S2" <?= $subject['yearlevel_sem'] == 'Y1S2' ? 'selected' : '' ?>>1st Year - 2nd Semester</option>
                                                <option value="Y2S1" <?= $subject['yearlevel_sem'] == 'Y2S1' ? 'selected' : '' ?>>2nd Year - 1st Semester</option>
                                                <option value="Y2S2" <?= $subject['yearlevel_sem'] == 'Y2S2' ? 'selected' : '' ?>>2nd Year - 2nd Semester</option>
                                                <option value="Y3S1" <?= $subject['yearlevel_sem'] == 'Y3S1' ? 'selected' : '' ?>>3rd Year - 1st Semester</option>
                                                <option value="Y3S2" <?= $subject['yearlevel_sem'] == 'Y3S2' ? 'selected' : '' ?>>3rd Year - 2nd Semester</option>
                                                <option value="Y3S3" <?= $subject['yearlevel_sem'] == 'Y3S3' ? 'selected' : '' ?>>3rd Year - Midyear</option>
                                                <option value="Y4S1" <?= $subject['yearlevel_sem'] == 'Y4S1' ? 'selected' : '' ?>>4th Year - 1st Semester</option>
                                                <option value="Y4S2" <?= $subject['yearlevel_sem'] == 'Y4S2' ? 'selected' : '' ?>>4th Year - 2nd Semester</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Subject Code</label>
                                            <input type="text" name="subject_code" class="form-control" value="<?= esc($subject['subject_code']) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Subject Name</label>
                                            <input type="text" name="subject_name" class="form-control" value="<?= esc($subject['subject_name']) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Subject Type</label>
                                            <select name="subject_type" class="form-select edit-subject-type" data-id="<?= $subject['subject_id'] ?>" onchange="toggleEditUnits(<?= $subject['subject_id'] ?>)" required>
                                                <option value="LEC" <?= $subject['subject_type'] == 'LEC' ? 'selected' : '' ?>>LEC</option>
                                                <option value="LEC with LAB" <?= $subject['subject_type'] == 'LEC with LAB' ? 'selected' : '' ?>>LEC with LAB</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Lecture Units</label>
                                            <input type="number" name="lec_units" class="form-control" value="<?= esc($subject['lec_units']) ?>" required min="0">
                                        </div>

                                        <div class="mb-3 edit-lab-units-group-<?= $subject['subject_id'] ?>" style="<?= $subject['subject_type'] == 'LEC with LAB' ? '' : 'display: none;' ?>">
                                            <label>Lab Units</label>
                                            <input type="number" name="lab_units" class="form-control" min="0" value="<?= esc($subject['lab_units']) ?>">
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-outline-success">Update</button>
                                        <button type="button" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal<?= $subject['subject_id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="post" action="<?= site_url('admin/academics/subjects/delete/' . $subject['subject_id']) ?>">
                                <?= csrf_field() ?>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete <strong><?= esc($subject['subject_code']) ?></strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                                        <button type="button" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
                        <nav aria-label="Subjects pagination">
                <ul class="pagination justify-content-center my-4">
                    <?php if ($page > 1): ?>
                        <li class="page-item mx-1">
                            <a class="page-link" href="<?= site_url('admin/academics/subjects?page=' . ($page - 1)) ?>">Previous</a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled mx-1">
                            <span class="page-link">Previous</span>
                        </li>
                    <?php endif; ?>

                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item mx-1 <?= ($page == $p) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= site_url('admin/academics/subjects?page=' . $p) ?>">
                                <?= $p ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item mx-1">
                            <a class="page-link" href="<?= site_url('admin/academics/subjects?page=' . ($page + 1)) ?>">Next</a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled mx-1">
                            <span class="page-link">Next</span>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>

        </div>
    </div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= site_url('admin/academics/subjects/create') ?>" method="post">
        <?= csrf_field() ?>
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="addModalLabel">Add New Subject</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
            <div class="modal-body">

            <div class="mb-3">
            <label>Curriculum</label>
            <select name="curriculum_id" class="form-select" required>
                <option value="">Select Curriculum</option>
                <?php foreach ($curriculums as $curr): ?>
                <option value="<?= $curr['curriculum_id'] ?>">
                <?= esc($curr['curriculum_name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            </div>

            <!-- Year Level & Semester Dropdown (ENUM Values) -->
            <div class="mb-3">
                <label>Year Level & Semester</label>
                <select name="yearlevel_sem" class="form-select" required>
                    <option value="">Select Year Level & Semester</option>
                    <option value="Y1S1">1st Year - 1st Semester</option>
                    <option value="Y1S2">1st Year - 2nd Semester</option>
                    <option value="Y2S1">2nd Year - 1st Semester</option>
                    <option value="Y2S2">2nd Year - 2nd Semester</option>
                    <option value="Y3S1">3rd Year - 1st Semester</option>
                    <option value="Y3S2">3rd Year - 2nd Semester</option>
                    <option value="Y3S3">3rd Year - Midyear</option>
                    <option value="Y4S1">4th Year - 1st Semester</option>
                    <option value="Y4S2">4th Year - 2nd Semester</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Subject Code</label>
                <input type="text" name="subject_code" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Subject Name</label>
                <input type="text" name="subject_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Subject Type</label>
                <select name="subject_type" class="form-select" id="add_subject_type" onchange="toggleAddUnits()" required>
                    <option value="LEC">LEC</option>
                    <option value="LEC with LAB">LEC with LAB</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Lecture Units</label>
                <input type="number" id="lec_units" name="lec_units" class="form-control" required min="0">
            </div>

            <div class="mb-3" id="lab_units_group" style="display: none;">
                <label>Lab Units</label>
                <input type="number" id="lab_units" name="lab_units" class="form-control" min="0" value="0">
            </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-success">Add Subject</button>
                <button type="button" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
        </form>
    </div>
</div>





<!-- FILTER SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filter = document.getElementById('categoryFilter');
        const search = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearFilterBtn');
        const rows = document.querySelectorAll('#coursesTable tbody tr');

        function filterRows() {
            const categoryVal = filter.value.toLowerCase();
            const searchVal = search.value.toLowerCase();

            rows.forEach(row => {
                const code = row.cells[0].textContent.toLowerCase(); // Course Code column
                const desc = row.cells[1].textContent.toLowerCase(); // Description column

                const matchCategory = !categoryVal || code.startsWith(categoryVal); // Example: starts with 'cs'
                const matchSearch = !searchVal || code.includes(searchVal) || desc.includes(searchVal);

                row.style.display = matchCategory && matchSearch ? '' : 'none';
            });
        }

        filter.addEventListener('change', filterRows);
        search.addEventListener('input', filterRows);
        clearBtn.addEventListener('click', () => {
            filter.value = '';
            search.value = '';
            filterRows();
        });

        // Initialize to show all rows
        filterRows();
    });
</script>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="successMessage"></p>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="errorMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" onclick="location.reload()">Retry</button>
                <button type="button" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Flash Message Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filter = document.getElementById('categoryFilter');
        const search = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearFilterBtn');
        const rows = document.querySelectorAll('#subjectsTable tbody tr');

        function filterRows() {
            const categoryVal = filter.value.toLowerCase();
            const searchVal = search.value.toLowerCase();

            rows.forEach(row => {
                const code = row.cells[0].textContent.toLowerCase(); // Subject Code
                const name = row.cells[1].textContent.toLowerCase(); // Subject Name

                const matchCategory = !categoryVal || code.startsWith(categoryVal); // Example filter
                const matchSearch = !searchVal || code.includes(searchVal) || name.includes(searchVal);

                row.style.display = matchCategory && matchSearch ? '' : 'none';
            });
        }

        filter.addEventListener('change', filterRows);
        search.addEventListener('input', filterRows);
        clearBtn.addEventListener('click', () => {
            filter.value = '';
            search.value = '';
            filterRows();
        });

        filterRows();
    });
</script>
\<script>
function toggleAddUnits() {
    const typeSelect = document.getElementById('add_subject_type');
    const labGroup = document.getElementById('lab_units_group');
    const lecInput = document.getElementById('lec_units');
    const labInput = document.getElementById('lab_units');

    if (typeSelect.value === 'LEC') {
        labGroup.style.display = 'none';
        labInput.value = '';
    } else {
        labGroup.style.display = 'block';
    }
}

document.getElementById('add_subject_type').addEventListener('change', toggleAddUnits);

// Form validation before submit
document.querySelector('#addModal form').addEventListener('submit', function(e) {
    const typeSelect = document.getElementById('add_subject_type');
    const lecInput = document.getElementById('lec_units');
    const labInput = document.getElementById('lab_units');

    if (lecInput.value === '' || parseFloat(lecInput.value) < 0) {
        alert('Lecture Units must not be empty or negative.');
        e.preventDefault();
        return;
    }

    if (typeSelect.value === 'LEC with LAB') {
        if (labInput.value === '' || parseFloat(labInput.value) < 0) {
            alert('Lab Units must not be empty or negative.');
            e.preventDefault();
            return;
        }
    }
});

// Run once on page load (optional for safety)
toggleAddUnits();
</script>

<script>
function toggleAddUnits() {
    const typeSelect = document.getElementById('add_subject_type');
    const labGroup = document.getElementById('lab_units_group');
    const lecInput = document.getElementById('lec_units');
    const labInput = document.getElementById('lab_units');

    if (typeSelect.value === 'LEC') {
        labGroup.style.display = 'none';
        labInput.value = 0; // Automatically sets Lab units to 0 for LEC
    } else {
        labGroup.style.display = 'block';
    }
}

// Prevent submitting with empty or negative units
document.querySelector('#addModal form').addEventListener('submit', function(e) {
    const typeSelect = document.getElementById('add_subject_type');
    const lecInput = document.getElementById('lec_units');
    const labInput = document.getElementById('lab_units');

    if (lecInput.value === '' || parseFloat(lecInput.value) < 0) {
        alert('Lecture Units must not be empty or negative.');
        e.preventDefault();
        return;
    }

    if (typeSelect.value === 'LEC with LAB') {
        if (labInput.value === '' || parseFloat(labInput.value) < 0) {
            alert('Lab Units must not be empty or negative.');
            e.preventDefault();
            return;
        }
    }
});

document.getElementById('add_subject_type').addEventListener('change', toggleAddUnits);
document.addEventListener('DOMContentLoaded', toggleAddUnits);
</script>

<script>
function toggleEditUnits(id) {
    const subjectType = document.querySelector(`select.edit-subject-type[data-id="${id}"]`).value;
    const labUnitsGroup = document.querySelector(`.edit-lab-units-group-${id}`);
    if (subjectType === 'LEC with LAB') {
        labUnitsGroup.style.display = '';
    } else {
        labUnitsGroup.style.display = 'none';
    }
}
</script>

<script>
    document.addEventListener('keydown', function(event) {
        const currentPage = <?= $page ?>;
        const totalPages = <?= $totalPages ?>;
        const baseUrl = "<?= site_url('admin/academics/subjects?page=') ?>";

        if (event.key === 'ArrowRight') {
            let nextPage = currentPage + 1;
            if (nextPage > totalPages) nextPage = totalPages;
            window.location.href = baseUrl + nextPage;
        }

        if (event.key === 'ArrowLeft') {
            let prevPage = currentPage - 1;
            if (prevPage < 1) prevPage = 1;
            window.location.href = baseUrl + prevPage;
        }
    });
</script>


