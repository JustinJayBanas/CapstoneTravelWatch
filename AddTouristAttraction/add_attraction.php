<?php
// Connect to the database
require '../config.php'; // Assuming this is the filename of your config

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all tourist attractions
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
    header("Location: adminlogin.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Attraction Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="styless.css"> <!-- Corrected CSS file name -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>
<header>
    <div id="header-text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
    <a href="../AdminLogin/logout.php" class="logout-button">
        <i class="fas fa-sign-out-alt"></i>
        <span class="tooltip-text">Logout</span> <!-- Tooltip text -->
    </a>
</header>


    <div id="sidebar">
    <img src="testlogo.jpg" alt="Logo" class="sidebar-logo">
    <button onclick="location.href='dashboard.php'"><i class="fas fa-home"></i> Dashboard</button>
    <button onclick="location.href='add_attraction.php'"><i class="fas fa-map-marker-alt"></i> Add Tourist Attraction</button>
    <button onclick="location.href='../UploadOfflineData/upload_offlinedata.php'"><i class="fas fa-upload"></i> Upload Offline Data</button>
    <button onclick="location.href='backup_restore.php'"><i class="fas fa-database"></i> Backup and Restore</button>
    <button onclick="location.href='generate_report.php'"><i class="fas fa-chart-bar"></i> Generate Report</button>
</div>

        <div id="counter-container">
        <div id="totaltitle-text">City / Municipal Dashboard</div> <!-- New text element -->
    <div id="counter-text">Total Tourist Attractions</div> <!-- New text element -->
    <div class="counter-title-container">
        <span class="counter-title">Tourist Attractions</span>
        <img src="location.png" alt="Attraction Icon" /> <!-- Replace with the path to your icon -->
    </div>
    <span id="attraction-count"><?php echo count($attractions); ?></span>
</div>



<div id="map-container">
    <div id="map-text">Tourist Attraction Map</div>
    <div id="map"></div>
</div>

    <!-- Draggable Form container with a header -->
    <div id="form-container">
        <div id="form-header">
            <span class="close-btn" onclick="closeForm()">×</span>
            <h3>Add / Edit Tourist Attraction</h3>
        </div>
        <form id="attraction-form" enctype="multipart/form-data">
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <input type="hidden" id="attraction-id" name="attraction-id">
            
            <label for="name">Attraction Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter attraction name" required>

            <label for="proximity">Proximity Size:</label>
            <select id="proximity" name="proximity" required>
                <option value="" disabled selected>Select Size</option>
                <option value="1">Small</option>
                <option value="2">Medium</option>
                <option value="3">Large</option>
            </select>

            <label for="photo">Tourist Attraction Photo:</label>
            <input type="file" id="photo" name="photo">

            <input type="submit" value="Save Information">
        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([13.1407, 123.7438], 13); // Center near Legazpi City

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var formContainer = document.getElementById("form-container");
        var attractions = <?php echo json_encode($attractions); ?>;

        // Circle radius in meters based on proximity size
        var proximitySizes = {
            1: 500,  // Small
            2: 1000, // Medium
            3: 1500  // Large
        };

        // Add markers and circles for existing attractions
        attractions.forEach(function(attraction) {
            var marker = L.marker([attraction.latitude, attraction.longitude]).addTo(map)
                .bindPopup('<strong>' + attraction.name + '</strong><br><img src="' + attraction.photo + '" width="100px"><br>Proximity: ' + attraction.proximity_size +
                    '<br><button onclick="editAttraction(' + attraction.id + ')" class="popup-button">Edit</button> ' +
                    '<button onclick="deleteAttraction(' + attraction.id + ')"class="popup-button delete">Delete</button>');

            // Add circle around the marker
            var circle = L.circle([attraction.latitude, attraction.longitude], {
                color: 'blue',
                fillColor: '#30a3ec',
                fillOpacity: 0.4,
                radius: proximitySizes[attraction.proximity_size] // Use proximity size for radius
            }).addTo(map);
        });

        // Event listener for map click to add a new tourist attraction
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            // Show the form at the clicked location
            formContainer.style.display = 'block';
            formContainer.style.top = (e.originalEvent.pageY + 10) + 'px';
            formContainer.style.left = (e.originalEvent.pageX + 10) + 'px';

            // Clear form fields
            document.getElementById('attraction-id').value = '';
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('name').value = '';
            document.getElementById('proximity').value = '';
        });

        // Close the form
        function closeForm() {
            formContainer.style.display = "none";
        }

        // Edit attraction
        function editAttraction(id) {
            var attraction = attractions.find(a => a.id == id);

            if (!attraction) {
                console.error("Attraction not found for ID:", id);
                return;
            }

            // Populate form fields
            document.getElementById('attraction-id').value = attraction.id;
            document.getElementById('latitude').value = attraction.latitude;
            document.getElementById('longitude').value = attraction.longitude;
            document.getElementById('name').value = attraction.name;
            document.getElementById('proximity').value = attraction.proximity_size;

            // Show the form
            formContainer.style.display = 'block';
        }   

        // Delete attraction
        function deleteAttraction(id) {
            if (confirm("Are you sure you want to delete this attraction?")) {
                fetch('delete_attraction.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                })
                .catch(error => {
                    console.error('Error deleting attraction:', error);
                });
            }
        }

        // Add event listener for form submission
        document.getElementById('attraction-form').addEventListener('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            fetch('save_attraction.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert('Attraction saved successfully!');
                location.reload();
            })
            .catch(error => {
                console.error('Error saving attraction:', error);
            });
        });

        // Draggable Form functionality
        let formHeader = document.getElementById("form-header");
        let isDragging = false;
        let offsetX, offsetY;

        formHeader.addEventListener("mousedown", function(e) {
            isDragging = true;
            offsetX = e.clientX - formContainer.getBoundingClientRect().left;
            offsetY = e.clientY - formContainer.getBoundingClientRect().top;
        });

        document.addEventListener("mousemove", function(e) {
            if (isDragging) {
                formContainer.style.left = e.clientX - offsetX + "px";
                formContainer.style.top = e.clientY - offsetY + "px";
            }
        });

        document.addEventListener("mouseup", function() {
            isDragging = false;
        });
    </script>
</body>
</html>
