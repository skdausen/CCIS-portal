// calendar.js
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 400,
        events: announcements.map(a => ({
            id: a.announcement_id, // ✅ Add the ID here
            title: a.title,
            start: a.event_datetime,
            description: a.content,
        })),
        eventClick: function (info) {
            const announcementId = info.event.id;
            const announcement = announcements.find(a => a.announcement_id == announcementId);

            if (announcement) {
                // Set values in the modal
                document.getElementById('eventTitle').innerText = announcement.title;

                const dateObj = new Date(announcement.event_datetime);
                const formattedDate = dateObj.toLocaleDateString('en-US', {
                    year: 'numeric', month: 'long', day: 'numeric'
                });
                const formattedTime = dateObj.toLocaleTimeString('en-US', {
                    hour: 'numeric', minute: '2-digit', hour12: true
                });
                document.getElementById('eventDate').innerText = `${formattedDate} at ${formattedTime}`;
                document.getElementById('eventDescription').innerText = announcement.content;

                // Assign the ID to the delete form
                document.getElementById('modalAnnouncementId').value = announcementId;

                // Set up Edit button
                document.getElementById('editAnnouncementBtn').onclick = function () {
                    document.getElementById('editAnnouncementId').value = announcement.announcement_id;
                    document.getElementById('editTitle').value = announcement.title;
                    document.getElementById('editContent').value = announcement.content;
                    document.getElementById('editAudience').value = announcement.audience;
                    document.getElementById('editEventDatetime').value = announcement.event_datetime.replace(' ', 'T');

                    const editModal = new bootstrap.Modal(document.getElementById('editAnnouncementModal'));
                    editModal.show();
                };

                // Show the modal
                const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
                eventModal.show();
            }
        }
    });

    calendar.render();
});
