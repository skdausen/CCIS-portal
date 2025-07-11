<div class="container mt-5">
    <!-- Course Title -->
    <div class="text-center mb-4">
        <h3 class="fw-bold"><?= esc($class['course_description'] ?? 'N/A') ?></h3>
    </div>

    <!-- Course Info -->
    <div class="row mb-3 justify-content-center">
        <div class="col-md-6">
            <p><strong>Course Code: </strong> <?= esc($class['course_code'] ?? 'N/A') ?></p>
            <p><strong>Schedule: </strong> 
                <?= esc($class['class_day'] ?? 'N/A') ?>
                <?= isset($class['class_start'], $class['class_end']) 
                ? date("g:i A", strtotime($class['class_start'])) . ' - ' . date("g:i A", strtotime($class['class_end'])) 
                : 'N/A' ?>                      
            </p>
        </div>
        <div class="col-md-6 text-md-end">
            <p><strong>Room: </strong> <?= esc($class['class_room'] ?? 'N/A') ?></p>
        </div>
    </div>
    <hr>

    <!-- Students Section -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="text-uppercase fw-bold text-muted mb-0">Students:</h6>
        <div>
            <a href="#" class="btn btn-success btn-sm me-2"
                data-bs-toggle="modal"
                data-bs-target="#manageStudentModal">
                Manage Student
            </a>
            <a href="#" class="btn btn-success btn-sm">Manage Grades</a>
        </div>
    </div>

    <!-- Students Table -->
    <div class="table-responsive mt-3">
        <table class="table table-borderless align-middle">
            <thead class="border-bottom border-purple text-uppercase small text-muted">
                <tr>
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Program</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>NLP-00-00000</td>
                    <td>Dela Cruz, Juan A.</td>
                    <td>Third Year</td>
                    <td>BSCS</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Manage Student Modal -->
<div class="modal fade" id="manageStudentModal" tabindex="-1" aria-labelledby="manageStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="manageStudentModalLabel">Manage Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- COURSE DETAILS -->
        <div class="text-center mb-4">
            <h5 class="fw-bold"><?= esc($class['course_description'] ?? 'N/A') ?></h5>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <p><strong>Course Code:</strong> <?= esc($class['course_code'] ?? 'N/A') ?></p>
            <p><strong>Schedule:</strong> 
              <?= esc($class['class_day'] ?? 'N/A') ?>
              <?= isset($class['class_start'], $class['class_end'])
                  ? date("g:i A", strtotime($class['class_start'])) . ' - ' . date("g:i A", strtotime($class['class_end']))
                  : 'N/A' ?>
            </p>
          </div>
          <div class="col-md-6 text-md-end">
            <p><strong>Room:</strong> <?= esc($class['class_room'] ?? 'N/A') ?></p>
          </div>
        </div>

        <hr>

        <!-- ADD STUDENT FORM -->
        <form action="<?= site_url('faculty/addStudentToClass') ?>" method="post" class="d-flex mb-4">
          <input type="hidden" name="class_id" value="<?= esc($class['class_id']) ?>">
          <input type="text" name="username" class="form-control me-2" placeholder="Student ID" required>
          <button type="submit" class="btn btn-success">Add Student</button>
        </form>

        <!-- STUDENT LIST -->
        <div class="table-responsive">
          <table class="table table-borderless align-middle">
            <thead class="border-bottom border-purple text-uppercase small text-muted">
              <tr>
                <th>ID Number</th>
                <th>Name</th>
                <th>Year</th>
                <th>Program</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($students as $student): ?>
              <tr>
                <td><?= esc($student['username']) ?></td>
                <td><?= esc($student['fname'] . ' ' . $student['mname'] . ' ' . $student['lname']) ?></td>
                <td><?= esc($student['year_level']) ?></td>
                <td><?= esc($student['program_name']) ?></td>
                <td>
                  <form action="<?= site_url('faculty/removeStudentFromClass') ?>" method="post">
                    <input type="hidden" name="class_id" value="<?= esc($class['class_id']) ?>">
                    <input type="hidden" name="student_id" value="<?= esc($student['user_id']) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <hr>
        <div class="text-center mt-5">
          <button class="btn btn-success" data-bs-dismiss="modal">Done</button>
        </div>
      </div>
    </div>
  </div>
</div>

