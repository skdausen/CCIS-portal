
  <!-- HEADER -->
<!-- HEADER -->
<div class="d-flex w-100 justify-content-between align-items-center bg-dark text-white px-4 py-2 position-fixed top-0 start-0" style="z-index: 1000;">
  <div style="font-weight: bold;">CCIS</div>
  <div style="font-weight: bold;">LOGO</div>
</div>


  <!-- MAIN CONTENT -->
    <div class="container-fluid vh-100 row">
        <div class="row flex-grow-1">
            <!-- LEFT: LOGIN FORM -->
            <div class="col-md-6 d-flex align-items-center justify-content-center bg-white">
            <div class="card shadow p-4" style="width: 100%; max-width: 400px; border-radius: 15px;">
                <img src="<?= base_url('rsc/assets/cs-logo.png'); ?>" alt="" class="mb-3 mx-auto login-img">
                <h3 class="mb-4 text-center">Login</h3>
                <p class="text-muted small mb-4">
                Lorem ipsum dolor sit amet consectetur. Aliquam dictumst nisl imperdiet scelerisque ut urna.
                </p>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="/login" method="post" onsubmit="showSpinner()">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Enter UserID" required autofocus>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label small" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-teal w-100">
                    <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status"></span>
                    <span id="btnText">LOGIN</span>
                </button>
                </form>
                <div class="text-end mt-2">
                <a href="#" class="small text-muted">Forgot password?</a>
                </div>
            </div>
            </div>

            <!-- RIGHT: WELCOME & IMAGE -->
            <div class="col-md-6 d-flex flex-column justify-content-center align-items-center bg-gradient" style="background: linear-gradient(to bottom right, #e0f7fa, #b2ebf2);">
            <div class="text-center px-4">
                <h2 class="fw-bold">Welcome...</h2>
                <p class="text-muted">Lorem ipsum dolor sit amet consectetur. Iaculis sapien et vitae sit molestie viverra turpis faucibus.</p>
                <img src="<?= base_url('rsc/assets/robot-mascot.png'); ?>" alt="Mascot" class="img-fluid" style="max-width: 300px;">
            </div>
            </div>
        </div>
    </div>
</div>


