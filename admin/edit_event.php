<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: ../auth/login.html');
    exit;
}
include '../config/db_connect.php';

// Initialize event data variables
$event = [
    'event_id' => '',
    'event_name' => '',
    'event_date' => '',
    'event_time' => '',
    'location' => '',
    'description' => '',
    'sponsor_name' => '' // Added sponsor_name
];

// Check if the event ID is provided
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch event details for the given ID
    $stmt = $conn->prepare("SELECT * FROM Events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if event exists
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        // Ensure 'sponsor_name' is in the event array or set to NULL
        $event['sponsor_name'] = $event['sponsor_name'] ?? null;
    } else {
        echo "No event found with the provided ID.";
        exit;
    }
} else {
    echo "Event ID not provided.";
    exit;
}

// Fetch all sponsors from the database
$sponsors = $conn->query("SELECT sponsor_id, sponsor_name FROM Sponsors ORDER BY sponsor_name ASC");

// Update event details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $name = $_POST['event_name'];
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $sponsor_id = $_POST['sponsor_id']; // Get selected sponsor ID from POST

    // Determine the sponsor name based on the selected ID
    $sponsor_name = $sponsor_id == 0 ? NULL : $conn->query("SELECT sponsor_name FROM Sponsors WHERE sponsor_id = $sponsor_id")->fetch_assoc()['sponsor_name'];

    $sql = "UPDATE Events SET event_name = ?, event_date = ?, event_time = ?, location = ?, description = ?, sponsor_name = ? WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $date, $time, $location, $description, $sponsor_name, $event_id);

    if ($stmt->execute()) {
        header('Location: admin_events.php');
        exit;
    } else {
        echo "Error updating event: " . $stmt->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Edit Event</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="admin_events.php">Manage Events</a>
        <a href="admin_users.php">Manage Users</a>
    </nav>

    <!-- Edit Event Form -->
    <div class="container">
        <div class="section">
            <h2>Update Event</h2>
            <form action="edit_event.php?id=<?php echo $event['event_id']; ?>" method="POST">
                <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">

                <label for="event_name">Event Name:</label>
                <input type="text" id="event_name" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required>

                <label for="event_date">Event Date:</label>
                <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>" required>

                <label for="event_time">Event Time:</label>
                <input type="time" id="event_time" name="event_time" value="<?php echo $event['event_time']; ?>" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>

                <label for="sponsor_id">Sponsor:</label>
                <select id="sponsor_id" name="sponsor_id" required>
                <option value="0" <?php echo $event['sponsor_name'] ? '' : 'selected'; ?>>-</option>
                <?php if ($sponsors->num_rows > 0): ?>
                <?php while ($sponsor = $sponsors->fetch_assoc()): ?>
                <option value="<?php echo $sponsor['sponsor_id']; ?>" <?php echo (isset($event['sponsor_name']) && $sponsor['sponsor_name'] === $event['sponsor_name']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($sponsor['sponsor_name']); ?>
                </option>
                <?php endwhile; ?>
                <?php endif; ?>
                </select>


                <button type="submit">Update Event</button>
            </form>
        </div>
    </div>

</body>
</html>
