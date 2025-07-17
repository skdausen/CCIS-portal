<!-- new design -->
<div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
    <div class="card p-4 shadow rounded-4">
        <div class="back-arrow fs-4">
            <a href="<?= site_url('auth/login') ?>" class="small text-decoration-none"><i class="fa-solid fa-arrow-left"></i></a>
        </div> 
        <div class="text-center mb-4">
            <img src="<?= base_url('rsc/assets/cs-logo.png') ?>" alt="CS Logo" class="mb-3" style="width: 80px;">
            <h4 class="fw-bold">Forgot Password</h4>
            <p>Enter your New Password</p>
        </div>

        <form action="<?= site_url('password/reset') ?>" method="post">
                        <?= csrf_field() ?>

            <div class="alert alert-danger d-none" id="passwordMismatch" role="alert">
                Passwords do not match
            </div>

            <div class="alert alert-danger d-none" id="passwordTooShort" role="alert">
                Password must be at least 8 characters long
            </div>

            <div class="mb-3">
                <input type="hidden" name="email" value="<?= esc($email) ?>">
            </div>

            <div class="mb-3">
                <label for="newpw" class="form-label">New Password:</label>
                <input type="password" name="password" class="form-control rounded-pill py-2" placeholder="Enter New password" required>
            </div>

            <div class="mb-3">
                <label for="confirmpw" class="form-label">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control rounded-pill py-2" placeholder="Re-type New Password" required>
            </div>

            <button type="submit" class="btn btn-success w-100 rounded-pill py-2">
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                <span id="btnText">Reset Password</span>
            </button>
        </form>

        </div>
    </div>

<!-- confirm password -->
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.querySelector('input[name="password"]').value;
    const confirmPassword = document.querySelector('input[name="confirm_password"]').value;

    const alertMismatch = document.getElementById('passwordMismatch');
    const alertShort = document.getElementById('passwordTooShort');

    // Hide both alerts at the start
    alertMismatch.classList.add('d-none');
    alertShort.classList.add('d-none');

    // Validation checks
    if (password.length < 8) {
        e.preventDefault();
        alertShort.classList.remove('d-none');
    } else if (password !== confirmPassword) {
        e.preventDefault();
        alertMismatch.classList.remove('d-none');
    }
});
</script>