<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: /auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Get the current date
$current_date = date('Y-m-d');

// Fetch only upcoming events with sponsors and max attendance
$events = $conn->query("
    SELECT 
        Events.event_id, 
        Events.event_name, 
        Events.event_date, 
        Events.event_time, 
        Locations.location_name,
        Events.location AS room, 
        Events.max_attendance, 
        Sponsors.sponsor_name
    FROM Events
    LEFT JOIN event_sponsors ON Events.event_id = event_sponsors.event_id
    LEFT JOIN sponsors ON event_sponsors.sponsor_id = sponsors.sponsor_id
    LEFT JOIN locations ON Events.location_id = locations.location_id
    WHERE Events.event_date >= '$current_date'
    ORDER BY Events.event_date ASC, Events.event_time ASC
");

// Check for database errors
if (!$events) {
    die("Database query failed: " . $conn->error);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="/assets/css/style2.css">
    <style>

        /* Container styles */
        .container {
            padding: 20px;
            background-color: #333333;
        }

        .section {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #555555;
            border-radius: 5px;
            background-color: #252525;
        }


        /* Responsive styles */
        @media (max-width: 768px) {
            .section {
                padding: 15px;
            }
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Manage Events</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="/auth/logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="/admin/admin.php">Dashboard</a>
        <a href="/admin/admin_events.php">Manage Events</a>
        <a href="/admin/admin_users.php">Manage Users</a>
        <a href="/admin/admin_sponsors.php">Manage Sponsors</a>
        <a href="/admin/admin_locations.php">Manage Locations</a>
    </nav>

    <!-- Events Table -->
    <div class="container">
        <div class="section">
            <h2>Upcoming Events</h2>
            <a href="add_event.php" class="add-btn">Add New Event</a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Room</th>
                    <th>Sponsor</th>
                    <th>Max People</th>
                    <th>Actions</th>
                </tr>
                <?php if ($events->num_rows > 0): ?>
                    <?php while ($event = $events->fetch_assoc()): ?>
                        <?php  if (!isset($event['location'])) 
                        error_log("Missing 'location' for event ID: {$event['event_id']}"); ?>
                    <tr>
                        <td><?php echo $event['event_id']; ?></td>
                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($event['event_date'])); ?></td>
                        <td><?php echo date('H:i', strtotime($event['event_time'])); ?></td>
                        <td><?php echo htmlspecialchars(($event['location_name'] ?? '-')); ?></td>
                        <td><?php echo htmlspecialchars($event['room'] ?: '-'); ?></td>
                        <td><?php echo htmlspecialchars($event['sponsor_name'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($event['max_attendance']); ?></td>
                        <td>
                            <a href="edit_event.php?id=<?php echo $event['event_id']; ?>">Edit</a> |
                            <a href="delete_event.php?id=<?php echo $event['event_id']; ?>" onclick="return confirmDelete()">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No upcoming events found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <script>
        // Confirm deletion of an event
        function confirmDelete() {
            return confirm('Are you sure you want to delete this event?');
        }
    </script>

</body>
</html>
