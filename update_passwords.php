<?php
include 'db_connect.php';

// List of users with plain passwords to be updated
$users = [
    ['email' => 'admin1@example.com', 'password' => 'password1'],
    ['email' => 'admin2@example.com', 'password' => 'password2'],
    ['email' => 'admin3@example.com', 'password' => 'password3'],
    ['email' => 'admin4@example.com', 'password' => 'password4']
];

// Hash and update each password
foreach ($users as $user) {
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
    $email = $user['email'];

    $sql = "UPDATE Users SET password = '$hashed_password' WHERE email = '$email'";
    if ($conn->query($sql) === TRUE) {
        echo "Password updated for user: $email <br>";
    } else {
        echo "Error updating password for $email: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
