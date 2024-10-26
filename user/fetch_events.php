<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Add headers to allow CORS and set content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database connection
include 'config/db_connect.php';

// Check database connection
if (!$conn) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . mysqli_connect_error()
    ]);
    exit;
}

// SQL to fetch all events, including the new fields
$sql = "SELECT event_id, event_name, event_date, event_time, location, description, organizer_id, location_id FROM Events";
$result = $conn->query($sql);

// Check if query was successful
if ($result) {
    $events = array();

    // Check if there are events
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Sanitize output data
            $row['event_name'] = htmlspecialchars($row['event_name']);
            $row['location'] = htmlspecialchars($row['location']);
            $row['description'] = htmlspecialchars($row['description']);

            $events[] = $row;
        }

        // Return event data as JSON
        echo json_encode([
            'success' => true,
            'data' => $events
        ]);
    } else {
        // No events found
        echo json_encode([
            'success' => true,
            'data' => [],
            'message' => 'No events found.'
        ]);
    }
} else {
    // Query error
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching events: ' . $conn->error
    ]);
}

// Close the connection
$conn->close();
?>
