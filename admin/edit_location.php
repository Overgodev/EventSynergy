<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: /auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Check if location_id is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin_locations.php');
    exit;
}

$location_id = intval($_GET['id']);

// Fetch the current location details
$stmt = $conn->prepare("SELECT * FROM locations WHERE location_id = ?");
$stmt->bind_param('i', $location_id);
$stmt->execute();
$result = $stmt->get_result();
$location = $result->fetch_assoc();

if (!$location) {
    header('Location: admin_locations.php');
    exit;
}

// Update location details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location_name = $_POST['location_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip_code'];
    $capacity = intval($_POST['capacity']);

    $update_stmt = $conn->prepare("UPDATE locations SET location_name = ?, address = ?, city = ?, state = ?, zip_code = ?, capacity = ? WHERE location_id = ?");
    $update_stmt->bind_param('ssssssi', $location_name, $address, $city, $state, $zip_code, $capacity, $location_id);

    if ($update_stmt->execute()) {
        header('Location: admin_locations.php?message=Location updated successfully');
        exit;
    } else {
        $error = "Failed to update location. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Location</title>
    <link rel="stylesheet" href="/assets/css/style2.css">
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Edit Location</h1>
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

    <!-- Form to Edit Location -->
    <div class="container">
        <div class="section">
            <h2>Edit Location</h2>
            <form method="POST">
                <label for="location_name">Location Name:</label><br>
                <input type="text" id="location_name" name="location_name" value="<?php echo htmlspecialchars($location['location_name']); ?>" required><br><br>

                <label for="address">Address:</label><br>
                <textarea id="address" name="address" required><?php echo htmlspecialchars($location['address']); ?></textarea><br><br>

                <label for="city">City:</label><br>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($location['city']); ?>" required><br><br>

                <label for="state">State:</label><br>
                <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($location['state']); ?>" required><br><br>

                <label for="zip_code">Zip Code:</label><br>
                <input type="text" id="zip_code" name="zip_code" value="<?php echo htmlspecialchars($location['zip_code']); ?>" required><br><br>

                <label for="capacity">Capacity:</label><br>
                <input type="number" id="capacity" name="capacity" value="<?php echo htmlspecialchars($location['capacity']); ?>" required><br><br>

                <!-- Update and Cancel Buttons -->
                <button type="submit" class="add-btn">Update Location</button>
                <a href="admin_locations.php" class="add-btn">Cancel</a>
            </form>

            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
