<!-- student_header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Force browser not to cache this page -->
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>AdaL CCIS Portal</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= base_url("rsc/bootstrap-5.3.7/css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url('rsc/bootstrap-icons/bootstrap-icons.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('rsc/custom_css/style.css'); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- FullCalendar Styles -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper d-flex flex-column min-vh-100">
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-darker">
            <div class="container-fluid">
                <a class="navbar-brand ms-0 ms-md-2 p-0" href="<?= site_url('student/home') ?>">
                    <img src="<?= base_url('rsc/assets/cs-logo.png') ?>" alt="CS Logo" class="cs-logo me-2" style="width: 50px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse small-collapse-box" id="navbarNav">
                    <!-- Main navigation links - pushed to the right -->
                    <ul class="navbar-nav main-nav-links">
                        <li class="nav-item">
                            <a class="nav-link active px-3" aria-current="page" href="<?= site_url('student/home') ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="<?= site_url('student/curriculum') ?>">Curriculum</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="<?= site_url('student/grades/grades') ?>">Grades</a>
                        </li>
                    </ul>
                    <!-- Icons on the far right -->
                    <ul class="navbar-nav icon-nav-links me-3">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle profile-dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <!-- Placeholder for user profile image -->
                                <img src="<?= base_url('rsc/assets/uploads/' . esc(session('profimg') ?? 'default.png')) ?>"  alt="User Profile">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end nav-item p-0" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item nav-link p-3" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa-regular fa-circle-user me-2"></i>Profile</a></li>
                                <li><hr class="dropdown-divider p-0 m-0"></li>
                                <li><a class="dropdown-item nav-link p-3" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>      
    </header>
    <main class="mt-5 p-3 flex-grow-1">