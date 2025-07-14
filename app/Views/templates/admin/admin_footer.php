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

  <?php if (session()->getFlashdata('error')): ?>
  <!-- Error Modal -->
  <div class="modal fade" id="errorModal" tabindex="-1" data-error-message="<?= esc(session()->getFlashdata('error')) ?>">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                  <h5 class="modal-title">Error</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                  <p id="errorMessage"></p>
              </div>
          </div>
      </div>
  </div>
  <?php endif; ?>

  <!-- PROFILE MODAL -->
  <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                <img src="<?= base_url('rsc/assets/uploads/' . esc(session('profimg') ?? 'default.png')) ?>" 
                    alt="Profile Photo" 
                    class="rounded-circle shadow"
                    style="width: 120px; height: 120px; object-fit: cover;">
                <p class="mt-2 mb-0 fw-bold"><?= esc(session('username')) ?></p>
                <span class="text-muted"><?= esc(session('role')) ?></span>
              </div>
            </div>

            <!-- DETAILS -->
            <div class="col-md-8">
              <table class="table table-sm table-hover custom-padding">
                <tbody>
                  <tr>
                    <th>Email</th>
                    <td><?= esc(session('email')) ?></td>
                  </tr>
                  <tr>
                    <th>Full Name</th>
                    <td><?= esc(session('fname')) ?> <?= esc(session('mname')) ?> <?= esc(session('lname')) ?></td>
                  </tr>
                  <tr>
                    <th>Sex</th>
                    <td><?= esc(session('sex')) ?></td>
                  </tr>
                  <tr>
                    <th>Birthday</th>
                    <td><?= esc(session('birthdate')) ?></td>
                  </tr>
                  <tr>
                    <th>Address</th>
                    <td><?= esc(session('address')) ?></td>
                  </tr>
                  <tr>
                    <th>Contact Number</th>
                    <td><?= esc(session('contactnum')) ?></td>
                  </tr>
                  <tr>
                    <th>Last Login</th>
                    <td><?= esc(session('last_login')) ?></td>
                  </tr>
                  <tr>
                    <th>Account Created</th>
                    <td><?= esc(session('created_at')) ?></td>
                  </tr>
                  <tr>
                    <th>Password</th>
                    <td class="d-flex justify-content-between align-items-center">
                      <span id="maskedPassword"><?= str_repeat('•', 8) ?></span>
                      <a href="#editPasswordModal" id="editPasswordLink" data-bs-toggle="modal" class="ms-2 text-decoration-none small">Change</a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>



  <!-- EDIT PROFILE MODAL (Styled like Profile View) -->
  <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                <img src="<?= base_url('rsc/assets/uploads/' . esc(session('profimg') ?? 'default.png')) ?>"
                    alt="Profile Picture"
                    class="rounded-circle shadow"
                    style="width: 120px; height: 120px; object-fit: cover;">
                <div class="mt-2">
                  <label for="profimg" class="form-label small text-muted">Change Photo</label>
                  <input type="file" name="profimg" id="profimg" class="form-control form-control-sm">
                </div>
              </div>

              <!-- First Name -->
              <div class="col-md-4">
                <label class="form-label">First Name</label>
                <input type="text" name="fname" class="form-control" value="<?= esc(session('fname')) ?>" required placeholder="e.g. Juan">
              </div>

              <!-- Middle Name -->
              <div class="col-md-4">
                <label class="form-label">Middle Name</label>
                <input type="text" name="mname" class="form-control" value="<?= esc(session('mname')) ?>" placeholder="Optional">
              </div>

              <!-- Last Name -->
              <div class="col-md-4">
                <label class="form-label">Last Name</label>
                <input type="text" name="lname" class="form-control" value="<?= esc(session('lname')) ?>" required placeholder="e.g. Santos">
              </div>

              <?php if (session('role') === 'student'): ?>
              <div class="col-md-6">
                  <label for="program_id" class="form-label">Program:</label>
                  <select name="program_id" id="program_id" class="form-select">
                      <option value="">Select program</option>
                      <?php foreach ($programs as $program): ?>
                          <option value="<?= $program['program_id'] ?>" 
                              <?= ($student['program_id'] ?? '') == $program['program_id'] ? 'selected' : '' ?>>
                              <?= $program['program_name'] ?>
                          </option>
                      <?php endforeach; ?>
                  </select>
              </div>

              <div class="col-md-6">
                  <label for="year_level" class="form-label">Year Level:</label>
                  <select name="year_level" id="year_level" class="form-select">
                      <option value="">Select year level</option>
                      <option value="1" <?= ($student['year_level'] ?? '') == 1 ? 'selected' : '' ?>>1st Year</option>
                      <option value="2" <?= ($student['year_level'] ?? '') == 2 ? 'selected' : '' ?>>2nd Year</option>
                      <option value="3" <?= ($student['year_level'] ?? '') == 3 ? 'selected' : '' ?>>3rd Year</option>
                      <option value="4" <?= ($student['year_level'] ?? '') == 4 ? 'selected' : '' ?>>4th Year</option>
                  </select>
              </div>
          <?php endif; ?>


              <!-- Email -->
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= esc(session('email')) ?>" required placeholder="e.g. juan@example.com">
              </div>

              <!-- Contact -->
            <div class="col-md-6">
              <label class="form-label">Contact Number</label>
              <input type="text"
                    name="contactnum"
                    class="form-control"
                    value="<?= esc(session('contactnum')) ?>"
                    placeholder="e.g. 09123456789"
                    pattern="^09\d{9}$"
                    maxlength="11"
                    title="Enter a valid 11-digit number starting with 09"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    required>
            </div>

              <!-- Sex -->
              <div class="col-md-6">
                <label class="form-label">Sex</label>
                <select name="sex" class="form-select">
                  <option value="">-- Select --</option>
                  <option value="Male" <?= session('sex') === 'Male' ? 'selected' : '' ?>>Male</option>
                  <option value="Female" <?= session('sex') === 'Female' ? 'selected' : '' ?>>Female</option>
                </select>
              </div>

              <!-- Birthday -->
              <div class="col-md-6">
                <label class="form-label">Birthday</label>
                <input type="date" name="birthdate" id="birthdate" class="form-control" max="<?= date('Y-m-d', strtotime('-1 day')) ?>" value="<?= esc(session('birthdate')) ?>" placeholder="MM/DD/YYYY" required>
              </div>

              <!-- Address -->
              <div class="col-md-12">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2" placeholder="e.g. 123 Sampaguita St., Quezon City"><?= esc(session('address')) ?></textarea>
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

  <!-- EDIT PASSWORD MODAL -->
<div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="<?= site_url('profile/update_password') ?>" method="post">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="editPasswordModalLabel">Change Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="currentPassword" class="form-label">Current Password</label>
            <input type="password" name="current_password" id="currentPassword" class="form-control" placeholder="Enter Current password" required>
          </div>
          <div class="mb-3">
            <label for="newPassword" class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter New password" required>
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Confirm New password" required>
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

<?php if (session()->getFlashdata('open_modal')): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
      var modalId = "<?= session()->getFlashdata('open_modal') ?>";
      var modalElement = document.getElementById(modalId);
      if (modalElement) {
        var modal = new bootstrap.Modal(modalElement);
        modal.show();
      }
    }, 1000); // Delay in milliseconds (1000ms = 1s)
  });
</script>
<?php endif; ?>


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

  <!-- Error Modal JS -->
  <script src="<?= base_url('rsc/custom_js/errorModal.js') ?>"></script>

</body>
</html>
