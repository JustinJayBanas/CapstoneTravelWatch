<?php
// Include the database connection file
include 'database_php/db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
    <link rel="stylesheet" href="css\reset.css">
</head>
<body>
    <div class="recovery-container">
        <div class="recovery-header">
            <img src="illustration.png" alt="Illustration">
        </div>
        <h2>Provide your registered email address</h2>
        <p>Input your email to recover password</p>
        <form>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="recover-btn">Recover</button>
        </form>
        <div class="links">
            <a href="login.php">Go Back</a>
        </div>
    </div>
</body>
</html>
