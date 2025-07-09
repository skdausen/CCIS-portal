<!-- admin_footer.php -->

<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Logout</h5>
      </div>
      <div class="modal-body">
        Are you sure you want to log out?
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="<?= site_url('auth/logout') ?>" class="btn btn-danger">Confirm Logout</a>
      </div>
    </div>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
<!-- Success Modal -->  
<div class="modal fade" id="successModal" tabindex="-1" data-success-message="<?= esc(session()->getFlashdata('success')) ?>">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="successMessage"></p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- PROFILE MODAL -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">👤 My Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="row align-items-center">
          <!-- PHOTO & BASIC INFO -->
          <div class="col-md-4 d-flex justify-content-center mb-3">
            <div class="text-center">
              <img src="<?= base_url('rsc/assets/uploads/' . esc(session('profile_img') ?? 'default.png')) ?>" 
                  alt="Profile Photo" 
                  class="rounded-circle shadow"
                  style="width: 120px; height: 120px; object-fit: cover;">
              <p class="mt-2 mb-0 fw-bold"><?= esc(session('username')) ?></p>
              <span class="text-muted"><?= esc(session('role')) ?></span>
            </div>
          </div>

          <!-- DETAILS -->
          <div class="col-md-8">
            <p><strong>Email:</strong> <?= esc(session('email')) ?></p>
            <p><strong>Full Name:</strong> <?= esc(session('fname')) ?> <?= esc(session('mname')) ?> <?= esc(session('lname')) ?></p>
            <p><strong>Sex:</strong> <?= esc(session('sex')) ?></p>
            <p><strong>Birthday:</strong> <?= esc(session('birthday')) ?></p>
            <p><strong>Address:</strong> <?= esc(session('address')) ?></p>
            <p><strong>Contact Number:</strong> <?= esc(session('contact_number')) ?></p>
            <p><strong>Last Login:</strong> <?= esc(session('last_login')) ?></p>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- EDIT PROFILE MODAL (Styled like Profile View) -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="<?= site_url('profile/update') ?>" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <!-- PROFILE PHOTO -->
            <div class="col-12 text-center">
              <img src="<?= base_url('rsc/assets/uploads/' . esc(session('profile_img') ?? 'default.png')) ?>"
                   alt="Profile Picture"
                   class="rounded-circle shadow"
                   style="width: 120px; height: 120px; object-fit: cover;">
              <div class="mt-2">
                <label for="profile_img" class="form-label small text-muted">Change Photo</label>
                <input type="file" name="profile_img" id="profile_img" class="form-control form-control-sm">
              </div>
            </div>

            <!-- First Name -->
            <div class="col-md-4">
              <label class="form-label">First Name</label>
              <input type="text" name="fname" class="form-control" value="<?= esc(session('fname')) ?>" required>
            </div>

            <!-- Middle Name -->
            <div class="col-md-4">
              <label class="form-label">Middle Name</label>
              <input type="text" name="mname" class="form-control" value="<?= esc(session('mname')) ?>">
            </div>

            <!-- Last Name -->
            <div class="col-md-4">
              <label class="form-label">Last Name</label>
              <input type="text" name="lname" class="form-control" value="<?= esc(session('lname')) ?>" required>
            </div>

            <!-- Email -->
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= esc(session('email')) ?>" required>
            </div>

            <!-- Contact -->
            <div class="col-md-6">
              <label class="form-label">Contact Number</label>
              <input type="text" name="contact_number" class="form-control" value="<?= esc(session('contact_number')) ?>">
            </div>

            <!-- Sex -->
            <div class="col-md-6">
              <label class="form-label">Sex</label>
              <select name="sex" class="form-select">
                <option value="Male" <?= session('sex') === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= session('sex') === 'Female' ? 'selected' : '' ?>>Female</option>
              </select>
            </div>

            <!-- Birthday -->
            <div class="col-md-6">
              <label class="form-label">Birthday</label>
              <input type="date" name="birthday" class="form-control" value="<?= esc(session('birthday')) ?>">
            </div>

            <!-- Address -->
            <div class="col-md-12">
              <label class="form-label">Address</label>
              <textarea name="address" class="form-control" rows="2"><?= esc(session('address')) ?></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- Bootstrap JS -->
<script src="<?= base_url("rsc/bootstrap-5.3.7/js/bootstrap.bundle.min.js") ?>"></script>

<!-- Prevent back history script -->
<script src="<?= base_url("rsc/custom_js/preventBackHistory.js") ?>"></script>

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<!-- Your calendar logic -->
<script src="<?= base_url('rsc/custom_js/calendar.js') ?>"></script>

<!-- Event Modal JS -->
<script src="<?= base_url('rsc/custom_js/eventModal.js') ?>"></script>

<!--View, Search, & Filter Users JS -->
<script src="<?= base_url('rsc/custom_js/users.js') ?>"></script>

<!-- Success Modal JS -->
<script src="<?= base_url('rsc/custom_js/successModal.js') ?>"></script>

</body>
</html>
