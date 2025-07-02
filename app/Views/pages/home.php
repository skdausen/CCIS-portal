

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand ms-3" href="#">CCIS</a>
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
            <a href="<?= site_url('admin/register-user') ?>" class="btn btn-primary rounded-pill">Add New User</a>
            <a href="<?= site_url('logout') ?>" class="btn btn-primary rounded-pill">Logout</a>
        </div>
        <!-- End of your provided home.php content -->

    </div>


