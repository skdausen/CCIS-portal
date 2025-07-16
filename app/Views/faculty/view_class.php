<div class="container mt-5">
    <div>
      <a href="<?= base_url('faculty/classes') ?>" class="btn btn-secondary mb-3">
        ← Back to My Classes
      </a>
    </div>
    <!-- Course Title -->
    <div class="text-center mb-4">
        <h2><?= esc($class['subject_code']) ?> - <?= esc($class['subject_name']) ?></h2>
    </div>

    <!-- Course Info -->
    <div class="row mb-3 justify-content-center">
        <div class="col-md-6">
            <p><strong>Section:</strong> <?= esc($class['section']) ?></p>
            <p><strong>Type:</strong> <?= esc($class['subject_type']) ?></p>
            <p><strong>Semester:</strong> <?= esc($class['semester']) ?>, A.Y <?= esc($class['schoolyear']) ?></p>
        </div>
        <div class="col-md-6 text-md-end">
            <p><strong>Schedule: </strong> 
                <ul class="list-unstyled">
                  <li><strong>Lecture:</strong> <?= esc($class['lec_day']) ?>, <?= esc($class['lec_start']) ?> - <?= esc($class['lec_end']) ?> (Room <?= esc($class['lec_room']) ?>)</li>
                  <?php if ($class['subject_type'] === 'LEC with LAB'): ?>
                  <li><strong>Lab:</strong> <?= esc($class['lab_day']) ?>, <?= esc($class['lab_start']) ?> - <?= esc($class['lab_end']) ?> (Room <?= esc($class['lab_room']) ?>)</li>
                  <?php endif; ?>
              </ul>                     
            </p>
        </div>
    </div>
    <hr>

    <!-- Students Section -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="text-uppercase fw-bold text-muted mb-0">Enrolled Students:</h6>
        <div>
            <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#manageStudentsModal">
                Manage Students
            </button>
            <a href="#" class="btn btn-success btn-sm">Manage Grades</a>
        </div>
    </div>

    <!-- Students Table -->
    <div class="container">
      <?php if (empty($students)): ?>
          <div class="alert alert-warning">No students enrolled in this class.</div>
      <?php else: ?>
          <div class="table-responsive">
              <table class="table table-bordered table-sm custom-padding">
                <thead class="table-dark">
                  <tr>
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>Year Level</th>
                    <th>Program</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($students as $student): ?>
                    <tr>
                      <td><?= esc($student['student_id']) ?></td>
                      <td><?= esc("{$student['lname']}, {$student['fname']} {$student['mname']}") ?></td>
                      <td><?= esc($student['year_level']) ?></td>
                      <td><?= esc($student['program_name']) ?></td>
                      <td>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmRemoveModal"
                          data-stbid="<?= $student['stb_id'] ?>" data-name="<?= esc("{$student['lname']}, {$student['fname']}") ?>">
                          Remove
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
          </div>
      <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="manageStudentsModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="manageStudentsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form action="<?= base_url('faculty/class/' . $class['class_id'] . '/enroll') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="manageStudentsModalLabel">Enroll Students</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <!-- 🔎 Filter bar -->
          <div class="row mb-3">
            <div class="col-md-4">
              <input type="text" id="studentSearch" class="form-control" placeholder="Search by name or ID number...">
            </div>
            <div class="col-md-4">
              <select id="filterProgram" class="form-select">
                <option value="">All Programs</option>
                <?php 
                  $programs = array_unique(array_column($allStudents, 'program_name'));
                  foreach ($programs as $program): ?>
                    <option value="<?= esc($program) ?>"><?= esc($program) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <select id="filterYear" class="form-select">
                <option value="">All Year Levels</option>
                <?php 
                  $years = array_unique(array_column($allStudents, 'year_level'));
                  sort($years);
                  foreach ($years as $year): ?>
                    <option value="<?= esc($year) ?>">Year <?= esc($year) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-hover table-bordered" id="studentsTable">
              <thead class="table-dark">
                <tr>
                  <th>Select</th>
                  <th>ID Number</th>
                  <th>Full Name</th>
                  <th>Year Level</th>
                  <th>Program</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($allStudents as $student): ?>
                  <tr class="selectable-row">
                    <td>
                      <input type="checkbox" name="student_ids[]" value="<?= esc($student['stb_id']) ?>" class="form-check-input d-none">
                      <span class="check-indicator"><i class="far fa-square text-muted"></i></span>
                    </td>
                    <td class="id-number"><?= esc($student['student_id']) ?></td>
                    <td class="name"><?= esc($student['lname']) ?>, <?= esc($student['fname']) ?> <?= esc($student['mname']) ?></td>
                    <td class="year"><?= esc($student['year_level']) ?></td>
                    <td class="program"><?= esc($student['program_name']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Enroll Selected</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Confirm Remove Modal -->
<div class="modal fade" id="confirmRemoveModal" tabindex="-1" aria-labelledby="confirmRemoveLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="post" id="removeStudentForm">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="confirmRemoveLabel">Confirm Removal</h5>
        </div>
        <div class="modal-body">
          Are you sure you want to remove <strong id="studentToRemoveName"></strong> from this class?
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Yes, Remove</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  // Fill modal with student info
  const removeModal = document.getElementById('confirmRemoveModal');
  removeModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const stbId = button.getAttribute('data-stbid');
    const name = button.getAttribute('data-name');

    document.getElementById('studentToRemoveName').textContent = name;
    document.getElementById('removeStudentForm').action = "<?= base_url('faculty/class/' . $class['class_id'] . '/remove-student') ?>/" + stbId;
  });
</script>


<!-- 🔥 Style for selection feedback -->
<style>
  .selectable-row {
    cursor: pointer;
  }

  .selectable-row.table-active {
    background-color: #e9f7ef !important;
  }

  .selectable-row:hover {
    background-color: #f3f3f3;
  }
</style>

<!-- 💫 JS for row selection + filtering -->
<script>
  function filterStudents() {
    const search = document.getElementById('studentSearch').value.toLowerCase();
    const selectedProgram = document.getElementById('filterProgram').value.toLowerCase();
    const selectedYear = document.getElementById('filterYear').value.toLowerCase();

    const rows = document.querySelectorAll('#studentsTable tbody tr');

    rows.forEach(row => {
      const name = row.querySelector('.name').textContent.toLowerCase();
      const id = row.querySelector('.id-number').textContent.toLowerCase();
      const year = row.querySelector('.year').textContent.toLowerCase();
      const program = row.querySelector('.program').textContent.toLowerCase();

      const matchesSearch = name.includes(search) || id.includes(search);
      const matchesProgram = !selectedProgram || program === selectedProgram;
      const matchesYear = !selectedYear || year === selectedYear;

      row.style.display = (matchesSearch && matchesProgram && matchesYear) ? '' : 'none';
    });
  }

  document.getElementById('studentSearch').addEventListener('keyup', filterStudents);
  document.getElementById('filterProgram').addEventListener('change', filterStudents);
  document.getElementById('filterYear').addEventListener('change', filterStudents);

  // 🔄 Make entire row clickable to toggle checkbox
  document.querySelectorAll('.selectable-row').forEach(row => {
    row.addEventListener('click', function (e) {
      const checkbox = this.querySelector('input[type="checkbox"]');
      const icon = this.querySelector('.check-indicator i');

      // Only toggle if not clicking directly on input
      if (!e.target.matches('input')) {
        checkbox.checked = !checkbox.checked;
      }

      if (checkbox.checked) {
        this.classList.add('table-active');
        icon.classList.remove('fa-square', 'text-muted');
        icon.classList.add('fa-check-square', 'text-success');
      } else {
        this.classList.remove('table-active');
        icon.classList.remove('fa-check-square', 'text-success');
        icon.classList.add('fa-square', 'text-muted');
      }
    });
  });
</script>
