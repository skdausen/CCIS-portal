<!-- home.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h1>Welcome, <?= session('username') ?>!</h1>
    <p>You are logged in.</p>
    <a href="<?= base_url('logout') ?>">Logout</a>
</body>
</html>
