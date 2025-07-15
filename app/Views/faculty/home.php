<!-- Views/faculty/home.php -->
<div class="container mt-5">
    <h2>Welcome, <?= session('username'); ?>!</h2>
    <p class="lead">You are logged in as <strong><?= session('role'); ?></strong>.</p>

    <div class="row mt-5">
    <!-- LEFT COLUMN: WEEKLY SCHEDULE -->
    <div class="col-md-8">
        <h4 class="fw-bold mb-4">My Weekly Schedule</h4>
        <div class="card p-3 shadow-sm">
            <?php foreach ($schedule as $day => $entries): ?>
                <h4><?= $day ?></h4>
                <?php if (count($entries) > 0): ?>
                    <ul>
                        <?php foreach ($entries as $entry): ?>
                            <li>
                                <strong><?= esc($entry['subject_code']) ?> - <?= esc($entry['subject_name']) ?> (<?= $entry['type'] ?>)</strong><br>
                                <?= date('h:i A', strtotime($entry['start'])) ?> to <?= date('h:i A', strtotime($entry['end'])) ?><br>
                                Room: <?= esc($entry['room']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p><em>No classes.</em></p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

    <!-- RIGHT COLUMN: CALENDAR & ANNOUNCEMENTS -->
    <div class="col-md-4 px-3">
        <h4 class="fw-bold mb-4 px-3">Events & Announcements</h4>
        <div class="card p-3 border-0 bg-transparent">
            <div class="row">
                <!-- Calendar -->
                <div class="col-12 mb-4">
                    <div id="calendar" class="calendar-sm shadow-lg"></div>
                </div>

                <!-- Announcements -->
                <div class="col-12">
                    <div class="p-3 border-0 shadow-lg" id="latest-update" style="background-color: #ffffff; border-radius: 10px;">
                        <h5 class="text-purple mb-3">🆕 Latest Announcement</h5>
                        <?php if (!empty($announcements)) : ?>
                            <?php $latest = reset($announcements); ?>
                            <h6 class="mt-2"><?= esc($latest['title']); ?></h6>
                            <small class="text-muted"><?= date('F j, Y \a\t g:i A', strtotime($latest['event_datetime'])); ?></small>
                            <p class="mt-2"><?= esc($latest['content']); ?></p>
                        <?php else : ?>
                            <p>No announcements yet.</p>
                        <?php endif; ?>

                        <hr>

                        <h6 class="text-purple mt-3">📌 Nearing Announcements</h6>
                        <?php
                            $today = date('Y-m-d H:i:s');
                            $nearing = array_filter($announcements, function($a) use ($today) {
                                return $a['event_datetime'] >= $today;
                            });

                            usort($nearing, function($a, $b) {
                                return strtotime($a['event_datetime']) - strtotime($b['event_datetime']);
                            });

                            $nearing = array_slice($nearing, 0, 3);
                        ?>

                        <?php if (!empty($nearing)) : ?>
                            <ul class="list-group list-group-flush mt-2">
                                <?php foreach ($nearing as $n) : ?>
                                    <li class="list-group-item">
                                        <strong>
                                            <button class="btn btn-link p-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#eventModal"
                                                data-id="<?= $n['announcement_id']; ?>"
                                                data-title="<?= esc($n['title']); ?>"
                                                data-date="<?= date('Y-m-d\TH:i:s', strtotime($n['event_datetime'])); ?>"
                                                data-description="<?= esc($n['content']); ?>">
                                                <?= esc($n['title']); ?>
                                            </button>
                                        </strong><br>
                                        <small class="text-muted"><?= date('F j, Y \a\t g:i A', strtotime($n['event_datetime'])); ?></small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p class="text-muted mt-2">No upcoming announcements.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
        <!-- Modal for Announcement -->
        <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header text-dark">
                    <h5 class="modal-title" id="eventModalLabel">Announcement Details</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 id="eventTitle"></h5>
                    <p class="text-muted" id="eventDate"></p>
                    <p id="eventDescription"></p>
                </div>
                <div class="modal-footer">
                    <?php if (in_array(session('role'), ['admin', 'superadmin'])) : ?>
                        <button type="button" class="btn btn-warning" id="editAnnouncementBtn">Edit</button>
                        <form id="deleteForm" method="post" action="<?= site_url('admin/deleteAnnouncement') ?>" class="d-inline">
                            <input type="hidden" name="announcement_id" id="modalAnnouncementId">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>
</div>

<script>
    const announcements = <?= isset($announcements) ? json_encode($announcements) : '[]'; ?>;
</script>








