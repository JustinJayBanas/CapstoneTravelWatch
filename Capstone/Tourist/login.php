<?php
// Include the database connection file
include 'db_connection.php';

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Initialize user variable
    $user = null;

    // Function to fetch user from a specific table
    function getUserFromTable($conn, $username, $table) {
        $sql = "SELECT * FROM $table WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
            $stmt->close();
        }
        return null;
    }

    // Check local_tourist first, then foreign_tourist
    $user = getUserFromTable($conn, $username, 'local_tourist') ?? getUserFromTable($conn, $username, 'foreign_tourist');

    // If user is found, verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, store session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Redirect to the dashboard or homepage
        header("Location: ../Dashboard/Footer/discover.php");
        exit;
    } else {
        // Handle invalid username or password
        $error = $user ? "Incorrect password. Please try again." : "No user found with that username.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelWatch Login</title>
    <link rel="stylesheet" href="css\login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="illustration.png" alt="Illustration">
        </div>
        <h2>Welcome back!</h2>
        <p>Login to continue with TravelWatch</p>
        
        <!-- Display error message if any -->
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <span id="togglePassword" style="cursor:pointer;">👁️</span>
            </div>
            <button type="submit" class="login-btn">Log In</button>
        </form>
        <div class="links">
            <a href="reset_pass.php">I need to reset my password.</a>
            <a href="signup.php">Sign up for a new account.</a>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type; 
            // Toggle the eye icon
            togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>
