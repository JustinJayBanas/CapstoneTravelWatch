<?php
// Connect to the database
require '../config.php'; // Assuming this is the filename of your config

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

// Handle Tourist Attraction Form Submission
if (isset($_POST['attraction_id']) && isset($_POST['number_of_tourists'])) {
    $attraction_id = intval($_POST['attraction_id']);
    $number_of_tourists = intval($_POST['number_of_tourists']);
    
    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO tourist_data (attraction_id, number_of_tourists) VALUES (?, ?)");
    if ($stmt === false) {
        error_log("Error preparing the query: " . $conn->error);
        echo "<script>alert('Error saving attraction data');</script>";
    } else {
        $stmt->bind_param("ii", $attraction_id, $number_of_tourists);
        if ($stmt->execute()) {
            echo "<script>alert('Attraction data saved successfully');</script>";
        } else {
            echo "<script>alert('Error saving attraction data');</script>";
        }
        $stmt->close();
    }
}


$stmt->close();
$conn->close();
echo json_encode(["status" => "success"]);
?>
