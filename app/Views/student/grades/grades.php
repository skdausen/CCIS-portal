<div class="container mt-5 pb-5">
    <div class="mb-4">
        <h2 class="text">My Grades</h2>
    </div>

    <!-- Grades Table -->
        <div class="col-12 col-lg-12">
            <!-- Filters -->
            <form method="get" class="d-flex align-items-end gap-3 mb-4 flex-wrap">
                <div>
                    <label for="semester_id" class="form-label">Semester</label>
                    <select name="semester_id" id="semester_id" class="form-select" onchange="this.form.submit()" style="min-width: 250px;">
                        <?php foreach ($semesters as $sem): ?>
                            <option value="<?= $sem->semester_id ?>" <?= ($selectedSemester == $sem->semester_id) ? 'selected' : '' ?>>
                                <?= esc($sem->schoolyear . ' - ' . $sem->semester) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="ms-auto">
                    <a href="<?= site_url('student/grades/curriculum_planview') ?>" class="btn btn-outline-success">
                        Curriculum Plan View
                    </a>
                </div>
            </form>

            
            <?php if (empty($grades)): ?>
                <div class="alert alert-warning shadow-sm rounded">No grades available for selected filters.</div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered student-grade-table custom-padding">
                    <thead class="table-dark align-middle ">
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th class="text-center">Midterm Grade</th>
                            <th class="text-center">Final Grade</th>
                            <th class="text-center">Semestral Grade</th>
                            <th class="text-center">Units</th>
                            <th class="text-center">Units Earned</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grades as $grade): ?>
                            <tr>
                                <td><?= esc($grade->subject_code) ?></td>
                                <td><?= esc($grade->subject_name) ?></td>
                                <td class="text-center"><?= ($grade->mt_grade === null || $grade->mt_grade == 0) ? 'NE' : $grade->mt_grade ?></td>
                                <td class="text-center"><?= ($grade->fn_grade === null || $grade->fn_grade == 0) ? 'NE' : $grade->fn_grade ?></td>
                                <td class="text-center"><?= ($grade->sem_grade === null || $grade->sem_grade == 0) ? 'NE' : $grade->sem_grade ?></td>
                                <td class="text-center"><?= $grade->total_units ?></td>
                                <!-- units earned -->
                                <td class="text-center">
                                    <?= (is_numeric($grade->sem_grade) && $grade->sem_grade != 0 && $grade->sem_grade != 5.00) ? $grade->total_units : '--' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

            <!-- Grade System Legend -->
            <div class="d-flex justify-content-between align-items-start w-100">

                <!-- Grade Card -->
                <div class="card border-0 shadow-sm col-md-3 scale-down">
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

                <!-- Button aligned to bottom right -->
                <div class="mb-4 ms-auto d-flex flex-column align-items-end">
                        <div class="alert <?= $isDeanLister ? 'alert-success' : 'alert-info' ?> mt-4 text-end">
                            <?php
                                $displayedGwa = ($gwa === null || $gwa === 0 || $hasIncomplete) ? '--' : number_format($gwa, 3);
                            ?>
                            <strong>GWA:</strong> <?= esc($displayedGwa) ?><br>

                            <?php if ($displayedGwa === '--'): ?>
                                <small class="text-muted">Some grades may be NE. GWA not available.</small>
                            <?php elseif ($isDeanLister): ?>
                                <strong>You're qualified for the Dean's List.</strong> Congratulations!
                            <?php else: ?>
                                Keep striving! Aim for a GWA ≤ 1.75 and Grades ≤ 2.25 to qualify for the Dean's List.
                            <?php endif; ?>
                        </div>
                    <a href="<?= site_url('student/grades/download?semester_id=' . $selectedSemester) ?>" class="btn btn-outline-success mb-3">
                        Download PDF
                    </a>
                </div>

            </div>
        </div>
    <?php endif; ?>
</div>
