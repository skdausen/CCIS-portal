<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>My Curriculum</h3>
    </div>

    <?php if ($currentYearKey && !empty($groupedSubjects[$currentYearKey])): ?>
        <div class="row m-0">
            <h5 class="col-6 mt-4 fw-bold p-0"><?= $currentYearKey ?></h5>
            <div class="col-6 d-flex justify-content-end p-0">
                <nav aria-label="Curriculum pagination" class="small">
                    <p class="text-muted mb-3 text-end small">Page <?= $page ?> of <?= $totalPages ?></p>
                    <ul class="pagination pagination-sm justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item mx-1">
                                <a class="page-link page-link-top" href="<?= site_url('student/curriculum?page=' . ($page - 1)) ?>">Previous</a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled mx-1 page-item-top">
                                <span class="page-link page-link-top">Previous</span>
                            </li>
                        <?php endif; ?>

                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <li class="page-item mx-1 <?= ($page == $p) ? 'active' : '' ?>">
                                <a class="page-link page-link-top" href="<?= site_url('student/curriculum?page=' . $p) ?>">
                                    <?= $p ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item mx-1">
                                <a class="page-link page-link-top" href="<?= site_url('student/curriculum?page=' . ($page + 1)) ?>">Next</a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled mx-1">
                                <span class="page-link page-link-top">Next</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
        <?php foreach ($groupedSubjects[$currentYearKey] as $semester => $subjects): ?>
            <?php if (!empty($subjects)): ?>
                <h6 class="mt-3"><?= $semester ?></h6>
                <table class="table table-bordered curriculum-table custom-padding">
                    <thead class="table-light">
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>LEC Units</th>
                            <th>LAB Units</th>
                            <th>Total Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalLec = 0;
                        $totalLab = 0;
                        foreach ($subjects as $subject):
                            $totalLec += $subject['lec_units'];
                            $totalLab += $subject['lab_units'];
                        ?>
                        <tr>
                            <td><?= esc($subject['subject_code']) ?></td>
                            <td><?= esc($subject['subject_name']) ?></td>
                            <td class="text-center"><?= esc($subject['lec_units']) ?></td>
                            <td class="text-center"><?= esc($subject['lab_units']) ?></td>
                            <td class="text-center"><?= esc($subject['lec_units'] + $subject['lab_units']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="fw-bold bg-light">
                            <td colspan="2" class="text-end">Total Units:</td>
                            <td class="text-center"><?= $totalLec ?></td>
                            <td class="text-center"><?= $totalLab ?></td>
                            <td class="text-center"><?= $totalLec + $totalLab ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">No subjects for this year.</p>
    <?php endif; ?>

    <nav aria-label="Curriculum pagination">
        <ul class="pagination justify-content-center my-4">
            <?php if ($page > 1): ?>
                <li class="page-item mx-1">
                    <a class="page-link" href="<?= site_url('student/curriculum?page=' . ($page - 1)) ?>">Previous</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled mx-1">
                    <span class="page-link">Previous</span>
                </li>
            <?php endif; ?>

            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <li class="page-item mx-1 <?= ($page == $p) ? 'active' : '' ?>">
                    <a class="page-link" href="<?= site_url('student/curriculum?page=' . $p) ?>">
                        <?= $p ?>
                    </a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item mx-1">
                    <a class="page-link" href="<?= site_url('student/curriculum?page=' . ($page + 1)) ?>">Next</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled mx-1">
                    <span class="page-link">Next</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

<script>
    document.addEventListener('keydown', function(event) {
        const currentPage = <?= $page ?>;
        const totalPages = <?= $totalPages ?>;

        if (event.key === 'ArrowRight') {
            const nextPage = (currentPage >= totalPages) ? 1 : currentPage + 1;
            window.location.href = "<?= site_url('student/curriculum?page=') ?>" + nextPage;
        }
        if (event.key === 'ArrowLeft') {
            const prevPage = (currentPage <= 1) ? totalPages : currentPage - 1;
            window.location.href = "<?= site_url('student/curriculum?page=') ?>" + prevPage;
        }
    });
</script>