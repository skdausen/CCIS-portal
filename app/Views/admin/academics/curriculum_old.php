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
            <h3>Curriculum Courses</h3>
        </div>

        <!-- FILTERS -->
        <!-- FILTERS -->
<form method="get" class="row mb-3">
    <div class="col-md-3 mb-2">
        <select name="year_level" class="form-select" onchange="this.form.submit()">
            <option value="">Filter by Year Level</option>
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <option value="<?= $i ?>" <?= ($yearLevel == $i) ? 'selected' : '' ?>>
                    <?= $i ?> Year
                </option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="col-md-3 mb-2">
        <select name="semester" class="form-select" onchange="this.form.submit()">
            <option value="">Filter by Semester</option>
            <?php foreach ($semesterOptions as $option): ?>
                <option value="<?= $option ?>" <?= ($semester == $option) ? 'selected' : '' ?>>
                    <?= esc($option) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3 mb-2">
        <a href="<?= current_url() ?>" class="btn btn-secondary">Clear</a>
    </div>
</form>


        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Course Code</th>
                        <th>Course Description</th>
                        <th>Lecture Units</th>
                        <th>Lab Units</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($courses)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No courses found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?= esc($course->course_code) ?></td>
                                <td><?= esc($course->course_description) ?></td>
                                <td><?= esc($course->lec_units) ?></td>
                                <td><?= esc($course->lab_units) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>