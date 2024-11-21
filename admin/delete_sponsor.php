<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: /auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Check if sponsor_id is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin_sponsors.php');
    exit;
}

$sponsor_id = intval($_GET['id']);

// Delete the sponsor
$stmt = $conn->prepare("DELETE FROM sponsors WHERE sponsor_id = ?");
$stmt->bind_param('i', $sponsor_id);

if ($stmt->execute()) {
    header('Location: admin_sponsors.php?message=Sponsor deleted successfully');
    exit;
} else {
    header('Location: admin_sponsors.php?error=Failed to delete sponsor');
    exit;
}
?>
