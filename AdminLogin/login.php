<?php
session_start();
include 'db_config.php';

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
