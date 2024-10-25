<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'User') {
    header('Location: ../auth/login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page - Browse Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Header styling */
        header {
            background-color: #1e5bb7; /* Dark blue */
            color: white; /* White text */
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
        }
        header p {
            margin: 0;
        }
        header a {
            color: white; /* White text for logout link */
            text-decoration: none;
            font-weight: bold;
            margin-left: 15px;
        }
        header a:hover {
            text-decoration: underline;
        }
        /* Navigation bar styling */
        nav {
            background-color: #1e5bb7; /* Dark blue */
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }
        nav a {
            color: white; /* White text */
            font-weight: bold;
            margin: 0 20px;
            text-decoration: none; /* No underline */
            font-size: 18px;
        }
        nav a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        // Function to fetch events for users
        function fetchEvents() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../user/fetch_events.php', true);

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
                                <form action="../user/register_event.php" method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
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

        // Show feedback form
        function showFeedbackForm() {
            document.getElementById('feedback-section').style.display = 'block';
            document.getElementById('user-events').style.display = 'none';
        }

        // Show event registration
        function showEventRegistration() {
            document.getElementById('user-events').style.display = 'block';
            document.getElementById('feedback-section').style.display = 'none';
        }

        // Call fetchEvents when the page loads
        window.onload = fetchEvents;
    </script>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>User Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="../auth/logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="javascript:void(0);" onclick="showEventRegistration()">Browse Events</a>
        <a href="javascript:void(0);" onclick="showFeedbackForm()">Submit Feedback</a>
    </nav>

    <!-- User Events Section -->
    <div class="container" id="user-events">
        <div class="section">
            <h2>Available Events</h2>
            <div id="user-events-container">
                <!-- Event cards for users will be dynamically added here -->
            </div>
        </div>
    </div>

    <!-- Feedback Section -->
    <div class="container" id="feedback-section" style="display: none;">
        <div class="section">
            <h2>Submit Feedback</h2>
            <form action="../user/feedback.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">

                <label for="feedback">Your Feedback:</label>
                <textarea id="feedback" name="feedback" rows="4" required></textarea>

                <button type="submit">Submit Feedback</button>
            </form>
        </div>
    </div>

</body>
</html>
