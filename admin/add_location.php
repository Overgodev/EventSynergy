<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: /auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location_name = $conn->real_escape_string($_POST['location_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $zip_code = $conn->real_escape_string($_POST['zip_code']);
    $max_capacity = $conn->real_escape_string($_POST['max_capacity']); // For storing maximum capacity

    // Insert location details into the database
    $sql = "INSERT INTO Locations (location_name, address, city, state, zip_code, capacity) 
        VALUES ('$location_name', '$address', '$city', '$state', '$zip_code', '$capacity')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Location added successfully!";
        header('Location: admin_locations.php');
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
    <title>Add Location</title>
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

        /* Navigation bar styling */
        nav {
            background-color: #0065a9;
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }
        nav a {
            color: #A7E4FF;
            font-weight: bold;
            margin: 0 20px;
            text-decoration: none;
            font-size: 18px;
        }
        nav a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        /* Container and Form Styles */
        .container {
            padding: 20px;
            background-color: #333333;
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
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #333333;
            color: #ffffff;
        }

        /* Button Styling */
        button {
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #45a049;
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
        <h1>Add Location</h1>
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

    <!-- Add Location Form -->
    <div class="container">
        <div class="section">
            <h2>Create New Location</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="add_location.php" method="POST">
                <label for="location_name">Location Name:</label>
                <input type="text" id="location_name" name="location_name" required>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>

                <label for="city">City:</label>
                <input type="text" id="city" name="city" required>

                <label for="state">State:</label>
                <input type="text" id="state" name="state" required>

                <label for="zip_code">Zip Code:</label>
                <input type="text" id="zip_code" name="zip_code" required>

                <label for="max_capacity">Max Capacity:</label>
                <input type="number" id="max_capacity" name="max_capacity" required>

                <button type="submit">Add Location</button>
            </form>
        </div>
    </div>

</body>
</html>
