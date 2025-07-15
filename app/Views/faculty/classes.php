<div class="container mt-5">
    <div class="row">
        <h2>Classes for <?= esc($selectedSemester->semester) ?>, A.Y. <?= esc($selectedSemester->schoolyear) ?></h2>
            <form method="get" class="mb-3">
                <label for="semester_id">Select Semester:</label>
                <select name="semester_id" id="semester_id" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($semesters as $semester): ?>
                        <option value="<?= $semester->semester_id ?>" <?= ($semester->semester_id == $selectedSemester->semester_id) ? 'selected' : '' ?>>
                            <?= $semester->semester ?> - <?= $semester->schoolyear ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        <div class="col-md-12">
            <?php if (count($classes) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Type</th>
                            <th>Lecture Schedule</th>
                            <th>Lab Schedule</th>
                            <th>Section</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($classes as $class): ?>
                            <tr>
                                <td><?= esc($class->subject_code) ?></td>
                                <td><?= esc($class->subject_name) ?></td>
                                <td><?= esc($class->subject_type) ?></td>
                                <td>
                                    <?= !empty($class->lec_day) ? esc($class->lec_day) . ', ' . date('h:i A', strtotime($class->lec_start)) . ' - ' . date('h:i A', strtotime($class->lec_end)) : 'N/A' ?><br>
                                    Room: <?= esc($class->lec_room) ?>
                                </td>
                                <td>
                                    <?php if ($class->subject_type === 'LEC with LAB'): ?>
                                        <?= !empty($class->lab_day) ? esc($class->lab_day) . ', ' . date('h:i A', strtotime($class->lab_start)) . ' - ' . date('h:i A', strtotime($class->lab_end)) : 'N/A' ?><br>
                                        Room: <?= esc($class->lab_room) ?>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($class->section) ?></td>
                                <td>
                                    <a href="<?= base_url('faculty/class/' . $class->class_id) ?>" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning">No classes found for the selected semester.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

