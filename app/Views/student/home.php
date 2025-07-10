<!-- Views/admin/home.php -->
<div class="container mt-5">
    <h2>Welcome, <?= esc(session('fname')) ?> <?= esc(session('mname')) ?> <?= esc(session('lname')) ?>!</h2>
    <p class="lead">You are logged in as <strong><?= session('role'); ?></strong>.</p>

    <!-- Calendar -->
    <div class="card mt-4 p-3 border-0" style="background-color:rgba(248, 249, 255, 0);">
        <div class="card-header bg-gray text-white d-flex justify-content-between align-items-center">
            <div>
                Events & Announcements
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Calendar LEFT -->
                <div class="col-md-6">
                    <div id="calendar" class="calendar-sm"></div>
                </div>

                <!-- Updates RIGHT -->
                <div class="col-md-6">
                    <div class="p-3 border-0 shadow-sm" id="latest-update" style="background-color: #ffffff; border-radius: 10px;">
                        <!-- 🔍 Filter Logic -->
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
                            $nearing = array_filter($announcements, function ($a) use ($today, $currentMonth, $currentYear) {
                                $eventDate = strtotime($a['event_datetime']);
                                $now = strtotime('now');
                                $daysLater = strtotime('+11 days');

                                return
                                    $eventDate > $now &&
                                    $eventDate <= $daysLater &&
                                    date('Y-m-d', $eventDate) !== $today &&
                                    date('m', $eventDate) === $currentMonth &&
                                    date('Y', $eventDate) === $currentYear;
                            });

                            usort($nearing, function ($a, $b) {
                                return strtotime($a['event_datetime']) - strtotime($b['event_datetime']);
                            });

                            $nearing = array_slice($nearing, 0, 3);
                        ?>

                        <!-- 🆕 Latest Announcement -->
                        <h5 class="text-purple mb-3">🆕 Latest Announcement</h5>
                        <div class="ms-3">
                            <?php if ($latest) : ?>
                                <h6 class="mt-2"><?= esc($latest['title']); ?></h6>
                                <small class="text-muted">
                                    <?= date('F j, Y \a\t g:i A', strtotime($latest['event_datetime'])); ?>
                                </small>
                                <p class="mt-2"><?= esc($latest['content']); ?></p>
                            <?php else : ?>
                                <p>No announcements for today.</p>
                            <?php endif; ?>
                        </div>

                        <hr>

                        <!-- 📌 Nearest Events -->
                        <h6 class="text-purple mt-3">📌 Nearing Events</h6>
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
                                            <br>
                                        </strong>
                                        <small class="text-muted">
                                            <?= date('F j, Y \a\t g:i A', strtotime($n['event_datetime'])); ?>
                                        </small>
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








