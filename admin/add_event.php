<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: /auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Fetch sponsors for the dropdown
$sponsor_result = $conn->query("SELECT sponsor_id, sponsor_name FROM sponsors");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['event_name']);
    $date = $conn->real_escape_string($_POST['event_date']);
    $time = $conn->real_escape_string($_POST['event_time']);
    $location = $conn->real_escape_string($_POST['location']);
    $description = $conn->real_escape_string($_POST['description']);
    $sponsor_id = $conn->real_escape_string($_POST['sponsor_id']);  // New sponsor ID

    // Insert event details
    $sql = "INSERT INTO events (event_name, event_date, event_time, location, description) 
            VALUES ('$name', '$date', '$time', '$location', '$description')";

    if ($conn->query($sql) === TRUE) {
        // Get the ID of the newly created event
        $event_id = $conn->insert_id;

        // Insert into event_sponsors table
        if ($sponsor_id) {
            $sql_sponsor = "INSERT INTO event_sponsors (event_id, sponsor_id) VALUES ('$event_id', '$sponsor_id')";
            $conn->query($sql_sponsor);
        }

        header('Location: admin_events.php');
        exit;
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
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
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* Styling here */
        .container {
            margin: 20px;
        }
        .section {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            max-width: 500px;
            margin: auto;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="date"], input[type="time"], textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Add Event</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="/admin/admin.php">Dashboard</a>
        <a href="/admin/admin_events.php">Manage Events</a>
        <a href="/admin/admin_users.php">Manage Users</a>
    </nav>

    <!-- Add Event Form -->
    <div class="container">
        <div class="section">
            <h2>Create New Event</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
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

                <!-- New Sponsor Selection -->
                <label for="sponsor_id">Sponsor:</label>
                <select id="sponsor_id" name="sponsor_id">
                    <option value="">-- Select Sponsor --</option>
                    <?php while ($sponsor = $sponsor_result->fetch_assoc()): ?>
                        <option value="<?php echo $sponsor['sponsor_id']; ?>"><?php echo $sponsor['sponsor_name']; ?></option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Add Event</button>
            </form>
        </div>
    </div>
</body>
</html>
