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
    <!-- Header with Add Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Semesters</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSemesterModal">Add Semester</button>
    </div>

    <!-- Flash Message Modals -->
    <?php if (session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (session()->getFlashdata('success')): ?>
                const successMessage = <?= json_encode(session()->getFlashdata('success')) ?>;
                document.getElementById('successMessage').textContent = successMessage;
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
                setTimeout(() => successModal.hide(), 2000);
            <?php elseif (session()->getFlashdata('error')): ?>
                const errorMessage = <?= json_encode(session()->getFlashdata('error')) ?>;
                document.getElementById('errorMessage').textContent = errorMessage;
                const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            <?php endif; ?>
        });
    </script>
    <?php endif; ?>

    <!-- Semester Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Semester</th>
                <th>School Year</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($semesters)): ?>
                <?php foreach ($semesters as $semester): ?>
                    <tr>
                        <td><?= esc(ucfirst($semester['semester'])) ?></td>
                        <td><?= esc($semester['schoolyear']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="text-center">No semesters found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Semester Modal -->
<div class="modal fade" id="addSemesterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= site_url('admin/academics/semesters/create') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Add New Semester</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <input type="text" name="schoolyear" class="form-control" placeholder="e.g., 2024-2025" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
