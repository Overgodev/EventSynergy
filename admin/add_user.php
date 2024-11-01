<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: /auth/login.php'); // Adjusted path for login
    exit;
}

include '../config/db_connect.php'; // Adjusted path for DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $plain_password = $conn->real_escape_string($_POST['password']);
    $user_type = $conn->real_escape_string($_POST['user_type']);

    // Hash the password before storing it
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO Users (username, email, password, user_type) 
            VALUES ('$username', '$email', '$hashed_password', '$user_type')";

    if ($conn->query($sql) === TRUE) {
        header('Location: admin_users.php'); // Redirect to user management
        exit;
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
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
    <link rel="stylesheet" href="/assets/css/style2.css"> <!-- Adjusted path for CSS -->
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


        /* Container and Form Styles */
        .container {
            padding: 20px;
            background-color: #1e1e1e;
        }
        .section {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #555555;
            border-radius: 5px;
            background-color: #444444;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #ffffff;
            width: 150px; /* Set a consistent width */            
            margin-bottom: 0; /* Remove margin for better alignment */

        /* Container styling */
        }form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin: 0 auto; /* Center form on page */
        }

        form > div {
            display: flex;
            flex-direction: row-reverse;
            align-items: center;
            width: 100%;
            margin-bottom: 15px;
            justify-content: flex-end;
        }

        

        /* Input, textarea, and select styling */
        input,
        textarea,
        select {
            flex: 1; /* Allow inputs to take available space */
            padding: 10px;
            border: 1px solid #555555;
            border-radius: 4px;
            background-color: #333333;
            color: #ffffff;
            box-sizing: border-box; /* Ensures padding doesn't exceed container */
        }

        input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
            cursor: pointer;
            padding: 10px;
            margin: 10px 0 0px;
            border-radius: 5px;
            border: 1px solid #555555;;
        }
        

        /* Button Styling */
        button {
            padding: 10px 20px;
            background-color: #0098ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #0065a9;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Add User</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="/auth/logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="/admin/admin.php">Dashboard</a>
        <a href="/admin/admin_events.php">Manage Events</a>
        <a href="/admin/admin_users.php">Manage Users</a>
        <a href="/admin/admin_sponsors.php">Manage Sponsors</a>
        <a href="/admin/admin_locations.php">Manage Locations</a>
    </nav>

    <!-- Add User Form -->
    <div class="container">
        <div class="section">
            <h2>Create New User</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
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
