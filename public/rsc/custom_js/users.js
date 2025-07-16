    // SEARCH & FILTER SCRIPT
    document.addEventListener('DOMContentLoaded', function () {
        const filter = document.getElementById('roleFilter');
        const search = document.getElementById('searchInput');
        const rows = document.querySelectorAll('#usersTable tbody tr');

        function filterRows() {
            const roleVal = filter.value.toLowerCase();
            const searchVal = search.value.toLowerCase();

            rows.forEach(row => {
                const role = row.cells[1].textContent.toLowerCase();
                const username = row.cells[2].textContent.toLowerCase();
                const email = row.cells[3].textContent.toLowerCase();

                let matchRole = true;

                if (roleVal === 'admin') {
                    matchRole = role === 'admin' || role === 'superadmin'; // Include superadmin under admin
                } else if (roleVal) {
                    matchRole = role === roleVal;
                }

                const matchSearch = !searchVal || username.includes(searchVal) || email.includes(searchVal);

                row.style.display = matchRole && matchSearch ? '' : 'none';
            });
        }

        filter.addEventListener('change', filterRows);
        search.addEventListener('input', filterRows);
    });

    // ADD USER MODAL SCRIPT
    // This script handles the reset of the add user modal form when it is closed
    document.addEventListener('DOMContentLoaded', function () {
        const addUserModal = document.getElementById('addUserModal');
        addUserModal.addEventListener('hidden.bs.modal', function () {
            addUserModal.querySelector('form').reset();
        });
    });
    
    // VIEW USER DETAILS SCRIPT
    // This script handles the viewing of user details in a modal when the "View" button
    document.addEventListener('DOMContentLoaded', function () {
    const base_url = document.querySelector('[data-base-url]').getAttribute('data-base-url');
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
                    // Fill modal fields

                    function capitalizeWords(str) {
                        return str
                            .toLowerCase()
                            .replace(/\b\w/g, char => char.toUpperCase());
                    }

                    user.role = capitalizeWords(user.role);
                    user.status = capitalizeWords(user.status);
                    const fullName = `${user.fname ?? ''} ${user.mname ?? ''} ${user.lname ?? ''}`;

                    document.getElementById('detailUserID').textContent = user.user_id;
                    document.getElementById('detailRole').textContent = user.role;
                    document.getElementById('detailUsername').textContent = user.username;
                    document.getElementById('detailEmail').textContent = user.email;
                    document.getElementById('detailStatus').textContent = user.status;
                    document.getElementById('detailFullname').textContent = capitalizeWords(fullName);
                    // document.getElementById('detailSex').textContent = user.sex ?? '-';
                    // document.getElementById('detailBirthday').textContent = user.birthdate ?? '-';
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
});
