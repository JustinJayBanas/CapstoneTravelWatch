<?php
$conn = new mysqli('localhost', 'root', '', 'travelwatch');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT username, latitude, longitude FROM user_locations";
$result = $conn->query($sql);

$locations = [];
while ($row = $result->fetch_assoc()) {
    $locations[] = $row;
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($locations);
?>
