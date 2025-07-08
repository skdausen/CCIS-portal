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
                        <h6 class="text-primary mt-3">📌 Nearing Announcements</h6>
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
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>

                        <!-- Content -->
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" name="content" id="content" rows="3" required></textarea>
                        </div>

                        <!-- Audience -->
                        <div class="mb-3">
                            <label for="audience" class="form-label">Audience</label>
                            <select class="form-select" name="audience" id="audience" required>
                                <option value="all">All</option>
                                <option value="students">Students</option>
                                <option value="faculty">Faculty</option>
                            </select>
                        </div>

                        <!-- 📅 Event Date & Time -->
                        <div class="mb-3">
                        <label for="event_datetime" class="form-label">Event Date & Time</label>
                        <input type="datetime-local" class="form-control" id="event_datetime" name="event_datetime" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Post Announcement</button>
                    </div>
                </form>
            </div>
        </div>
        </div>

        <!-- ✏️ Edit Announcement Modal -->
        <div class="modal fade" id="editAnnouncementModal" tabindex="-1" aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="editAnnouncementModalLabel">Edit Announcement</h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editForm" action="<?= site_url('admin/updateAnnouncement') ?>" method="post">
                        <input type="hidden" name="announcement_id" id="editAnnouncementId">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" id="editTitle" required>
                            </div>
                            <div class="mb-3">
                                <label for="editContent" class="form-label">Content</label>
                                <textarea class="form-control" name="content" id="editContent" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="editAudience" class="form-label">Audience</label>
                                <select class="form-select" name="audience" id="editAudience" required>
                                    <option value="all">All</option>
                                    <option value="students">Students</option>
                                    <option value="faculty">Faculty</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editEventDatetime" class="form-label">Event Date & Time</label>
                                <input type="datetime-local" class="form-control" name="event_datetime" id="editEventDatetime" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (session()->getFlashdata('success')): ?>
<!-- 🎉 Success Modal (centered modal for updates and deletes) -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center fs-5">
        <?= session()->getFlashdata('success') ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
    const announcements = <?= isset($announcements) ? json_encode($announcements) : '[]'; ?>;

    const eventModalEl = document.getElementById('eventModal');
    eventModalEl.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const title = button.getAttribute('data-title');
        const dateRaw = new Date(button.getAttribute('data-date'));
        const description = button.getAttribute('data-description');

        const formattedDate = dateRaw.toLocaleDateString('en-US', {
            year: 'numeric', month: 'long', day: 'numeric'
        });
        const formattedTime = dateRaw.toLocaleTimeString('en-US', {
            hour: 'numeric', minute: '2-digit', hour12: true
        });

        document.getElementById('eventTitle').innerText = title;
        document.getElementById('eventDate').innerText = `${formattedDate} at ${formattedTime}`;
        document.getElementById('eventDescription').innerText = description;

        // Also set hidden ID for delete/edit
        const announcementId = button.getAttribute('data-id');
        document.getElementById('modalAnnouncementId').value = announcementId;

        // On Edit Button click → open the edit modal with data
        document.getElementById('editAnnouncementBtn').onclick = function () {
            const announcement = announcements.find(a => a.announcement_id == announcementId);
            if (announcement) {
                document.getElementById('editAnnouncementId').value = announcement.announcement_id;
                document.getElementById('editTitle').value = announcement.title;
                document.getElementById('editContent').value = announcement.content;
                document.getElementById('editAudience').value = announcement.audience;
                document.getElementById('editEventDatetime').value = announcement.event_datetime.replace(' ', 'T');

                const editModal = new bootstrap.Modal(document.getElementById('editAnnouncementModal'));
                editModal.show();
            }
        };
    });


    document.addEventListener('DOMContentLoaded', function () {
        const successModalEl = document.getElementById('successModal');
        if (successModalEl) {
            const modal = new bootstrap.Modal(successModalEl);
            modal.show();

            // Optional: hide automatically after 3 seconds
            setTimeout(() => {
                modal.hide();
            }, 3000);
        }
    });
</script>








