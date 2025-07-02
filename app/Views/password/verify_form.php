
<form action="<?= site_url('password/verify') ?>" method="post">
    <?= csrf_field() ?>
    <input type="email" name="email" value="<?= esc($email) ?>" readonly>
    <input type="text" name="otp" placeholder="Enter OTP" required>
    <button type="submit">Verify</button>
</form>
