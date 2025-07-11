        <div class="row px-0 w-100 justify-content-center">
            <!-- LEFT: LOGIN FORM -->
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
                <div class="card shadow p-4" style="width: 100%; border-radius: 15px;">
                    <img src="<?= base_url('rsc/assets/cs-logo.png'); ?>" alt="" class="mb-3 mx-auto login-img">
                    
                    <h3 class="mb-3 text-center">Forgot Password</h3>
                    
                    <p class="text-center text-muted small mb-5">
                        Enter your email
                    </p>

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
                            <label for="email" class="form-label">Email</label>
                            <!-- The set_value() function provided by the Form Helper is used to show old input data when errors occur. -->
                            <input type="email" class="form-control" name="email" id="email" required placeholder="Enter your email">
                        </div>

                        <button type="submit" class="btn btn-teal w-100">
                            <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status"></span>
                            <span id="btnText">Send OTP</span>
                        </button>

                        <div class="text-end">
                            <a href="<?= site_url('auth/login') ?>" class="small text-decoration-none text-reset"> cancel </a>
                        </div>
                    </form>
                    
                </div>
            </div>

        </div>
    </div>
</div>