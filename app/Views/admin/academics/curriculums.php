 <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-title">Academics</div>
            <ul class="sidebar-nav">
                <li><a href="<?=site_url('admin/academics/semesters')?>">Semesters</a></li>
                <li><a href="<?=site_url('admin/academics/subjects')?>">Subjects</a></li>
                <li><a href="<?=site_url('admin/academics/curriculums')?>">Curriculum</a></li>
                <li><a href="<?=site_url('admin/academics/classes')?>">Classes</a></li>
            </ul>
        </div>
<div class="container mt-5">
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Curriculum Courses</h3>
        </div>
 
<!-- CURRICULUM CARDS -->
<div class="d-flex justify-content-center my-5">
    <div class="row g-4" style="max-width: 1000px; width: 100%;">
        <div class="col-12 col-md-6">
            <div class="card text-white text-center p-5" style="background-color: #007bff; border-radius: .5rem; height: 450px;">
                <div class="d-flex flex-column justify-content-between h-100">
                    <div>
                        <h3 class="card-title mb-4">Old Curriculum</h3>
                        <p class="card-text fs-5">View the courses for the Old Curriculum.</p>
                    </div>
                    <a href="<?= site_url('admin/academics/curriculum_old') ?>" class="btn btn-light btn-lg">View</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card text-white text-center p-5" style="background-color: #28a745; border-radius: .5rem; height: 450px;">
                <div class="d-flex flex-column justify-content-between h-100">
                    <div>
                        <h3 class="card-title mb-4">New Curriculum</h3>
                        <p class="card-text fs-5">View the courses for the New Curriculum.</p>
                    </div>
                    <a href="<?= site_url('admin/academics/curriculum_new') ?>" class="btn btn-light btn-lg">View</a>
                </div>
            </div>
        </div>
    </div>
</div>
