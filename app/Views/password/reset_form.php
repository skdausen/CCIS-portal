    <div class="container-fluid vh-100 row">
        <div class="row flex-grow-1 px-0">
            <!-- LEFT: LOGIN FORM -->
            <div class="col-md-6 d-flex align-items-center justify-content-center bg-white">
                <div class="card shadow p-4" style="width: 100%; max-width: 400px; border-radius: 15px;">
                    <img src="<?= base_url('rsc/assets/cs-logo.png'); ?>" alt="" class="mb-3 mx-auto login-img">
                    
                    <h3 class="mb-3 text-center">Forgot Password</h3>
                    
                    <p class="text-center text-muted small mb-5">
                        Enter your New Password
                    </p>


                    <form action="<?= site_url('password/reset') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <input type="hidden" name="email" value="<?= esc($email) ?>">
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="New password" required>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-type New Password" required>
                        </div>

                        <div class="alert alert-danger d-none" id="passwordMismatch" role="alert">
                            Passwords do not match
                        </div>

                        <div class="alert alert-danger d-none" id="passwordTooShort" role="alert">
                            Password must be at least 8 characters long
                        </div>

                        <button type="submit" class="btn btn-teal w-100">
                            <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status"></span>
                            <span id="btnText">Reset Password</span>
                        </button>

                        <div class="text-end">
                            <a href="<?= site_url('auth/login') ?>" class="small text-decoration-none text-reset"> cancel </a>
                        </div>
                    </form>

                </div>
            </div>

            <!-- RIGHT: WELCOME & IMAGE -->
            <div class="col-md-6 d-flex flex-column justify-content-center align-items-center bg-gradient" style="background: linear-gradient(to bottom right, #e0f7fa, #b2ebf2);">
            <div class="text-center px-4">
                <h2 class="fw-bold">Welcome to AdaL</h2>
                <p class="text-muted mb-5">Unlock a smarter experience with AdaL, your dedicated intelligent partner for seamless productivity.</p>
                <img src="<?= base_url('rsc/assets/mascot.png'); ?>" alt="Mascot" class="img-fluid" style="max-width: 300px;">
            </div>
            </div>
        </div>
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