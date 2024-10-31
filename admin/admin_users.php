<?php
session_start();

// Display success or error message
if (isset($_SESSION['success_message'])) {
    echo "<div class='success-message'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']); // Clear message after displaying
}

if (isset($_SESSION['error_message'])) {
    echo "<div class='error-message'>" . $_SESSION['error_message'] . "</div>";
    unset($_SESSION['error_message']); // Clear message after displaying
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/db_connect.php';

// Fetch users based on roles
$users = $conn->query("SELECT * FROM Users WHERE user_type = 'User' ORDER BY user_id ASC");
$admins = $conn->query("SELECT * FROM Users WHERE user_type = 'Admin' ORDER BY user_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/style.css">
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
            background-color: #1e1e1e; /* Dark grey for nav */
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
            background-color: #333333; /* Dark grey background for container */
        }

        .section {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #555555; /* Dark grey border */
            border-radius: 5px;
            background-color: #444444; /* Slightly lighter dark grey for section background */
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px; /* Added margin for spacing */
        }

        th, td {
            padding: 10px;
            border: 1px solid #555555; /* Dark grey border for table cells */
            text-align: left;
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


    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Manage Users</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="../auth/logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="/admin/admin.php">Dashboard</a>
        <a href="/admin/admin_events.php">Manage Events</a>
        <a href="/admin/admin_users.php">Manage Users</a>
        <a href="/admin/admin_sponsors.php">Manage Sponsors</a> <!-- New link for managing sponsors -->
        <a href="/admin/admin_locations.php">Manage Locations</a> <!-- New link for managing locations -->
    </nav>

    <!-- Admins Table -->
    <div class="container">
        <div class="section">
            <h2>Admin Users</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php while ($admin = $admins->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $admin['user_id']; ?></td>
                    <td><?php echo $admin['username']; ?></td>
                    <td><?php echo $admin['email']; ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $admin['user_id']; ?>">Edit</a> |
                        <a href="delete_user.php?id=<?php echo $admin['user_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Users Table -->
        <div class="section">
            <h2>Regular Users</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['user_id']; ?>">Edit</a> |
                        <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

</body>
</html>
