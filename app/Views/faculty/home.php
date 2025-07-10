<!-- Views/faculty/home.php -->
<div class="container mt-5">
    <h2>Welcome, <?= session('username'); ?>!</h2>
    <p class="lead">You are logged in as <strong><?= session('role'); ?></strong>.</p>

    <!-- Views/faculty/home.php -->
<div class="container mt-5">
    <!-- <h2 class="fw-bold">Home</h2>
    <hr> -->

    <!-- FILTER & SEARCH BAR -->
    <!-- <div class="d-flex align-items-center mb-4">
        <select class="form-select w-auto me-2">
            <option>All</option>
        </select>
        <input type="text" class="form-control" placeholder="Search">
    </div> -->

<div class="row">
    <!-- LEFT COLUMN: WEEKLY SCHEDULE -->
    <div class="col-md-6 px-3">
        <h4 class="fw-bold mb-4">Your Weekly Schedule</h4>

        <!-- MONDAY -->
        <div class="mb-4">
            <h6 class="fw-bold">Monday</h6>
            <table class="table table-borderless">
                <thead class="border-bottom border-purple">
                    <tr>
                        <th>Code</th>
                        <th>Course</th>
                        <th>Time</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CS 101</td>
                        <td>System Fundamentals</td>
                        <td>8:00-9:00</td>
                        <td>CLD</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- TUESDAY -->
        <div class="mb-4">
            <h6 class="fw-bold">Tuesday</h6>
            <table class="table table-borderless">
                <thead class="border-bottom border-purple">
                    <tr>
                        <th>Code</th>
                        <th>Course</th>
                        <th>Time</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CS 201</td>
                        <td>Intermediate Programming</td>
                        <td>3:00-4:00</td>
                        <td>CLC</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- WEDNESDAY -->
        <div class="mb-4">
            <h6 class="fw-bold">Wednesday</h6>
            <table class="table table-borderless">
                <thead class="border-bottom border-purple">
                    <tr>
                        <th>Code</th>
                        <th>Course</th>
                        <th>Time</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CS 101</td>
                        <td>System Fundamentals</td>
                        <td>8:00-9:00</td>
                        <td>CLD</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- THURSDAY -->
        <div class="mb-4">
            <h6 class="fw-bold">Thursday</h6>
            <table class="table table-borderless">
                <thead class="border-bottom border-purple">
                    <tr>
                        <th>Code</th>
                        <th>Course</th>
                        <th>Time</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CS 101</td>
                        <td>System Fundamentals</td>
                        <td>8:00-9:00</td>
                        <td>CLD</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- FRIDAY -->
        <div class="mb-4">       
            <h6 class="fw-bold">Friday</h6>
            <table class="table table-borderless">
                <thead class="border-bottom border-purple">
                    <tr>
                        <th>Code</th>
                        <th>Course</th>
                        <th>Time</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CS 101</td>
                        <td>System Fundamentals</td>
                        <td>8:00-9:00</td>
                        <td>CLD</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- RIGHT COLUMN: CALENDAR & ANNOUNCEMENTS -->
    <div class="col-md-6 px-3">
        <h4 class="fw-bold mb-4 px-3">Events & Announcements</h4>
        <div class="card p-3 border-0 bg-transparent">
            <div class="row">
                <!-- Calendar -->
                <div class="col-12 mb-4">
                    <div id="calendar" class="calendar-sm"></div>
                </div>

                <!-- Announcements -->
                <div class="col-12">
                    <div class="p-3 border-0" id="latest-update" style="background-color: #ffffff; border-radius: 10px;">
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








