<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Link to your CSS file -->
</head>
<body>

<header>
    <h1>Event Feedback</h1>
</header>

<div class="container">
    <h2>Share Your Feedback</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="event">Select Event:</label>
            <select id="event" name="event" required>
                <option value="">Select an event</option>
                <!-- Populate with events from your database -->
                <option value="1">Event 1</option>
                <option value="2">Event 2</option>
                <option value="3">Event 3</option>
            </select>
        </div>
        <div class="form-group">
            <label for="feedback">Feedback:</label>
            <textarea id="feedback" name="feedback" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn">Submit Feedback</button>
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