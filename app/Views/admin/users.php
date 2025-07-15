<!-- users.php -->
<div class="container mt-5 users-page" data-base-url="<?= base_url() ?>">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 class="mb-2">👥 List of Users</h3>
    </div>

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
        <table class="table table-bordered table-hover custom-padding" id="usersTable">
            <thead class="table-light">
                <tr>
                    <th>User ID</th>
                    <th>Role</th>
                    <th>Username</th>
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
                                data-user-id="<?= esc($user['user_id']) ?>">
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

                    <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Enter ID Number" required>
                    </div>

                    <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address" required>
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
            <div class="row g-4 align-items-center">
            <!-- PROFILE IMAGE -->
            <div class="col-md-4 text-center">
                <img id="detailProfileImg" src="" alt="Profile Image" class="rounded-circle shadow" style="object-fit: cover;">
                <p class="fw-bold mt-2" id="detailUsername"></p>
                <small class="text-muted" id="detailRole"></small>
            </div>

            <!-- USER DETAILS -->
            <div class="col-md-8">
                <table class="table table-sm table-hover custom-padding">
                <tbody>
                    <tr>
                    <th>User ID</th>
                    <td id="detailUserID"></td>
                    </tr>
                    <tr>
                    <th>Email</th>
                    <td id="detailEmail"></td>
                    </tr>
                    <tr>
                    <th>Status</th>
                    <td id="detailStatus"></td>
                    </tr>
                    <tr>
                    <th>Full Name</th>
                    <td id="detailFullname"></td>
                    </tr>
                    <tr>
                    <th>Sex</th>
                    <td id="detailSex"></td>
                    </tr>
                    <tr>
                    <th>Birthday</th>
                    <td id="detailBirthday"></td>
                    </tr>
                    <tr>
                    <th>Address</th>
                    <td id="detailAddress"></td>
                    </tr>
                    <tr>
                    <th>Contact Number</th>
                    <td id="detailContact"></td>
                    </tr>
                    <tr>
                    <th>Created At</th>
                    <td id="detailCreated"></td>
                    </tr>
                    <tr>
                    <th>Last Login</th>
                    <td id="detailLogin"></td>
                    </tr>
                </tbody>
                </table>
            </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>


</div>



