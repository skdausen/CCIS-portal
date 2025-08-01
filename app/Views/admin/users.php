<!-- users.php -->
<div class="container mt-5 users-page" id="userPage" data-users-url="<?= site_url('admin/users') ?>" data-base-url="<?= base_url() ?>">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 class="mb-3"><i class="fa-solid fa-users me-3 text-secondary"></i>List of Users</h3>
    </div>

    <!-- FILTERS & SEARCH -->

    <div class="row mb-4">
        <form method="get" id="filterForm" class="col-md-7">
            <div class="row">
                <!-- Role filter -->
                <div class="col-md-5">
                    <select name="role" class="form-select" id="filterRole">
                        <option value="">All Roles</option>
                        <option value="admin" <?= ($role === 'admin') ? 'selected' : '' ?>>Admin</option>
                        <option value="faculty" <?= ($role === 'faculty') ? 'selected' : '' ?>>Faculty</option>
                        <option value="student" <?= ($role === 'student') ? 'selected' : '' ?>>Student</option>
                        <option value="superadmin" <?= ($role === 'superadmin') ? 'selected' : '' ?>>Superadmin</option>
                    </select>
                </div>

                <!-- Search input -->
                <div class="col-md-7">
                    <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search username or email" value="<?= esc($search ?? '') ?>">
                </div>
            </div>
        </form>
        <!-- Add User Button -->
        <div class="col-md-5 mb-2 d-flex justify-content-end">
            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addUserModal">Add Account</button>
        </div>
    </div>

    <!-- USERS TABLE -->
    <div id="userTableContainer">
        <div class="table-responsive" >
            <div class="table-scroll">
                <table class="table table-bordered table-hover custom-padding users-table" id="usersTable">
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
                    <tbody class="users-table-body">
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= esc($user['user_id']) ?></td>
                                    <td><?=strtoupper( esc($user['role']) )?></td>
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
        </div>
    </div>

    
    <!-- PAGINATION -->
    <?php
        $queryParams = [];
        if (!empty($search)) $queryParams['search'] = $search;
        if (!empty($role)) $queryParams['role'] = $role;
        $baseQuery = http_build_query($queryParams);
    ?>

    <nav aria-label="Curriculum pagination">
        <ul class="pagination justify-content-center my-4">
            <!-- Previous Button -->
            <?php if ($page > 1): ?>
                <li class="page-item mx-1">
                    <a class="page-link" href="<?= site_url('admin/users?page=' . ($page - 1) . (!empty($baseQuery) ? '&' . $baseQuery : '')) ?>">Previous</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled mx-1">
                    <span class="page-link">Previous</span>
                </li>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <li class="page-item mx-1 <?= ($page == $p) ? 'active' : '' ?>">
                    <a class="page-link" href="<?= site_url('admin/users?page=' . $p . (!empty($baseQuery) ? '&' . $baseQuery : '')) ?>">
                        <?= $p ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Next Button -->
            <?php if ($page < $totalPages): ?>
                <li class="page-item mx-1">
                    <a class="page-link" href="<?= site_url('admin/users?page=' . ($page + 1) . (!empty($baseQuery) ? '&' . $baseQuery : '')) ?>">Next</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled mx-1">
                    <span class="page-link">Next</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>


    <!-- ADD USER MODAL -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Add New User Account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form action="<?= site_url('admin/create-user') ?>" method="post" id="addUser">
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

                    <div class="mb-3 d-none" id="curriculumGroup">
                        <label for="curriculum_id" class="form-label">Curriculum:</label>
                        <select name="curriculum_id" id="curriculum_id" class="form-select">
                            <option value="">Select Curriculum</option>
                            <?php foreach ($curriculums as $curriculum): ?>
                                <option value="<?= $curriculum['curriculum_id'] ?>">
                                    <?= esc($curriculum['curriculum_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="programGroup">
                        <label for="program" class="form-label">Program:</label>
                        <select name="program_id" id="program_id" class="form-select">
                            <option value="">Select Program</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?= $program['program_id'] ?>">
                                    <?= esc($program['program_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="yearlevelGroup">
                        <label for="year_level" class="form-label">Year Level:</label>
                        <select name="year_level" id="year_level" class="form-select">
                            <option value="">Select year level</option>
                            <option value="1" <?= ($student['year_level'] ?? '') == 'First Year' ? 'selected' : '' ?>>1st Year</option>
                            <option value="2" <?= ($student['year_level'] ?? '') == 'Second Year' ? 'selected' : '' ?>>2nd Year</option>
                            <option value="3" <?= ($student['year_level'] ?? '') == 'Third Year' ? 'selected' : '' ?>>3rd Year</option>
                            <option value="4" <?= ($student['year_level'] ?? '') == 'Fourth Year' ? 'selected' : '' ?>>4th Year</option>
                        </select>
                    </div>


                    <p class="mt-3"><strong>Default password:</strong> <code>ccis1234</code></p>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-success">Create Account</button>
                    <button type="button" class="btn btn-outline-secondary btn-thin px-3 py-2 rounded-1" data-bs-dismiss="modal">Cancel</button>
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
                    <tr>
                    <!-- STUDENT-ONLY FIELDS -->
                    <tr class="student-only d-none">
                        <th>Curriculum</th>
                        <td id="detailCurriculum"></td>
                    </tr>
                    <tr class="student-only d-none">
                        <th>Program</th>
                        <td id="detailProgram"></td>
                    </tr>
                    <tr class="student-only d-none">
                        <th>Year Level</th>
                        <td id="detailYearLevel"></td>
                    </tr>
                        <th>Address</th>
                        <td id="detailAddress"></td>
                    </tr>
                    <tr>
                        <th>Contact Number</th>
                        <td id="detailContact"></td>
                    </tr>
                    <tr>
                        <th>Account Created</th>
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
            <button class="btn btn-outline-secondary btn-thin px-3 py-2 rounded-1" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>

    <!-- Custom JS for Users Page -->
    <script src="<?= base_url('rsc/custom_js/users.js') ?>"></script>

    <script>
    document.addEventListener('keydown', function(event) {
        const currentPage = <?= $page ?>;
        const totalPages = <?= $totalPages ?>;

        if (event.key === 'ArrowRight') {
            const nextPage = (currentPage >= totalPages) ? 1 : currentPage + 1;
            window.location.href = "<?= site_url('admin/users?page=') ?>" + nextPage;
        }
        if (event.key === 'ArrowLeft') {
            const prevPage = (currentPage <= 1) ? totalPages : currentPage - 1;
            window.location.href = "<?= site_url('admin/users?page=') ?>" + prevPage;
        }
    });
    </script>

</div>





