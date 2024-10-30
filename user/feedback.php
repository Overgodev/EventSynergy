<?php
include '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event'];
    $feedback = $_POST['feedback'];

    // SQL to insert feedback
    $sql = "INSERT INTO Feedback (event_id, feedback_text) VALUES ('$event_id', '$feedback')";

    if ($conn->query($sql) === TRUE) {
        echo "Thank you for your feedback!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
