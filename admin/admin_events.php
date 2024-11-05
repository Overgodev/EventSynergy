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
    SELECT Events.event_id, Events.event_name, Events.event_date, Events.event_time, Events.location, Events.max_attendance, Sponsors.sponsor_name
    FROM Events
    LEFT JOIN event_sponsors ON Events.event_id = event_sponsors.event_id
    LEFT JOIN sponsors ON event_sponsors.sponsor_id = sponsors.sponsor_id
    WHERE Events.event_date >= '$current_date'
    ORDER BY event_date ASC, event_time ASC
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
        /* Header styling */
        header {
            background-color: #0065a9;
            color: white;
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
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-left: 15px;
        }
        header a:hover {
            text-decoration: underline;
        }

       

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

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #555555;
        }

        th {
            background-color: #0065a9;
            color: white;
        }

        /* Link styles */
        a {
            color: #0098ff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }

        /* Add button styles */
        .add-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #0098ff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .add-btn:hover {
            background-color: #0065a9;
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
                    <th>Sponsor</th>
                    <th>Max People</th>
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
                        <td colspan="8">No upcoming events found.</td>
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
