<!-- Views/admin/home.php -->
<div class="container mt-5">
    <h2>Welcome, <?= session('username'); ?>!</h2>
    <p class="lead">You are logged in as <strong><?= session('role'); ?></strong>.</p>

    <a href="<?= site_url('admin/users') ?>" class="btn btn-primary mt-3">Manage Users</a>
</div>
