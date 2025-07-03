document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');

  // Format announcement data to FullCalendar format
  const calendarEvents = announcements.map(announcement => ({
    title: announcement.title,
    start: announcement.created_at,
    description: announcement.content
  }));

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    height: 400, // Makes calendar smaller
    contentHeight: 400,
    selectable: true,
    headerToolbar: {
      left: 'prev,next',
      center: 'title',
      right: ''
    },
    events: calendarEvents,
    eventClick: function (info) {
      document.getElementById('eventTitle').innerText = info.event.title;
      document.getElementById('eventDate').innerText = new Date(info.event.start).toDateString();
      document.getElementById('eventDescription').innerText = info.event.extendedProps.description;
      new bootstrap.Modal(document.getElementById('eventModal')).show();
    }
  });

  calendar.render();
});
