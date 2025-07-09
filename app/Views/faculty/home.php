<!-- Views/faculty/home.php -->
<div class="container mt-5">
    <h2>Welcome, <?= session('username'); ?>!</h2>
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
                        <!-- Latest Announcement -->
                        <h5 class="text-purple mb-3
                        ">🆕 Latest Announcement</h5>
                        <?php if (!empty($announcements)) : ?>
                            <?php $latest = reset($announcements); ?>
                            <h6 class="mt-2"><?= esc($latest['title']); ?></h6>
                            <small class="text-muted">
                                <?= date('F j, Y \a\t g:i A', strtotime($latest['event_datetime'])); ?>
                            </small>
                            <p class="mt-2"><?= esc($latest['content']); ?></p>
                        <?php else : ?>
                            <p>No announcements yet.</p>
                        <?php endif; ?>

                        <hr>

                        <!-- Nearest Upcoming Announcements -->
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
            </div>
        </div>
        </div>
</div>

<script>
    const announcements = <?= isset($announcements) ? json_encode($announcements) : '[]'; ?>;
</script>








