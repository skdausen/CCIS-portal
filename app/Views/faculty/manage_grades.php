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
                                        value="<?= esc($s['mt_numgrade']) ?>" 
                                        class="form-control text-end" 
                                        placeholder="e.g., 87.50">
                                    <small class="text-muted">Transmuted: <?= $s['mt_grade'] ?? '--' ?></small>
                                </td>
                                <td>
                                    <input type="number" 
                                        step="0.01" 
                                        min="0" 
                                        max="99.99" 
                                        name="grades[<?= $s['stb_id'] ?>][fn_numgrade]" 
                                        value="<?= esc($s['fn_numgrade']) ?>" 
                                        class="form-control text-end" 
                                        placeholder="e.g., 92.00">
                                    <small class="text-muted">Transmuted: <?= $s['fn_grade'] ?? '--' ?></small>
                                </td>
                                <td class="text-center">
                                    <div><?= $s['sem_numgrade'] ?? '--' ?></div>
                                    <small class="text-muted">Transmuted: <?= $sem ?: '--' ?></small>
                                </td>
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
