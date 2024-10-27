<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'User') {
    header('Location: ../auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Get current user ID
$user_id = $_SESSION['user_id'];

// Fetch registered events for the user
$registered_query = "
    SELECT e.event_id, e.event_name, e.event_date, e.event_time, e.location, e.description
    FROM events e
    INNER JOIN attendees a ON e.event_id = a.event_id
    WHERE a.user_id = ?
    ORDER BY e.event_date ASC, e.event_time ASC";

$registered_stmt = $conn->prepare($registered_query);
if (!$registered_stmt) {
    die("Error preparing the statement: " . $conn->error);
}
$registered_stmt->bind_param("i", $user_id);
$registered_stmt->execute();
$registered_result = $registered_stmt->get_result();

// Prepare list of registered events
$registered_events = [];
if ($registered_result->num_rows > 0) {
    while ($row = $registered_result->fetch_assoc()) {
        $registered_events[] = $row;
    }
}

// Fetch all events
$all_events_query = "
    SELECT event_id, event_name, event_date, event_time, location, description
    FROM events
    ORDER BY event_date ASC, event_time ASC";

$all_events_result = $conn->query($all_events_query);

// Prepare list of all events
$all_events = [];
if ($all_events_result->num_rows > 0) {
    while ($row = $all_events_result->fetch_assoc()) {
        $all_events[] = $row;
    }
}

// Total number of registered events
$total_registered_events = count($registered_events);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
            background-color: #1e5bb7; /* Dark blue */
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

        /* Dashboard container styling */
        .container {
            width: 80%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #333333; /* White background for the main container */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Light gray shadow for container */
        }

        /* Summary section styling */
        .summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .summary div {
            padding: 15px;
            background-color: #282828; /* Light gray background for summary box */
            border-color : #444444;
            border-radius: 5px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        /* Section heading styling */
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #f4f4f4; /* Dark blue for section headings */
        }

        /* Horizontal event box container */
        .horizontal-container {
            display: flex;
            overflow-x: auto;
            gap: 20px;
            padding: 20px 0;
            scroll-behavior: smooth;
        }

        /* Event box styling */
        .event-box {
            flex: 0 0 auto;
            width: 250px;
            border: 1px solid #555555; /* Dark blue border */
            padding: 15px;
            border-radius: 8px;
            background-color: #444444; /* Light gray background */
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }
        .event-box h3 {
            margin: 0 0 10px;
            color: #0098ff; /* Dark blue for event title */
        }
        .event-box p {
            margin: 5px 0;
        }

        /* No events message styling */
        .no-events-message {
            font-size: 20px;
            color: #f4f4f4; /* Gray text for no event message */
            text-align: center;
            margin-top: 20px;
        }

    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>User Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="../auth/logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="user_dashboard.php">Dashboard</a>
        <a href="user.php">Browse Events</a>
        <a href="user_profile.php">Profile</a>
    </nav>

    <!-- Dashboard Content -->
    <div class="container">
        <h2>Dashboard Overview</h2>

        <!-- Summary Section -->
        <div class="summary">
            <div>Total Events Registered: <?php echo $total_registered_events; ?></div>
        </div>

        <!-- Registered Events Section -->
        <h2>Upcoming Registered Events</h2>
        <?php if ($total_registered_events > 0): ?>
            <div class="horizontal-container">
                <?php foreach ($registered_events as $event): ?>
                    <div class="event-box">
                        <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                        <p><strong>Date:</strong> <?php echo date('Y-m-d', strtotime($event['event_date'])); ?></p>
                        <p><strong>Time:</strong> <?php echo date('H:i', strtotime($event['event_time'])); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-events-message">You are not registered for any events.</div>
        <?php endif; ?>

        <!-- All Events Section -->
        <h2>All Events</h2>
        <?php if (!empty($all_events)): ?>
            <div class="horizontal-container">
                <?php foreach ($all_events as $event): ?>
                    <div class="event-box">
                        <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                        <p><strong>Date:</strong> <?php echo date('Y-m-d', strtotime($event['event_date'])); ?></p>
                        <p><strong>Time:</strong> <?php echo date('H:i', strtotime($event['event_time'])); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-events-message">No events available.</div>
        <?php endif; ?>
    </div>

</body>
</html>
