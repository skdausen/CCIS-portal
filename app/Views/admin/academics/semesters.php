<!-- Main Container -->
<div class="main-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-title">Academics</div>
        <ul class="sidebar-nav">
            <li><a href="<?= site_url('admin/academics/semesters') ?>">Semesters</a></li>
            <li><a href="<?= site_url('admin/academics/courses') ?>">Courses</a></li>
            <li><a href="<?= site_url('admin/academics/curriculums') ?>">Curriculum</a></li>
            <li><a href="<?= site_url('admin/academics/classes') ?>">Classes</a></li>
            <li><a href="<?= site_url('admin/academics/teaching_loads') ?>">Teaching Loads</a></li>
        </ul>
    </div>

    <div class="container mt-5">
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Semesters Management</h3>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSemesterModal">Add New Semester</button>
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
        </div>

        <!-- SEMESTERS TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="semestersTable">
                <thead class="table-light">
                    <tr>
                        <th>Semester</th>
                        <th>School Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($semesters as $semester): ?>
                        <tr>
                            <td><?= esc(ucfirst($semester['semester'])) ?></td>
                            <td><?= esc($semester['schoolyear']) ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $semester['semester_id'] ?>">Edit</button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $semester['semester_id'] ?>">Delete</button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <!-- Edit Modal -->
<div class="modal fade" id="editModal<?= $semester['semester_id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="<?= site_url('admin/academics/semesters/update/' . $semester['semester_id']) ?>" onsubmit="return checkDuplicate(this, <?= $semester['semester_id'] ?>)">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Semester</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="">-- Select Semester --</option>
                            <option value="first" <?= $semester['semester'] === 'first' ? 'selected' : '' ?>>First</option>
                            <option value="second" <?= $semester['semester'] === 'second' ? 'selected' : '' ?>>Second</option>
                            <option value="midyear" <?= $semester['semester'] === 'midyear' ? 'selected' : '' ?>>Midyear</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>School Year</label>
                        <input type="text" name="schoolyear" class="form-control" value="<?= esc($semester['schoolyear']) ?>" required>
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


                        <!-- Delete Modal -->
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
                                <option value="first">First</option>
                                <option value="second">Second</option>
                                <option value="midyear">Midyear</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">School Year</label>
                            <input type="text" name="schoolyear" class="form-control" placeholder="e.g., 2025-2026" required>
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
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Duplicate Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>The semester and school year combination already exists. Please try again.</p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Filter & Duplicate Check Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filter = document.getElementById('semesterFilter');
        const search = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearFilterBtn');
        const rows = document.querySelectorAll('#semestersTable tbody tr');

        function filterRows() {
            const filterVal = filter.value.toLowerCase();
            const searchVal = search.value.toLowerCase();

            rows.forEach(row => {
                const semester = row.cells[0].textContent.toLowerCase();
                const year = row.cells[1].textContent.toLowerCase();

                const matchFilter = !filterVal || semester === filterVal;
                const matchSearch = !searchVal || year.includes(searchVal);

                row.style.display = matchFilter && matchSearch ? '' : 'none';
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

    function checkDuplicate(form, currentId = null) {
        const semester = form.querySelector('[name="semester"]').value.toLowerCase();
        const schoolyear = form.querySelector('[name="schoolyear"]').value.toLowerCase();

        let duplicate = false;
        document.querySelectorAll('#semestersTable tbody tr').forEach(row => {
            const existingSemester = row.cells[0].textContent.toLowerCase();
            const existingYear = row.cells[1].textContent.toLowerCase();
            const editButton = row.querySelector('button[data-bs-target^="#editModal"]');
            const semesterId = editButton?.getAttribute('data-bs-target')?.replace('#editModal', '');

            // If semester + schoolyear match & it's not the current row being edited
            if (semester === existingSemester && schoolyear === existingYear && semesterId !== String(currentId)) {
                duplicate = true;
            }
        });

        if (duplicate) {
            const duplicateModal = new bootstrap.Modal(document.getElementById('duplicateModal'));
            duplicateModal.show();
            return false;
        }
        return true;
    }
</script>
