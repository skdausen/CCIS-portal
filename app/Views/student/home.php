<!-- Views/admin/home.php -->
<div class="container mt-5 <?= esc(session('role')) ?>">
    <h2>Welcome, <?= esc(session('fname')) ?> <?= esc(session('mname')) ?> <?= esc(session('lname')) ?>!</h2>

    <div class="row mt-5">
        <!-- LEFT COLUMN: WEEKLY SCHEDULE -->
        <div class="col-12 col-lg-8 mb-5">
            <h4 class="fw-bold mb-4">My Weekly Schedule</h4>
            <div class="card p-3 shadow-darker">
                <?php foreach ($schedule as $day => $entries): ?>
                    <h5 class="mt-4"><?= esc(strtoupper($day)) ?></h5>

                    <?php if (!empty($entries)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped custom-padding">
                                <thead class="table-light">
                                    <tr>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Type</th>
                                        <th>Time</th>
                                        <th>Room</th>
                                        <th>Instructor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($entries as $entry): ?>
                                        <tr>
                                            <td><?= esc($entry['subject_code']) ?></td>
                                            <td><?= esc($entry['subject_name']) ?></td>
                                            <td><?= esc(ucfirst($entry['type'])) ?></td>
                                            <td>
                                                <?= date('h:i A', strtotime($entry['start'])) ?>
                                                – <?= date('h:i A', strtotime($entry['end'])) ?>
                                            </td>
                                            <td><?= esc($entry['room']) ?></td>
                                            <td><?= esc($entry['instructor'] ?? 'N/A') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted"><em>No classes.</em></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- RIGHT COLUMN: CALENDAR & ANNOUNCEMENTS -->
        <div class="col-12 col-lg-4 mb-5">
            <h4 class="fw-bold mb-4 px-3">Events & Announcements</h4>
            <div class="card p-3 border-0 bg-transparent">
                <div class="row">
                    <!-- Calendar -->
                    <div class="col-12 mb-4 card shadow-darker">
                        <div id="calendar" class="calendar-sm p-3"></div>
                    </div>

                    <?php
                                $today = date('Y-m-d');
                                $currentMonth = date('m');
                                $currentYear = date('Y');

                                // Get today's announcements
                                $todaysAnnouncements = array_filter($announcements, function ($a) use ($today) {
                                    return date('Y-m-d', strtotime($a['event_datetime'])) === $today;
                                });

                                usort($todaysAnnouncements, function ($a, $b) {
                                    return strtotime($b['event_datetime']) - strtotime($a['event_datetime']);
                                });

                                $latest = !empty($todaysAnnouncements) ? $todaysAnnouncements[0] : null;

                                // Get nearing announcements (this month, not today)
                    ?>

                    <!-- Announcements -->
                    <div class="col-12 card shadow-darker">
                        <div class="py-3 p-2" id="latest-update">
                            <!-- Latest Announcement -->
                            <h6 class="text-purple mb-2 fw-semibold" style="font-size: 1rem;">🆕 Latest Announcement</h6>
                            <div class="ms-2">
                                <?php if ($latest) : ?>
                                    <strong class="d-block" style="font-size: 0.95rem;"><?= esc($latest['title']); ?></strong>
                                    <small class="text-muted d-block">
                                        <?= date('F j, Y \a\t g:i A', strtotime($latest['event_datetime'])); ?>
                                    </small>
                                    <p class="mt-1 mb-1"><?= esc($latest['content']); ?></p>
                                <?php else : ?>
                                    <p class="text-muted mb-1">No announcements for today.</p>
                                <?php endif; ?>
                            </div>
                            <hr>
                            <!-- 📌 Nearing Events -->
                            <div class="ms-2">
                                <h6 class="text-purple mb-2 fw-semibold" style="font-size: 1rem;">📌 Nearing Events</h6>
                                <?php if (!empty($nearing)) : ?>
                                <ul class="list-group list-group-flush small">
                                    <?php foreach ($nearing as $n) : ?>
                                        <li class="list-group-item py-1 px-2">
                                            <button class="btn btn-link p-0 text-decoration-none text-start"
                                                style="font-size: 0.9rem;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#eventModal"
                                                data-id="<?= $n['announcement_id']; ?>"
                                                data-title="<?= esc($n['title']); ?>"
                                                data-date="<?= date('Y-m-d\TH:i:s', strtotime($n['event_datetime'])); ?>"
                                                data-description="<?= esc($n['content']); ?>">
                                                <strong><?= esc($n['title']); ?></strong>
                                            </button><br>
                                            <small class="text-muted"><?= date('F j, Y \a\t g:i A', strtotime($n['event_datetime'])); ?></small>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php else : ?>
                                        <p class="text-muted small">No upcoming announcements.</p>
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
</div>

<script>
    const announcements = <?= isset($announcements) ? json_encode($announcements) : '[]'; ?>;
</script>








