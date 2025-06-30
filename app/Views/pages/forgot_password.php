<?= session()->getFlashdata('message') ?>
<form method="post" action="<?= base_url('/send-reset-link') ?>">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="email">Email address</label>
        <input 
            type="email" 
            name="email" 
            class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
            id="email" 
            placeholder="Enter email">
        <div class="invalid-feedback">
            <?= isset($validation) ? $validation->getError('email') : '' ?>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Send Reset Link</button>
</form>