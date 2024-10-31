<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: /auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Fetch the list of locations
$locations = $conn->query("SELECT * FROM locations ORDER BY location_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Locations</title>
    <link rel="stylesheet" href="/assets/css/style2.css">
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

        /* Main Content */
        .container {
            padding: 20px;
            background-color: #333333;
        }

        .section {
            padding: 20px;
            border-radius: 5px;
            background-color: #252525;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #555555;
        }
        th {
            background-color: #0065a9;
            color: white;
        }

        /* Add button */
        .add-btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #0098ff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .add-btn:hover {
            background-color: #0065a9;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Manage Locations</h1>
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

    <!-- Locations Table -->
    <div class="container">
        <div class="section">
            <h2>Locations List</h2>
            <a href="add_location.php" class="add-btn">Add New Location</a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip Code</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>
                <?php if ($locations->num_rows > 0): ?>
                    <?php while ($location = $locations->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $location['location_id']; ?></td>
                        <td><?php echo htmlspecialchars($location['location_name']); ?></td>
                        <td><?php echo htmlspecialchars($location['address']); ?></td>
                        <td><?php echo htmlspecialchars($location['city']); ?></td>
                        <td><?php echo htmlspecialchars($location['state']); ?></td>
                        <td><?php echo htmlspecialchars($location['zip_code']); ?></td>
                        <td><?php echo htmlspecialchars($location['capacity']); ?></td>
                        <td>
                            <a href="edit_location.php?id=<?php echo $location['location_id']; ?>">Edit</a> |
                            <a href="delete_location.php?id=<?php echo $location['location_id']; ?>" onclick="return confirm('Are you sure you want to delete this location?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No locations found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

</body>
</html>
