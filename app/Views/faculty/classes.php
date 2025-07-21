
<div class="container mt-5">
    <h2 class="mb-3">My Classes</h2>
        <div class="row mb-3">
            <label for="semesterSelect">Semester:</label>
            <div class="col-md-4 mb-2">
                <select id="semesterSelect" class="form-select">
                    <?php foreach ($semesters as $sem): ?>
                        <option value="<?= $sem->semester_id ?>" <?= ($sem->semester_id == $activeSemesterId) ? 'selected' : '' ?>>
                            <?= $sem->semester ?> - <?= $sem->schoolyear ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 mb-2">
                    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search subject...">
            </div>
            <div class="d-flex justify-content-end">
                <div class="form-check form-switch">
                    <label class="form-check-label me-2" for="cardToggle">Show as Cards</label>
                    <input class="form-check-input toggle-green" type="checkbox" id="cardToggle">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div id="classesContainer"></div>
        </div>
            <nav>
                <ul id="pagination" class="pagination justify-content-center mt-3"></ul>
            </nav>
</div>



<script>
document.addEventListener("DOMContentLoaded", function() {
    const semesterSelect = document.getElementById('semesterSelect');
    const searchInput = document.getElementById('searchInput');
    const cardToggle = document.getElementById('cardToggle');
    const container = document.getElementById('classesContainer');
    const pagination = document.getElementById('pagination');

    let currentPage = 1;
    const itemsPerPage = 9;
    let allClasses = [];

    function fetchClasses() {
        const semesterId = semesterSelect.value;
        fetch(`<?= base_url('faculty/classes/ajax') ?>?semester_id=${semesterId}`)
            .then(res => res.json())
            .then(data => {
                allClasses = data;
                currentPage = 1;
                renderClasses();
            });
    }

    function paginate(items, page, perPage) {
        const start = (page - 1) * perPage;
        return items.slice(start, start + perPage);
    }

    function renderPagination(totalItems) {
        const pageCount = Math.ceil(totalItems / itemsPerPage);
        let html = '';

        if (pageCount <= 1) {
            pagination.innerHTML = '';
            return;
        }

        html += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
            </li>`;

        for (let i = 1; i <= pageCount; i++) {
            html += `
                <li class="page-item ${currentPage === i ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
        }

        html += `
            <li class="page-item ${currentPage === pageCount ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
            </li>`;

        pagination.innerHTML = html;

        document.querySelectorAll('#pagination .page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.dataset.page);
                if (!isNaN(page)) {
                    currentPage = page;
                    renderClasses();
                }
            });
        });
    }

    function renderClasses() {
        const search = searchInput.value.toLowerCase();
        const useCards = cardToggle.checked;

        const filtered = allClasses.filter(c => 
            c.subject_name.toLowerCase().includes(search) ||
            c.subject_code.toLowerCase().includes(search) ||
            c.subject_type.toLowerCase().includes(search) ||
            c.section.toLowerCase().includes(search)
        );

        const paginated = paginate(filtered, currentPage, itemsPerPage);
        renderPagination(filtered.length);

        if (filtered.length === 0) {
            container.innerHTML = "<div class='alert alert-warning'>No classes found.</div>";
            return;
        }

        if (useCards) {
            let html = '<div class="row">';
            paginated.forEach(c => {
                html += `
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow">
                        <div class="card-body">
                            <h5 class="card-title">${c.subject_code} - ${c.subject_name}</h5>
                            <p class="card-text">
                                <strong>Type:</strong> ${c.subject_type}<br>
                                <strong>Section:</strong> ${(c.section ?? 'N/A').toUpperCase()}
                            </p>
                            <p>
                                <strong>Lecture:</strong> ${c.lec_day ?? 'N/A'}, ${formatTime(c.lec_start) ?? ''} - ${formatTime(c.lec_end) ?? ''}<br>
                                Room: ${(c.lec_room).toUpperCase() ?? ''}
                            </p>
                            ${c.subject_type === 'LEC with LAB' ? `
                                <p>
                                    <strong>Lab:</strong> ${c.lab_day ?? 'N/A'}, ${formatTime(c.lab_start) ?? ''} - ${formatTime(c.lab_end) ?? ''}<br>
                                    Room: ${c.lab_room ?? ''}
                                </p>` : ''}
                        </div>
                        <div class="card-footer text-end">
                            <a href="<?= base_url('faculty/class/') ?>${c.class_id}" class="btn btn-sm btn-outline-primary">Manage Class</a>
                        </div>
                    </div>
                </div>`;
            });
            html += '</div>';
            container.innerHTML = html;

        } else {
            let html = `
            <table class="table table-bordered custom-padding">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Type</th>
                        <th>Section</th>
                        <th>Lecture</th>
                        <th>Lab</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>`;
            paginated.forEach(c => {
                const fullTitle = `${c.subject_name}`;
                const shortTitle = fullTitle.length > 52 ? fullTitle.substring(0, 52) + '...' : fullTitle;

                html += `<tr>
                    <td>${c.subject_code}</td>
                    <td title="${fullTitle}">${shortTitle}</td>
                    <td>${c.subject_type}</td>
                    <td>${(c.section ?? 'N/A').toUpperCase()}</td>
                    <td>${c.lec_day ?? ''}, ${formatTime(c.lec_start) ?? ''} - ${formatTime(c.lec_end) ?? ''}<br>Room: ${c.lec_room.toUpperCase() ?? ''}</td>
                    <td>${c.subject_type === 'LEC with LAB' ? `${c.lab_day ?? ''}, ${formatTime(c.lab_start) ?? ''} - ${formatTime(c.lab_end) ?? ''}<br>Room: ${c.lab_room.toUpperCase() ?? ''}` : 'N/A'}</td>
                    <td>
                        <a href="<?= base_url('faculty/class/') ?>${c.class_id}" class="btn btn-sm btn-outline-primary">Manage Class</a>
                    </td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        }
    }

    semesterSelect.addEventListener('change', fetchClasses);
    searchInput.addEventListener('input', () => {
        currentPage = 1;
        renderClasses();
    });
    cardToggle.addEventListener('change', renderClasses);

    fetchClasses();
});
function formatTime(timeStr) {
    if (!timeStr) return '';
    const [hour, minute] = timeStr.split(':');
    const h = parseInt(hour, 10);
    const ampm = h >= 12 ? 'PM' : 'AM';
    const hour12 = h % 12 || 12;
    return `${hour12}:${minute} ${ampm}`;
}
</script>

