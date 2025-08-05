<!-- footer.php -->

<!-- Logout Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="confirmRemoveLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Confirm Logout</h5>
        </div>
        <div class="modal-body">
          Are you sure you want to log out?
        </div>
        <div class="modal-footer">
          <a href="<?= site_url('auth/logout') ?>" class="btn btn-outline-danger">Confirm Logout</a>
          <button class="btn btn-outline-secondary rounded-1 px-3 py-2 btn-thin" data-bs-dismiss="modal">Cancel</button>
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
          <h5 class="modal-title" id="profileModalLabel"><i class="fa-solid fa-user me-2"></i> My Profile</h5>
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
                    <td><?= ucwords(esc(session('fname') . ' ' . session('mname') . ' ' . session('lname'))) ?></td>
                  </tr>
                  <tr>
                    <th>Sex</th>
                    <td><?= esc(session('sex')) ?></td>
                  </tr>
                <?php if (session('role') === 'student'): ?>
                  <tr>
                    <th>Program</th>
                    <td><?= esc(session('program')) ?></td>
                  </tr>
                  <tr>
                    <th>Year Level</th>
                    <td><?= esc(session('year_level')) ?></td>
                  </tr>
                <?php endif; ?>


                  <?php if (session('role') === 'faculty'): ?>
                    <tr>
                        <th>Employee Status</th>
                        <td>    
                          <?php
                            $status = session('employee_status');
                            if ($status === 'Full-time') {
                                echo 'Regular';
                            } elseif ($status === 'Part-time') {
                                echo 'Part-Time';
                            } else {
                                echo 'N/A';
                            }
                          ?>
                        </td>
                    </tr>
                  <?php endif; ?>
                  <tr>
                    <th>Birthday</th>
                    <td><?= esc(session('birthdate')) ?></td>
                  </tr>
                  <tr>
                    <th>Address</th>
                    <td><?= esc(ucwords(session('address'))) ?></td>
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
          <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
          <button class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- EDIT PROFILE MODAL -->
  <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <form action="<?= site_url('profile/update') ?>" method="post" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          
          <input type="hidden" name="clear_image" id="clearImageFlag" value="0">

          <div class="modal-body overflow-y-auto">
            <div class="row g-3">
              <!-- PROFILE PHOTO -->
                <div class="col-12 text-center">
                <img id="profilePreview"
                    src="<?= base_url('rsc/assets/uploads/' . esc(session('profimg') ?? 'default.png')) ?>"
                    alt="Profile Picture"
                    class="rounded-circle shadow"
                    style="width: 120px; height: 120px; object-fit: cover;">
                
                <div class="mt-2">
                    <label for="profimg" class="form-label small text-muted">
                      Change Photo
                      <span class="d-block text-muted fst-italic" style="font-size:0.75rem;">Max file size: 1MB</span>
                    </label>

                    <div class="d-flex align-items-center gap-2">
                        <input type="file"
                            name="profimg"
                            id="profimg"
                            class="form-control form-control-sm flex-grow-1"
                            accept="image/*"
                            placeholder="Max file size: 1MB">

                        <button type="button"
                            id="clearProfileImage"
                            class="btn btn-outline-secondary btn-sm">
                            Clear
                        </button>
                    </div>
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

              <!-- Program -->
              <?php if (session('role') === 'student' && isset($programs)): ?>
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

              <!-- Year Level -->
              <div class="col-md-6">
                  <label for="year_level" class="form-label">Year Level:</label>
                  <select name="year_level" id="year_level" class="form-select">
                      <option value="">Select year level</option>
                      <option value="1" <?= ($student['year_level'] ?? '') == 'First Year' ? 'selected' : '' ?>>1st Year</option>
                      <option value="2" <?= ($student['year_level'] ?? '') == 'Second Year' ? 'selected' : '' ?>>2nd Year</option>
                      <option value="3" <?= ($student['year_level'] ?? '') == 'Third Year' ? 'selected' : '' ?>>3rd Year</option>
                      <option value="4" <?= ($student['year_level'] ?? '') == 'Fourth Year' ? 'selected' : '' ?>>4th Year</option>
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

              <?php if (session('role') === 'faculty'): ?>
                <div class="col-md-12">
                  <label for="employee_status" class="form-label">Employee Status:</label>
                  <select name="employee_status" id="employee_status" class="form-select" required>
                    <option value="">Select status</option>
                    <option value="Full-time"
                      <?= session('employee_status') === 'Full-time' ? 'selected' : '' ?>>
                      Regular
                    </option>
                    <option value="Part-time"
                      <?= session('employee_status') === 'Part-time' ? 'selected' : '' ?>>
                      Part-Time
                    </option>
                  </select>
                </div>


              <?php endif; ?>

              <!-- Address -->
              <div class="col-md-12">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2" placeholder="e.g. 123 Sampaguita St., Quezon City"><?= esc(session('address')) ?></textarea>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-outline-success">Save Changes</button>
            <button type="button" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- EDIT PASSWORD MODAL -->
  <div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="editPasswordForm" action="<?= site_url('profile/update_password') ?>" method="post">
          <?= csrf_field() ?>
          <div class="modal-header">
            <h5 class="modal-title" id="editPasswordModalLabel">Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="passwordError" class="alert alert-danger <?= session()->getFlashdata('error') ? '' : 'd-none' ?>">
              <?= session()->getFlashdata('error') ?>
            </div>
            <div class="mb-3">
              <label for="currentPassword" class="form-label">Current Password</label>
              <input type="password" name="current_password" id="currentPassword" class="form-control" placeholder="Enter Current password" required>
            </div>
            <div class="mb-3">
              <label for="newPassword" class="form-label">New Password</label>
              <input type="password" name="password" id="newPassword" class="form-control" placeholder="Enter New password" required>
            </div>
            <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirm New Password</label>
              <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Confirm New password" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-outline-success">Save Changes</button>
            <button type="button" class="btn btn-outline-secondary rounded-1 px-3 py-2 btn-thin" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- No Active Semester Modal -->
  <div class="modal fade" id="noSemesterModal" tabindex="-1" aria-labelledby="noSemesterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-danger">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="noSemesterModalLabel">Cannot Add Class</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <p class="mb-0">No active semester is set. Please activate a semester first before adding a class.</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Okay</button>
        </div>
      </div>
    </div>
  </div>

  <!-- For update password error -->
  <?php if (session()->getFlashdata('open_modal')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const targetModal = new bootstrap.Modal(document.getElementById('<?= session()->getFlashdata('open_modal') ?>'));
      targetModal.show();
    });
  </script>
  <?php endif; ?>

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

<!-- EXTERNAL JS  -->

  <!-- Bootstrap JS -->
  <script src="<?= base_url("rsc/bootstrap-5.3.7/js/bootstrap.bundle.min.js") ?>"></script>

  <!-- Password JS -->
  <script src="<?= base_url('rsc/custom_js/password.js') ?>"></script>

  <!-- Event, Error, Success Modal JS -->
  <script src="<?= base_url('rsc/custom_js/modals.js') ?>"></script>

  <!-- sidebar script -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const toggleBtn = document.getElementById("toggleSidebarBtn");
      const toggleIcon = document.getElementById("toggleIcon");
      const html = document.documentElement;

      toggleBtn.addEventListener("click", () => {
        const isCollapsed = html.classList.toggle("sidebar-collapsed");
        localStorage.setItem("sidebarCollapsed", isCollapsed);

        toggleIcon.classList.toggle("bi-chevron-right", isCollapsed);
        toggleIcon.classList.toggle("bi-chevron-left", !isCollapsed);
      });

      // Set correct icon on load
      const isCollapsed = html.classList.contains("sidebar-collapsed");
      toggleIcon.classList.toggle("bi-chevron-right", isCollapsed);
      toggleIcon.classList.toggle("bi-chevron-left", !isCollapsed);
    });
  </script>

  <!-- Prevent back history script -->
  <script src="<?= base_url("rsc/custom_js/preventBackHistory.js") ?>"></script>

  <!-- FullCalendar JS -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

  <!--Calendar-->
  <script src="<?= base_url('rsc/custom_js/calendar.js') ?>"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const profileInput = document.getElementById('profimg');
      const profilePreview = document.getElementById('profilePreview');
      const clearBtn = document.getElementById('clearProfileImage');
      const clearFlag = document.getElementById('clearImageFlag');
      

      const defaultImage = "<?= base_url('rsc/assets/uploads/default.png') ?>";
      const currentImage = "<?= base_url('rsc/assets/uploads/' . esc(session('profimg') ?? 'default.png')) ?>";

      profileInput.addEventListener('change', function () {
          const file = this.files[0];
          if (file) {
              // Check if file size is more than 1MB (1048576 bytes)
              if (file.size > 1048576) {
                  alert('Image must not exceed 1MB.');
                  this.value = ''; // Clear the input
                  profilePreview.src = currentImage; // Reset preview
                  clearFlag.value = '0';
                  return;
              }
              profilePreview.src = URL.createObjectURL(file);
              clearFlag.value = '0'; // user picked new file
          }
      });

      clearBtn.addEventListener('click', function () {
          profilePreview.src = defaultImage;
          profileInput.value = '';
          clearFlag.value = '1'; // tell backend to reset to default
      });

      const modal = document.getElementById('editProfileModal');
      modal.addEventListener('show.bs.modal', function () {
          profilePreview.src = currentImage;
          profileInput.value = '';
          clearFlag.value = '0'; // reset clear flag on open
      });
  });

  </script>

  </main>

  <footer class="footer mt-5 text-center shadow-darker border-top">
    <div class="container py-4 footer-container">
      <div class="row align-items-center">
        <!-- Logo Column -->
        <div class="col-12 col-md-3 mb-3 mb-md-0 text-md-start">
          <a href="<?= site_url('admin/home') ?>" class="navbar-brand d-inline-block gap-2">
            <img src="<?= base_url('rsc/assets/ispsc-logo.png') ?>" alt="ISPSC Logo" class="cs-logo" style="width: 140px;">
            <img src="<?= base_url('rsc/assets/cs-logo.png') ?>" alt="CS Logo" class="cs-logo" style="width: 140px;">
          </a>
        </div>

        <!-- Main Navigation Links -->
        <div class="col-12 col-md-6 mb-3 mb-md-0">
        <ul class="nav justify-content-center flex-wrap">
            <?php if (in_array(session('role'), ['admin', 'superadmin'])): ?>
              <li class="nav-item">
                <a class="nav-link px-3 text-dark" href="<?= site_url('admin/home') ?>">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link px-3 text-dark" href="<?= site_url('admin/users') ?>">Users</a>
              </li>
              <li class="nav-item">
                <a class="nav-link px-3 text-dark" href="<?= site_url('admin/academics') ?>">Academics</a>
              </li>

            <?php elseif (session('role') === 'faculty'): ?>
              <li class="nav-item">
                <a class="nav-link px-3 text-dark" href="<?= site_url('faculty/home') ?>">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link px-3 text-dark" href="<?= site_url('faculty/classes') ?>">Classes</a>
              </li>

            <?php elseif (session('role') === 'student'): ?>
              <li class="nav-item">
                <a class="nav-link px-3 text-dark" href="<?= site_url('student/home') ?>">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link px-3 text-dark" href="<?= site_url('student/curriculum') ?>">Curriculum</a>
              </li>
              <li class="nav-item">
                <a class="nav-link px-3 text-dark" href="<?= site_url('student/grades/grades') ?>">Grades</a>
              </li>
            <?php endif; ?>
            <li class="nav-item">
              <a class="nav-link px-3 text-dark" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link px-3 text-dark" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
            </li>
          </ul>
        </div>

        <!-- Quick Links -->
        <div class="col-12 col-md-3">
          <ul class="nav flex-row flex-md-column justify-content-center align-items-center align-items-md-end small">
            <h6 class="d-none d-md-block">Quick Links</h6>
            <li class="nav-item">
              <a class="nav-link px-2 text-dark" href="https://www.facebook.com/people/Ispsc-Main-Campus-Registrar/61576774508246/" target="_blank">
                <i class="fa-brands fa-facebook me-2"></i>Registrar FB
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link px-2 text-dark" href="https://www.facebook.com/people/ISPSC-Main-Campus-Office-of-Student-Affairs-and-Services/100095246231734/" target="_blank">
                <i class="fa-brands fa-facebook me-2"></i>SAS FB
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link px-2 text-dark" href="https://www.facebook.com/nlpsccssocandon" target="_blank">
                <i class="fa-brands fa-facebook me-2"></i>CSSO FB
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link px-2 text-dark" href="https://www.facebook.com/ComputingStudiesISPSCMain" target="_blank">
                <i class="fa-brands fa-facebook me-2"></i>CCIS FB
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- Footer Bottom -->
      <hr class="my-3">
      <p class="mb-0 small">&copy; <?= date('Y') ?> CCIS Portal. All rights reserved.</p>
    </div>
  </footer>
</div>
</body>
</html>
