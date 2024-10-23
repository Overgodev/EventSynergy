<?php
include 'db_connect.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if 'user_type' is set and valid
    if (isset($_POST['user_type']) && ($_POST['user_type'] == 'User' || $_POST['user_type'] == 'Admin')) {
        $user_type = $_POST['user_type'];
    } else {
        $user_type = 'User'; // Set a default value
    }

    // Insert into database
    $sql = "INSERT INTO Users (username, email, password, user_type) 
            VALUES ('$username', '$email', '$password', '$user_type')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful. Redirecting to the home page in 3 seconds...";
        // Wait for 3 seconds and redirect to the homepage
        header("Refresh:3; url=index.php");
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>
