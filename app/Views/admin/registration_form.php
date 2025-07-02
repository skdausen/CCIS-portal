<div class="container">

    <form action="<?= site_url('admin/register') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <!-- The set_value() function provided by the Form Helper is used to show old input data when errors occur. -->
            <input type="email" class="form-control" name="email" id="email" required placeholder="Enter your email">
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Send OTP</button>

    </form>

</div>