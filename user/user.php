<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'User') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/db_connect.php';

// Fetch all events for users
$events = $conn->query("SELECT * FROM Events ORDER BY event_date ASC, event_time ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Header styling */
        header {
            background-color: #1e5bb7; /* Dark blue */
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
            background-color: #1e5bb7; /* Dark blue */
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
        }
        .section {
            margin: 20px 0;
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
            background-color: #1e5bb7;
            color: white;
        }
        a {
            color: #1e5bb7;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .register-btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #4caf50;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .register-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>User Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="../auth/logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="user_dashboard.php">Dashboard</a>
        <a href="user.php">Browse Events</a>
        <a href="user_profile.php">Profile</a>
    </nav>

    <!-- Events Table -->
    <div class="container">
        <div class="section">
            <h2>Available Events</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Description</th>
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
                        <td><?php echo htmlspecialchars($event['description']); ?></td>
                        <td>
                            <form action="register_event.php" method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                                <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                                <button type="submit" class="register-btn">Register</button>
                            </form>
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
