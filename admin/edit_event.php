<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: ../auth/login.html');
    exit;
}
include '../config/db_connect.php';

// Initialize event data variables
$event = [
    'event_id' => '',
    'event_name' => '',
    'event_date' => '',
    'event_time' => '',
    'location_id' => null,
    'description' => '',
    'max_attendance' => '',
    'sponsors' => []
];

// Check if the event ID is provided
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch event details for the given ID
    $stmt = $conn->prepare("
        SELECT Events.*, event_sponsors.sponsor_id 
        FROM Events 
        LEFT JOIN event_sponsors ON Events.event_id = event_sponsors.event_id 
        WHERE Events.event_id = ?
    ");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if event exists
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        $event['sponsors'] = [];
        while ($row = $result->fetch_assoc()) {
            $event['sponsors'][] = $row['sponsor_id'];
        }
    } else {
        echo "No event found with the provided ID.";
        exit;
    }
} else {
    echo "Event ID not provided.";
    exit;
}

// Fetch all sponsors from the database
$sponsors = $conn->query("SELECT sponsor_id, sponsor_name FROM Sponsors ORDER BY sponsor_name ASC");

// Fetch all locations from the database
$locations = $conn->query("SELECT location_id, location_name FROM Locations ORDER BY location_name ASC");

// Update event details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $name = $_POST['event_name'];
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $location_id = $_POST['location_id'];
    $description = $_POST['description'];
    $max_attendance = $_POST['max_attendance'];
    $selected_sponsors = isset($_POST['sponsor_id']) ? $_POST['sponsor_id'] : [];

    // Update event details in Events table
    $sql = "UPDATE Events SET event_name = ?, event_date = ?, event_time = ?, location_id = ?, description = ?, max_attendance = ? WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisi", $name, $date, $time, $location_id, $description, $max_attendance, $event_id);

    if ($stmt->execute()) {
        // Update sponsors in event_sponsors table
        $conn->query("DELETE FROM event_sponsors WHERE event_id = $event_id");
        foreach ($selected_sponsors as $sponsor_id) {
            $stmt_sponsor = $conn->prepare("INSERT INTO event_sponsors (event_id, sponsor_id) VALUES (?, ?)");
            $stmt_sponsor->bind_param("ii", $event_id, $sponsor_id);
            $stmt_sponsor->execute();
        }

        header('Location: admin_events.php');
        exit;
    } else {
        echo "Error updating event: " . $stmt->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
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
        /* Container styling */
        form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin: 0 auto; /* Center form on page */
        }

        /* Label and input alignment */
        form > div {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin-bottom: 15px;
        }

        /* Input, textarea, and select styling */
        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #333333;
            color: #ffffff;
            box-sizing: border-box; /* Ensures padding doesn't exceed container */
        }

        /* Optional label styling */
        label {
            font-weight: bold;
            margin-bottom: 5px;
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
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Edit Event</h1>
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

    <!-- Edit Event Form -->
    <div class="container">
        <div class="section">
            <h2>Update Event</h2>
            <form action="edit_event.php?id=<?php echo $event['event_id']; ?>" method="POST">
                <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">

                <label for="event_name">Event Name:</label>
                <input type="text" id="event_name" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required>

                <label for="event_date">Event Date:</label>
                <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>" required>

                <label for="event_time">Event Time:</label>
                <input type="time" id="event_time" name="event_time" value="<?php echo $event['event_time']; ?>" required>

                <label for="location_id">Location:</label>
                <select id="location_id" name="location_id" required>
                    <?php while ($location = $locations->fetch_assoc()): ?>
                        <option value="<?php echo $location['location_id']; ?>" <?php echo ($event['location_id'] == $location['location_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($location['location_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="max_attendance">Max People:</label>
                <input type="number" id="max_attendance" name="max_attendance" value="<?php echo htmlspecialchars($event['max_attendance']); ?>" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>

                <label for="sponsor_id">Sponsors:</label>
                <?php if ($sponsors->num_rows > 0): ?>
                    <?php while ($sponsor = $sponsors->fetch_assoc()): ?>
                        <div>
                            <input type="checkbox" id="sponsor_<?php echo $sponsor['sponsor_id']; ?>" name="sponsor_id[]" value="<?php echo $sponsor['sponsor_id']; ?>" <?php echo in_array($sponsor['sponsor_id'], $event['sponsors']) ? 'checked' : ''; ?>>
                            <label for="sponsor_<?php echo $sponsor['sponsor_id']; ?>"><?php echo htmlspecialchars($sponsor['sponsor_name']); ?></label>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>

                <button type="submit">Update Event</button>
            </form>
        </div>
    </div>

</body>
</html>
