<!-- Main Container -->
<div class="main-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-title">Academics</div>
        <ul class="sidebar-nav">
            <li><a href="<?=site_url('admin/academics/semesters')?>">Semesters</a></li>
            <li><a href="<?=site_url('admin/academics/subjects')?>">Subjects</a></li>
            <li><a href="<?=site_url('admin/academics/curriculums')?>">Curriculum</a></li>
            <li><a href="<?=site_url('admin/academics/classes')?>">Classes</a></li>
        </ul>
    </div>

    <div class="container mt-5">
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Semesters Management</h3>
        </div>

        <!-- FILTERS & SEARCH -->
        <div class="row mb-3">
            <div class="col-md-3 mb-2 d-flex">
                <select id="semesterFilter" class="form-select">
                    <option value="">Filter by Semester</option>
                    <option value="first">First</option>
                    <option value="second">Second</option>
                    <option value="midyear">Midyear</option>
                </select>
                <button type="button" id="clearFilterBtn" class="btn btn-secondary ms-2">Clear</button>
            </div>
            <div class="col-md-5 mb-2">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by School Year...">
            </div>
            <div class="col-md-4 mb-2 d-flex justify-content-end">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSemesterModal">Add New Semester</button>
            </div>
        </div>

        <!-- SEMESTERS TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="semestersTable">
                <thead class="table-light">
                    <tr>
                        <th>Semester</th>
                        <th>School Year</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($semesters as $semester): ?>
                    <tr data-id="<?= $semester['semester_id'] ?>">
                        <td><?= esc(ucfirst($semester['semester'])) ?></td>
                        <td><?= esc($semester['schoolyear']) ?></td>
                        <td>
                            <?php if (!empty($semester['is_active'])): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $semester['semester_id'] ?>">Edit</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $semester['semester_id'] ?>">Delete</button>
                        </td>
                    </tr>

                    <!-- Edit Modal (inside foreach) -->
                    <div class="modal fade" id="editModal<?= $semester['semester_id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="post" action="<?= site_url('admin/academics/semesters/update/' . $semester['semester_id']) ?>">
                                    <?= csrf_field() ?>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Semester</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Semester</label>
                                            <select name="semester" class="form-select" required>
                                                <option value="first semester" <?= $semester['semester'] === 'first semester' ? 'selected' : '' ?>>First Semester</option>
                                                <option value="second semester" <?= $semester['semester'] === 'second semester' ? 'selected' : '' ?>>Second Semester</option>
                                                <option value="midyear" <?= $semester['semester'] === 'midyear' ? 'selected' : '' ?>>Midyear</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>School Year</label>
                                            <input type="text" name="schoolyear" class="form-control" value="<?= esc($semester['schoolyear']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Status</label>
                                            <select name="status" class="form-select" required>
                                                <option value="1" <?= $semester['is_active'] == 1 ? 'selected' : '' ?>>Active</option>
                                                <option value="0" <?= $semester['is_active'] == 0 ? 'selected' : '' ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal (inside foreach) -->
                    <div class="modal fade" id="deleteModal<?= $semester['semester_id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="post" action="<?= site_url('admin/academics/semesters/delete/' . $semester['semester_id']) ?>">
                                <?= csrf_field() ?>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete <strong><?= esc(ucfirst($semester['semester'])) ?> - <?= esc($semester['schoolyear']) ?></strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Add Semester Modal -->
        <div class="modal fade" id="addSemesterModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="<?= site_url('admin/academics/semesters/create') ?>" method="post" onsubmit="return checkDuplicate(this)">
                        <?= csrf_field() ?>
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Semester</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Semester</label>
                                <select name="semester" class="form-select" required>
                                    <option value="">-- Select Semester --</option>
                                    <option value="first semester">First Semester</option>
                                    <option value="second semester">Second Semester</option>
                                    <option value="midyear">Midyear</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">School Year</label>
                                <input type="text" name="schoolyear" class="form-control" placeholder="e.g., 2025-2026" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="1" <?= isset($semester) && $semester['is_active'] === 'Active' ? 'selected' : '' ?>>Active</option>
                                    <option value="0" <?= isset($semester) && $semester['is_active'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
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

        <!-- Duplicate Alert Modal -->
        <div class="modal fade" id="duplicateModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">Warning</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p id="duplicateMessage">The semester and school year combination already exists. Please try again.</p>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End of container -->
</div> <!-- End of main-container -->


<!-- Trigger duplicate modal if backend sets an error -->
<?php if (session()->getFlashdata('error')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const duplicateModal = new bootstrap.Modal(document.getElementById('duplicateModal'));
        document.getElementById('duplicateMessage').innerText = "<?= esc(session()->getFlashdata('error')) ?>";
        duplicateModal.show();
        setTimeout(() => { duplicateModal.hide(); }, 1500);
    });
</script>
<?php endif; ?>

<!-- Duplicate Check Script -->
<script>
    function checkDuplicate(form, currentId = null) {
    const semester = form.querySelector('[name="semester"]').value.trim().toLowerCase();
    const schoolyear = form.querySelector('[name="schoolyear"]').value.trim().toLowerCase();

    let isDuplicate = false;

    document.querySelectorAll('#semestersTable tbody tr').forEach(row => {
        const rowSemester = row.cells[0].textContent.trim().toLowerCase();
        const rowSchoolYear = row.cells[1].textContent.trim().toLowerCase();
        const rowId = row.getAttribute('data-id');

        if (semester === rowSemester && schoolyear === rowSchoolYear && rowId !== String(currentId || '')) {
            isDuplicate = true;
        }
    });

    if (isDuplicate) {
        const duplicateModal = new bootstrap.Modal(document.getElementById('duplicateModal'));
        duplicateModal.show();
        setTimeout(() => { duplicateModal.hide(); }, 1500);
        return false; // Prevent form submit
    }

    return true; // Allow form submit
    }
</script>

<!-- Duplicate Check Script -->
<script>
    function checkDuplicate(form, currentId = null) {
        const semester = form.querySelector('[name="semester"]').value.trim().toLowerCase();
        const schoolyear = form.querySelector('[name="schoolyear"]').value.trim().toLowerCase();

        let isDuplicate = false;

        document.querySelectorAll('#semestersTable tbody tr').forEach(row => {
            const rowSemester = row.cells[0].textContent.trim().toLowerCase();
            const rowSchoolYear = row.cells[1].textContent.trim().toLowerCase();
            const rowId = row.getAttribute('data-id');

            if (semester === rowSemester && schoolyear === rowSchoolYear && rowId !== String(currentId || '')) {
                isDuplicate = true;
            }
        });

        if (isDuplicate) {
            const duplicateModal = new bootstrap.Modal(document.getElementById('duplicateModal'));
            duplicateModal.show();

            // Auto-close after 1.5 seconds
            setTimeout(() => {
                duplicateModal.hide();
            }, 1500);

            return false; // Prevent form submission
        }

        return true; // Allow submission
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const semesterFilter = document.getElementById('semesterFilter');
        const searchInput = document.getElementById('searchInput');
        const clearFilterBtn = document.getElementById('clearFilterBtn');
        const rows = document.querySelectorAll('#semestersTable tbody tr');

        function applyFilters() {
            const semesterValue = semesterFilter.value.toLowerCase();
            const searchValue = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const semesterText = row.cells[0].textContent.trim().toLowerCase();
                const schoolYearText = row.cells[1].textContent.trim().toLowerCase();

                const matchesSemester = !semesterValue || semesterText === semesterValue;
                const matchesSearch = !searchValue || schoolYearText.includes(searchValue);

                if (matchesSemester && matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        semesterFilter.addEventListener('change', applyFilters);
        searchInput.addEventListener('input', applyFilters);
        clearFilterBtn.addEventListener('click', () => {
            semesterFilter.value = '';
            searchInput.value = '';
            applyFilters();
        });
    });
</script>

