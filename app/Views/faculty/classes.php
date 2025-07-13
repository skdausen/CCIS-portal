<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">
            Your Classes - <?= esc(($semester['semester'] ?? '') . ' Semester A.Y. ' . ($semester['schoolyear'] ?? '')) ?>
        </h2>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle table-bordered">
            <thead class="table-light text-center">
                <tr>
                    <th scope="col">Course Code</th>
                    <th scope="col">Course Description</th>
                    <th scope="col">Class Type</th>
                    <th scope="col">Class Day</th>
                    <th scope="col">Class Time</th>
                    <th scope="col">Class Room</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php foreach ($classes as $class) : ?>
                    <tr>
                        <td><?= esc($class['course_code'] ?? 'N/A') ?></td>
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
                            <a href="<?= site_url('faculty/classes/view/' . $class['class_id']) ?>" 
                               class="btn btn-sm btn-outline-primary">
                                Manage Class
                            </a>
                        </td> 
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
