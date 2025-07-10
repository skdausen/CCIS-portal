<div class="container mt-5">
    <h2>Your Classes - <?= esc(($semester['semester'] ?? '') . ' Semester A.Y. ' . ($semester['schoolyear'] ?? '')) ?></h2>

    <table>
    <thead>
        <tr>
            <th>Course Description</th>
            <th>Class Type</th>
            <th>Class Day</th>
            <th>Class Time</th>
            <th>Class Room</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($classes as $class) : ?>
        
    <tr>
        <td><?= esc($class['course_description'] ?? 'N/A') ?></td>
        <td><?= esc($class['class_type'] ?? 'N/A') ?></td>
        <td><?= esc($class['class_day'] ?? 'N/A') ?></td>
        <td>
            <?= isset($class['class_start'], $class['class_end']) 
                ? date("g:i A", strtotime($class['class_start'])) . ' - ' . date("g:i A", strtotime($class['class_end'])) 
                : 'N/A' ?>
        </td>
        <td><?= esc($class['class_room'] ?? 'N/A') ?></td>
        <td>
        <a href="<?= site_url('faculty/classes/view/' . $class['class_id']) ?>" class="btn btn-primary btn-sm">
            Manage Class </a>
        </td> 
    </tr>
    <?php endforeach ?>
    </tbody>
    </table>
</div>