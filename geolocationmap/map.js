// Initialize the map
var map = L.map('map', {
    minZoom: 10,  // Set minimum zoom level (optional)
    maxZoom: 18   // Set maximum zoom level (optional)
}).setView([13.1407, 123.7438], 13);  // Set initial zoom level (optional)

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

// Variables
var userLocation = null;
var timeSpent = 0;
var timeInterval = null;
var recentVisits = [];
var userMarker = null; // To hold the user's marker
var currentAttraction = null; // Store the current attraction the user is visiting

// Initially hide the time spent info
document.getElementById('time-spent-info').style.display = 'none';

// Add markers and circles for each attraction
attractions.forEach(function (attraction) {
    if (attraction.latitude && attraction.longitude) {
        var marker = L.marker([attraction.latitude, attraction.longitude]).addTo(map)
            .bindPopup(
                '<strong>' + attraction.name + '</strong><br>' +
                '<img src="' + attraction.photo + '" width="100px"><br>' +
                'Proximity: ' + attraction.proximity_size
            );

        var circle = L.circle([attraction.latitude, attraction.longitude], {
            color: 'blue',
            fillColor: '#30a3ec',
            fillOpacity: 0.4,
            radius: proximitySizes[attraction.proximity_size]
        }).addTo(map);
    }
});

// Notification functions
function updateNotifications(attraction) {
    document.querySelector('#vicinity-notification p').innerHTML = attraction.name;
    document.querySelector('#visit-info p').innerHTML = attraction.name;

    var hours = Math.floor(timeSpent / 3600);
    var minutes = Math.floor((timeSpent % 3600) / 60);
    var seconds = timeSpent % 60;

    document.querySelector('#time-spent-info p').innerHTML =
        (hours > 0 ? hours + " hour(s) " : "") +
        (minutes > 0 ? minutes + " minute(s) " : "") +
        seconds + " second(s)";
}

function startCountingTime(attraction) {
    if (!timeInterval) {
        fetchTimeSpent(attraction.name); // Fetch existing time spent

        timeInterval = setInterval(function () {
            timeSpent++; // Increment time spent
            updateNotifications(attraction);
            saveTimeSpent(attraction.name, timeSpent, userLocation.lat, userLocation.lng);
        }, 1000);
    }
}

function stopCountingTime(attraction) {
    clearInterval(timeInterval);
    timeInterval = null;
    // Save the time spent when the user leaves the vicinity
    saveTimeSpent(attraction.name, timeSpent, userLocation.lat, userLocation.lng);
}

// Add Recent Visit
function addRecentVisit(attractionName) {
    recentVisits.push(attractionName);
    var visitList = document.getElementById('visit-list');
    var listItem = document.createElement('li');
    listItem.textContent = attractionName;
    visitList.appendChild(listItem);
}

document.getElementById('visit-info').addEventListener('click', function () {
    var visitList = document.getElementById('recent-visit-list');
    visitList.style.display = visitList.style.display === 'none' ? 'block' : 'none';
});

// Geolocation - Tracking user in real-time
if (navigator.geolocation) {
    navigator.geolocation.watchPosition(function (position) {
        userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };

        // Update the user's marker or create a new one
        if (userMarker) {
            userMarker.setLatLng([userLocation.lat, userLocation.lng]);
        } else {
            userMarker = L.marker([userLocation.lat, userLocation.lng]).addTo(map)
                .bindPopup('<strong>You are here</strong>').openPopup();
        }

        // Check proximity for each attraction
        attractions.forEach(function (attraction) {
            var distance = map.distance([userLocation.lat, userLocation.lng], [attraction.latitude, attraction.longitude]);
            
            // Check if the user is within proximity of the attraction
            if (distance <= proximitySizes[attraction.proximity_size]) {
                // If not already visiting this attraction, start the time count
                if (currentAttraction !== attraction.name) {
                    currentAttraction = attraction.name;
                    document.getElementById('time-spent-info').style.display = 'block';  // Show time spent info
                    startCountingTime(attraction);
                    addRecentVisit(attraction.name);
                }
            } else {
                // If user leaves attraction vicinity, stop the timer but do not reset time spent
                if (currentAttraction === attraction.name) {
                    currentAttraction = null;
                    document.getElementById('time-spent-info').style.display = 'none';  // Hide time spent info
                    stopCountingTime(attraction);
                }
            }
        });
    }, function(error) {
        console.error('Geolocation Error: ' + error.message);
        alert("Unable to retrieve your location.");
    }, {
        enableHighAccuracy: true,  // Use high accuracy
        maximumAge: 10000,         // Cache location for 10 seconds
        timeout: 5000              // Timeout after 5 seconds
    });
}

// Add event listener to recenter button
document.getElementById('recenter-btn').addEventListener('click', function() {
    if (userLocation) {
        map.setView([userLocation.lat, userLocation.lng], 13);
    } else {
        alert("Unable to recenter, user location is not available yet.");
    }
});

// Add event listener to minimize button
document.getElementById('minimize-btn').addEventListener('click', function() {
    var timeSpentInfo = document.getElementById('time-spent-info');
    
    // Toggle the "minimized" class to hide or show content
    timeSpentInfo.classList.toggle('minimized');

    // Change the button text to reflect the current state
    if (timeSpentInfo.classList.contains('minimized')) {
        document.getElementById('minimize-btn').textContent = '+';
    } else {
        document.getElementById('minimize-btn').textContent = '-';
    }
});

// AJAX functions
function fetchTimeSpent(attractionName) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_time_spent.php?attraction_name=" + encodeURIComponent(attractionName) + "&username=" + encodeURIComponent(username), true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.time_spent !== undefined) {
                timeSpent = response.time_spent; // Set time spent from the server
                updateNotifications({ name: attractionName });
            }
        }
    };
    xhr.send();
}

function saveTimeSpent(attractionName, timeSpent, userLat, userLng) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_visit.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(
        "username=" + encodeURIComponent(username) +
        "&attraction_name=" + encodeURIComponent(attractionName) +
        "&time_spent=" + timeSpent +
        "&location_lat=" + userLat +
        "&location_lng=" + userLng
    );
}
function sendLocationUpdate(username, latitude, longitude) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_location.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("username=" + encodeURIComponent(username) + "&latitude=" + latitude + "&longitude=" + longitude);
}

// Call this function whenever the user's location changes
navigator.geolocation.watchPosition(function (position) {
    sendLocationUpdate(username, position.coords.latitude, position.coords.longitude);
});
