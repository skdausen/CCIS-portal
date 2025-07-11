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
                    document.getElementById('detailSex').textContent = user.sex ?? '-';
                    document.getElementById('detailBirthday').textContent = user.birthdate ?? '-';
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
                });
        });
    });
});
