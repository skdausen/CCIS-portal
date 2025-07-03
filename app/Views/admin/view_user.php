<!-- view_user.php -->
<div class="container mt-5">
    <h3 class="mb-4">👤 User Details</h3>

    <div class="card shadow-sm p-4">
        <p><strong>User ID:</strong> <?= esc($user['user_id']) ?></p>
        <p><strong>Role:</strong> <?= esc($user['role']) ?></p>
        <p><strong>Username:</strong> <?= esc($user['username']) ?></p>
        <p><strong>Email:</strong> <?= esc($user['email']) ?></p>
        <p><strong>Status:</strong> <?= esc($user['status']) ?></p>
        <p><strong>Full Name:</strong> <?= esc($user['lname']) ?> <?= esc($user['fname']) ?> <?= esc($user['mname']) ?></p>
        <p><strong>Sex:</strong> <?= esc($user['sex']) ?></p>
        <p><strong>Birthday:</strong> <?= esc($user['birthday']) ?></p>
        <p><strong>Address:</strong> <?= esc($user['address']) ?></p>
        <p><strong>Contact Number:</strong> <?= esc($user['contact_number']) ?></p>
        <p><strong>Created At:</strong> <?= esc($user['created_at']) ?></p>
        <p><strong>Last Login:</strong> <?= esc($user['last_login']) ?></p>

        <div class="d-flex gap-2 mt-3">
            <a href="<?= site_url('admin/users') ?>" class="btn btn-sm btn-secondary">← Back</a>
        </div>
    </div>
</div>
