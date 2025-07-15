// This script handles the dynamic behavior of the class type selection and filtering functionality

// It updates the class type input based on the selected subject and manages the display of lecture and lab schedules
const subjectSelect = document.getElementById('addSubjectSelect');
const classTypeInput = document.getElementById('subjectTypeInput'); // ✅ Corrected
const lectureSchedule = document.getElementById('lectureSchedule');
const labSchedule = document.getElementById('labSchedule');

subjectSelect.addEventListener('change', function () {
    const selectedOption = subjectSelect.options[subjectSelect.selectedIndex];
    const subjectType = selectedOption.getAttribute('data-type') || '';

    classTypeInput.value = subjectType; // ✅ Now this works.

    if (subjectType === 'LEC') {
        lectureSchedule.classList.remove('d-none');
        labSchedule.classList.add('d-none');
        lectureSchedule.classList.remove('col-md-6');
        lectureSchedule.classList.add('col-md-12');
    } else {
        lectureSchedule.classList.remove('d-none');
        labSchedule.classList.remove('d-none');
        lectureSchedule.classList.remove('col-md-12');
        lectureSchedule.classList.add('col-md-6');
    } 
});



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
                const instructor = row.cells[5].textContent.toLowerCase();  // Corrected from 4 ➡️ 5

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

    setTimeout(() => {
        noSemesterModal.hide();
    }, 1500); // Auto-hide after 1500 ms (1.5 seconds)
}