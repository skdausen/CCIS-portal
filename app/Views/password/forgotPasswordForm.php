
<div class="container mt-5">
    <h2>Enter your email</h2>
    <form action="/forgot" method="post">
        <?= csrf_field() ?>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <!-- The set_value() function provided by the Form Helper is used to show old input data when errors occur. -->
            <input type="email" class="form-control" name="email" id="email" value="<?= set_value('email') ?>" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Forgot Password</button>

    </form>
</div>

