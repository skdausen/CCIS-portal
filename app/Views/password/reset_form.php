<form action="<?= site_url('password/reset') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="email" value="<?= esc($email) ?>">
    <input type="password" name="password" placeholder="New password" required>
    <button type="submit">Reset Password</button>
</form>
