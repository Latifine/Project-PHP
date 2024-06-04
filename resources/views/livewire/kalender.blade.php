<html lang='en'>
<head>
    <meta charset='utf-8' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/locales/nl.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: new Date(), // Set the initial date to the current date
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'today', // Include the default button along with custom button
                },
                locale: 'nl',
                buttonText: { // Customize the text for different views
                    today: 'Vandaag', // Change text for the month view button
                },
                firstDay: 1, // Set the first day of the week to Monday
                events: @json($events) // Livewire automatically converts to JSON
            });

            calendar.render();
        });
    </script>

    <title>Kalender</title>
</head>
<body>
    <div id='calendar'></div>
</body>
</html>
