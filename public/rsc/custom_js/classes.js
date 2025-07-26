// This script handles the dynamic behavior of the class type selection and filtering functionality
// FILTER SCRIPT
    document.addEventListener('DOMContentLoaded', function () {
        const instructorFilter = document.getElementById('instructorFilter');
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearFilterBtn');
        const rows = document.querySelectorAll('table tbody tr');

        function filterRows() {
        const instructorVal = instructorFilter.value.toLowerCase();
        const searchVal = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const course = row.cells[0].textContent.toLowerCase();
                const room = row.cells[4].textContent.toLowerCase();
                const instructor = row.cells[5].textContent.toLowerCase(); 

                const matchInstructor = !instructorVal || instructor.includes(instructorVal);
                const matchSearch = !searchVal || course.includes(searchVal) || room.includes(searchVal);

                row.style.display = matchInstructor && matchSearch ? '' : 'none';
            });
        }

        instructorFilter.addEventListener('change', filterRows);
        searchInput.addEventListener('input', filterRows);
        clearBtn.addEventListener('click', () => {
            instructorFilter.value = '';
            searchInput.value = '';
            filterRows();
        });

        filterRows();
    });


// SEMESTER FILTER SCRIPT
    // This script handles the semester filter for classes
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

    function showNoSemesterModal() {
        const noSemesterModal = new bootstrap.Modal(document.getElementById('noSemesterModal'));
        noSemesterModal.show();
    }

// TIME VALIDATION
// VALIDATE LECTURE TIME
document.getElementById('lecStart').addEventListener('change', function () {
    const start = this.value;
    const endInput = document.getElementById('lecEnd');
    endInput.min = start;

    if (endInput.value < start) {
        endInput.value = '';
        alert('Lecture End Time cannot be earlier than Start Time!');
    }
});

document.getElementById('lecEnd').addEventListener('change', function () {
    const end = this.value;
    const startInput = document.getElementById('lecStart');
    if (end < startInput.value) {
        this.value = '';
        alert('Lecture End Time cannot be earlier than Start Time!');
    }
});

// VALIDATE LAB TIME
document.getElementById('labStart').addEventListener('change', function () {
    const start = this.value;
    const endInput = document.getElementById('labEnd');
    endInput.min = start;

    if (endInput.value < start) {
        endInput.value = '';
        alert('Lab End Time cannot be earlier than Start Time!');
    }
});

document.getElementById('labEnd').addEventListener('change', function () {
    const end = this.value;
    const startInput = document.getElementById('labStart');
    if (end < startInput.value) {
        this.value = '';
        alert('Lab End Time cannot be earlier than Start Time!');
    }
});

// For Add Modal
const addInput = document.getElementById('instructorSearchInput');
const addHidden = document.getElementById('instructorIdInput');
const addSuggestions = document.getElementById('instructorSuggestions');

addInput.addEventListener('input', function () {
    const query = this.value.toLowerCase();
    addSuggestions.innerHTML = '';
    if (!query) return;

    const matches = instructors.filter(i => i.name.toLowerCase().includes(query));
    matches.forEach(i => {
        const li = document.createElement('li');
        li.classList.add('list-group-item', 'list-group-item-action');
        li.textContent = i.name;
        li.addEventListener('click', () => {
            addInput.value = i.name;
            addHidden.value = i.id;
            addSuggestions.innerHTML = '';
        });
        addSuggestions.appendChild(li);
    });
});

document.addEventListener('click', function (e) {
    if (!addSuggestions.contains(e.target) && e.target !== addInput) {
        addSuggestions.innerHTML = '';
    }
});

// For Edit Modals
(function() {
    const id = "<?= $class['class_id'] ?>";
    const input = document.getElementById('editInstructorSearchInput' + id);
    const hiddenInput = document.getElementById('editInstructorIdInput' + id);
    const suggestions = document.getElementById('editInstructorSuggestions' + id);

    input.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        suggestions.innerHTML = '';
        if (!query) return;

        const matches = instructors.filter(i => i.name.toLowerCase().includes(query));
        matches.forEach(i => {
            const li = document.createElement('li');
            li.classList.add('list-group-item', 'list-group-item-action');
            li.textContent = i.name;
            li.addEventListener('click', () => {
                input.value = i.name;
                hiddenInput.value = i.id;
                suggestions.innerHTML = '';
            });
            suggestions.appendChild(li);
        });
    });

    document.addEventListener('click', function(e) {
        if (!suggestions.contains(e.target) && e.target !== input) {
            suggestions.innerHTML = '';
        }
    });
})();

