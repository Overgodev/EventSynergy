<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: login.html');
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connect.php';
    $name = $_POST['event_name'];
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    $sql = "INSERT INTO Events (event_name, event_date, event_time, location, description) 
            VALUES ('$name', '$date', '$time', '$location', '$description')";

    if ($conn->query($sql) === TRUE) {
        header('Location: admin_events.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Add Event</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="admin_events.php">Manage Events</a>
        <a href="admin_users.php">Manage Users</a>
    </nav>

    <!-- Add Event Form -->
    <div class="container">
        <div class="section">
            <h2>Create New Event</h2>
            <form action="add_event.php" method="POST">
                <label for="event_name">Event Name:</label>
                <input type="text" id="event_name" name="event_name" required>

                <label for="event_date">Event Date:</label>
                <input type="date" id="event_date" name="event_date" required>

                <label for="event_time">Event Time:</label>
                <input type="time" id="event_time" name="event_time" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>

                <button type="submit">Add Event</button>
            </form>
        </div>
    </div>

</body>
</html>
