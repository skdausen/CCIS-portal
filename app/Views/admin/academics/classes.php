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
            <li><a href="<?=site_url('admin/academics/teaching_loads')?>">Teaching Loads</a></li>
        </ul>
    </div>

    <div class="container mt-5">
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Classes Management</h3>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Add New Class</button>
        </div>

        <!-- FILTERS & SEARCH -->
        <div class="row mb-3">
            <div class="col-md-3 mb-2 d-flex">
                <select id="instructorFilter" class="form-select">
                    <option value="">Filter by Instructor</option>
                    <?php foreach ($instructors as $facultyId => $instructorName): ?>
                        <option value="<?= strtolower(esc($instructorName)) ?>">
                            <?= esc($instructorName) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="clearFilterBtn" class="btn btn-secondary ms-2">Clear</button>
            </div>
            <div class="col-md-5 mb-2">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by course or room...">
            </div>
        </div>

        <!-- CLASSES TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Course</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Instructor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?= esc($class['course_description']) ?></td>
                        <td><?= esc($class['class_day']) ?></td>
                        <td><?= date("g:i A", strtotime($class['class_start'])) ?> - <?= date("g:i A", strtotime($class['class_end'])) ?></td>
                        <td><?= esc($class['class_room']) ?></td>
                        <td><?= esc($class['fname'] . ' ' . $class['lname']) ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $class['class_id'] ?>">Edit</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $class['class_id'] ?>">Delete</button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $class['class_id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="post" action="<?= site_url('admin/academics/classes/update/' . $class['class_id']) ?>">
                                    <?= csrf_field() ?>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Class</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Instructor</label>
                                            <select name="faculty_id" class="form-select" required>
                                                <?php foreach ($instructors as $facultyId => $instructorName): ?>
                                                    <option value="<?= $facultyId ?>" <?= $facultyId == $class['faculty_id'] ? 'selected' : '' ?>><?= esc($instructorName) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Course</label>
                                            <select name="course_id" class="form-select" required>
                                                <?php foreach ($courses as $course): ?>
                                                    <option value="<?= $course['course_id'] ?>" <?= $course['course_id'] == $class['course_id'] ? 'selected' : '' ?>><?= esc($course['course_description']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Day</label>
                                            <input type="text" name="class_day" class="form-control" value="<?= esc($class['class_day']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Start Time</label>
                                            <input type="time" name="class_start" class="form-control" value="<?= esc($class['class_start']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>End Time</label>
                                            <input type="time" name="class_end" class="form-control" value="<?= esc($class['class_end']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Room</label>
                                            <input type="text" name="class_room" class="form-control" value="<?= esc($class['class_room']) ?>" required>
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
                    <div class="modal fade" id="deleteModal<?= $class['class_id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="post" action="<?= site_url('admin/academics/classes/delete/' . $class['class_id']) ?>">
                                <?= csrf_field() ?>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete <strong><?= esc($class['course_description']) ?></strong>?
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
        <!-- Add Class Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?= site_url('admin/academics/classes/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addModalLabel">Add New Class</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <!-- Instructor -->
          <div class="mb-3">
            <label for="faculty_id" class="form-label">Instructor</label>
            <select name="faculty_id" class="form-select" required>
              <option value="">Select Instructor</option>
              <?php foreach ($instructors as $facultyId => $instructorName): ?>
                <option value="<?= $facultyId ?>"><?= esc($instructorName) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Course -->
          <div class="mb-3">
            <label for="course_id" class="form-label">Course</label>
            <select name="course_id" class="form-select" required>
              <option value="">Select Course</option>
              <?php foreach ($courses as $course): ?>
                <option value="<?= $course['course_id'] ?>"><?= esc($course['course_description']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Class Day -->
          <div class="mb-3">
            <label for="class_day" class="form-label">Day</label>
            <input type="text" name="class_day" class="form-control" placeholder="e.g., Monday" required>
          </div>

          <!-- Class Start -->
          <div class="mb-3">
            <label for="class_start" class="form-label">Start Time</label>
            <input type="time" name="class_start" class="form-control" required>
          </div>

          <!-- Class End -->
          <div class="mb-3">
            <label for="class_end" class="form-label">End Time</label>
            <input type="time" name="class_end" class="form-control" required>
          </div>

          <!-- Room -->
          <div class="mb-3">
            <label for="class_room" class="form-label">Room</label>
            <input type="text" name="class_room" class="form-control" placeholder="e.g., Room 101" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Add Class</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>


</div>

<!-- FILTER SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const instructorFilter = document.getElementById('instructorFilter');
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearFilterBtn');
        const rows = document.querySelectorAll('table tbody tr');

        function filterRows() {
            const instructorVal = instructorFilter.value.toLowerCase();
            const searchVal = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const course = row.cells[0].textContent.toLowerCase();
                const room = row.cells[3].textContent.toLowerCase();
                const instructor = row.cells[4].textContent.toLowerCase();

                const matchInstructor = !instructorVal || instructor.includes(instructorVal);
                const matchSearch = !searchVal || course.includes(searchVal) || room.includes(searchVal);

                row.style.display = matchInstructor && matchSearch ? '' : 'none';
            });
        }

        instructorFilter.addEventListener('change', filterRows);
        searchInput.addEventListener('input', filterRows);
        clearBtn.addEventListener('click', () => {
            instructorFilter.value = '';
            searchInput.value = '';
            filterRows();
        });

        filterRows();
    });
</script>
<!-- ✅ Success Modal -->
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

<!-- ❌ Error Modal -->
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

<!-- ✅ Flash Message Script -->
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