<div class="container mt-5">

    <!-- Title -->
    <div class="text-center my-4">
        <h3 class="text"><?= esc($class['subject_code'] . ' - ' . $class['subject_name']) ?></h3>
    </div>

    <!-- Grade Form Section -->
    <div class="card bg-white shadow rounded mb-5 mr-3">
        <div class="card-body p-4">
            <form action="<?= base_url('faculty/class/' . $class['class_id'] . '/grades/save') ?>" method="post">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle custom-padding">
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
                            <?php
                            $allowedGrades = ['1.00', '1.25', '1.50', '1.75', '2.00', '2.25', '2.50', '2.75', '3.00', '5.00'];
                            ?>
                            <?php foreach ($students as $s): $sem = $s['sem_grade'] ?? '';?>
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
                                <td class="text-center"><?= $sem ?: '--' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Card Footer Inside the Form but Outside the Table -->
                <div class="card-footer bg-white border-0 d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-outline-success btn-sm me-2">Save Grades</button>
                    <a href="<?= base_url('faculty/class/' . $class['class_id']) ?>" class="btn btn btn-outline-secondary btn-thin btn-sm px-3 py-1 rounded-1">Back</a>
                </div>
            </form>
        </div>
    </div>

</div>
