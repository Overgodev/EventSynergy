<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id']; // Retrieve from session or input
    $event_id = $_POST['event_id'];

    // SQL to register user for an event
    $sql = "INSERT INTO Attendees (user_id, event_id) VALUES ('$user_id', '$event_id')";

    if ($conn->query($sql) === TRUE) {
        echo "You have successfully registered for the event!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
