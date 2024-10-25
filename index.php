<?php
session_start(); // Start session at the beginning
include 'config/db_connect.php'; // Adjusted path for database connection

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userType = $isLoggedIn ? $_SESSION['user_type'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Event Management System</title>
    <link rel="stylesheet" href="/assets/css/style.css"> <!-- Adjusted path for CSS -->
</head>
<body>
    <header>
        <nav>
            <!-- Navigation Links -->
            <?php if ($isLoggedIn): ?>
                <?php if ($userType == 'Admin'): ?>
                    <a href="/admin/admin.php">Admin Panel</a>
                <?php else: ?>
                    <a href="/user/user.php">User Dashboard</a>
                <?php endif; ?>
                <a href="/index.php">Home</a>
                <a href="/auth/logout.php">Logout</a>
            <?php else: ?>
                <a href="/index.php">Home</a>
                <a href="/auth/login.php">Login</a>
                <a href="/auth/register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h1>Welcome to the Event Management System</h1>
        <?php if ($isLoggedIn): ?>
            <p>Hello, <?php echo $_SESSION['username']; ?>!</p>
            <p>Explore upcoming events, register to participate, and provide feedback.</p>
        <?php else: ?>
            <p>Please log in or register to access more features.</p>
        <?php endif; ?>
    </main>
</body>
</html>
