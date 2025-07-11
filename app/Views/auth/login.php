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
            <input type="text" name="username" class="form-control rounded-pill py-2" placeholder="Enter Username" required autofocus>
          </div>
          <div class="mb-3">
            <input type="password" name="password" class="form-control rounded-pill py-2" placeholder="Enter Password" required>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="remember">
              <label class="form-check-label" for="remember" style="font-size: 0.9rem;">Remember me</label>
            </div>
            <a href="<?= site_url('password/forgot') ?>" class="small text-muted">Forgot?</a>
          </div>

          <button type="submit" class="btn btn-primary w-100 rounded-pill py-2" style="font-size: 1.05rem;">
            <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status"></span>
            <span id="btnText">LOGIN</span>
          </button>
        </form>
      </div>
    </div>
</div>


