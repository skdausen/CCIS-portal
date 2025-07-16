<h3 class="mb-4">Manage Grades - <?= esc($class['subject_code'] . ' - ' . $class['subject_name']) ?></h3>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<form action="<?= base_url('faculty/class/' . $class['class_id'] . '/grades/save') ?>" method="post">
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
        <thead class="table-dark">
            <tr>
            <th>ID Number</th>
            <th>Full Name</th>
            <th>Midterm Grade</th>
            <th>Final Grade</th>
            <th>Semestral Grade</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $allowedGrades = ['1.00', '1.25', '1.50', '1.75', '2.00', '2.25', '2.50', '2.75', '3.00', '5.00'];
            ?>
            <?php foreach ($students as $s): 
                $sem = $s['sem_grade'] ?? '';
                ?>
            <tr>
                <td><?= esc($s['student_id']) ?></td>
                <td><?= esc("{$s['lname']}, {$s['fname']} {$s['mname']}") ?></td>
                <td>
                    <select name="grades[<?= $s['stb_id'] ?>][mt_grade]" class="form-select">
                        <option value="">--</option>
                        <?php foreach ($allowedGrades as $g): ?>
                        <option value="<?= $g ?>" <?= ($s['mt_grade'] == $g) ? 'selected' : '' ?>><?= $g ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="grades[<?= $s['stb_id'] ?>][fn_grade]" class="form-select">
                        <option value="">--</option>
                        <?php foreach ($allowedGrades as $g): ?>
                        <option value="<?= $g ?>" <?= ($s['fn_grade'] == $g) ? 'selected' : '' ?>><?= $g ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td><?= $sem ?: '--' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Save Grades</button>
        <a href="<?= base_url('faculty/classes') ?>" class="btn btn-secondary">Back to Classes</a>
    </div>
</form>

