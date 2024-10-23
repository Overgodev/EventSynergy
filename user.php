<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'User') {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page - Browse Events</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Function to fetch events for users
        function fetchEvents() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'http://localhost/fetch_events.php', true);

            xhr.onload = function() {
                if (this.status === 200) {
                    const events = JSON.parse(this.responseText);
                    const userEventsContainer = document.getElementById('user-events-container');
                    userEventsContainer.innerHTML = ''; // Clear previous content

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
                                <form action="register_event.php" method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                                    <input type="hidden" name="event_id" value="${event.event_id}">
                                    <button type="submit">Register for Event</button>
                                </form>
                            `;
                            userEventsContainer.appendChild(eventCard);
                        });
                    } else {
                        userEventsContainer.innerHTML = '<p>No events available.</p>';
                    }
                }
            };

            xhr.onerror = function() {
                document.getElementById('user-events-container').innerHTML = '<p>Error loading events.</p>';
            };

            xhr.send();
        }

        // Call fetchEvents when the page loads
        window.onload = fetchEvents;
    </script>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>User Page - Browse Events</h1>
        <div class="user-info">
            <p>Welcome, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>
        </div>
    </header>

    <!-- User Events Section -->
    <div class="container">
        <div class="section" id="user-events">
            <h2>Available Events</h2>
            <div id="user-events-container">
                <!-- Event cards for users will be dynamically added here -->
            </div>
        </div>
    </div>

</body>
</html>
