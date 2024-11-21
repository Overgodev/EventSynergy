<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: /auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Check if location_id is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin_locations.php');
    exit;
}

$location_id = intval($_GET['id']);

// Delete the location
$stmt = $conn->prepare("DELETE FROM locations WHERE location_id = ?");
$stmt->bind_param('i', $location_id);

if ($stmt->execute()) {
    header('Location: admin_locations.php?message=Location deleted successfully');
    exit;
} else {
    header('Location: admin_locations.php?error=Failed to delete location');
    exit;
}
?>
