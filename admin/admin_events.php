<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: /auth/login.php'); // Adjusted path for login
    exit;
}
include '../config/db_connect.php'; // Adjusted path for DB connection

// Fetch all events
$events = $conn->query("SELECT * FROM Events ORDER BY event_date ASC, event_time ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="/assets/css/style.css"> <!-- Adjusted path for CSS -->
    <style>
        .container {
            margin: 20px;
        }
        .section {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f5f5f5;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            color: #0056b3;
        }
        .add-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #4caf50;
            color: #fff;
            border-radius: 5px;
        }
        .add-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Manage Events</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?> | <a href="/auth/logout.php">Logout</a></p> <!-- Adjusted path for logout -->
    </header>

    <!-- Navigation -->
    <nav>
        <a href="/admin/admin.php">Dashboard</a>
        <a href="/admin/admin_events.php">Manage Events</a>
        <a href="/admin/admin_users.php">Manage Users</a>
    </nav>

    <!-- Events Table -->
    <div class="container">
        <div class="section">
            <h2>All Events</h2>
            <a href="add_event.php" class="add-btn">Add New Event</a> <!-- Adjusted path -->
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
                <?php if ($events->num_rows > 0): ?>
                    <?php while ($event = $events->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $event['event_id']; ?></td>
                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($event['event_date'])); ?></td>
                        <td><?php echo date('H:i', strtotime($event['event_time'])); ?></td>
                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                        <td>
                            <a href="edit_event.php?id=<?php echo $event['event_id']; ?>">Edit</a> | <!-- Adjusted path -->
                            <a href="delete_event.php?id=<?php echo $event['event_id']; ?>" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a> <!-- Adjusted path -->
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No events found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

</body>
</html>
