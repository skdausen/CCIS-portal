const monthYearEl = document.getElementById('monthYear');
const calendarBody = document.getElementById('calendarBody');
const prevMonthBtn = document.getElementById('prevMonth');
const nextMonthBtn = document.getElementById('nextMonth');

let currentDate = new Date();

function renderCalendar(date) {
  const year = date.getFullYear();
  const month = date.getMonth();

  // Set month-year header
  const monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];
  monthYearEl.textContent = `${monthNames[month]} ${year}`;

  // First day of month (0=Sun, 1=Mon, ...)
  const firstDay = new Date(year, month, 1).getDay();
  // Number of days in month
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  // Clear previous calendar body
  calendarBody.innerHTML = '';

  let day = 1;
  // We will create up to 6 rows (weeks)
  for (let i = 0; i < 6; i++) {
    const row = document.createElement('tr');

    for (let j = 0; j < 7; j++) {
      const cell = document.createElement('td');

      if (i === 0 && j < firstDay) {
        // Empty cells before first day
        cell.textContent = '';
      } else if (day > daysInMonth) {
        // Empty cells after last day
        cell.textContent = '';
      } else {
        cell.textContent = day;

        // Highlight today's date if visible
        const today = new Date();
        if (
          day === today.getDate() &&
          month === today.getMonth() &&
          year === today.getFullYear()
        ) {
          const span = document.createElement('span');
          span.textContent = day;
          span.className = 'inline-block rounded-full bg-blue-200 text-blue-600 w-7 h-7 leading-7';
          cell.textContent = '';
          cell.appendChild(span);
        } else if (day === 10 && month === 5 && year === 2024) {
          // Special styling for June 10, 2024 (blue text)
          cell.classList.add('text-blue-600', 'cursor-pointer');
        }

        day++;
      }
      row.appendChild(cell);
    }
    calendarBody.appendChild(row);

    // Stop creating rows if all days are rendered
    if (day > daysInMonth) break;
  }
}

prevMonthBtn.addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  renderCalendar(currentDate);
});

nextMonthBtn.addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar(currentDate);
});

// Initial render
renderCalendar(currentDate);

