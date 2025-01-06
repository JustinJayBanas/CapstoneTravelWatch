<?php
session_start();

$message = "";

// Database connection
$servername = "localhost";  // Change this to your database server
$db_username = "root";      // Change this to your database username
$db_password = "";          // Change this to your database password
$dbname = "travelwatch";    // Change this to your database name

// Connect to the database
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $input_username = htmlspecialchars(trim($_POST['username']));
    $input_password = htmlspecialchars(trim($_POST['password']));

    if (!empty($input_username) && !empty($input_password)) {
        // Prepare and execute SQL query
        $stmt = $conn->prepare("SELECT password FROM user_account WHERE username = ?");
        $stmt->bind_param("s", $input_username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Fetch the plain-text password from the database
            $stmt->bind_result($db_password);
            $stmt->fetch();

            // Compare passwords (plain text)
            if ($input_password === $db_password) {
                $_SESSION['username'] = $input_username; // Save username to session
                header("Location: geolocation.php");
                exit;
            } else {
                $message = "Invalid username or password.";
            }
        } else {
            $message = "Invalid username or password.";
        }

        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <p><?php echo htmlspecialchars($message); ?></p>
</body>
</html>
