<?php
session_start(); // Start session at the beginning
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Event Management System</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <!-- Check if user is logged in -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Welcome, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>
            
            <!-- Show different options based on user role -->
            <?php if ($_SESSION['user_type'] == 'Admin'): ?>
                <a href="admin.php">Admin Panel</a>
            <?php elseif ($_SESSION['user_type'] == 'User'): ?>
                <a href="user.php">User Page</a>
            <?php endif; ?>
        <?php else: ?>
            <!-- If user is not logged in, show the Login link -->
            <a href="login.html">Login</a>
        <?php endif; ?>

        <!-- Common links for all users -->
        <a href="index.php">Home</a>
        <a href="register.html">Register</a>
        <a href="events.html">Browse Events</a>
        <a href="feedback.html">Feedback</a>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="section">
            <h2>Welcome to the Event Management System</h2>
            <p>Explore upcoming events, register to participate, and provide feedback on your experiences.</p>
            
            <!-- Dynamic content based on login status -->
            <?php
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION['user_type'] == 'Admin') {
                    echo "<p>As an admin, you can manage events and users.</p>";
                } else {
                    echo "<p>As a user, you can browse and register for events.</p>";
                }
            } else {
                echo "<p>Please log in to access additional features.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Event Management System. All rights reserved.</p>
    </footer>

</body>
</html>
