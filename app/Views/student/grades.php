<div class="container mt-5">
    <h3 class="mb-3">My Grades</h3>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Grade</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($grades)): ?>
                <?php foreach ($grades as $grade): ?>
                    <tr>
                        <td><?= esc($grade['subject_code']) ?></td>
                        <td><?= esc($grade['subject_name']) ?></td>
                        <td><?= esc($grade['grade']) ?></td>
                        <td><?= esc($grade['remarks']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">No grades available.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
