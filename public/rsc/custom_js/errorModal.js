document.addEventListener('DOMContentLoaded', function () {
    const errorModalEl = document.getElementById('errorModal');

    if (errorModalEl) {
        const errorMessage = errorModalEl.getAttribute('data-error-message');

        if (errorMessage && errorMessage.trim() !== "") {
            const errorText = document.getElementById('errorMessage');
            if (errorText) {
                errorText.textContent = errorMessage;
            }

            const errorModal = new bootstrap.Modal(errorModalEl);
            errorModal.show();

            // Optional: hide after 3 seconds
            // setTimeout(() => {
            //     errorModal.hide();
            // }, 3000);
        }
    }
});
