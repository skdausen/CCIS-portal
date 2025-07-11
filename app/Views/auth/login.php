  <div class="row w-100 justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
      <div class="card p-4 shadow rounded-4">
        <div class="text-center mb-4">
          <img src="<?= base_url('rsc/assets/cs-logo.png') ?>" alt="CS Logo" class="mb-3" style="width: 80px;">
          <h4 class="fw-bold" style="font-size: 1.5rem;">Login Portal</h4>
          <p class="text-muted" style="font-size: 1rem;">Enter your credentials to access your account</p>
        </div>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
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
