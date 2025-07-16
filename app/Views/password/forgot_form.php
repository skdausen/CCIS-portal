<!-- new design -->
<div class="row w-100 justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
        <div class="card p-4 shadow rounded-4">
        <div class="text-center mb-4">
            <img src="<?= base_url('rsc/assets/cs-logo.png') ?>" alt="CS Logo" class="mb-3" style="width: 80px;">
            <h4 class="fw-bold" style="font-size: 1.5rem;">Forgot Password</h4>
            <p class="text-muted" style="font-size: 1rem;">Enter your email</p>
        </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('password/send') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <!-- The set_value() function provided by the Form Helper is used to show old input data when errors occur. -->
                    <input type="email" class="form-control rounded-pill py-2" name="email" id="email" required placeholder="Enter your email">
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2" style="font-size: 1.05rem;">
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    <span id="btnText">Send OTP</span>
                </button>
            </form>

            <div class="text-end">
                <a href="<?= site_url('auth/login') ?>" class="small text-decoration-none text-reset"> cancel </a>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
    document.querySelector('form').addEventListener('submit', function () {
        const button = this.querySelector('button[type="submit"]');
        const spinner = button.querySelector('.spinner-border');
        const btnText = button.querySelector('#btnText');

        // Show spinner and update text
        spinner.classList.remove('d-none');
        btnText.textContent = 'Sending...';

        // Disable the button to prevent double submission
        button.disabled = true;
    });
</script>
