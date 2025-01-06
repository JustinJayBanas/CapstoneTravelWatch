<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'travelwatch');

// Check for connection errors
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Get the logged-in username and attraction name
$username = $_SESSION['username'];
$attraction_name = isset($_GET['attraction_name']) ? $_GET['attraction_name'] : ''; // Get attraction name

// Check if attraction_name is provided
if (empty($attraction_name)) {
    echo json_encode(['error' => 'Attraction name is required']);
    exit;
}

// Query to fetch time spent from the database
$sql = "SELECT time_spent FROM visits WHERE username = ? AND attraction_name = ?";
$stmt = $conn->prepare($sql);

// Error handling for the prepared statement
if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare the SQL statement']);
    exit;
}

// Bind parameters and execute
$stmt->bind_param('ss', $username, $attraction_name);
if (!$stmt->execute()) {
    echo json_encode(['error' => 'Failed to execute the SQL statement']);
    exit;
}

$stmt->bind_result($time_spent);

// Check if a result was found
if ($stmt->fetch()) {
    echo json_encode(['time_spent' => $time_spent]);
} else {
    // No record found for this user and attraction
    echo json_encode(['time_spent' => 0]); // Return 0 if no time spent record
}

$stmt->close();
$conn->close();
?>
