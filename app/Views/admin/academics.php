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
        
<div class="container mt-5">
    <h2 class="mb-4">Academics Summary</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h5>📅 School Years</h5>
                <div class="fs-1"><?= $schoolYearsCount ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h5>🗓️ Semesters</h5>
                <div class="fs-1"><?= $semestersCount ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h5>📚 Courses</h5>
                <div class="fs-1"><?= $coursesCount ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h5>🏫 Classes</h5>
                <div class="fs-1"><?= $classesCount ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h5>👨‍🏫 Faculty</h5>
                <div class="fs-1"><?= $facultyCount ?></div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="mt-5">
        <h4>Courses vs Classes</h4>
        <canvas id="summaryChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('summaryChart').getContext('2d');
        const summaryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['School Years', 'Semesters', 'Courses', 'Classes', 'Faculty'],
                datasets: [{
                    label: 'Total Count',
                    data: [<?= $schoolYearsCount ?>, <?= $semestersCount ?>, <?= $coursesCount ?>, <?= $classesCount ?>, <?= $facultyCount ?>],
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1'],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>

    <!-- Recently Added Courses -->
    <div class="mt-5">
        <h4>Recently Added Courses</h4>
        <ul class="list-group">
            <?php if (!empty($recentCourses)): ?>
                <?php foreach ($recentCourses as $course): ?>
                    <li class="list-group-item">
                        <strong><?= esc($course['course_code']) ?></strong>: <?= esc($course['course_description']) ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">No recent courses found.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>
