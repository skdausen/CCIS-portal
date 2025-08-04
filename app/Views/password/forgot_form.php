<!-- new design -->
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
        <div class="card p-4 shadow rounded-4">
        <div class="back-arrow fs-4">
            <a href="<?= site_url('auth/login') ?>" class="small text-decoration-none"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <div class="text-center mb-4">
            <img src="<?= base_url('rsc/assets/cs-logo.png') ?>" alt="CS Logo" class="mb-3" style="width: 80px;">
            <h4 class="fw-bold" >Forgot Password</h4>
            <p>Enter your email</p>
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
                    <div class="input-group rounded-pill">
                        <span class="input-group-text border-0">
                            <i class="bi bi-envelope-fill text-secondary"></i>
                        </span>
                        <!-- The set_value() function provided by the Form Helper is used to show old input data when errors occur. -->
                        <input type="email" class="form-control border-0 py-2" name="email" id="email" required placeholder="Enter your email">
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 rounded-pill py-2">
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    <span id="btnText">Send OTP</span>
                </button>
            </form>
        </form>
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
