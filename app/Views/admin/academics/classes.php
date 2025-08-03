<!-- Main Container -->
<div class="main-container">

    <div class="container mt-5">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Classes Management</h3>
        </div>

        <!-- FILTERS & SEARCH -->
        <div class="row align-items-center mb-3">
            <div class="col-md-3 mb-2">
                <input type="text" id="instructorSearch" class="form-control" placeholder="Search Instructor..." value="<?= esc($_GET['instructor'] ?? '') ?>">

            </div>

            <div class="col-md-2 mb-2">
                <select id="sectionFilter" class="form-select">
                    <option value="">All Sections</option>
                    <?php 
                    $sections = array_unique(array_column($classes, 'section'));
                    foreach ($sections as $section):
                    ?>
                        <option value="<?= strtolower(esc($section)) ?>"><?= strtoupper(esc($section)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4 mb-2 d-flex">
                <select id="semesterFilter" class="form-select">
                    <option value="">
                        <?= isset($activeSemester) 
                            ? ucwords($activeSemester['semester']) . ' ' . $activeSemester['schoolyear'] 
                            : 'No Active Semester' ?>
                    </option>
                    <?php foreach ($semesters as $semester): ?>
                        <?php if (!isset($activeSemester) || $semester['semester_id'] != $activeSemester['semester_id']): ?>
                            <option value="<?= $semester['semester_id'] ?>" <?= isset($_GET['semester_id']) && $_GET['semester_id'] == $semester['semester_id'] ? 'selected' : '' ?>>
                                <?= ucwords($semester['semester']) . ' ' . $semester['schoolyear'] ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <button id="clearFiltersBtn" class="btn btn-outline-secondary btn-thin rounded-1 px-3 py-2 ms-2">Clear</button>
            </div>

            <div class="col-md-3 mb-2 text-end">
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

        <div class="table-responsive">
            <div class="table-scroll">
                <table class="table table-bordered table-hover classes-table custom-padding">
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
                        <tr data-instructor="<?= strtolower($class['fname'] . ' ' . $class['lname']) ?>" data-section="<?= strtolower($class['section']) ?>">
                            <?php
                                $subjectName = trim($class['subject_name']);
                                $fullTitle = $class['subject_code'] . " - " . $subjectName;
    
                                // Use mb_strlen to properly count multibyte characters
                                $shortTitle = (mb_strlen($fullTitle) > 52)
                                    ? mb_substr($fullTitle, 0, 52) . '...'
                                    : $fullTitle;
                            ?>
                            <td title="<?= esc($fullTitle) ?>"><?= esc($shortTitle) ?></td>
                            <td><?= esc($class['subject_type']) ?></td>
                            <td class="small-font">
                                <?= !empty($class['lec_day']) ? '<strong>Lec</strong>: ' . esc(strtoupper($class['lec_day'])) : '' ?>
                                <?php if (!empty($class['lec_start']) && !empty($class['lec_end'])): ?>
                                    <?= date("g:i A", strtotime($class['lec_start'])) ?> - <?= date("g:i A", strtotime($class['lec_end'])) ?>
                                <?= !empty($class['lec_room']) ? '' . esc(strtoupper($class['lec_room'])) : '' ?>
                                <?php else: ?> - <?php endif; ?>
                                <?php if (!empty($class['lab_day'])): ?>
                                    <br><strong>Lab</strong>: <?= esc(strtoupper($class['lab_day'])) ?>
                                    <?php if (!empty($class['lab_start']) && !empty($class['lab_end'])): ?>
                                    <?= date("g:i A", strtotime($class['lab_start'])) ?> - <?= date("g:i A", strtotime($class['lab_end'])) ?>
                                    <?php endif; ?>
                                    <?= esc(strtoupper($class['lab_room'])) ?>
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
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center gap-3">
                            <!-- Prev Button -->
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link"
                                href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)])) ?>">
                                    Prev
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link"
                                    href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next Button -->
                            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link"
                                href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => min($totalPages, $page + 1)])) ?>">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
        </div>

        <!-- OUTSIDE THE TABLE: Edit and Delete Modals -->
        <?php foreach ($classes as $class): ?>
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?= $class['class_id'] ?>" tabindex="-1"  data-bs-backdrop="static">
            <div class="modal-dialog modal-lg modal-lg-dialog-centered justify-content-center modal-dialog-scrollable">
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
                                <!-- Instructor Search (Left Side) -->
                                <div class="col-md-6 position-relative">
                                    <label for="editInstructorSearchInput<?= $class['class_id'] ?>" class="form-label">Instructor</label>
                                    <input type="text" id="editInstructorSearchInput<?= $class['class_id'] ?>" 
                                        class="form-control" placeholder="Search Instructor..." autocomplete="off"
                                        value="<?= esc($instructors[$class['ftb_id']] ?? '') ?>" required>

                                    <input type="hidden" name="ftb_id" id="editInstructorIdInput<?= $class['class_id'] ?>" 
                                        value="<?= esc($class['ftb_id']) ?>">

                                    <ul id="editInstructorSuggestions<?= $class['class_id'] ?>" 
                                        class="list-group position-absolute w-100 shadow"
                                        style="top: 100%; z-index: 1050; max-height: 200px; overflow-y: auto;"></ul>
                                </div>

                                <!-- Section (Right Side) -->
                                <div class="col-md-6">
                                    <label class="form-label">Section</label>
                                    <input type="text" name="section" class="form-control" 
                                        value="<?= esc($class['section']) ?>" required>
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
                                <div class="edit-lecture-schedule <?= $class['subject_type'] == 'LEC with LAB' ? 'col-md-6' : 'col-md-12' ?>" data-id="<?= $class['class_id'] ?>">
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

        <!-- Add Class Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg modal-lg-dialog-centered justify-content-center modal-dialog-scrollable">
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
                                <!-- Instructor Search -->
                                <div class="col-md-6 position-relative">
                                    <label for="instructorSearchInput" class="form-label">Instructor</label>
                                    <input type="text" id="instructorSearchInput" class="form-control" placeholder="Search Instructor..." autocomplete="off" required>
                                    <input type="hidden" name="ftb_id" id="instructorIdInput">
                                    <ul id="instructorSuggestions" class="list-group position-absolute w-100 shadow" style="top: 100%; z-index: 1050; max-height: 200px; overflow-y: auto;"></ul>
                                </div>

                                <!-- Section -->
                                <div class="col-md-6">
                                    <label for="section" class="form-label">Section</label>
                                    <input type="text" name="section" id="section" class="form-control" <?= old('section') ?> placeholder="Section e.g., BSCS 1A" required>
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
                                <div id="lectureSchedule" class="col-md-12">
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

<!-- No Active Semester Modal -->
<div class="modal fade" id="noSemesterModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
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

<div class="modal fade" id="invalidEntryModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Invalid Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="invalidEntryMessage">
                Invalid input.
            </div>
        </div>
    </div>
</div>



<!-- External JS for class-related features -->
<script src="<?= base_url('rsc/custom_js/classes.js') ?>"></script>

<!-- Initialize the subject data array using PHP (used for both add/edit subject search) -->
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
    // Handles subject search input and display for the "Add Class" form
    const searchInput = document.getElementById('subjectSearchInput');
    const subjectIdInput = document.getElementById('subjectIdInput');
    const subjectTypeInput = document.getElementById('subjectTypeInput');
    const suggestionsList = document.getElementById('subjectSuggestions');
    const lectureSchedule = document.getElementById('lectureSchedule');
    const labScheduleSection = document.getElementById('labSchedule');
    const labFields = ['labDay', 'labRoom', 'labStart', 'labEnd'].map(id => document.getElementById(id));

    // Handle user typing in subject search
    searchInput.addEventListener('input', function () {
        const query = searchInput.value.toLowerCase();
        const filteredSubjects = subjects.filter(sub =>
            sub.code.toLowerCase().includes(query) || sub.name.toLowerCase().includes(query)
        );

        suggestionsList.innerHTML = '';
        filteredSubjects.forEach(sub => {
            // Create suggestion item
            const li = document.createElement('li');
            li.classList.add('list-group-item', 'list-group-item-action');
            li.textContent = `${sub.code} - ${sub.name}`;
            li.dataset.id = sub.id;
            li.dataset.type = sub.type;

            // On selection of a subject
            li.addEventListener('click', () => {
                searchInput.value = `${sub.code} - ${sub.name}`;
                subjectIdInput.value = sub.id;
                subjectTypeInput.value = sub.type;
                suggestionsList.innerHTML = '';

                // Toggle lab section and column layout based on subject type
                if (sub.type === 'LEC with LAB') {
                    lectureSchedule.classList.remove('d-none');
                    labScheduleSection.classList.remove('d-none');
                    lectureSchedule.classList.remove('col-md-12');
                    lectureSchedule.classList.add('col-md-6');
                    labFields.forEach(field => field.setAttribute('required', 'required'));
                } else {
                    lectureSchedule.classList.remove('d-none');
                    labScheduleSection.classList.add('d-none');
                    lectureSchedule.classList.remove('col-md-6');
                    lectureSchedule.classList.add('col-md-12');
                    labFields.forEach(field => {
                        field.removeAttribute('required');
                        field.value = '';
                    });
                }
            });

            suggestionsList.appendChild(li);
        });
    });

    // Hide suggestions on click outside
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !suggestionsList.contains(e.target)) {
            suggestionsList.innerHTML = '';
        }
    });


    // ----- EDIT CLASS SUBJECT SEARCH (MULTIPLE MODALS) -----
    // Handle subject selection logic inside edit modals
    document.querySelectorAll('.edit-subject-search').forEach(input => {
    const classId = input.dataset.id;
    const hiddenInput = document.querySelector(`.edit-subject-id[data-id="${classId}"]`);
    const typeInput = document.querySelector(`.edit-subject-type[data-id="${classId}"]`);
    const suggestionList = document.querySelector(`.edit-suggestions[data-id="${classId}"]`);
    const labSection = document.querySelector(`.edit-lab-schedule[data-id="${classId}"]`);
    const lectureSection = document.querySelector(`.edit-lecture-schedule[data-id="${classId}"]`);

    // Input event for filtering suggestions
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
                    lectureSection.classList.remove('col-md-12');
                    lectureSection.classList.add('col-md-6');
                    labSection.classList.remove('col-md-0');
                    labSection.classList.add('col-md-6');
                } else {
                    labSection.classList.add('d-none');
                    labSection.querySelectorAll('input').forEach(input => input.value = '');
                    lectureSection.classList.remove('col-md-6');
                    lectureSection.classList.add('col-md-12');
                }
            });

            suggestionList.appendChild(li);
        });
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !suggestionList.contains(e.target)) {
            suggestionList.innerHTML = '';
        }
    });

    // Set correct columns initially if already has value
    if (typeInput.value === 'LEC with LAB') {
        labSection.classList.remove('d-none');
        lectureSection.classList.remove('col-md-12');
        lectureSection.classList.add('col-md-6');
        labSection.classList.remove('col-md-0');
        labSection.classList.add('col-md-6');
    } else {
        labSection.classList.add('d-none');
        lectureSection.classList.remove('col-md-6');
        lectureSection.classList.add('col-md-12');
    }
});
    // ----- TIME INPUT FOCUS (Optional UX Tweak) -----
    // If user clicks 'end' time without filling 'start', auto-focus the start field

    const lecEnd = document.getElementById('lecEnd');
    const lecStart = document.getElementById('lecStart');
    lecEnd?.addEventListener('focus', () => { if (!lecStart.value) lecStart.focus(); });

    const labEnd = document.getElementById('labEnd');
    const labStart = document.getElementById('labStart');
    labEnd?.addEventListener('focus', () => { if (!labStart.value) labStart.focus(); });

    // Same behavior for all edit modals
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
    const instructors = [
        <?php foreach ($instructors as $ftbId => $instructorName): ?>,
        {
            id: "<?= esc($ftbId) ?>",
            name: "<?= esc($instructorName) ?>"
        },
        <?php endforeach; ?>
    ];

    const instructorSearchInput = document.getElementById('instructorSearchInput');
    const instructorSuggestions = document.getElementById('instructorSuggestions');
    const instructorIdInput = document.getElementById('instructorIdInput');

    instructorSearchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        instructorSuggestions.innerHTML = '';

        if (!query) return;

        const matches = instructors.filter(instructor =>
            instructor.name.toLowerCase().includes(query)
        );

        matches.forEach(instructor => {
            const li = document.createElement('li');
            li.classList.add('list-group-item', 'list-group-item-action');
            li.textContent = instructor.name;
            li.addEventListener('click', () => {
                instructorSearchInput.value = instructor.name;
                instructorIdInput.value = instructor.id;
                instructorSuggestions.innerHTML = '';
            });
            instructorSuggestions.appendChild(li);
        });
    });

    document.addEventListener('click', function (e) {
        if (!instructorSuggestions.contains(e.target) && e.target !== instructorSearchInput) {
            instructorSuggestions.innerHTML = '';
        }
    });

</script>

<!-- Filter table by instructor name or section as well as semester -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const instructorInput = document.getElementById('instructorSearch');
        const sectionFilter = document.getElementById('sectionFilter');
        const semesterFilter = document.getElementById('semesterFilter');
        const clearBtn = document.getElementById('clearFiltersBtn');

        const urlParams = new URLSearchParams(window.location.search);
        const currentInstructor = urlParams.get('instructor') || '';
        const currentSection = urlParams.get('section') || '';
        const currentSemester = urlParams.get('semester_id') || '';

        instructorInput.value = currentInstructor;
        sectionFilter.value = currentSection;
        semesterFilter.value = currentSemester;

        // Focus & keep cursor at the end if instructor search has value
        if (currentInstructor) {
            instructorInput.focus();
            const val = instructorInput.value;
            instructorInput.value = '';
            instructorInput.value = val;
        }

        // Section filter change
        sectionFilter.addEventListener('change', () => {
            urlParams.set('section', sectionFilter.value);
            urlParams.set('page', 1);
            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        });

        // Semester filter change
        semesterFilter.addEventListener('change', () => {
            urlParams.set('semester_id', semesterFilter.value);
            urlParams.set('page', 1);
            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        });

        // Instructor input typing (with debounce)
        let instructorTimeout;
        instructorInput.addEventListener('input', () => {
            clearTimeout(instructorTimeout);
            instructorTimeout = setTimeout(() => {
                urlParams.set('instructor', instructorInput.value.trim());
                urlParams.set('page', 1);
                window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
            }, 500);
        });

        // Clear button resets all filters
        clearBtn.addEventListener('click', () => {
            window.location.href = window.location.pathname;
        });
    });
</script>




<script>
    document.addEventListener('DOMContentLoaded', function () {
    <?php foreach ($classes as $class): ?>
    (function () {
        const input = document.getElementById('editInstructorSearchInput<?= $class['class_id'] ?>');
        const list = document.getElementById('editInstructorSuggestions<?= $class['class_id'] ?>');
        const hidden = document.getElementById('editInstructorIdInput<?= $class['class_id'] ?>');

        if (!input || !list || !hidden) return;

        input.addEventListener('input', function () {
            const query = this.value.toLowerCase();
            list.innerHTML = '';

            if (!query) return;

            const matches = instructors.filter(instr =>
                instr.name.toLowerCase().includes(query)
            );

            matches.forEach(instr => {
                const li = document.createElement('li');
                li.classList.add('list-group-item', 'list-group-item-action');
                li.textContent = instr.name;
                li.addEventListener('click', () => {
                    input.value = instr.name;
                    hidden.value = instr.id;
                    list.innerHTML = '';
                });
                list.appendChild(li);
            });
        });

        document.addEventListener('click', function (e) {
            if (!list.contains(e.target) && e.target !== input) {
                list.innerHTML = '';
            }
        });
    })();
    <?php endforeach; ?>
});
</script>

<script>
    document.getElementById('semesterFilter').addEventListener('change', function () {
        const selectedSemester = this.value;
        const url = new URL(window.location.href);

        if (selectedSemester) {
            url.searchParams.set('semester_id', selectedSemester);
        } else {
            url.searchParams.delete('semester_id');
        }

        window.location.href = url.toString();
    });

</script>


<script>
    document.addEventListener('DOMContentLoaded', () => {
    const instructorsList = instructors.map(ins => ins.name.toLowerCase());
    const subjectsList = subjects.map(sub => (sub.code + ' - ' + sub.name).toLowerCase());

    function showInvalidModal(message) {
        const modalElement = document.getElementById('invalidEntryModal');
        const bootstrapModal = new bootstrap.Modal(modalElement);
        document.getElementById('invalidEntryMessage').textContent = message;
        bootstrapModal.show();

    }

    // ADD CLASS FORM VALIDATION
    const addForm = document.querySelector('#addModal form');
    addForm.addEventListener('submit', function (e) {
        const instructorName = document.getElementById('instructorSearchInput').value.toLowerCase();
        const subjectName = document.getElementById('subjectSearchInput').value.toLowerCase();

        if (!instructorsList.includes(instructorName)) {
            e.preventDefault();
            showInvalidModal('The instructor you selected does not exist.');
            return;
        }

        if (!subjectsList.includes(subjectName)) {
            e.preventDefault();
            showInvalidModal('The subject you selected does not exist.');
            return;
        }
    });

    // EDIT CLASS FORM VALIDATION (multiple modals)
    document.querySelectorAll('[id^="editModal"]').forEach(modal => {
        const form = modal.querySelector('form');
        form.addEventListener('submit', function (e) {
            const classId = form.querySelector('.edit-subject-search').dataset.id;
            const instructorInput = form.querySelector(`#editInstructorSearchInput${classId}`);
            const subjectInput = form.querySelector(`.edit-subject-search[data-id="${classId}"]`);

            const instructorName = instructorInput.value.toLowerCase();
            const subjectName = subjectInput.value.toLowerCase();

            if (!instructorsList.includes(instructorName)) {
                e.preventDefault();
                showInvalidModal('The instructor you selected does not exist.');
                return;
            }

            if (!subjectsList.includes(subjectName)) {
                e.preventDefault();
                showInvalidModal('The subject you selected does not exist.');
                return;
            }
        });
    });
});
</script>

<script>
    document.addEventListener('keydown', function(event) {
        // Skip if modal is open
        const isModalOpen = document.querySelector('.modal.show');
        if (isModalOpen) return;

        const currentPage = <?= $page ?>;
        const totalPages = <?= $totalPages ?>;
        const baseUrl = "<?= site_url('admin/academics/classes?page=') ?>";

        if (event.key === 'ArrowRight') {
            let nextPage = currentPage + 1;
            if (nextPage > totalPages) nextPage = 1; // Loop to page 1
            window.location.href = baseUrl + nextPage;
        }

        if (event.key === 'ArrowLeft') {
            let prevPage = currentPage - 1;
            if (prevPage < 1) prevPage = totalPages; // Loop to last page
            window.location.href = baseUrl + prevPage;
        }
    });
</script>