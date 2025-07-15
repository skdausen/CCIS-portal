<div class="container mt-5">
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
                <ul>
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
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#manageStudentsModal">
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
              <table class="table table-striped table-bordered">
                  <thead class="table-dark">
                      <tr>
                          <th>ID Number</th>
                          <th>Full Name</th>
                          <th>Year Level</th>
                          <th>Program</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($students as $s): ?>
                      <tr>
                          <td><?= esc($s['student_id']) ?></td>
                          <td><?= esc($s['lname']) ?>, <?= esc($s['fname']) ?> <?= esc($s['mname']) ?></td>
                          <td><?= esc($s['year_level']) ?></td>
                          <td><?= esc($s['program_name']) ?></td>
                      </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
          </div>
      <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="manageStudentsModal" tabindex="-1" aria-labelledby="manageStudentsModalLabel" aria-hidden="true">
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
                  <tr>
                    <td><input type="checkbox" name="student_ids[]" value="<?= esc($student['stb_id']) ?>"></td>
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

      if (matchesSearch && matchesProgram && matchesYear) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  // Add event listeners
  document.getElementById('studentSearch').addEventListener('keyup', filterStudents);
  document.getElementById('filterProgram').addEventListener('change', filterStudents);
  document.getElementById('filterYear').addEventListener('change', filterStudents);
</script>

