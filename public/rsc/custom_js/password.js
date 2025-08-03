document.getElementById('editPasswordForm').addEventListener('submit', function(e) {
    const currentPassword = document.getElementById('currentPassword').value.trim();
    const newPassword = document.getElementById('newPassword').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();
    const errorDiv = document.getElementById('passwordError');

    errorDiv.classList.add('d-none');
    errorDiv.innerHTML = '';

    if (!currentPassword) {
        e.preventDefault();
        errorDiv.textContent = 'Current password is required.';
        errorDiv.classList.remove('d-none');
        return;
    }

    if (newPassword !== confirmPassword) {
        e.preventDefault();
        errorDiv.textContent = 'New passwords do not match.';
        errorDiv.classList.remove('d-none');
        return;
    }

    if (newPassword.length < 8) {
        e.preventDefault();
        errorDiv.textContent = 'New password must be at least 8 characters.';
        errorDiv.classList.remove('d-none');
        return;
    }

    if (newPassword === currentPassword) {
        e.preventDefault();
        errorDiv.textContent = 'New password cannot be the same as the current password.';
        errorDiv.classList.remove('d-none');
        return;
    }
});
