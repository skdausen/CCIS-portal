<div class="container mt-5">
    <h2>Your Classes - <?= esc($semester['semester'] . ' ' . $semester['schoolyear_id']) ?></h2>

    <?php if (!empty($classes)) : ?>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Schedule</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class) : ?>
                    <tr>
                        <td><?= esc($class['course_name']) ?></td>
                        <td><?= esc($class['schedule']) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No classes found for this semester.</p>
    <?php endif; ?>
</div>