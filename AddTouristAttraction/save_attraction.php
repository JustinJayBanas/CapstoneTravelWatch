<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'travelwatch');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['attraction-id'] ?? null;
$name = $_POST['name'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$proximity = $_POST['proximity'];

// Handle file upload
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["photo"]["name"]);
if (!empty($_FILES["photo"]["name"])) {
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
}

// If ID is provided, update the record; otherwise, insert a new record
if ($id) {
    // Update existing attraction
    $stmt = $conn->prepare("UPDATE attractions SET name = ?, latitude = ?, longitude = ?, proximity_size = ?, photo = ? WHERE id = ?");
    $stmt->bind_param("sddisi", $name, $latitude, $longitude, $proximity, $target_file, $id);
} else {
    // Insert new attraction
    $stmt = $conn->prepare("INSERT INTO attractions (name, latitude, longitude, proximity_size, photo) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sddis", $name, $latitude, $longitude, $proximity, $target_file);
}

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
