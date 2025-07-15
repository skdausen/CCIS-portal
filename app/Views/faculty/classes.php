<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">My Classes</h2>
        <p>
            <strong>Semester:</strong> <?= esc($semester['semester']) ?><br>
            <strong>School Year:</strong> <?= esc($semester['schoolyear']) ?>
        </p>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <?php if (!empty($classes)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Semester</th>
                    <th>School Year</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?= esc($class['subject_code']) ?></td>
                        <td><?= esc($class['subject_name']) ?></td>
                        <td><?= esc($class['semester']) ?></td>
                        <td><?= esc($class['schoolyear']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No classes found for the current semester.</p>
    <?php endif; ?>

    </div>
</div>
