<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: /auth/login.php');
    exit;
}
include '../config/db_connect.php';

// Fetch all events with sponsors
$events = $conn->query("
    SELECT Events.event_id, Events.event_name, Events.event_date, Events.event_time, Events.location, Sponsors.sponsor_name
    FROM Events
    LEFT JOIN event_sponsors ON Events.event_id = event_sponsors.event_id
    LEFT JOIN sponsors ON event_sponsors.sponsor_id = sponsors.sponsor_id
    ORDER BY event_date ASC, event_time ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* Header styling */
        header {
            background-color: #0065a9; /* Dark cyan */
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
            background-color: #1e1e1e; /* Dark cyan */
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

        /* Container styles */
        .container {
            padding: 20px;
            background-color: #333333; /* Dark grey background */
        }

        .section {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #555555; /* Dark grey border */
            border-radius: 5px;
            background-color: #444444; /* Dark grey for section background */
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px; /* Added margin for spacing */
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #555555; /* Dark grey border for table cells */
        }

        th {
            background-color: #0065a9; /* Dark cyan for table header */
            color: white; /* White text for header */
        }

        /* Link styles */
        a {
            color: #0098ff; /* Cyan for links */
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
            background-color: #0098ff; /* Cyan */
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .add-btn:hover {
            background-color: #0065a9; /* Dark cyan */
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
    </nav>

    <!-- Events Table -->
    <div class="container">
        <div class="section">
            <h2>All Events</h2>
            <a href="add_event.php" class="add-btn">Add New Event</a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Sponsor</th> <!-- New Sponsor column -->
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
                        <td><?php echo htmlspecialchars($event['sponsor_name'] ?? '-'); ?></td> <!-- Display sponsor or '-' if no sponsor -->
                        <td>
                            <a href="edit_event.php?id=<?php echo $event['event_id']; ?>">Edit</a> |
                            <a href="delete_event.php?id=<?php echo $event['event_id']; ?>" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No events found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

</body>
</html>
