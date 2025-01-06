<?php 
// Include the database connection file at the beginning
include 'db_connection.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Status Selection</title>
    <link rel="stylesheet" href="css\try.css">
</head>
<body>
    <div class="status-container">
        <div class="status-header">
            <img src="illustration.png" alt="Illustration">
        </div>
        <h2>Are you a local or foreign User?</h2>
        <p>Select your status to customize your registration process.</p>
        <div class="button-group">
            <button id="localBtn" class="status-btn local-btn">Local</button>
            <button id="foreignBtn" class="status-btn foreign-btn">Foreign</button>
        </div>
        <button id="continueBtn" class="continue-btn">Continue</button>
        <div class="links">
            <a href="login.php">I already have an Account</a>
        </div>
    </div>

    <script>
        let selectedStatus = '';

        function handleButtonClick(status) {
            selectedStatus = status;
            // Remove 'selected' class from both buttons
            document.getElementById('localBtn').classList.remove('selected');
            document.getElementById('foreignBtn').classList.remove('selected');

            // Add 'selected' class to the clicked button
            if (status === 'local') {
                document.getElementById('localBtn').classList.add('selected');
            } else if (status === 'foreign') {
                document.getElementById('foreignBtn').classList.add('selected');
            }
        }

        document.getElementById('localBtn').addEventListener('click', function() {
            handleButtonClick('local');
        });

        document.getElementById('foreignBtn').addEventListener('click', function() {
            handleButtonClick('foreign');
        });

        document.getElementById('continueBtn').addEventListener('click', function() {
            if (selectedStatus === 'local') {
                window.location.href = 'local.php';
            } else if (selectedStatus === 'foreign') {
                window.location.href = 'foreign.php';
            } else {
                alert('Please select your status before continuing.');
            }
        });
    </script>
</body>
</html>
