<?php
require '../config.php'; // Assuming this is the filename of your config

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the data from the POST request
$username = isset($_POST['username']) ? $_POST['username'] : '';
$attractionName = isset($_POST['attraction_name']) ? $_POST['attraction_name'] : '';
$timeSpent = isset($_POST['time_spent']) ? intval($_POST['time_spent']) : 0;
$locationLat = isset($_POST['location_lat']) ? floatval($_POST['location_lat']) : 0.0;
$locationLng = isset($_POST['location_lng']) ? floatval($_POST['location_lng']) : 0.0;

if (!empty($username) && !empty($attractionName)) {
    // Check if a visit record already exists for the user and attraction
    $stmt = $conn->prepare("SELECT id FROM visits WHERE username = ? AND attraction_name = ?");
    $stmt->bind_param("ss", $username, $attractionName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the existing record
        $stmt = $conn->prepare("UPDATE visits SET time_spent = ?, location_lat = ?, location_lng = ? WHERE username = ? AND attraction_name = ?");
        $stmt->bind_param("iddss", $timeSpent, $locationLat, $locationLng, $username, $attractionName);
    } else {
        // Insert a new record
        $stmt = $conn->prepare("INSERT INTO visits (username, attraction_name, time_spent, location_lat, location_lng) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssidd", $username, $attractionName, $timeSpent, $locationLat, $locationLng);
    }

    $stmt->execute();
    $stmt->close();
}

$conn->close();

echo "Success";
?>
