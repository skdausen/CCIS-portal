<!-- Views/admin/home.php -->
<div class="container mt-5">
    <h2>Welcome, <?= session('username'); ?>!</h2>
    <p class="lead">You are logged in as <strong><?= session('role'); ?></strong>.</p>

    <a href="<?= site_url('admin/users') ?>" class="btn btn-primary mt-3">Manage Users</a>

    <!-- Calendar -->
    <div class="card mt-4 p-3 border-0" style="background-color:rgba(248, 249, 255, 0);">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div>
                📅 Announcements Calendar & Latest Updates
            </div>
            <div>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                    ➕ Add Announcement
                </button>
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
                        <h5 class="text-primary">🆕 Latest Announcement</h5>
                        <?php if (!empty($announcements)) : ?>
                            <?php $latest = end($announcements); ?>
                            <h6 class="mt-2"><?= esc($latest['title']); ?></h6>
                            <small class="text-muted">
                                <?= date('F j, Y', strtotime($latest['created_at'])); ?> 
                                by <?= esc($latest['username']); ?>
                            </small>
                            <p class="mt-2"><?= esc($latest['content']); ?></p>
                        <?php else : ?>
                            <p>No announcements yet.</p>
                        <?php endif; ?>


                        <hr>

                        <!-- Nearest Upcoming Announcements -->
                        <h6 class="text-primary mt-3">📌 Nearing Announcements</h6>
                        <?php
                            $today = date('Y-m-d');
                            $nearing = array_filter($announcements, function($a) use ($today) {
                                return $a['created_at'] >= $today;
                            });

                            usort($nearing, function($a, $b) {
                                return strtotime($a['created_at']) - strtotime($b['created_at']);
                            });

                            $nearing = array_slice($nearing, 0, 3); // show only 3 upcoming
                        ?>

                        <?php if (!empty($nearing)) : ?>
                            <ul class="list-group list-group-flush mt-2">
                                <?php foreach ($nearing as $n) : ?>
                                    <li class="list-group-item">
                                        <strong><?= esc($n['title']); ?></strong><br>
                                        <small class="text-muted">
                                            <?= date('F j, Y', strtotime($n['created_at'])); ?>
                                            by <?= esc($n['username']); ?>
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
    
    </div>
        <!-- 📌 Modal for Announcement -->
        <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
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
        <!-- 📢 Add Announcement Modal -->
        <div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addAnnouncementModalLabel">Add New Announcement</h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/saveAnnouncement') ?>" method="post">
                <div class="modal-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="content" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="audience" class="form-label">Audience</label>
                    <select name="audience" class="form-select" required>
                    <option value="all">All</option>
                    <option value="students">Students</option>
                    <option value="faculty">Faculty</option>
                    </select>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Post</button>
                </div>
            </form>
            </div>
        </div>
        </div>


</div>
<script>
    const announcements = <?= isset($announcements) ? json_encode($announcements) : '[]'; ?>;
</script>





