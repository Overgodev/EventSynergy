<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $plain_password = $_POST['password']; // Plain password from form

    // Hash the password before storing it
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
    $user_type = $_POST['user_type']; // 'Admin' or 'User'

    // Insert the new user with the hashed password
    $sql = "INSERT INTO Users (username, email, password, user_type) 
            VALUES ('$username', '$email', '$hashed_password', '$user_type')";

    if ($conn->query($sql) === TRUE) {
        echo "User registered successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
