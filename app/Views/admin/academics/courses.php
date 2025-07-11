 <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-title">Academics</div>
            <ul class="sidebar-nav">
                <li><a href="<?=site_url('admin/academics/semesters')?>">Semesters</a></li>
                <li><a href="<?=site_url('admin/academics/courses')?>">Courses</a></li>
                <li><a href="<?=site_url('admin/academics/curriculums')?>">Curriculum</a></li>
                <li><a href="<?=site_url('admin/academics/classes')?>">Classes</a></li>
               
            </ul>
        </div>
<div class="container mt-5">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Courses Management</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Add New Course</button>
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
            <button type="button" id="clearFilterBtn" class="btn btn-secondary ms-2">Clear</button>
        </div>
        <div class="col-md-5 mb-2">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by code or description...">
        </div>
    </div>

    <!-- COURSES TABLE -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="coursesTable">
            <thead class="table-light">
                <tr>
                    <th>Course Code</th>
                    <th>Description</th>
                    <th>Lecture Units</th>
                    <th>Lab Units</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?= esc($course['course_code']) ?></td>
                    <td><?= esc($course['course_description']) ?></td>
                    <td><?= esc($course['lec_units']) ?></td>
                    <td><?= esc($course['lab_units']) ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $course['course_id'] ?>">Edit</button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $course['course_id'] ?>">Delete</button>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?= $course['course_id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="post" action="<?= site_url('admin/academics/courses/update/' . $course['course_id']) ?>">
                                <?= csrf_field() ?>
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Course</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Course Code</label>
                                        <input type="text" name="course_code" class="form-control" value="<?= esc($course['course_code']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Description</label>
                                        <input type="text" name="course_description" class="form-control" value="<?= esc($course['course_description']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Lecture Units</label>
                                        <input type="number" name="lec_units" class="form-control" value="<?= esc($course['lec_units']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Lab Units</label>
                                        <input type="number" name="lab_units" class="form-control" value="<?= esc($course['lab_units']) ?>" required>
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
                <div class="modal fade" id="deleteModal<?= $course['course_id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="post" action="<?= site_url('admin/academics/courses/delete/' . $course['course_id']) ?>">
                            <?= csrf_field() ?>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete <strong><?= esc($course['course_code']) ?></strong>?
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

<!-- ADD COURSE MODAL -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="<?= site_url('admin/academics/courses/create') ?>">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Course Code</label>
                        <input type="text" name="course_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <input type="text" name="course_description" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Lecture Units</label>
                        <input type="number" name="lec_units" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Lab Units</label>
                        <input type="number" name="lab_units" class="form-control" required>
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
                <button type="button" class="btn btn-danger" onclick="location.reload()">Retry</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Flash Message Script -->
<?php if (session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (session()->getFlashdata('success')): ?>
            const successMessage = <?= json_encode(session()->getFlashdata('success')) ?>;
            document.getElementById('successMessage').textContent = successMessage;
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            setTimeout(() => successModal.hide(), 2500);  // Auto close after 2.5 seconds
        <?php elseif (session()->getFlashdata('error')): ?>
            const errorMessage = <?= json_encode(session()->getFlashdata('error')) ?>;
            document.getElementById('errorMessage').textContent = errorMessage;
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        <?php endif; ?>
    });
</script>
<?php endif; ?>

