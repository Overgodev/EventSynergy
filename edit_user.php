<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: login.html');
    exit;
}
include 'db_connect.php';

// Fetch user details for the given ID
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user = $conn->query("SELECT * FROM Users WHERE user_id = $user_id")->fetch_assoc();
}

// Update user details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];

    $sql = "UPDATE Users SET username = '$username', email = '$email', user_type = '$user_type' WHERE user_id = $user_id";

    if ($conn->query($sql) === TRUE) {
        header('Location: admin_users.php');
    } else {
        echo "Error updating user: " . $conn->error;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Edit User</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="admin_events.php">Manage Events</a>
        <a href="admin_users.php">Manage Users</a>
    </nav>

    <!-- Edit User Form -->
    <div class="container">
        <div class="section">
            <h2>Update User</h2>
            <form action="edit_user.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

                <label for="user_type">Role:</label>
                <select id="user_type" name="user_type" required>
                    <option value="Admin" <?php if ($user['user_type'] == 'Admin') echo 'selected'; ?>>Admin</option>
                    <option value="User" <?php if ($user['user_type'] == 'User') echo 'selected'; ?>>User</option>
                </select>

                <button type="submit">Update User</button>
            </form>
        </div>
    </div>

</body>
</html>
