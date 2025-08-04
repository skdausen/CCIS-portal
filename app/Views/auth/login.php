    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
      <div class="card p-4 rounded-4" id="login-card">
        <div class="text-center mb-4">
          <img src="<?= base_url('rsc/assets/cs-logo.png') ?>" alt="CS Logo" class="mb-3" style="width: 80px;">
          <h4 class="fw-bold ">Login Portal</h4>
          <p>Enter your credentials to access your account</p>
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
            <div class="input-group rounded-pill">
              <span class="input-group-text border-0">
                <i class="bi bi-person-fill text-secondary"></i>
              </span>
              <input type="text" name="username" class="form-control border-0 py-2" placeholder="Enter Username" required autofocus>
            </div>
          </div>

          <!-- PASSWORD INPUT WITH ICON -->
          <div class="mb-3">
            <div class="input-group rounded-pill">
              <span class="input-group-text border-0">
                <i class="bi bi-lock-fill text-secondary"></i>
              </span>
              <input type="password" name="password" class="form-control border-0 py-2" placeholder="Enter Password" required>
            </div>
          </div>

          <div class="d-flex justify-content-end align-items-center mb-3">
            <a href="<?= site_url('password/forgot') ?>" class="small forgot-password">Forgot Password?</a>
          </div>

          <button type="submit" class="btn btn-success w-100 rounded-pill py-2" style="font-size: 1.05rem;">
            <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status"></span>
            <span id="btnText">LOGIN</span>
          </button>
        </form>
      </div>
    </div>