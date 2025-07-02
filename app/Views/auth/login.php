<!-- login.php -->

<!-- MAIN CONTENT -->
    <div class="container-fluid vh-100 row">
        <div class="row flex-grow-1 px-0">
            <!-- LEFT: LOGIN FORM -->
            <div class="col-md-6 d-flex align-items-center justify-content-center bg-white">
            <div class="card shadow p-4" style="width: 100%; max-width: 400px; border-radius: 15px;">
                <img src="<?= base_url('rsc/assets/cs-logo.png'); ?>" alt="" class="mb-3 mx-auto login-img">
                <h3 class="mb-3 text-center">Login Portal</h3>
                <p class="text-center text-muted small mb-5">
                Enter your credentials to access your CCIS account.
                </p>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= site_url('auth/login') ?>" method="post" onsubmit="showSpinner()">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Enter Username" required autofocus>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                </div>
                <div class="d-flex mb-3">
                    <div class="form-check mb-3 col-md-6">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>
                    <div class="text-end col-md-6">
                            <a href="<?= site_url('password/forgot') ?>" class="small text-muted">Forgot password?</a>
                    </div>
                </div>
                <button type="submit" class="btn btn-teal w-100">
                    <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status"></span>
                    <span id="btnText">LOGIN</span>
                </button>
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


