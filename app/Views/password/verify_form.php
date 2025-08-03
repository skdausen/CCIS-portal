<!-- new design -->
<div class="row w-100 justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
        <div class="card p-4 rounded-4">
            <div class="back-arrow fs-4">
                <a href="<?= site_url('auth/login') ?>" class="small text-decoration-none"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
            <div class="text-center mb-4">
                <img src="<?= base_url('rsc/assets/cs-logo.png') ?>" alt="CS Logo" class="mb-3" style="width: 80px;">
                <h4 class="fw-bold">Forgot Password</h4>
                <p>Enter your OTP</p>
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

            <form action="<?= site_url('password/verify') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control rounded-pill py-2" value="<?= esc($email) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="otp" class="form-label">OTP Code:</label>
                    <input type="text" name="otp"  max="6" pattern="\d{6}" class="form-control rounded-pill py-2" placeholder="Enter OTP" required>
                </div>

                <button type="submit" class="btn btn-success w-100 rounded-pill py-2">
                    <span class="spinner-border spinner-border-sm d-none"  role="status"></span>
                    <span id="btnText">Verify</span>
                </button>

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
        btnText.textContent = 'Verifying...';

        // Disable button to prevent double submit
        button.disabled = true;
    });
</script>