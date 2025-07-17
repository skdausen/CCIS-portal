<div class="container mt-5">
    <div class="mb-4">
        <h2 class="text">My Grades</h2>
    </div>

    <!-- Grades Table -->
        <div class="col-12 col-lg-12">
            <!-- Filters -->
            <form method="get" class="row mb-4 g-2">
                <div class="col-md-3">
                    <label for="semester_id" class="form-label">Semester</label>
                    <select name="semester_id" id="semester_id" class="form-select" onchange="this.form.submit()">
                        <?php foreach ($semesters as $sem): ?>
                            <option value="<?= $sem->semester_id ?>" <?= ($selectedSemester == $sem->semester_id) ? 'selected' : '' ?>>
                                <?= esc($sem->schoolyear . ' - ' . $sem->semester) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
            <?php if (empty($grades)): ?>
                <div class="alert alert-warning shadow-sm rounded">No grades available for selected filters.</div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered grade-table custom-padding">
                    <thead class="table-dark align-middle ">
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Midterm Grade</th>
                            <th>Final Grade</th>
                            <th>Semestral Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grades as $grade): ?>
                            <tr>
                                <td><?= esc($grade->subject_code) ?></td>
                                <td><?= esc($grade->subject_name) ?></td>
                                <td><?= ($grade->mt_grade === null || $grade->mt_grade == 0) ? 'NE' : $grade->mt_grade ?></td>
                                <td><?= ($grade->fn_grade === null || $grade->fn_grade == 0) ? 'NE' : $grade->fn_grade ?></td>
                                <td class="text-center"><?= ($grade->sem_grade === null || $grade->sem_grade == 0) ? 'NE' : $grade->sem_grade ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
            <!-- Grade System Legend -->
            <div class="card mt-4 border-0 shadow-sm col-md-3">
                <div class="card-header">
                    Grade System Guide
                </div>
                <div class="card-body">
                    <ul class="mb-0" style="list-style-type: none; padding-left: 0;">
                        <li><strong>1.00</strong> – 97–100%</li>
                        <li><strong>1.25</strong> – 94–96%</li>
                        <li><strong>1.50</strong> – 91–93%</li>
                        <li><strong>1.75</strong> – 88–90%</li>
                        <li><strong>2.00</strong> – 85–87%</li>
                        <li><strong>2.25</strong> – 82–84%</li>
                        <li><strong>2.50</strong> – 79–81%</li>
                        <li><strong>2.75</strong> – 76–78%</li>
                        <li><strong>3.00</strong> – 75%</li>
                        <li><strong>5.00</strong> – Failed</li>
                        <li><strong>NE</strong> – No Entry</li>
                    </ul>
                </div>
            </div>

        </div>
    <?php endif; ?>
</div>
