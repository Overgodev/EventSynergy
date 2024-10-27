<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Link to your CSS file -->
</head>
<body>

<!-- Header -->
<header>
        <h1>Event Management System</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="index.php">Home</a>
        <a href="register.html">Register</a>
        <a href="events.html">Browse Events</a>
        <a href="feedback.html">Feedback</a>
    </nav>
    
    <!-- Feedback Form -->
    <div class="container">
        <div class="section" id="feedback">
            <h2>Event Feedback</h2>
            <form action="feedback.php" method="POST">
                <label for="event">Event Attended:</label>
                <select id="event" name="event">
                    <option value="1">Tech Conference 2024</option>
                    <option value="2">Startup Expo 2024</option>
                </select>

                <label for="feedback">Your Feedback:</label>
                <textarea id="feedback" name="feedback" rows="4" required></textarea>

                <button type="submit">Submit Feedback</button>
            </form>

<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event'];
    $feedback = $_POST['feedback'];

    // SQL to insert feedback
    $sql = "INSERT INTO Feedback (event_id, feedback_text) VALUES ('$event_id', '$feedback')";

    if ($conn->query($sql) === TRUE) {
        echo "Thank you for your feedback!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
</div>

</body>
</html>