<!-- admin_header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <title>AdaL CCIS Portal</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo base_url("rsc/bootstrap-5.3.7/css/bootstrap.css"); ?>">
    <link rel="stylesheet" href="<?= base_url("rsc/bootstrap-5.3.7/css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url('rsc/custom_css/style.css'); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- FullCalendar Styles from correct path -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">


    <!-- Force browser not to cache this page -->
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand ms-3" href="#">CCIS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Main navigation links - pushed to the right -->
                <ul class="navbar-nav main-nav-links">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?= site_url('admin/home') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/users') ?>">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/academics') ?>">Academics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                </ul>
                <!-- Icons on the far right -->
                <ul class="navbar-nav icon-nav-links me-3">
                    <li class="nav-item me-3">
                        <a class="nav-link" href="#"><i class="fas fa-bell mt-2"></i></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle profile-dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Placeholder for user profile image -->
                            <img src="<?= base_url('rsc/assets/uploads/' . esc(session('profimg') ?? 'default.png')) ?>"  alt="User Profile">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="bi bi-person-circle"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</body>
</html>
