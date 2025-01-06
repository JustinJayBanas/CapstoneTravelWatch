<?php
// Connect to the database
require '../config.php'; // Assuming this is the filename of your config

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data from the JSON body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the ID
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        http_response_code(400); // Bad Request
        echo "Invalid attraction ID.";
        exit();
    }

    $id = intval($data['id']);

    // Delete the attraction from the database
    $sql = "DELETE FROM attractions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Check if any row was affected
        if ($stmt->affected_rows > 0) {
            echo "Attraction deleted successfully.";
        } else {
            echo "Attraction not found or already deleted.";
        }
    } else {
        http_response_code(500); // Internal Server Error
        echo "Error deleting attraction: " . $conn->error;
    }

    $stmt->close();
} else {
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method.";
}

$conn->close();
?>
