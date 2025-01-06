<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'travelwatch');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the attraction name and username from the GET request
$attractionName = isset($_GET['attraction_name']) ? $_GET['attraction_name'] : '';
$username = isset($_GET['username']) ? $_GET['username'] : '';

$response = ['time_spent' => 0];

if (!empty($attractionName) && !empty($username)) {
    // Query to fetch the time spent for the specific attraction by the user
    $stmt = $conn->prepare("SELECT time_spent FROM visits WHERE attraction_name = ? AND username = ? LIMIT 1");
    $stmt->bind_param("ss", $attractionName, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['time_spent'] = intval($row['time_spent']);
    }

    $stmt->close();
}

$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
