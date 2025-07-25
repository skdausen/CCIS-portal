<!-- Main Container -->
<div class="main-container">
        
<div class="container mt-5">
    <h2 class="mb-4">Academics Summary</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h5><i class="bi bi-calendar3 me-2 text-purple"></i> Semesters</h5>
                <div class="fs-1"><?= $semestersCount ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h5><i class="bi bi-journal-bookmark me-2 text-purple"></i> Subjects</h5>
                <div class="fs-1"><?= $subjectsCount ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h5><i class="bi bi-list-columns-reverse me-2 text-purple"></i> Classes</h5>
                <div class="fs-1"><?= $classesCount ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h5><i class="bi bi-person-lines-fill me-2 text-purple"></i> Faculty</h5>
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
                labels: [ 'Semesters', 'Subjects', 'Classes', 'Faculty'],
                datasets: [{
                    label: 'Total Count',
                    data: [<?= $semestersCount ?>, <?= $subjectsCount ?>, <?= $classesCount ?>, <?= $facultyCount ?>],
                    backgroundColor: ['#198754', '#ffc107', '#dc3545', '#6f42c1'],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        ticks: { color: 'white' },
                        grid: { color: '#E0E0E0' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: 'white' },
                        grid: { color: '#E0E0E0' }
                    }
                },
                plugins: {
                    legend: {
                        labels: { color: 'white' }
                    }
                }
            }
        });
    </script>



    <!-- Recently Added Subjects -->
    <div class="mt-5">
        <h4>Recently Added Subjects</h4>
        <ul class="list-group">
            <?php if (!empty($recentSubjects)): ?>
                <?php foreach ($recentSubjects as $subjects): ?>
                    <li class="list-group-item">
                        <strong><?= esc($subjects['subject_code']) ?></strong>: <?= esc($subjects['subject_name']) ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">No recent courses found.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>
