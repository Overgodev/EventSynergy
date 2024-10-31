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
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Edit User</h1>
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
