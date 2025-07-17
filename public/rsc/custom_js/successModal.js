document.addEventListener('DOMContentLoaded', function () {
    const successModalEl = document.getElementById('successModal');

    if (successModalEl) {
        const message = successModalEl.getAttribute('data-success-message');

        if (message && message.trim() !== "") {
            document.getElementById('successMessage').textContent = message;

            const successModal = new bootstrap.Modal(successModalEl);
            successModal.show();

            // setTimeout(() => {
            //     successModal.hide();
            // }, 2000);
        }
    }
});
