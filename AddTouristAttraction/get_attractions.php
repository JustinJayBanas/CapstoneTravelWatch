<?php
// Database connection details
$servername = "localhost";
$username = "root"; // Change if you have a different username
$password = ""; // Change if you have a password
$dbname = "travelwatch";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM attractions";
$result = $conn->query($sql);

$attractions = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $attractions[] = $row;
    }
}

// Return attractions as JSON
header('Content-Type: application/json');
echo json_encode($attractions);

$conn->close();
?>