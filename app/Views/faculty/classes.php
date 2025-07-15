<h2>My Classes</h2>

<div class="mb-3">
    <label for="semesterSelect">Semester:</label>
    <select id="semesterSelect" class="form-select">
        <?php foreach ($semesters as $sem): ?>
            <option value="<?= $sem->semester_id ?>" <?= ($sem->semester_id == $activeSemesterId) ? 'selected' : '' ?>>
                <?= $sem->semester ?> - <?= $sem->schoolyear ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<input type="text" id="searchInput" class="form-control mb-3" placeholder="Search subject...">

<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" id="cardToggle">
    <label class="form-check-label" for="cardToggle">Show as Cards</label>
</div>

<div id="classesContainer" class="table-responsive"></div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const semesterSelect = document.getElementById('semesterSelect');
    const searchInput = document.getElementById('searchInput');
    const cardToggle = document.getElementById('cardToggle');
    const container = document.getElementById('classesContainer');

    function fetchClasses() {
        const semesterId = semesterSelect.value;
        fetch(`<?= base_url('faculty/classes/ajax') ?>?semester_id=${semesterId}`)
            .then(res => res.json())
            .then(data => renderClasses(data));
    }

    function renderClasses(classes) {
        const search = searchInput.value.toLowerCase();
        const useCards = cardToggle.checked;
        const filtered = classes.filter(c => 
            c.subject_name.toLowerCase().includes(search) ||
            c.subject_code.toLowerCase().includes(search) ||
            c.subject_type.toLowerCase().includes(search) ||
            c.section.toLowerCase().includes(search)
        );

        if (filtered.length === 0) {
            container.innerHTML = "<div class='alert alert-warning'>No classes found.</div>";
            return;
        }

        if (useCards) {
            let html = '<div class="row">';
            filtered.forEach(c => {
                html += `
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow">
                        <div class="card-body">
                            <h5 class="card-title">${c.subject_code} - ${c.subject_name}</h5>
                            <p class="card-text">
                                <strong>Type:</strong> ${c.subject_type}<br>
                                <strong>Section:</strong> ${c.section ?? 'N/A'}
                            </p>
                            <p>
                                <strong>Lecture:</strong> ${c.lec_day ?? 'N/A'}, ${c.lec_start ?? ''} - ${c.lec_end ?? ''}<br>
                                Room: ${c.lec_room ?? ''}
                            </p>
                            ${c.subject_type === 'LEC with LAB' ? `
                                <p>
                                    <strong>Lab:</strong> ${c.lab_day ?? 'N/A'}, ${c.lab_start ?? ''} - ${c.lab_end ?? ''}<br>
                                    Room: ${c.lab_room ?? ''}
                                </p>
                            ` : ''}
                            <a href="<?= base_url('faculty/class/') ?>${c.class_id}" class="btn btn-sm btn-primary">View</a>
                        </div>
                    </div>
                </div>`;
            });
            html += '</div>';
            container.innerHTML = html;
        } else {
            let html = `
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Section</th>
                        <th>Lecture</th>
                        <th>Lab</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>`;
            filtered.forEach(c => {
                html += `<tr>
                    <td>${c.subject_code}</td>
                    <td>${c.subject_name}</td>
                    <td>${c.subject_type}</td>
                    <td>${c.section ?? 'N/A'}</td>
                    <td>${c.lec_day ?? ''}, ${c.lec_start ?? ''} - ${c.lec_end ?? ''}<br>Room: ${c.lec_room ?? ''}</td>
                    <td>${c.subject_type === 'LEC with LAB' ? `${c.lab_day ?? ''}, ${c.lab_start ?? ''} - ${c.lab_end ?? ''}<br>Room: ${c.lab_room ?? ''}` : 'N/A'}</td>
                    <td><a href="<?= base_url('faculty/class/') ?>${c.class_id}" class="btn btn-sm btn-primary">View</a></td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        }
    }

    semesterSelect.addEventListener('change', fetchClasses);
    searchInput.addEventListener('input', fetchClasses);
    cardToggle.addEventListener('change', fetchClasses);

    fetchClasses();
});
</script>