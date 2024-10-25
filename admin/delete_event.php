<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];

    $sql = "DELETE FROM Events WHERE event_id = $event_id";

    if ($conn->query($sql) === TRUE) {
        echo "Event deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
