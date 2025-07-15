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
            <button type="button" id="clearFilterBtn" class="btn btn-secondary ms-2">Clear</button>
        </div>
        <div class="col-md-5 mb-2">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by code or description...">
        </div>
        
        <div class="col-md-4 mb-2 d-flex justify-content-end">
            <!-- Add Subject Button -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Add New Subject</button>
        </div>
    </div>


        <!-- Subjects Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="subjectsTable">
                <thead class="table-light">
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Subject Type</th>
                        <th>Lecture Units</th>
                        <th>Lab Units</th>
                        <th>Total Units</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject): ?>
                    <tr>
                        <td><?= esc($subject['subject_code']) ?></td>
                        <td><?= esc($subject['subject_name']) ?></td>
                        <td><?= esc($subject['subject_type']) ?></td>
                        <td><?= esc($subject['lec_units']) ?></td>
                        <td><?= esc($subject['lab_units']) ?></td>
                        <td><?= esc($subject['total_units']) ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $subject['subject_id'] ?>">Edit</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $subject['subject_id'] ?>">Delete</button>
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
                                            <label>Subject Code</label>
                                            <input type="text" name="subject_code" class="form-control" value="<?= esc($subject['subject_code']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Subject Name</label>
                                            <input type="text" name="subject_name" class="form-control" value="<?= esc($subject['subject_name']) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Subject Type</label>
                                            <select name="subject_type" class="form-select" required>
                                                <option value="LEC" <?= $subject['subject_type'] == 'LEC' ? 'selected' : '' ?>>LEC</option>
                                                <option value="LEC with LAB" <?= $subject['subject_type'] == 'LEC with LAB' ? 'selected' : '' ?>>LEC with LAB</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Lecture Units</label>
                                            <input type="number" name="lec_units" class="form-control" value="<?= esc($subject['lec_units']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Lab Units</label>
                                            <input type="number" name="lab_units" class="form-control" value="<?= esc($subject['lab_units']) ?>" required>
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
            <input type="number" name="lec_units" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Lab Units</label>
            <input type="number" name="lab_units" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Add Subject</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
                <button type="button" class="btn btn-danger" onclick="location.reload()">Retry</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
