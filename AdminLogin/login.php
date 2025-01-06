<?php
session_start();
// Connect to the database
require '../config.php'; // Assuming this is the filename of your config

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        header("Location: adminlogin.html?error=" . urlencode("Username and password are required."));
        exit();
    }

    // Prepare and execute query securely
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    if (!$stmt) {
        $error_message = "Error preparing statement: " . $conn->error;
        header("Location: adminlogin.html?error=" . urlencode($error_message));
        exit();
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Login successful
        $_SESSION['username'] = $username;
        header("Location: ../AddTouristAttraction/add_attraction.php"); // Redirect to a protected page
        exit();
    } else {
        // Login failed
        header("Location: adminlogin.html?error=" . urlencode("Invalid username or password."));
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
