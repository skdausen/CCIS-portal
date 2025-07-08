<!-- users.php -->
<div class="container mt-5">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 class="mb-2">👥 List of Users</h3>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- FILTERS & SEARCH -->
    <div class="row mb-3">
        <div class="col-md-3 mb-2">
            <select id="roleFilter" class="form-select">
                <option value="">Filter by Role</option>
                <option value="admin">Admin</option>
                <option value="superadmin">Superadmin</option>
                <option value="faculty">Faculty</option>
                <option value="student">Student</option>
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by username or email...">
        </div>
        <div class="col-md-5 mb-2 d-flex justify-content-end">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">Add Account</button>
        </div>
    </div>

    <!-- USERS TABLE -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="usersTable">
            <thead class="table-light">
                <tr>
                    <th>User ID</th>
                    <th>Role</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= esc($user['user_id']) ?></td>
                            <td><?= esc($user['role']) ?></td>
                            <td><?= esc($user['username']) ?></td>
                            <td><?= esc($user['lname']) . ', ' . esc($user['fname']) . ' ' . esc($user['mname']) ?></td>
                            <td><?= esc($user['email']) ?></td>
                            <td>
                                <?php if ($user['status'] === 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="#" 
                                class="btn btn-sm btn-outline-primary viewUserBtn"
                                data-user='<?= json_encode($user) ?>'>
                                View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ADD USER MODAL -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Add New User Account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form action="<?= site_url('admin/create-user') ?>" method="post">
                <div class="modal-body">

                    <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php elseif (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Enter ID Number" required>
                    </div>

                    <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address" required>
                    </div>

                    <div class="mb-3">
                    <label for="fname" class="form-label">First Name:</label>
                    <input type="text" name="fname" id="fname" class="form-control" placeholder="e.g. Juan" required>
                    </div>

                    <div class="mb-3">
                    <label for="mname" class="form-label">Middle Name:</label>
                    <input type="text" name="mname" id="mname" class="form-control" placeholder="(Optional)">
                    </div>

                    <div class="mb-3">
                    <label for="lname" class="form-label">Last Name:</label>
                    <input type="text" name="lname" id="lname" class="form-control" placeholder="e.g. Dela Cruz" required>
                    </div>

                    <div class="mb-3">
                    <label for="role" class="form-label">Role:</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="faculty">Faculty</option>
                        <option value="student">Student</option>
                    </select>
                    </div>

                    <p class="mt-3"><strong>Default password:</strong> <code>ccis1234</code></p>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create Account</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>


        </div>
    </div>
    </div>

    <!-- VIEW USER MODAL -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="viewUserModalLabel">👤 User Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- USER INFO WILL BE INSERTED HERE VIA JS -->
            <div id="userDetailsContent">
            <p><strong>User ID:</strong> <span id="detailUserID"></span></p>
            <p><strong>Role:</strong> <span id="detailRole"></span></p>
            <p><strong>Username:</strong> <span id="detailUsername"></span></p>
            <p><strong>Email:</strong> <span id="detailEmail"></span></p>
            <p><strong>Status:</strong> <span id="detailStatus"></span></p>
            <p><strong>Full Name:</strong> <span id="detailFullname"></span></p>
            <p><strong>Sex:</strong> <span id="detailSex"></span></p>
            <p><strong>Birthday:</strong> <span id="detailBirthday"></span></p>
            <p><strong>Address:</strong> <span id="detailAddress"></span></p>
            <p><strong>Contact Number:</strong> <span id="detailContact"></span></p>
            <p><strong>Created At:</strong> <span id="detailCreated"></span></p>
            <p><strong>Last Login:</strong> <span id="detailLogin"></span></p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>

</div>



