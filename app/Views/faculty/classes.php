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
                    <th>Subject</th>
                    <th>Type</th>
                    <th>Day, Time, Room</th>
                    <th>Section</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?= esc($class['subject_code']) ?> - <?= esc($class['subject_name']) ?></td>
                        <td><?= esc($class['subject_type']) ?></td>

                        <td>
                            <?= !empty($class['lec_day']) ? 'Lec: ' . esc($class['lec_day']) : '' ?>
                            <?php if (!empty($class['lec_start']) && !empty($class['lec_end'])): ?>
                                <?= date("g:i A", strtotime($class['lec_start'])) ?> - <?= date("g:i A", strtotime($class['lec_end'])) ?>
                            <?= !empty($class['lec_room']) ? '' . esc($class['lec_room']) : '' ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                            <?php if (!empty($class['lab_day'])): ?>
                                <br>Lab: <?= esc($class['lab_day']) ?>
                                <?php if (!empty($class['lab_start']) && !empty($class['lab_end'])): ?>
                                <?= date("g:i A", strtotime($class['lab_start'])) ?> - <?= date("g:i A", strtotime($class['lab_end'])) ?>
                                <?php endif; ?>
                                <?php if (!empty($class['lab_room'])): ?>
                                <?= esc($class['lab_room']) ?>
                            <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($class['section'] ?? 'N/A') ?></td>
                        <td><button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#<?= $class['class_id'] ?>">View</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No classes found for the current semester.</p>
    <?php endif; ?>

    </div>
</div>
