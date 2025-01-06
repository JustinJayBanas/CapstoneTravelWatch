<?php
// Include the database connection file
include 'database_php/db_connection.php';

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
        header("Location: ../Dashboard/Sidebar/home1.php");
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
            </div>
            <div class="show-pass">
                <input type="checkbox" id="showPassword">
                <label for="showPassword">Show Password</label>
            </div>
            <button type="submit" class="login-btn">Log In</button>
            <button type="button" class="create-btn" onclick="window.location.href='create_account.php';">Create Account</button>
        </form>
        <div class="links">
            <a href="reset_pass.php">I need to reset my password.</a>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const showPasswordCheckbox = document.getElementById('showPassword');

            showPasswordCheckbox.addEventListener('change', function () {
                // Toggle the type attribute based on checkbox state
                const type = showPasswordCheckbox.checked ? 'text' : 'password';
                passwordInput.type = type;
        });
    </script>
</body>
</html>
