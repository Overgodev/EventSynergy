<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: login.html');
    exit;
}
include 'db_connect.php';

// Fetch all events
$events = $conn->query("SELECT * FROM Events");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Manage Events</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="admin_events.php">Manage Events</a>
        <a href="admin_users.php">Manage Users</a>
    </nav>

    <!-- Events Table -->
    <div class="container">
        <div class="section">
            <h2>All Events</h2>
            <a href="add_event.php">Add New Event</a>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
                <?php while ($event = $events->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $event['event_id']; ?></td>
                    <td><?php echo $event['event_name']; ?></td>
                    <td><?php echo $event['event_date']; ?></td>
                    <td><?php echo $event['event_time']; ?></td>
                    <td><?php echo $event['location']; ?></td>
                    <td>
                        <a href="edit_event.php?id=<?php echo $event['event_id']; ?>">Edit</a> |
                        <a href="delete_event.php?id=<?php echo $event['event_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

</body>
</html>
