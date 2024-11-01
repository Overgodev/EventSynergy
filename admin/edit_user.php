<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: ../auth/login.html');
    exit;
}
include '../config/db_connect.php';

// Fetch user details for the given ID
$user = [
    'user_id' => '',
    'username' => '',
    'email' => '',
    'user_type' => ''
];

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM Users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "No user found with the provided ID.";
        exit;
    }
} else {
    echo "User ID not provided.";
    exit;
}

// Update user details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];

    $stmt = $conn->prepare("UPDATE Users SET username = ?, email = ?, user_type = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $username, $email, $user_type, $user_id);

    if ($stmt->execute()) {
        header('Location: admin_users.php');
        exit;
    } else {
        echo "Error updating user: " . $stmt->error;
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
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Edit User</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="/auth/logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="/admin/admin.php">Dashboard</a>
        <a href="/admin/admin_events.php">Manage Events</a>
        <a href="/admin/admin_users.php">Manage Users</a>
        <a href="/admin/admin_sponsors.php">Manage Sponsors</a> <!-- New link for managing sponsors -->
        <a href="/admin/admin_locations.php">Manage Locations</a> <!-- New link for managing locations -->
    </nav>

    <!-- Edit User Form -->
    <div class="container">
        <div class="section">
            <h2>Update User</h2>
            <form action="edit_user.php?id=<?php echo $user['user_id']; ?>" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

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
