// Debounce utility
function debounce(func, delay) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

// Bind View Button Function (used both on load and after AJAX)
function bindViewUserButtons() {
    const base_url = document.querySelector('[data-base-url]')?.getAttribute('data-base-url');
    const viewButtons = document.querySelectorAll('.viewUserBtn');
    const modal = new bootstrap.Modal(document.getElementById('viewUserModal'));

    viewButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const userId = this.getAttribute('data-user-id');

            fetch(`${base_url}admin/user/${userId}`)
                .then(response => {
                    if (!response.ok) throw new Error('User not found.');
                    return response.json();
                })
                .then(user => {
                    function capitalizeWords(str) {
                        return str
                            .toLowerCase()
                            .replace(/\b\w/g, char => char.toUpperCase());
                    }

                    user.role = capitalizeWords(user.role);
                    user.status = capitalizeWords(user.status);
                    const fullName = `${user.fname ?? ''} ${user.mname ?? ''} ${user.lname ?? ''}`;
                    const isStudent = user.role.toLowerCase() === 'student';

                    document.querySelectorAll('.student-only').forEach(el => {
                        el.classList.toggle('d-none', !isStudent);
                    });

                    if (isStudent) {
                        document.getElementById('detailCurriculum').textContent = capitalizeWords(user.curriculum ?? '-');
                        document.getElementById('detailProgram').textContent = capitalizeWords(user.program ?? '-');
                        document.getElementById('detailYearLevel').textContent = user.year_level ?? '-';
                    }

                    document.getElementById('detailUserID').textContent = user.user_id;
                    document.getElementById('detailRole').textContent = user.role;
                    document.getElementById('detailUsername').textContent = user.username;
                    document.getElementById('detailEmail').textContent = user.email;
                    document.getElementById('detailStatus').textContent = user.status;
                    document.getElementById('detailFullname').textContent = capitalizeWords(fullName);
                    document.getElementById('detailAddress').textContent = capitalizeWords(user.address ?? '-');
                    document.getElementById('detailContact').textContent = user.contactnum ?? '-';
                    document.getElementById('detailCreated').textContent = user.created_at;
                    document.getElementById('detailLogin').textContent = user.last_login ?? '-';
                    document.getElementById('detailProfileImg').src =
                        user.profimg ? `${base_url}rsc/assets/uploads/${user.profimg}` : `${base_url}rsc/assets/uploads/default.png`;

                    modal.show();
                })
                .catch(err => {
                    alert('Failed to load user details: ' + err.message);
                    console.error('Fetch error:', err);
                });
        });
    });
}

// Load Users Function with AJAX (used on filter/search)
function loadUsers(page = 1) {
    const role = document.querySelector('#filterRole')?.value || '';
    const search = document.querySelector('#searchInput')?.value || '';
    const baseUrl = document.getElementById('userPage').dataset.usersUrl;

    fetch(`${baseUrl}?role=${role}&search=${search}&page=${page}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.querySelector('#userTableContainer')?.innerHTML;
        document.querySelector('#userTableContainer').innerHTML = newContent;

        // Re-bind after content is replaced
        bindViewUserButtons();
    });
}

// DOM Ready
document.addEventListener('DOMContentLoaded', function () {
    const addUserModal = document.getElementById('addUserModal');
    const roleSelect = document.getElementById('role');
    const curriculumGroup = document.getElementById('curriculumGroup');
    const programGroup = document.getElementById('programGroup');
    const yearlevelGroup = document.getElementById('yearlevelGroup');

    // Reset form on modal close
    addUserModal?.addEventListener('hidden.bs.modal', function () {
        addUserModal.querySelector('form').reset();

        // Hide student-specific fields
        curriculumGroup.classList.add('d-none');
        programGroup.classList.add('d-none');
        yearlevelGroup.classList.add('d-none');

        document.getElementById('curriculum_id').removeAttribute('required');
        document.getElementById('program_id').removeAttribute('required');
        document.getElementById('year_level').removeAttribute('required');
    });

    // Role change toggle
    roleSelect?.addEventListener('change', function () {
        const isStudent = this.value === 'student';
        curriculumGroup.classList.toggle('d-none', !isStudent);
        programGroup.classList.toggle('d-none', !isStudent);
        yearlevelGroup.classList.toggle('d-none', !isStudent);

        document.getElementById('curriculum_id').required = isStudent;
        document.getElementById('program_id').required = isStudent;
        document.getElementById('year_level').required = isStudent;
    });

    roleSelect?.dispatchEvent(new Event('change'));

    // Initial bind
    bindViewUserButtons();
});

// Auto-search on input
const debouncedSearch = debounce(() => loadUsers(1), 300);
document.querySelector('#searchInput')?.addEventListener('input', debouncedSearch);
document.querySelector('#filterRole')?.addEventListener('change', () => loadUsers(1));
