<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $organizer_id = 1; // Placeholder organizer ID

    $sql = "INSERT INTO Events (event_name, event_date, event_time, location, description, organizer_id) 
            VALUES ('$event_name', '$event_date', '$event_time', '$location', '$description', '$organizer_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Event added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
