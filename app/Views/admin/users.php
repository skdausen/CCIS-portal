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
            <a href="<?= site_url('admin/add-user') ?>" class="btn btn-success">➕ Add Account</a>
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
                                <a href="<?= site_url('admin/user/' . $user['user_id']) ?>" class="btn btn-sm btn-outline-primary">
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

<!-- SEARCH & FILTER SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filter = document.getElementById('roleFilter');
    const search = document.getElementById('searchInput');
    const rows = document.querySelectorAll('#usersTable tbody tr');

    function filterRows() {
        const roleVal = filter.value.toLowerCase();
        const searchVal = search.value.toLowerCase();

        rows.forEach(row => {
            const role = row.cells[1].textContent.toLowerCase();
            const username = row.cells[2].textContent.toLowerCase();
            const email = row.cells[3].textContent.toLowerCase();

            let matchRole = true;

            if (roleVal === 'admin') {
                matchRole = role === 'admin' || role === 'superadmin'; // Include superadmin under admin
            } else if (roleVal) {
                matchRole = role === roleVal;
            }

            const matchSearch = !searchVal || username.includes(searchVal) || email.includes(searchVal);

            row.style.display = matchRole && matchSearch ? '' : 'none';
        });
    }

    filter.addEventListener('change', filterRows);
    search.addEventListener('input', filterRows);
});
</script>

