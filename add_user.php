<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: login.html');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connect.php';
    $username = $_POST['username'];
    $email = $_POST['email'];
    $plain_password = $_POST['password'];
    $user_type = $_POST['user_type'];

    // Hash the password before storing it
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO Users (username, email, password, user_type) 
            VALUES ('$username', '$email', '$hashed_password', '$user_type')";

    if ($conn->query($sql) === TRUE) {
        header('Location: admin_users.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Add User</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="admin_events.php">Manage Events</a>
        <a href="admin_users.php">Manage Users</a>
    </nav>

    <!-- Add User Form -->
    <div class="container">
        <div class="section">
            <h2>Create New User</h2>
            <form action="add_user.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="user_type">Role:</label>
                <select id="user_type" name="user_type" required>
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                </select>

                <button type="submit">Add User</button>
            </form>
        </div>
    </div>

</body>
</html>
