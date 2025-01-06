<?php
$conn = new mysqli('localhost', 'root', '', 'travelwatch');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT user_id, latitude, longitude FROM user_locations";
$result = $conn->query($sql);

$locations = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
}

echo json_encode($locations);

$conn->close();
?>
