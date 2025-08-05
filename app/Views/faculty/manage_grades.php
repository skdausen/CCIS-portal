<div class="container mt-5">

    <!-- Title -->
    <div class="text-center my-4">
        <h3 class="text"><?= esc($class['subject_code'] . ' - ' . $class['subject_name']) ?></h3>
        <h3 class="text">Section: <?= esc($class['section']) ?></h3>
    </div>

    <!-- Grade Form Section -->
    <div class="card bg-white shadow rounded mb-5 mr-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-end gap-3">
                <button for="grades_file" class="btn btn-outline-success gi btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#uploadGradesModal">
                    Upload Excel
                </button>

                <!-- <button type="submit" class="btn btn-outline-primary btn-sm mb-3">Upload Grades</button> -->
                <a href="<?= base_url('faculty/class/' . $class['class_id'] . '/grades/download-template') ?>" class="btn btn-outline-success gi btn-sm mb-3">
                    Download Grade Template
                </a>
            </div>

            <div id="gradeTableContainer">
                <form action="<?= base_url('faculty/class/' . $class['class_id'] . '/grades/save') ?>" method="post">
                    <div class="table-responsive">
                        <table class="table table-bordered grade-table table-standard align-middle custom-padding">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>ID Number</th>
                                    <th>Full Name</th>
                                    <th>Midterm Grade</th>
                                    <th>Final Grade</th>
                                    <th>Semestral Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $s): $sem = $s['sem_grade'] ?? '';?>
                                <tr>
                                    <td><?= esc($s['student_id']) ?></td>
                                    <td><?= esc("{$s['lname']}, {$s['fname']} {$s['mname']}") ?></td>
                                    <td>
                                        <input type="number" 
                                            step="0.01" 
                                            min="0" 
                                            max="99.99" 
                                            name="grades[<?= $s['stb_id'] ?>][mt_numgrade]" 
                                            value="<?= ($s['mt_numgrade'] == 0 || is_null($s['mt_numgrade'])) ? '' : esc($s['mt_numgrade']) ?>"
                                            class="form-control text-end p-1" 
                                            placeholder="e.g., 87.50">
                                        <small class="text-muted">Transmuted: <?= $s['mt_grade'] ?? '--' ?></small>
                                    </td>
                                    <td>
                                        <input type="number" 
                                            step="0.01" 
                                            min="0" 
                                            max="99.99" 
                                            name="grades[<?= $s['stb_id'] ?>][fn_numgrade]" 
                                            value="<?= ($s['fn_numgrade'] == 0 || is_null($s['fn_numgrade'])) ? '' : esc($s['fn_numgrade']) ?>"
                                            class="form-control text-end p-1" 
                                            placeholder="e.g., 92.00">
                                        <small class="text-muted">Transmuted: <?= $s['fn_grade'] ?? '--' ?></small>
                                    </td>
                                    <td class="text-center">
                                        <div><?= $s['sem_numgrade'] === null ? '--' : $s['sem_numgrade'] ?></div>
                                        <small class="text-muted">Transmuted: <?= $sem === null ? '--' : $sem ?></small>
                                    </td>

                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Card Footer Inside the Form but Outside the Table -->
                    <div class="card-footer bg-white border-0 d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-outline-success me-2">Save Grades</button>
                        <a href="<?= base_url('faculty/class/' . $class['class_id']) ?>" class="btn btn btn-outline-secondary px-3 rounded-1">Back</a>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade front-modal" id="uploadFeedbackModal" tabindex="-1" aria-labelledby="uploadFeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="uploadFeedbackModalLabel">Upload Result</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="uploadFeedbackMessage">
            <!-- Message gets injected here -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>

<!-- Upload Grades Modal -->
<form id="uploadGradesForm" action="<?= base_url('faculty/class/' . $class['class_id'] . '/grades/upload') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="file" class="form-control d-none" name="grades_file" id="grades_file" accept=".xlsx,.xls" required>

    <div class="modal fade" id="uploadGradesModal" tabindex="-1" aria-labelledby="uploadGradesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadGradesModalLabel">Upload Grades (Excel)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>Upload a grade template. The Excel file must contain the following headers (case-insensitive):</p>
                    <ul class="mb-3">
                        <li><code>student_id</code> (Should be in the first column)</li>
                        <li><code>MG</code> (Midterm Grade)</li>
                        <li><code>TFG</code> (Tentative Final Grade)</li>
                    </ul>
                </div>

                <div class="modal-footer">
                    <button type="button" id="triggerUploadBtn" class="btn btn-outline-success">
                        Upload Grades
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmUploadModal" tabindex="-1" aria-labelledby="confirmUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="confirmUploadModalLabel">Confirm Grade Changes</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p class="mb-2">
                <strong>File:</strong> <span id="uploadedFileName" class="text-primary">[No file selected]</span>
            </p>
            <p>The following grades will be updated. Please review carefully:</p>
            <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-dark">
                <tr>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Midterm Grade (Old → New)</th>
                    <th>Final Grade (Old → New)</th>
                </tr>
                </thead>
                <tbody id="changePreviewTableBody"></tbody>
            </table>
            </div>
        </div>
        <div class="modal-footer">
            <button id="confirmUploadBtn" class="btn btn-outline-success">Confirm and Save</button>
            <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
        </div>
    </div>
</div>


<script src="<?= base_url('rsc/custom_js/jquery-3.6.0.min.js') ?>"></script>

<script>
    const fileInput = document.getElementById('grades_file');
    const uploadForm = document.getElementById('uploadGradesForm');
    const uploadBtn = document.getElementById('triggerUploadBtn');

    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('grades_file'); // your <input type="file"> element
        const fileNameSpan = document.getElementById('uploadedFileName');

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : '[No file selected]';
                fileNameSpan.textContent = fileName;
            });
        }
    });


    uploadBtn.addEventListener('click', function () {
        fileInput.click();
    });

    fileInput.addEventListener('change', function () {
        if (fileInput.files.length === 0) return;

        const formData = new FormData(uploadForm);
        formData.append("<?= csrf_token() ?>", "<?= csrf_hash() ?>");
        uploadBtn.disabled = true;
        const originalText = uploadBtn.innerHTML;
        uploadBtn.innerHTML = 'Uploading...';

        $.ajax({
            url: uploadForm.action,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = originalText;

                if (response.status === 'no_changes') {
                    $('#uploadGradesModal').modal('hide');
                    $('#uploadFeedbackMessage').html("No grade changes detected. Please review your inputs");
                    $('#uploadFeedbackModal').modal('show');
                    fileInput.value = "";
                    return;
                }

                if (response.status === 'changes_detected') {
                    $('#uploadGradesModal').modal('hide');
                    const tableBody = document.getElementById('changePreviewTableBody');
                    tableBody.innerHTML = "";

                    let feedbackMessages = [];

                    if (response.extra_students.length > 0) {
                        feedbackMessages.push("<strong>Extra student IDs not found:</strong><br>" + response.extra_students.map(id => id.toUpperCase()).join(', '));
                    }

                    if (response.students_with_no_grades.length > 0) {
                        feedbackMessages.push("<strong>Students with missing MG or TFG:</strong><br>" + response.students_with_no_grades.map(id => id.toUpperCase()).join(', '));
                    }

                    
                    if (feedbackMessages.length > 0) {
                        setTimeout(() => {
                            $('#uploadFeedbackMessage').html(feedbackMessages.join('<hr>'));
                            $('#uploadFeedbackModal').modal('show');
                        }, 750);
                    }


                    if (response.changes.length > 0) {
                        response.changes.forEach(change => {
                            const mtChange = change.changes.mt_numgrade 
                                ? `${change.changes.mt_numgrade.old} → <strong>${change.changes.mt_numgrade.new}</strong>` 
                                : 'No Change';
    
                            const fnChange = change.changes.fn_numgrade 
                                ? `${change.changes.fn_numgrade.old} → <strong>${change.changes.fn_numgrade.new}</strong>` 
                                : 'No Change';
    
                            tableBody.innerHTML += `
                                <tr>
                                    <td>${change.student_id}</td>
                                    <td>${change.fullname}</td>
                                    <td>${mtChange}</td>
                                    <td>${fnChange}</td>
                                </tr>
                            `;
                        });
    
                        const modal = new bootstrap.Modal(document.getElementById('confirmUploadModal'));
                        modal.show();
                    }
                }

                if (response.status === 'success') {
                    $('#uploadGradesModal').modal('hide');
                    $("#gradeTableContainer").load(location.href + " #gradeTableContainer > *");
                    $('#uploadFeedbackMessage').html(response.message);
                    $('#uploadFeedbackModal').modal('show');
                    fileInput.value = "";
                }
                
                if (response.status === 'error') {
                    $('#uploadGradesModal').modal('hide');
                    $('#uploadFeedbackMessage').html(response.message);
                    $('#uploadFeedbackModal').modal('show');
                    fileInput.value = "";
                }
            },
            error: function () {
                alert("An error occurred while uploading the grades.");
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = originalText;
            }
        });
    });

    // Confirm button click inside modal
    document.getElementById('confirmUploadBtn').addEventListener('click', function () {
        $.ajax({
            url: "<?= base_url('faculty/class/' . $class['class_id'] . '/grades/confirm-upload') ?>",
            type: 'POST',
            success: function (response) {
                $('#confirmUploadModal').modal('hide');
                $("#gradeTableContainer").load(location.href + " #gradeTableContainer > *");

                $('#uploadFeedbackMessage').html(response.message || "Grades uploaded successfully.");
                $('#uploadFeedbackModal').modal('show');
                fileInput.value = "";
            },
            error: function () {
                alert("An error occurred while confirming the upload.");
                fileInput.value = "";
            }
        });
    });

</script>


