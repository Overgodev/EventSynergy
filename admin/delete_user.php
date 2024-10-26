<?php
session_start();

// Check if the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: ../auth/login.php');
    exit;
}

include '../config/db_connect.php'; // Include database connection

// Check if the user ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']); // Get the user ID from URL and ensure it is an integer

    // Prevent admin self-deletion
    if ($user_id == $_SESSION['user_id']) {
        $_SESSION['error_message'] = "You cannot delete your own account.";
        header('Location: admin_users.php'); // Redirect back to user management page
        exit;
    }

    // Prepare delete query
    $delete_query = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($delete_query);

    if ($stmt) {
        $stmt->bind_param("i", $user_id); // Bind the user ID as an integer
        $stmt->execute();

        // Check if the user was successfully deleted
        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "User deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to delete user. User may not exist.";
        }
    } else {
        $_SESSION['error_message'] = "Error preparing the delete statement.";
    }

    $stmt->close(); // Close the prepared statement
} else {
    $_SESSION['error_message'] = "No user ID provided.";
}

$conn->close(); // Close the database connection

header('Location: admin_users.php'); // Redirect back to user management page
exit;
?>
