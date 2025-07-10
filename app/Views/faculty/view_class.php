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
            <a href="#" class="btn btn-success btn-sm me-2">Manage Student</a>
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
                <tr>
                    <td>NLP-00-00000</td>
                    <td>Doe, Jane B.</td>
                    <td>Third Year</td>
                    <td>BSCS</td>
                </tr>
                <tr>
                    <td>NLP-00-00000</td>
                    <td>Smith, John C.</td>
                    <td>Third Year</td>
                    <td>BSCS</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

