
const eventModalEl = document.getElementById('eventModal');
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

    // Also set hidden ID for delete/edit
    const announcementId = button.getAttribute('data-id');
    document.getElementById('modalAnnouncementId').value = announcementId;

    // On Edit Button click → open the edit modal with data
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


