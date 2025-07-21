<?php
  $current = service('uri')->getSegment(3); // admin/academics/{segment}
?>

<div class="sidebar">
    <div class="sidebar-inner pt-5"> 
        <div class="sidebar-header d-flex justify-content-between align-items-center px-3 pt-3">
            <a href="<?= site_url('admin/academics') ?>" class="text-decoration-none text-dark">
                <h5 class="sidebar-title mb-0">Academics</h5>
            </a>
            <!-- <h5 class="sidebar-title mb-0">Academics</h5> -->
            <button id="toggleSidebarBtn" title="Toggle sidebar">
                <i id="toggleIcon" class="bi bi-chevron-left"></i>
            </button>
        </div>
        <ul class="sidebar-nav px-3">
            <li>
                <a href="<?= site_url('admin/academics/semesters') ?>"
                    class="<?= $current == 'semesters' ? 'active' : '' ?> px-3" title="Semesters">
                    <i class="bi bi-calendar3"></i>
                    <span class="label">Semesters</span>
                </a>
            </li>
            <li>
                <a href="<?= site_url('admin/academics/subjects') ?>"
                    class="<?= $current == 'subjects' ? 'active' : '' ?> px-3" title="Subjects">
                    <i class="bi bi-journal-bookmark"></i>
                    <span class="label">Subjects</span>
                </a>
            </li>
            <li>
                <a href="<?= site_url('admin/academics/curriculums') ?>"
                    class="<?= $current == 'curriculums' ? 'active' : '' ?> px-3" title="Curriculum">
                    <i class="bi bi-list-columns-reverse"></i>
                    <span class="label">Curriculum</span>
                </a>
            </li>
            <li>
                <a href="<?= site_url('admin/academics/classes') ?>"
                    class="<?= $current == 'classes' ? 'active' : '' ?> px-3" title="Classes">
                    <i class="bi bi-person-lines-fill"></i>
                    <span class="label">Classes</span>
                </a>
            </li>
        </ul>
    </div>
</div>
