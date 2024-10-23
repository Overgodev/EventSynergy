<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Events</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Function to fetch events for admin management
        function fetchEvents() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'http://localhost/fetch_events.php', true);

            xhr.onload = function() {
                if (this.status === 200) {
                    const events = JSON.parse(this.responseText);
                    const adminEventsContainer = document.getElementById('admin-events-container');
                    adminEventsContainer.innerHTML = ''; // Clear previous content

                    if (events.length > 0) {
                        events.forEach(event => {
                            const eventCard = document.createElement('div');
                            eventCard.classList.add('event-card');
                            eventCard.innerHTML = `
                                <h3>${event.event_name}</h3>
                                <p><strong>Date:</strong> ${event.event_date}</p>
                                <p><strong>Time:</strong> ${event.event_time}</p>
                                <p><strong>Location:</strong> ${event.location}</p>
                                <p><strong>Description:</strong> ${event.description}</p>
                                <button onclick="deleteEvent(${event.event_id})">Delete</button>
                            `;
                            adminEventsContainer.appendChild(eventCard);
                        });
                    } else {
                        adminEventsContainer.innerHTML = '<p>No events available.</p>';
                    }
                }
            };

            xhr.onerror = function() {
                document.getElementById('admin-events-container').innerHTML = '<p>Error loading events.</p>';
            };

            xhr.send();
        }

        // Function to delete an event
        function deleteEvent(event_id) {
            if (confirm("Are you sure you want to delete this event?")) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_event.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        alert('Event deleted successfully!');
                        fetchEvents(); // Refresh events list
                    } else {
                        alert('Error deleting event.');
                    }
                };
                xhr.send(`event_id=${event_id}`);
            }
        }

        // Call fetchEvents when the page loads
        window.onload = fetchEvents;
    </script>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Admin Panel - Manage Events</h1>
        <div class="user-info">
            <p>Welcome, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>
        </div>
    </header>

    <!-- Admin Events Section -->
    <div class="container">
        <div class="section" id="admin-events">
            <h2>Manage Events</h2>
            <div id="admin-events-container">
                <!-- Event cards for admin management will be added here -->
            </div>
        </div>
    </div>

</body>
</html>
