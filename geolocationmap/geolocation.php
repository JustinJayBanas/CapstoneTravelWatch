<?php
// Start the session and check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

// Fetch tourist attractions from the database
require '../config.php'; // Assuming this is the filename of your config

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM attractions";
$result = $conn->query($sql);

$attractions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attractions[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Attraction Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="style.css"> <!-- Your custom CSS -->
</head>
<body>
    <div id="map-container">
        <h3>Geolocation Monitoring</h3>
        <div id="notification-panel">
            <div id="vicinity-notification" class="notification">
                <h4>Vicinity:</h4>
                <p>None</p>
            </div>
            <div id="visit-info" class="notification">
                <h4>Recent Visit:</h4>
                <p>None</p>
            </div>
        </div>
        <div id="map"></div>
        <div id="recent-visit-list">
            <h4>Recent Visits</h4>
            <ul id="visit-list"></ul>
        </div>
        <div id="time-spent-info">
            <h4>Time Spent:</h4>
            <p>0 seconds</p>
            <!-- Minimize Button -->
            <button id="minimize-btn">-</button>
        </div>

        <!-- Recenter Button -->
        <button id="recenter-btn">
            <span class="material-icons">my_location</span>
        </button>

        <!-- Logout Button -->
        <button id="logout-btn">Logout</button>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        const attractions = <?php echo json_encode($attractions); ?>;
        const username = "<?php echo $_SESSION['username']; ?>";

        // Logout functionality
        document.getElementById('logout-btn').addEventListener('click', function() {
            fetch('logout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'username=' + encodeURIComponent(username)
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                // Redirect to login page after logout
                window.location.href = '../index.php';
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
    <script src="map.js"></script>
    <script src="live.js"></script>
</body>
</html>
