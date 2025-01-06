<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - User Locations</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
</head>
<body>

<h3>Real-Time User Locations</h3>
<div id="map" style="height: 500px;"></div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Initialize the map
    var map = L.map('map').setView([13.1407, 123.7438], 13);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Store user markers by user ID for updating
    var userMarkers = {};

    // Function to fetch and display user locations
    function fetchUserLocations() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "get_user_locations.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                var users = JSON.parse(xhr.responseText);

                users.forEach(function(user) {
                    // Update marker if exists, else create new
                    if (userMarkers[user.user_id]) {
                        userMarkers[user.user_id].setLatLng([user.latitude, user.longitude]);
                    } else {
                        var marker = L.marker([user.latitude, user.longitude]).addTo(map)
                            .bindPopup("User " + user.user_id);
                        userMarkers[user.user_id] = marker;
                    }
                });
            }
        };
        xhr.send();
    }

    // Fetch user locations every second for real-time effect
    setInterval(fetchUserLocations, 1000);
</script>

</body>
</html>
