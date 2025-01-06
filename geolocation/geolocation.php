<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'travelwatch');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all tourist attractions from the database
$sql = "SELECT * FROM attractions";
$result = $conn->query($sql);

$attractions = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $attractions[] = $row;
    }
}
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Attraction Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="style.css"> <!-- Your custom CSS file -->
</head>
<body>

    <!-- Map Container -->
    <div id="map-container">
        <h3>Geolocation Monitoring</h3>
        
        <!-- Floating Notification Panel -->
        <div id="notification-panel">
            <!-- Vicinity Notification -->
            <div id="vicinity-notification" class="notification">
                <h4>Vicinity:</h4>
                <p>None</p>
            </div>

            <!-- Recent Visit Notification (Button) -->
            <div id="visit-info" class="notification">
                <h4>Recent Visit:</h4>
                <p>None</p>
            </div>
        </div>

        <div id="map"></div>
        
    <!-- Recent Visit List (Hidden by default) -->
        <div id="recent-visit-list">
            <h4>Recent Visits</h4>
            <ul id="visit-list">
                <!-- Recent visits will be populated here -->
            </ul>
        </div>

        <!-- Time Spent Notification -->
        <div id="time-spent-info">
            <h4>Time Spent:</h4>
            <p>0 seconds</p>
        </div>
    </div>

    <!-- Leaflet JS for Map -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
    // Initialize the map
    var map = L.map('map').setView([13.1407, 123.7438], 13); // Default to a fixed location initially

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Proximity radius mapping
    var proximitySizes = {
        1: 500,   // Small: 500 meters
        2: 1000,  // Medium: 1000 meters
        3: 1500   // Large: 1500 meters
    };

    // Fetch attraction data from PHP
    var attractions = <?php echo json_encode($attractions); ?>;
    
    var userLocation = null;  // To store the user's location
    var timeSpent = 0;  // Initialize time spent counter
    var timeInterval = null;  // To track the interval timer
    var recentVisits = []; // Store recent visits

    // Add markers and circles for each attraction
    attractions.forEach(function(attraction) {
        if (attraction.latitude && attraction.longitude) {
            // Create marker and bind popup
            var marker = L.marker([attraction.latitude, attraction.longitude]).addTo(map)
                .bindPopup(
                    '<strong>' + attraction.name + '</strong><br>' +
                    '<img src="' + attraction.photo + '" width="100px"><br>' +
                    'Proximity: ' + attraction.proximity_size
                );

            // Add circle based on proximity size
            var circle = L.circle([attraction.latitude, attraction.longitude], {
                color: 'blue',  // Circle border color
                fillColor: '#30a3ec',  // Circle fill color
                fillOpacity: 0.4,  // Circle fill transparency
                radius: proximitySizes[attraction.proximity_size] // Set radius from proximity size
            }).addTo(map);
        }
    });

    // Function to update notifications
    function updateNotifications(attraction) {
        var vicinityNotification = document.querySelector('#vicinity-notification p');
        var visitInfo = document.querySelector('#visit-info p');
        var timeSpentInfo = document.querySelector('#time-spent-info p');
        
        vicinityNotification.innerHTML = attraction.name;
        visitInfo.innerHTML = attraction.name;

        // Convert timeSpent into hours, minutes, and seconds
        var hours = Math.floor(timeSpent / 3600);
        var minutes = Math.floor((timeSpent % 3600) / 60);
        var seconds = timeSpent % 60;

        timeSpentInfo.innerHTML = (hours > 0 ? hours + " hour(s) " : "") +
            (minutes > 0 ? minutes + " minute(s) " : "") +
            seconds + " second(s)";
    }

    // Function to start counting time
    function startCountingTime(attraction) {
        if (!timeInterval) {
            fetchTimeSpent(attraction.name);  // Fetch initial time spent from the database

            timeInterval = setInterval(function() {
                timeSpent++;  // Increment time spent
                updateNotifications(attraction);  // Update the notification panel
                
                // Get the current user's location (latitude and longitude)
                var userLat = userLocation.lat;
                var userLng = userLocation.lng;

                // Send time spent and location to the server via AJAX
                saveTimeSpent(attraction.name, timeSpent, userLat, userLng);
            }, 1000);  // Update every second
        }
    }

    // Function to stop counting time when the user leaves the vicinity
    function stopCountingTime() {
        clearInterval(timeInterval);
        timeInterval = null;
    }

    // Function to add recent visit
    function addRecentVisit(attractionName) {
        recentVisits.push(attractionName);
        var visitList = document.getElementById('visit-list');
        var listItem = document.createElement('li');
        listItem.textContent = attractionName;
        visitList.appendChild(listItem);
    }

    // Toggle the visibility of the recent visit list
    document.getElementById('visit-info').addEventListener('click', function() {
        var visitList = document.getElementById('recent-visit-list');
        if (visitList.style.display === 'none' || visitList.style.display === '') {
            visitList.style.display = 'block'; // Show the list
        } else {
            visitList.style.display = 'none'; // Hide the list
        }
    });

    // Optional: Geolocate user and check if they are within any attraction's vicinity
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            
            // Center the map to the user's location
            map.setView([userLocation.lat, userLocation.lng], 13);

            var marker = L.marker([userLocation.lat, userLocation.lng]).addTo(map)
                .bindPopup('<strong>You are here</strong>').openPopup();

            var withinVicinity = false;  // To track if the user is within any attraction

            // Loop through attractions to check if the user is within any proximity
            attractions.forEach(function(attraction) {
                var distance = map.distance([userLocation.lat, userLocation.lng], [attraction.latitude, attraction.longitude]);
                var proximity = proximitySizes[attraction.proximity_size];
                
                if (distance <= proximity) {
                    withinVicinity = true;
                    startCountingTime(attraction);  // Start counting if within proximity
                    addRecentVisit(attraction.name); // Add to recent visits
                }
            });

            // Stop counting if the user leaves all vicinities
            if (!withinVicinity) {
                stopCountingTime();
            }
        }, function() {
            console.log("Geolocation is not available.");
        });
    }

    // Function to fetch time spent from the database
    function fetchTimeSpent(attractionName) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_time_spent.php?attraction_name=" + encodeURIComponent(attractionName), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.time_spent !== undefined) {
                    timeSpent = response.time_spent;  // Set the time spent from the database
                    updateNotifications({name: attractionName});  // Update the UI with the fetched time spent
                }
            }
        };
        xhr.send();
    }

    // Function to save time spent, recent visit, and location to the database
    function saveTimeSpent(attractionName, timeSpent, userLat, userLng) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "save_visit.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        var username = "<?php echo $_SESSION['username']; ?>";  // Get the logged-in username (PHP embedded into JS)

        // Send the POST request with the attraction name, time spent, username, and location
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log("Visit saved successfully.");
            }
        };

        // Send POST data
        xhr.send("username=" + encodeURIComponent(username) + "&attraction_name=" + encodeURIComponent(attractionName) + "&time_spent=" + timeSpent + "&location_lat=" + userLat + "&location_lng=" + userLng);
    }
</script>

</body>
</html>
