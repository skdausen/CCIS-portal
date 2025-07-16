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
            <h3>Classes Management</h3>
        </div>

        <!-- FILTERS & SEARCH -->
        <div class="row mb-3">
            <div class="col-md-3 mb-2 d-flex">
                <!-- Instructor Filter -->
                <select id="instructorFilter" class="form-select">
                    <option value="">Filter by Instructor</option>
                    <?php foreach ($instructors as $facultyId => $instructorName): ?>
                        <option value="<?= strtolower(esc($instructorName)) ?>">
                            <?= esc($instructorName) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="clearFilterBtn" class="btn btn-outline-secondarybtn-thin rounded-1 px-3 py-2 ms-2">Clear</button>
            </div>

            <div class="col-md-3 mb-2">
                <!-- Semester Filter -->
                <select id="semesterFilter" class="form-select">
                    <option value="">Filter by Semester</option>
                    <?php foreach ($semesters as $semester): ?>
                        <option value="<?= esc($semester['semester_id']) ?>"
                            <?= isset($_GET['semester_id']) && $_GET['semester_id'] == $semester['semester_id'] ? 'selected' : '' ?>>
                            <?= ucwords(esc($semester['semester'])) ?> - <?= esc($semester['schoolyear']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4 mb-2">
                <!-- Search Input -->
                <input type="text" id="searchInput" class="form-control" placeholder="Search by course or room...">
            </div>

            <div class="col-md-2 mb-2 d-flex justify-content-end">
                <!-- Add Class Button -->
                <button class="btn btn-outline-success"
                    <?= empty($activeSemester) ? 'onclick="showNoSemesterModal()"' : 'data-bs-toggle="modal" data-bs-target="#addModal"' ?>>
                    Add New Class
                </button>
            </div>
        </div>


        <?php
        $groupedClasses = [];

        foreach ($classes as $class) {
            $key = $class['subject_code'] . '_' . $class['section'] . '_' . $class['semester_id'];

            if (!isset($groupedClasses[$key])) {
                $groupedClasses[$key] = [
                    'course' => $class['subject_code'],
                    'type' => $class['subject_type'],
                    'days' => [],
                    'times' => [],
                    'rooms' => [],
                    'section' => $class['section'],
                    'instructor' => $class['fname'] . ' ' . $class['lname'],
                    'semester' => $class['semester'] . ' ' . $class['schoolyear'],
                ];
            }

    // Lecture
    if (!empty($class['lec_day'])) {
        $groupedClasses[$key]['days'][] = 'Lec: ' . $class['lec_day'];
        $groupedClasses[$key]['times'][] = 'Lec: ' . date('g:iA', strtotime($class['lec_start'])) . '-' . date('g:iA', strtotime($class['lec_end']));
        $groupedClasses[$key]['rooms'][] = 'Lec: ' . $class['lec_room'];
    }

    // Laboratory (optional)
    if (!empty($class['lab_day'])) {
        $groupedClasses[$key]['days'][] = 'Lab: ' . $class['lab_day'];
        $groupedClasses[$key]['times'][] = 'Lab: ' . date('g:iA', strtotime($class['lab_start'])) . '-' . date('g:iA', strtotime($class['lab_end']));
        $groupedClasses[$key]['rooms'][] = 'Lab: ' . $class['lab_room'];
    }
}

?>

<!-- CLASSES TABLE -->
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Subject</th>
                <th>Type</th>
                <th>Day, Time, Room</th>
                <th>Section</th>
                <th>Instructor</th>
                <th>Semester</th>
                <?php if (!empty($activeSemester) && (!isset($_GET['semester_id']) || $_GET['semester_id'] == $activeSemester['semester_id'])): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($classes as $class): ?>
            <tr>
                 <td><?= esc($class['subject_code']) ?> - <?= esc($class['subject_name']) ?></td>
                        <td><?= esc($class['subject_type']) ?></td>

                        <td>
                            <?= !empty($class['lec_day']) ? 'Lec: ' . esc(strtoupper($class['lec_day'])) : '' ?>
                            <?php if (!empty($class['lec_start']) && !empty($class['lec_end'])): ?>
                                <?= date("g:i A", strtotime($class['lec_start'])) ?> - <?= date("g:i A", strtotime($class['lec_end'])) ?>
                            <?= !empty($class['lec_room']) ? '' . esc(strtoupper($class['lec_room'])) : '' ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                            <?php if (!empty($class['lab_day'])): ?>
                                <br>Lab: <?= esc(strtoupper($class['lab_day'])) ?>
                                <?php if (!empty($class['lab_start']) && !empty($class['lab_end'])): ?>
                                <?= date("g:i A", strtotime($class['lab_start'])) ?> - <?= date("g:i A", strtotime($class['lab_end'])) ?>
                                <?php endif; ?>
                                <?php if (!empty($class['lab_room'])): ?>
                                <?= esc(strtoupper($class['lab_room'])) ?>
                            <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td><?= esc(strtoupper($class['section'] ?? 'N/A')) ?></td>
                        <td><?= ucwords(esc($class['fname'] . ' ' . $class['lname'])) ?></td>
                        <td><?= ucwords(esc($class['semester']) . ' ' . $class['schoolyear']) ?></td>
                        <?php if (!empty($activeSemester) && (!isset($_GET['semester_id']) || $_GET['semester_id'] == $activeSemester['semester_id'])): ?>
                        <td>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $class['class_id'] ?>">Edit</button>
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $class['class_id'] ?>">Delete</button>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ✅ OUTSIDE THE TABLE: Edit and Delete Modals -->
<?php foreach ($classes as $class): ?>
<!-- Edit Modal -->
<div class="modal fade" id="editModal<?= $class['class_id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-lg-dialog-centered justify-content-center">
        <form method="post" action="<?= site_url('admin/academics/classes/update/' . $class['class_id']) ?>">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="semester_id" value="<?= esc($activeSemester['semester_id'] ?? '') ?>">

                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <div class="form-control bg-light">
                            <?= esc(($activeSemester['semester'] ?? 'No Active Semester') . ' - ' . ($activeSemester['schoolyear'] ?? '')) ?>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Instructor</label>
                            <select name="ftb_id" class="form-select" required>
                                <option value="">Select Instructor</option>
                                <?php foreach ($instructors as $ftbId => $instructorName): ?>
                                    <option value="<?= $ftbId ?>" <?= $ftbId == $class['ftb_id'] ? 'selected' : '' ?>>
                                        <?= esc($instructorName) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Section</label>
                            <input type="text" name="section" class="form-control" value="<?= esc($class['section']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label class="form-label">Subject</label>
                       <input type="text" class="form-control edit-subject-search mb-2"
                            data-id="<?= $class['class_id'] ?>"
                            placeholder="Search Subject..." 
                            value="<?= esc($class['subject_code'] . ' - ' . $class['subject_name']) ?>" required>

                        <input type="hidden" name="subject_id" class="edit-subject-id"
                            data-id="<?= $class['class_id'] ?>"
                            value="<?= esc($class['subject_id']) ?>" required>

                        <input type="text" name="subject_type"
                            class="form-control edit-subject-type"
                            data-id="<?= $class['class_id'] ?>" readonly
                            value="<?= esc($class['subject_type']) ?>" required>

                        <ul class="list-group position-absolute w-100 shadow edit-suggestions"
                            data-id="<?= $class['class_id'] ?>"
                            style="top: 100%; z-index: 1050; max-height: 200px; overflow-y: auto;"></ul>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Lecture Schedule</h6>
                            <input type="text" name="lec_day" value="<?= esc($class['lec_day']) ?>" class="form-control mb-2" placeholder="Day/s" required>
                            <input type="text" name="lec_room" value="<?= esc($class['lec_room']) ?>" class="form-control mb-2" placeholder="Room" required>
                            <input type="time" name="lec_start" value="<?= esc($class['lec_start']) ?>" class="form-control mb-2" required>
                            <input type="time" name="lec_end" value="<?= esc($class['lec_end']) ?>" class="form-control mb-2" required>
                        </div>

                        <div class="col-md-6 edit-lab-schedule <?= $class['subject_type'] == 'LEC with LAB' ? '' : 'd-none' ?>" data-id="<?= $class['class_id'] ?>">
                            <h6>Lab Schedule</h6>
                           <input type="text" name="lab_day" value="<?= esc($class['lab_day']) ?>" class="form-control mb-2" placeholder="Lab Day/s" <?= $class['subject_type'] == 'LEC with LAB' ? 'required' : '' ?>>
                            <input type="text" name="lab_room" value="<?= esc($class['lab_room']) ?>" class="form-control mb-2" placeholder="Lab Room" <?= $class['subject_type'] == 'LEC with LAB' ? 'required' : '' ?>>
                            <input type="time" name="lab_start" value="<?= esc($class['lab_start']) ?>" class="form-control mb-2" <?= $class['subject_type'] == 'LEC with LAB' ? 'required' : '' ?>>
                            <input type="time" name="lab_end" value="<?= esc($class['lab_end']) ?>" class="form-control mb-2" <?= $class['subject_type'] == 'LEC with LAB' ? 'required' : '' ?>>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-success">Update</button>
                    <button type="button" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="deleteModal<?= $class['class_id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="<?= site_url('admin/academics/classes/delete/' . $class['class_id']) ?>">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong><?= esc($class['subject_name']) ?></strong>?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                    <button type="button" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>


<!-- ✅ ADD CLASS MODAL (Remains after everything) -->
<!-- Add Class Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-lg-dialog-centered justify-content-center">
            <form action="<?= site_url('admin/academics/classes/add') ?>" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add New Class</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Semester (Auto-filled) -->
                        <input type="hidden" name="semester_id" value="<?= esc($activeSemester['semester_id'] ?? '') ?>">

                        <div class="mb-3">
                            <label class="form-label">Semester</label>
                            <div class="form-control bg-light">
                                <?= ucwords(esc(($activeSemester['semester'] ?? 'No Active Semester')) . ' - ' . ($activeSemester['schoolyear'] ?? '')) ?>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <!-- Instructor -->
                                <label for="instructor" class="form-label">Instructor</label>
                                <select name="ftb_id" class="form-select" required>
                                    <option value="">Select Instructor</option>
                                    <?php foreach ($instructors as $ftbId => $instructorName): ?>
                                        <option value="<?= $ftbId ?>">
                                            <?= esc($instructorName) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Section -->
                            <div class="col-md-6">
                                <label for="section" class="form-label">Section</label>
                                <input type="text" name="section" id="section" class="form-control" placeholder="Section e.g., A, B, C" required>
                            </div>
                        </div>
                        
                         <!-- Subject -->
                       <div class="mb-3 position-relative">
                            <label for="subjectSearchInput" class="form-label">Subject</label>
                            <input type="text" id="subjectSearchInput" class="form-control" placeholder="Search Subject Code or Name..." autocomplete="off" required>
                            <input type="hidden" name="subject_id" id="subjectIdInput">

                            <ul id="subjectSuggestions" class="list-group position-absolute w-100 shadow" style="top: 100%; z-index: 1050; max-height: 200px; overflow-y: auto;"></ul>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <input type="text" id="subjectTypeInput" name="subject_type" class="form-control" readonly placeholder="Subject Type">
                        </div>

                        <ul id="subjectSuggestions" class="list-group position-absolute" style="z-index: 1050;"></ul>


                        <!-- Schedule -->
                     <div class="row">
                        <!-- Lecture Schedule -->
                        <div id="lectureSchedule" class="col-md-6">
                            <h6>Lecture Schedule</h6>
                            <div class="mb-3">
                                <label for="lecDay" class="form-label">Day/s</label>
                                <input type="text" id="lecDay" name="lec_day" class="form-control" placeholder="e.g., M,T,W,Th,F" required>
                            </div>
                            <div class="mb-3">
                                <label for="lecRoom" class="form-label">Room</label>
                                <input type="text" id="lecRoom" name="lec_room" class="form-control" placeholder="e.g., Room 101" required>
                            </div>
                            <div class="mb-3">
                                <label for="lecStart" class="form-label">Start Time</label>
                                <input type="time" id="lecStart" name="lec_start" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="lecEnd" class="form-label">End Time</label>
                                <input type="time" id="lecEnd" name="lec_end" class="form-control" required>
                            </div>
                        </div>

                        <!-- Lab Schedule -->
                        <div id="labSchedule" class="col-md-6 d-none">
                            <h6>Lab Schedule</h6>
                            <div class="mb-3">
                                <label for="labDay" class="form-label">Lab Day/s</label>
                                <input type="text" id="labDay" name="lab_day" class="form-control" placeholder="e.g., M,T,W,Th,F">
                            </div>
                            <div class="mb-3">
                                <label for="labRoom" class="form-label">Lab Room</label>
                                <input type="text" id="labRoom" name="lab_room" class="form-control" placeholder="e.g., Room 101">
                            </div>
                            <div class="mb-3">
                                <label for="labStart" class="form-label">Lab Start Time</label>
                                <input type="time" id="labStart" name="lab_start" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="labEnd" class="form-label">Lab End Time</label>
                                <input type="time" id="labEnd" name="lab_end" class="form-control">
                            </div>
                        </div>
                    </div>


                        
                    </div> <!-- /.modal-body -->

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-success">Add New Class</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>







<script src="<?= base_url('rsc/custom_js/classes.js') ?>"></script>

<!-- No Active Semester Modal -->
<div class="modal fade" id="noSemesterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Warning</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                No active semester found. Please activate a semester first before adding a class.
            </div>
        </div>
    </div>
</div>





<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="successMessage"></p>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="errorMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" onclick="location.reload()">Retry</button>
                <button type="button" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
    const subjects = <?= json_encode(array_map(function($subject) {
        return [
            'id' => $subject['subject_id'],
            'code' => $subject['subject_code'],
            'name' => $subject['subject_name'],
            'type' => $subject['subject_type']
        ];
    }, $courses)); ?>;
</script>


<script>
    // Data for subject search
    const subjects = <?= json_encode(array_map(function ($subject) {
        return [
            'id' => $subject['subject_id'],
            'code' => $subject['subject_code'],
            'name' => $subject['subject_name'],
            'type' => $subject['subject_type']
        ];
    }, $courses)); ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // ----- ADD CLASS SUBJECT SEARCH -----
    const searchInput = document.getElementById('subjectSearchInput');
    const subjectIdInput = document.getElementById('subjectIdInput');
    const subjectTypeInput = document.getElementById('subjectTypeInput');
    const suggestionsList = document.getElementById('subjectSuggestions');
    const labScheduleSection = document.getElementById('labSchedule');
    const labFields = ['labDay', 'labRoom', 'labStart', 'labEnd'].map(id => document.getElementById(id));

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.toLowerCase();
        const filteredSubjects = subjects.filter(sub =>
            sub.code.toLowerCase().includes(query) || sub.name.toLowerCase().includes(query)
        );

        suggestionsList.innerHTML = '';
        filteredSubjects.forEach(sub => {
            const li = document.createElement('li');
            li.classList.add('list-group-item', 'list-group-item-action');
            li.textContent = `${sub.code} - ${sub.name}`;
            li.dataset.id = sub.id;
            li.dataset.type = sub.type;
            li.addEventListener('click', () => {
                searchInput.value = `${sub.code} - ${sub.name}`;
                subjectIdInput.value = sub.id;
                subjectTypeInput.value = sub.type;
                suggestionsList.innerHTML = '';

                if (sub.type === 'LEC with LAB') {
                    labScheduleSection.classList.remove('d-none');
                    labFields.forEach(field => field.setAttribute('required', 'required'));
                } else {
                    labScheduleSection.classList.add('d-none');
                    labFields.forEach(field => {
                        field.removeAttribute('required');
                        field.value = '';
                    });
                }
            });
            suggestionsList.appendChild(li);
        });
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !suggestionsList.contains(e.target)) {
            suggestionsList.innerHTML = '';
        }
    });


    // ----- EDIT CLASS SUBJECT SEARCH (MULTIPLE MODALS) -----
    document.querySelectorAll('.edit-subject-search').forEach(input => {
        const classId = input.dataset.id;
        const hiddenInput = document.querySelector(`.edit-subject-id[data-id="${classId}"]`);
        const typeInput = document.querySelector(`.edit-subject-type[data-id="${classId}"]`);
        const suggestionList = document.querySelector(`.edit-suggestions[data-id="${classId}"]`);
        const labSection = document.querySelector(`.edit-lab-schedule[data-id="${classId}"]`);

        input.addEventListener('input', function () {
            const query = this.value.toLowerCase();
            const filteredSubjects = subjects.filter(sub =>
                sub.code.toLowerCase().includes(query) || sub.name.toLowerCase().includes(query)
            );

            suggestionList.innerHTML = '';
            filteredSubjects.forEach(sub => {
                const li = document.createElement('li');
                li.classList.add('list-group-item', 'list-group-item-action');
                li.textContent = `${sub.code} - ${sub.name}`;
                li.dataset.id = sub.id;
                li.dataset.type = sub.type;
                li.addEventListener('click', () => {
                    input.value = `${sub.code} - ${sub.name}`;
                    hiddenInput.value = sub.id;
                    typeInput.value = sub.type;
                    suggestionList.innerHTML = '';

                    if (sub.type === 'LEC with LAB') {
                        labSection.classList.remove('d-none');
                    } else {
                        labSection.classList.add('d-none');
                        labSection.querySelectorAll('input').forEach(input => input.value = '');
                    }
                });
                suggestionList.appendChild(li);
            });
        });

        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !suggestionList.contains(e.target)) {
                suggestionList.innerHTML = '';
            }
        });

        if (typeInput.value === 'LEC with LAB') {
            labSection.classList.remove('d-none');
        }
    });


    // ----- TIME INPUT FOCUS (Optional UX Tweak) -----
    const lecEnd = document.getElementById('lecEnd');
    const lecStart = document.getElementById('lecStart');
    lecEnd?.addEventListener('focus', () => { if (!lecStart.value) lecStart.focus(); });

    const labEnd = document.getElementById('labEnd');
    const labStart = document.getElementById('labStart');
    labEnd?.addEventListener('focus', () => { if (!labStart.value) labStart.focus(); });

    document.querySelectorAll('.edit-lab-schedule').forEach(section => {
        const lecStart = section.querySelector('input[name="lec_start"]');
        const lecEnd = section.querySelector('input[name="lec_end"]');
        const labStart = section.querySelector('input[name="lab_start"]');
        const labEnd = section.querySelector('input[name="lab_end"]');

        lecEnd?.addEventListener('focus', () => { if (!lecStart.value) lecStart.focus(); });
        labEnd?.addEventListener('focus', () => { if (!labStart.value) labStart.focus(); });
    });

});
</script>




<script>
    document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('subjectSearchInput');
    const subjectIdInput = document.getElementById('subjectIdInput');
    const subjectTypeInput = document.getElementById('subjectTypeInput');
    const suggestionsList = document.getElementById('subjectSuggestions');
    const labScheduleSection = document.getElementById('labSchedule');
    const labFields = ['labDay', 'labRoom', 'labStart', 'labEnd'].map(id => document.getElementById(id));

    function showSuggestions(filteredSubjects) {
        suggestionsList.innerHTML = '';
        filteredSubjects.forEach(sub => {
            const li = document.createElement('li');
            li.classList.add('list-group-item', 'list-group-item-action');
            li.textContent = `${sub.code} - ${sub.name}`;
            li.dataset.id = sub.id;
            li.dataset.type = sub.type;
            li.addEventListener('click', () => {
                searchInput.value = `${sub.code} - ${sub.name}`;
                subjectIdInput.value = sub.id;
                subjectTypeInput.value = sub.type;
                suggestionsList.innerHTML = '';

                if (sub.type === 'LEC with LAB') {
                    labScheduleSection.classList.remove('d-none');
                    labFields.forEach(field => field.setAttribute('required', 'required'));
                } else {
                    labScheduleSection.classList.add('d-none');
                    labFields.forEach(field => {
                        field.removeAttribute('required');
                        field.value = '';
                    });
                }
            });
            suggestionsList.appendChild(li);
        });
    }

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.toLowerCase();
        const filteredSubjects = subjects.filter(sub =>
            sub.code.toLowerCase().includes(query) ||
            sub.name.toLowerCase().includes(query)
        );
        if (query.length === 0) {
            suggestionsList.innerHTML = '';
        } else {
            showSuggestions(filteredSubjects);
        }
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !suggestionsList.contains(e.target)) {
            suggestionsList.innerHTML = '';
        }
    });
});
</script>


<script>

document.querySelectorAll('.edit-subject-search').forEach(input => {
    const classId = input.dataset.id;
    const hiddenInput = document.querySelector(`.edit-subject-id[data-id="${classId}"]`);
    const typeInput = document.querySelector(`.edit-subject-type[data-id="${classId}"]`);
    const suggestionList = document.querySelector(`.edit-suggestions[data-id="${classId}"]`);
    const labSection = document.querySelector(`.edit-lab-schedule[data-id="${classId}"]`);

    input.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const filteredSubjects = subjects.filter(sub =>
            sub.code.toLowerCase().includes(query) || sub.name.toLowerCase().includes(query)
        );

        suggestionList.innerHTML = '';
        filteredSubjects.forEach(sub => {
            const li = document.createElement('li');
            li.classList.add('list-group-item', 'list-group-item-action');
            li.textContent = `${sub.code} - ${sub.name}`;
            li.dataset.id = sub.id;
            li.dataset.type = sub.type;
            li.addEventListener('click', () => {
                input.value = `${sub.code} - ${sub.name}`;
                hiddenInput.value = sub.id;
                typeInput.value = sub.type;
                suggestionList.innerHTML = '';

                if (sub.type === 'LEC with LAB') {
                    labSection.classList.remove('d-none');
                } else {
                    labSection.classList.add('d-none');
                    labSection.querySelectorAll('input').forEach(input => input.value = '');
                }
            });
            suggestionList.appendChild(li);
        });
    });

    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !suggestionList.contains(e.target)) {
            suggestionList.innerHTML = '';
        }
    });

    if (typeInput.value === 'LEC with LAB') {
        labSection.classList.remove('d-none');
    }
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Lecture Time
    const lecStart = document.getElementById('lecStart');
    const lecEnd = document.getElementById('lecEnd');

    lecEnd.addEventListener('focus', function () {
        if (!lecStart.value) {
            lecStart.focus();
        }
    });

    // Lab Time
    const labStart = document.getElementById('labStart');
    const labEnd = document.getElementById('labEnd');

    labEnd.addEventListener('focus', function () {
        if (!labStart.value) {
            labStart.focus();
        }
    });
});

</script>


