<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$host = 'localhost';    // Database host
$username = 'root';     // Default MAMP username
$password = 'root';     // Default MAMP password
$database = 'EventManagement'; // Database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    // If there is an error, print it out
    die("Connection failed: " . $conn->connect_error);
}
?>
