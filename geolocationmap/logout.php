<?php
$conn = new mysqli('localhost', 'root', '', 'travelwatch');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = isset($_POST['username']) ? $_POST['username'] : '';

if (!empty($username)) {
    // Delete the user's location
    $stmt = $conn->prepare("DELETE FROM user_locations WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
echo "Logout successful";
?>
