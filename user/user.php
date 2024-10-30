<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'User') {
    header('Location: ../auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Get current user ID
$user_id = $_SESSION['user_id'];

// Fetch events that the user has not registered for
$query = "
    SELECT e.event_id, e.event_name, e.event_date, e.event_time, e.location, e.description 
    FROM events e
    LEFT JOIN attendees a ON e.event_id = a.event_id AND a.user_id = ?
    WHERE a.user_id IS NULL
    ORDER BY e.event_date ASC, e.event_time ASC";

$stmt = $conn->prepare($query);

// Check if the prepare statement failed
if (!$stmt) {
    die("Error preparing the statement: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Prepare events list
$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Events</title>
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
            margin: 30px auto;
            padding: 20px;
            background-color: #333333; /* Dark grey background */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Heading styling */
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #ffffff; /* White text for headers */
        }

        /* Event card styles */
        .event-card {
            border: 1px solid #555555; /* Dark grey border */
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #444444; /* Dark grey background */
        }
        .event-card h3 {
            margin: 0 0 10px;
            font-size: 20px;
            color: #0098ff; /* Cyan for event titles */
        }
        .event-card p {
            margin: 5px 0;
            color: #ffffff; /* White text for event descriptions */
        }

        /* Register button styling */
        .register-btn {
            padding: 10px 15px;
            background-color: #4caf50; /* Green */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        .register-btn:hover {
            background-color: #45a049; /* Darker green on hover */
        }

    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Browse Events</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="../auth/logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="user_dashboard.php">Dashboard</a>
        <a href="user.php">Browse Events</a>
        <a href="user_profile.php">Profile</a>
    </nav>

    <!-- Events List -->
    <div class="container">
        <h2>Available Events</h2>
         
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                    <p><strong>Date:</strong> <?php echo date('Y-m-d', strtotime($event['event_date'])); ?></p>
                    <p><strong>Time:</strong> <?php echo date('H:i', strtotime($event['event_time'])); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
                    <form action="register_event.php" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                        <button type="submit" class="register-btn">Register for Event</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No available events to register.</p>
        <?php endif; ?>
    </div>

</body>
</html>
