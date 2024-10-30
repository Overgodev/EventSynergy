<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'User') {
    header('Location: ../auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $event_id = $_POST['event_id'];

    // Prepared statement to insert the user registration
    $stmt = $conn->prepare("INSERT INTO Attendees (user_id, event_id) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ii", $user_id, $event_id);
        if ($stmt->execute()) {
            // Successful registration message
            $message = "You have successfully registered for the event!";
            $redirect = true;
        } else {
            // Error during execution
            $message = "Error: " . $stmt->error;
            $redirect = false;
        }
        $stmt->close();
    } else {
        // Error preparing the statement
        $message = "Error preparing the statement: " . $conn->error;
        $redirect = false;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Header styling */
        header {
            background-color: #1e5bb7; /* Dark blue */
            color: white; /* White text */
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
        }
        header h1 {
            margin: 0;
        }
        header p {
            margin: 0;
        }
        header a {
            color: white; /* White text for logout link */
            text-decoration: none;
            font-weight: bold;
            margin-left: 15px;
        }
        header a:hover {
            text-decoration: underline;
        }

        /* Navigation bar styling */
        nav {
            background-color: #252525; /* Dark blue */
            display: flex;
            justify-content: center;
            padding: 10px 0;
            width: 100%;
            box-sizing: border-box;
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

        /* Container styling */
        .container {
            width: 80%;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #333333; /* Dark grey background */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #ffffff; /* White text */
        }

        /* Success/Error message styling */
        .message {
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Redirect notice styling */
        .redirect-notice {
            font-size: 18px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Event Registration</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="../auth/logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="user_dashboard.php">Dashboard</a>
        <a href="user.php">Browse Events</a>
        <a href="user_profile.php">Profile</a>
    </nav>

    <!-- Message Container -->
    <div class="container">
        <div class="message">
            <?php echo $message; ?>
        </div>
        <?php if ($redirect): ?>
            <div class="redirect-notice">
                Redirecting you back to the events page in 3 seconds...
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = 'user.php';
                }, 3000);
            </script>
        <?php else: ?>
            <div class="redirect-notice">
                <a href="user.php">Go back to events page</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
