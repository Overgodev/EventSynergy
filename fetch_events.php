<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Add headers to allow CORS and set content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database connection
include 'db_connect.php';

// SQL to fetch all events
$sql = "SELECT event_id, event_name, event_date, event_time, location, description FROM Events";
$result = $conn->query($sql);

// Check if there are events
if ($result->num_rows > 0) {
    $events = array();

    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    // Return event data as JSON
    echo json_encode($events);
} else {
    // No events found
    echo json_encode([]);
}

// Close the connection
$conn->close();
?>
