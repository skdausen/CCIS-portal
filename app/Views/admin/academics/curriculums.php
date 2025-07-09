 <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-title">Academics</div>
            <ul class="sidebar-nav">
                <li><a href="<?=site_url('admin/academics/semesters')?>">Semesters</a></li>
                <li><a href="<?=site_url('admin/academics/courses')?>">Courses</a></li>
                <li><a href="<?=site_url('admin/academics/curriculums')?>">Curriculum</a></li>
                <li><a href="<?=site_url('admin/academics/classes')?>">Classes</a></li>
               
            </ul>
        </div>

 <!-- CURRICULUM CARDS -->
<!-- CURRICULUM CARDS -->
<div class="d-flex justify-content-center my-5">
    <div class="row g-4" style="max-width: 800px; width: 100%;">
        <div class="col-12 col-md-6">
            <div class="card text-white text-center p-4" style="background-color: #007bff; border-radius: 0; height: 250px;">
                <div class="d-flex flex-column justify-content-between h-100">
                    <div>
                        <h4 class="card-title mb-2">Old Curriculum</h4>
                        <p class="card-text">View the courses for the Old Curriculum.</p>
                    </div>
                    <a href="<?= site_url('admin/academics/curriculum_old') ?>" class="btn btn-light">View</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card text-white text-center p-4" style="background-color: #28a745; border-radius: 0; height: 250px;">
                <div class="d-flex flex-column justify-content-between h-100">
                    <div>
                        <h4 class="card-title mb-2">New Curriculum</h4>
                        <p class="card-text">View the courses for the New Curriculum.</p>
                    </div>
                    <a href="<?= site_url('admin/academics/curriculum_new') ?>" class="btn btn-light">View</a>
                </div>
            </div>
        </div>
    </div>
</div>

