// calendar.js
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    if (calendarEl && typeof announcements !== 'undefined') {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 500,
            events: announcements.map(a => ({
                id: a.announcement_id,
                title: a.title,
                start: a.event_datetime,
                description: a.content,
            })),
            eventClick: function (info) {
                const announcementId = info.event.id;
                const announcement = announcements.find(a => a.announcement_id == announcementId);

                if (!announcement) return;

                // Fill the view modal
                document.getElementById('eventTitle').innerText = announcement.title;

                const dateObj = new Date(announcement.event_datetime);
                document.getElementById('eventDate').innerText =
                    dateObj.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) +
                    ' at ' +
                    dateObj.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

                document.getElementById('eventDescription').innerText = announcement.content;

                //edit + delete only for admins
                const idInput = document.getElementById('modalAnnouncementId');
                if (idInput) {
                    idInput.value = announcementId;
                }

                const editBtn = document.getElementById('editAnnouncementBtn');
                if (editBtn) {
                    editBtn.onclick = function () {
                        const idField = document.getElementById('editAnnouncementId');
                        const titleField = document.getElementById('editTitle');
                        const contentField = document.getElementById('editContent');
                        const audienceField = document.getElementById('editAudience');
                        const datetimeField = document.getElementById('editEventDatetime');

                        if (idField && titleField && contentField && audienceField && datetimeField) {
                            idField.value = announcement.announcement_id;
                            titleField.value = announcement.title;
                            contentField.value = announcement.content;
                            audienceField.value = announcement.audience;
                            datetimeField.value = announcement.event_datetime.replace(' ', 'T');

                            const editModal = new bootstrap.Modal(document.getElementById('editAnnouncementModal'));
                            editModal.show();
                        }
                    };
                }

                // Show modal
                const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
                eventModal.show();
            }

                    });

        calendar.render();
    }
});
