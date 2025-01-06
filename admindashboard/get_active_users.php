<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'travelwatch');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch active users (last updated within 10 minutes)
$sql = "SELECT username, attraction_name, location_lat, location_lng, last_updated 
        FROM visits 
        WHERE last_updated >= NOW() - INTERVAL 10 MINUTE";

$result = $conn->query($sql);

$activeUsers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $activeUsers[] = $row;
    }
}

$conn->close();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($activeUsers);
?>
