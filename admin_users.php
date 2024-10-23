<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: login.php');
    exit;
}
include 'db_connect.php'; // No change to the include path

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
    <link rel="stylesheet" href="style.css"> <!-- No change in CSS path -->
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Manage Users</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="admin_events.php">Manage Events</a>
        <a href="admin_users.php">Manage Users</a>
    </nav>

    <!-- Admins Table -->
    <div class="container">
        <div class="section">
            <h2>Admin Users</h2>
            <table border="1">
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
            <table border="1">
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
