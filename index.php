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
    <style>
        /* Basic styling for the page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #1e5bb7;
            color: white;
            padding: 15px;
            text-align: center;
        }
        nav {
            background-color: #1e5bb7;
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }
        nav a {
            color: white;
            font-weight: bold;
            margin: 0 20px;
            text-decoration: none;
        }
        nav a:hover {
            text-decoration: underline;
        }
        main {
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #0098ff;
        }
        p {
            margin: 10px 0;
            font-size: 18px;
        }
        
    </style>
</head>
<body>

    <!-- Header -->
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

    <!-- Main Content -->
    <main>
        <h1>Welcome to the Event Management System</h1>
        <?php if ($isLoggedIn): ?>
            <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <p>Explore upcoming events, register to participate, and provide feedback.</p>
        <?php else: ?>
            <p>Please log in or register to access more features.</p>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Event Management System. All rights reserved.</p>
    </footer>

</body>
</html>
