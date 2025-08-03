<!-- Main Container -->
<div class="main-container">

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
                <button type="button" id="clearFilterBtn" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2 ms-2">Clear</button>
            </div>
            <div class="col-md-5 mb-2">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by School Year...">
            </div>
            <div class="col-md-4 mb-2 d-flex justify-content-end">
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addSemesterModal">Add New Semester</button>
            </div>
        </div>

        <!-- SEMESTERS TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover semester-table custom-padding" id="semestersTable">
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
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $semester['semester_id'] ?>">Edit</button>
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $semester['semester_id'] ?>">Delete</button>
                        </td>
                    </tr>

                    <!-- Edit Modal (inside foreach) -->
                    <div class="modal fade" id="editModal<?= $semester['semester_id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <?php
                                // Split the schoolyear for pre-filling
                                $schoolYearParts = explode('-', $semester['schoolyear']);
                                $startYear = $schoolYearParts[0] ?? '';
                                $endYear = $schoolYearParts[1] ?? '';
                                ?>

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
                                                <option value="first semester" <?= $semester['semester'] === 'First Semester' ? 'selected' : '' ?>>First Semester</option>
                                                <option value="second semester" <?= $semester['semester'] === 'Second Semester' ? 'selected' : '' ?>>Second Semester</option>
                                                <option value="midyear" <?= $semester['semester'] === 'Midyear' ? 'selected' : '' ?>>Midyear</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>School Year</label>
                                            <div class="d-flex gap-2">
                                                <input type="number" name="start_year" class="form-control" id="editStartYear<?= $semester['semester_id'] ?>" value="<?= esc($startYear) ?>" required min="2000" max="2099" required>
                                                <span class="align-self-center">-</span>
                                                <input type="number" name="end_year" class="form-control" id="editEndYear<?= $semester['semester_id'] ?>" value="<?= esc($endYear) ?>" required min="2001" max="2100" readonly required>
                                            </div>
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
                                        <button type="submit" class="btn btn-outline-success">Update</button>
                                        <button type="button" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2" data-bs-dismiss="modal">Cancel</button>
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
                            <!-- School Year Inputs -->
                            <div class="mb-3">
                                <label class="form-label">School Year</label>
                                <div class="d-flex gap-2">
                                    <input type="number" name="start_year" class="form-control" id="startYearInput" placeholder="Start Year (e.g., 2025)" required min="2000" max="2099" required>
                                    <span class="align-self-center">-</span>
                                    <input type="number" name="end_year" class="form-control" id="endYearInput" placeholder="End Year" required min="2001" max="2100" readonly required>
                                </div>
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
                            <button type="submit" class="btn btn-outline-success">Add</button>
                            <button type="button" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div> <!-- End of container -->
</div> <!-- End of main-container -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-increment for ADD modal
        const addStartInput = document.getElementById('startYearInput');
        const addEndInput = document.getElementById('endYearInput');

        if (addStartInput && addEndInput) {
            addStartInput.addEventListener('input', function () {
                const startYear = parseInt(this.value);
                addEndInput.value = !isNaN(startYear) ? startYear + 1 : '';
            });
        }

        // Auto-increment for all EDIT modals
        document.querySelectorAll('[id^="editStartYear"]').forEach(startInput => {
            const semesterId = startInput.id.replace('editStartYear', '');
            const endInput = document.getElementById('editEndYear' + semesterId);

            if (endInput) {
                startInput.addEventListener('input', function () {
                    const startYear = parseInt(this.value);
                    endInput.value = !isNaN(startYear) ? startYear + 1 : '';
                });
            }
        });

        // Filter
        const semesterFilter = document.getElementById('semesterFilter');
        const searchInput = document.getElementById('searchInput');
        const clearFilterBtn = document.getElementById('clearFilterBtn');
        const tableRows = document.querySelectorAll('#semestersTable tbody tr');

        function filterTable() {
            const semesterValue = semesterFilter.value.toLowerCase();
            const searchValue = searchInput.value.toLowerCase();

            tableRows.forEach(row => {
                const semesterText = row.children[0].textContent.toLowerCase();
                const schoolYearText = row.children[1].textContent.toLowerCase();

                const semesterMatch = !semesterValue || semesterText.includes(semesterValue);
                const searchMatch = !searchValue || schoolYearText.includes(searchValue);

                if (semesterMatch && searchMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        semesterFilter.addEventListener('change', filterTable);
        searchInput.addEventListener('input', filterTable);
        clearFilterBtn.addEventListener('click', function() {
            semesterFilter.value = '';
            searchInput.value = '';
            filterTable();
        });
    });
</script>
