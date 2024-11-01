<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: ../auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Display success or error message
if (isset($_SESSION['success_message'])) {
    echo "<div class='success-message'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']); // Clear message after displaying
}

if (isset($_SESSION['error_message'])) {
    echo "<div class='error-message'>" . $_SESSION['error_message'] . "</div>";
    unset($_SESSION['error_message']); // Clear message after displaying
}

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
    <link rel="stylesheet" href="../assets/css/style2.css">
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

       
        /* Message styles */
        .success-message, .error-message {
            padding: 10px;
            margin: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .success-message {
            background-color: #4caf50; /* Green for success */
            color: white;
        }
        .error-message {
            background-color: #f44336; /* Red for error */
            color: white;
        }

        /* Container styles */
        .container {
            padding: 20px;
            background-color: #333333;
        }

        .section {
            margin: 20px 0;
            padding: 10px;
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
            border: 1px solid #555555;
            text-align: left;
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

        /* Add User button */
        .add-user-btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #0098ff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
        }
        .add-user-btn:hover {
            background-color: #0065a9;
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
        <a href="/admin/admin_sponsors.php">Manage Sponsors</a>
        <a href="/admin/admin_locations.php">Manage Locations</a>
    </nav>

    <!-- Add User Button -->
    <div class="container">
        <a href="add_user.php" class="add-user-btn">Add New User</a>
    </div>

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
