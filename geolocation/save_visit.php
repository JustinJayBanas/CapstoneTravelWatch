<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'travelwatch');

// Check for connection errors
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the data sent via POST
$username = $_POST['username'];
$attraction_name = $_POST['attraction_name'];
$time_spent = $_POST['time_spent'];
$location_lat = $_POST['location_lat'];
$location_lng = $_POST['location_lng'];

// Validate inputs (example: ensure time_spent is a number, lat and lng are floats)
if (!is_numeric($time_spent) || !is_numeric($location_lat) || !is_numeric($location_lng)) {
    echo json_encode(['error' => 'Invalid input data']);
    exit;
}

// Prepare the SQL query to insert or update the visit
$sql = "INSERT INTO visits (username, attraction_name, time_spent, location_lat, location_lng)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        time_spent = time_spent + VALUES(time_spent),  -- Accumulate time spent
        location_lat = VALUES(location_lat),  -- Update location latitude
        location_lng = VALUES(location_lng),  -- Update location longitude
        visit_time = CURRENT_TIMESTAMP";  // Update visit time to current timestamp

$stmt = $conn->prepare($sql);

// Bind the parameters (ssidd means: string, string, integer, float, float)
$stmt->bind_param("ssidd", $username, $attraction_name, $time_spent, $location_lat, $location_lng);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Visit saved or updated successfully.']);
} else {
    echo json_encode(['error' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
