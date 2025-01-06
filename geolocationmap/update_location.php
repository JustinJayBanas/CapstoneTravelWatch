<?php
$conn = new mysqli('localhost', 'root', '', 'travelwatch');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = isset($_POST['username']) ? $_POST['username'] : '';
$latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : 0.0;
$longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : 0.0;

if (!empty($username)) {
    $stmt = $conn->prepare("INSERT INTO user_locations (username, latitude, longitude) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE latitude = ?, longitude = ?");
    $stmt->bind_param("sddds", $username, $latitude, $longitude, $latitude, $longitude);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
echo "Location updated";
?>