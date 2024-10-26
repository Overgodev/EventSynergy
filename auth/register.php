<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering to prevent header issues
ob_start();

// Include the database connection file from the 'config' folder
include '../config/db_connect.php';

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data and apply basic sanitization
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if 'user_type' is set and valid
    $user_type = 'User'; // Default to 'User'

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO Users (username, email, password, user_type) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $username, $email, $password, $user_type);

        // Execute the statement and check if it was successful
        if ($stmt->execute()) {
            echo "Registration successful. Redirecting to the home page in 3 seconds...";
            header("Refresh:3; url=../index.php");
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}

// End output buffering
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System - Register</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Header styling */
        header {
            background-color: #1e5bb7; /* Dark blue */
            color: white; /* White text */
            padding: 10px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        header h1 {
            margin: 0;
        }
        /* Navigation bar styling */
        nav {
            background-color: #1e5bb7; /* Dark blue */
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }
        nav a {
            color: white; /* White text */
            font-weight: bold;
            margin: 0 20px;
            text-decoration: none; /* No underline */
            font-size: 18px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        /* Container styles */
        .container {
            padding: 20px;
        }
        .section {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label, input, button {
            margin-bottom: 10px;
        }
        input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        button {
            padding: 10px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Event Management System</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="../index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="../user/events.html">Browse Events</a>
        <a href="../user/feedback.html">Feedback</a>
    </nav>
    
    <!-- Registration Form -->
    <div class="container">
        <div class="section" id="registration">
            <h2>Register</h2>
            <form action="register.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Register</button>
            </form>
        </div>
    </div>

</body>
</html>
