<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: login.html');
    exit;
}
include 'db_connect.php';

// Fetch event and user counts
$event_count = $conn->query("SELECT COUNT(*) AS total FROM Events")->fetch_assoc()['total'];
$user_count = $conn->query("SELECT COUNT(*) AS total FROM Users")->fetch_assoc()['total'];
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="admin_events.php">Manage Events</a>
        <a href="admin_users.php">Manage Users</a>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="section">
            <h2>Overview</h2>
            <p>Total Events: <?php echo $event_count; ?></p>
            <p>Total Users: <?php echo $user_count; ?></p>
        </div>
    </div>

</body>
</html>
