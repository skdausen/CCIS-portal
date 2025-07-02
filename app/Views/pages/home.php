



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

        <div class="welcome-message">
            <h1>Welcome, <?= session('username') ?>!</h1>
            <p>You are logged in.</p>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
        </div>

    </div>


