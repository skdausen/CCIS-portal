
    <div class="card p-4 shadow" style="min-width: 300px;">
        <img src="<?= base_url('rsc/assets/cs-logo.png'); ?>" alt="" class="mb-3 mx-auto login-img">
        <h3 class="mb-4 text-center">Login</h3>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="/login" method="post" onsubmit="showSpinner()">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>

            <button id="loginBtn" type="submit" class="btn btn-primary w-100">
                <span class="spinner-border spinner-border-sm" id="spinner" role="status" aria-hidden="true"></span>
                <span id="btnText">Login</span>
            </button>
        </form>
    </div>

