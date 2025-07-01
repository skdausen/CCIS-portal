<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - CCIS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to bottom right, #e0f7fa, #00bcd4); /* Gradient background from your image */
            min-height: 100vh; /* Ensure it takes full viewport height */
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background-color: #343a40; /* Dark background for navbar */
            border-radius: 0 0 10px 10px; /* Rounded bottom corners */
            width: 100%; /* Ensure it spans full width */
        }
        .navbar-brand {
            font-weight: bold;
            color: #fff !important;
        }
        .nav-link {
            color: #ccc !important;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .nav-link:hover {
            background-color: #495057;
            color: #fff !important;
        }
        .dropdown-menu {
            border-radius: 5px;
        }
        .main-content {
            flex-grow: 1; /* Allow content to grow and push footer down */
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center content horizontally */
            justify-content: flex-start; /* Align content to the top */
            text-align: center;
            width: 100%; /* Ensure it takes full width */
            max-width: 960px; /* A bit wider for better content flow */
            margin: 0 auto; /* Center the main content area itself */
            padding-top: 2rem; /* Add some padding from the top */
        }
        .search-container {
            display: flex;
            gap: 10px;
            margin-top: 1.5rem;
            justify-content: center;
            width: 100%;
            max-width: 600px; /* Limit search bar width */
            margin-bottom: 2rem; /* Space below search bar */
        }
        .search-container .form-select,
        .search-container .form-control {
            border-radius: 8px;
        }
        .search-container .form-select {
            max-width: 120px; /* Adjust width for the dropdown */
        }
        .search-container .form-control {
            flex-grow: 1;
        }
        .profile-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .profile-dropdown-toggle img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }
        .welcome-message {
            background-color: rgba(255, 255, 255, 0.9); /* Add a background for the welcome message */
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px; /* Limit width of the welcome message box */
            text-align: start; /* Align text within this box to start */
        }

        /* Custom styles for the navbar to ensure proper alignment and full width */
        .navbar-nav.main-nav-links {
            margin-left: auto; /* Push main nav links to the right */
            margin-right: 20px; /* Add some space before the icons */
        }
        .navbar-nav.icon-nav-links {
            margin-left: 0; /* Ensure icons are not pushed by auto-margins from other elements */
        }

        /* Adjustments for mobile responsiveness */
        @media (max-width: 991.98px) { /* Bootstrap's lg breakpoint for navbar collapse */
            .navbar-collapse {
                flex-direction: column; /* Stack items vertically when collapsed */
                align-items: flex-end; /* Align collapsed items to the right */
            }
            .navbar-nav.main-nav-links,
            .navbar-nav.icon-nav-links {
                width: 100%; /* Take full width when collapsed */
                justify-content: flex-end; /* Align content to the right */
                margin-left: 0;
                margin-right: 0;
            }
            .navbar-nav.main-nav-links .nav-item,
            .navbar-nav.icon-nav-links .nav-item {
                width: auto; /* Allow items to size naturally */
                text-align: right; /* Align text to the right within items */
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand ms-3" href="#">LOGO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Main navigation links - pushed to the right -->
                <ul class="navbar-nav main-nav-links">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Schedules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Grades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                </ul>
                <!-- Icons on the far right -->
                <ul class="navbar-nav icon-nav-links me-3">
                    <li class="nav-item me-3">
                        <a class="nav-link" href="#"><i class="fas fa-bell"></i></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle profile-dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Placeholder for user profile image -->
                            <img src="https://placehold.co/30x30/00bcd4/ffffff?text=U" alt="User Profile">
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= site_url('logout') ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <h2 class="mb-4 text-start w-100" style="max-width: 600px;">HOME</h2>
        <hr class="mb-4 w-100" style="max-width: 600px;">
        <div class="search-container">
            <select class="form-select" aria-label="Default select example">
                <option selected>All</option>
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
            </select>
            <input type="text" class="form-control" placeholder="Search" aria-label="Search">
        </div>

        <!-- Your provided home.php content now in a new container -->
        <div class="welcome-message">
            <h1>Welcome, <?= session('username') ?>!</h1>
            <p>You are logged in.</p>
            <a href="<?= site_url('logout') ?>" class="btn btn-primary rounded-pill">Logout</a>
        </div>
        <!-- End of your provided home.php content -->

    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
