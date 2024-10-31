<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: /auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sponsor_name = $conn->real_escape_string($_POST['sponsor_name']);
    $contact_person = $conn->real_escape_string($_POST['contact_person']);
    $contact_email = $conn->real_escape_string($_POST['contact_email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);

    // Insert sponsor details into the database
    $sql = "INSERT INTO Sponsors (sponsor_name, contact_person, contact_email, phone_number ) 
            VALUES ('$sponsor_name', '$contact_person', '$contact_email', '$phone_number')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Sponsor added successfully!";
        header('Location: admin_sponsors.php');
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
    <title>Add Sponsor</title>
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
        input[type="text"], input[type="email"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #333333;
            color: #ffffff;
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
        <h1>Add Sponsor</h1>
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

    <!-- Add Sponsor Form -->
    <div class="container">
        <div class="section">
            <h2>Create New Sponsor</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="add_sponsor.php" method="POST">
                <label for="sponsor_name">Sponsor Name:</label>
                <input type="text" id="sponsor_name" name="sponsor_name" required>

                <label for="contact_person">Contact Person:</label>
                <input type="text" id="contact_person" name="contact_person" required>

                <label for="contact_email">Contact Email:</label>
                <input type="email" id="contact_email" name="contact_email" required>

                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" required>

                <button type="submit">Add Sponsor</button>
            </form>
        </div>
    </div>

</body>
</html>
