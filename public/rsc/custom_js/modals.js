document.addEventListener('DOMContentLoaded', function () {
    // --- Event Modal Logic ---
    const eventModalEl = document.getElementById('eventModal');
    if (eventModalEl) {
        eventModalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const title = button.getAttribute('data-title');
            const dateRaw = new Date(button.getAttribute('data-date'));
            const description = button.getAttribute('data-description');

            const formattedDate = dateRaw.toLocaleDateString('en-US', {
                year: 'numeric', month: 'long', day: 'numeric'
            });
            const formattedTime = dateRaw.toLocaleTimeString('en-US', {
                hour: 'numeric', minute: '2-digit', hour12: true
            });

            document.getElementById('eventTitle').innerText = title;
            document.getElementById('eventDate').innerText = `${formattedDate} at ${formattedTime}`;
            document.getElementById('eventDescription').innerText = description;

            const announcementId = button.getAttribute('data-id');
            document.getElementById('modalAnnouncementId').value = announcementId;

            document.getElementById('editAnnouncementBtn').onclick = function () {
                const announcement = announcements.find(a => a.announcement_id == announcementId);
                if (announcement) {
                    document.getElementById('editAnnouncementId').value = announcement.announcement_id;
                    document.getElementById('editTitle').value = announcement.title;
                    document.getElementById('editContent').value = announcement.content;
                    document.getElementById('editAudience').value = announcement.audience;
                    document.getElementById('editEventDatetime').value = announcement.event_datetime.replace(' ', 'T');

                    const editModal = new bootstrap.Modal(document.getElementById('editAnnouncementModal'));
                    editModal.show();
                }
            };
        });
    }

    // --- Error Modal Logic ---
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
            // setTimeout(() => errorModal.hide(), 3000);
        }
    }

    // --- Success Modal Logic ---
    const successModalEl = document.getElementById('successModal');
    if (successModalEl) {
        const message = successModalEl.getAttribute('data-success-message');
        if (message && message.trim() !== "") {
            document.getElementById('successMessage').textContent = message;

            const successModal = new bootstrap.Modal(successModalEl);
            successModal.show();
            // setTimeout(() => successModal.hide(), 2000);
        }
    }
});


function showNoSemesterModal() {
    const modalElement = document.getElementById('noSemesterModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    // Auto-close after 1.5 seconds (1500 ms)
    setTimeout(() => {
        modal.hide();
    }, 1500);
}


