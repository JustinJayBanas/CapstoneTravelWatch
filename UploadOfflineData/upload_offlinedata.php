<?php
// Start the session if needed
session_start();

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'travelwatch');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Tourist Attraction Form Submission
if (isset($_POST['attraction_id']) && isset($_POST['number_of_tourists'])) {
    $attraction_id = intval($_POST['attraction_id']);
    $number_of_tourists = intval($_POST['number_of_tourists']);
    $monitoring_date = date('Y-m-d H:i:s'); // Current date and time in the correct format

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO tourist_data (attraction_id, number_of_tourists, monitoring_date) VALUES (?, ?, ?)");
    if ($stmt === false) {
        error_log("Error preparing the query: " . $conn->error);
        echo "<script>alert('Error saving attraction data');</script>";
    } else {
        // Bind the parameters
        $stmt->bind_param("iis", $attraction_id, $number_of_tourists, $monitoring_date); // Bind the date as a string
        if ($stmt->execute()) {
            echo "<script>alert('Attraction data saved successfully');</script>";
        } else {
            echo "<script>alert('Error saving attraction data');</script>";
        }
        $stmt->close();
    }
}
// Handle Tourist Attraction Form Submission
if (isset($_POST['attraction_id']) && isset($_POST['number_of_tourists'])) {
    $attraction_id = intval($_POST['attraction_id']);
    $number_of_tourists = intval($_POST['number_of_tourists']);
    $monitoring_date = date('Y-m-d H:i:s'); // Current date and time in the correct format

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO tourist_data_backup (attraction_id, number_of_tourists, monitoring_date) VALUES (?, ?, ?)");
    if ($stmt === false) {
        error_log("Error preparing the query: " . $conn->error);
        echo "<script>alert('Error saving attraction data');</script>";
    } else {
        // Bind the parameters
        $stmt->bind_param("iis", $attraction_id, $number_of_tourists, $monitoring_date); // Bind the date as a string
        if ($stmt->execute()) {
            echo "<script>alert('Attraction data saved successfully');</script>";
        } else {
            echo "<script>alert('Error saving attraction data');</script>";
        }
        $stmt->close();
    }
}


    // Handle Accommodation Form Submission
    if (isset($_POST['accommodation_name']) && isset($_POST['complete_address'])) {
        $accommodation_name = $conn->real_escape_string($_POST['accommodation_name']);
        $complete_address = $conn->real_escape_string($_POST['complete_address']);
        $accommodation_type = $conn->real_escape_string($_POST['accommodation_type']);
        $month_checked_in = intval($_POST['month_checked_in']);
        $remarks = $conn->real_escape_string($_POST['remarks']);

        // Insert into accommodations table
        $stmt = $conn->prepare("INSERT INTO accommodations (accommodation_name, complete_address, accommodation_type, month_checked_in, remarks) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssis", $accommodation_name, $complete_address, $accommodation_type, $month_checked_in, $remarks);
            if ($stmt->execute()) {
                $accommodation_id = $stmt->insert_id;

               // Handle Philippine Residences
if (isset($_POST['philippine_residences']) && !empty($_POST['philippine_residences'])) {
    $philippine_residences = json_decode($_POST['philippine_residences'], true);
    
    if (json_last_error() === JSON_ERROR_NONE && is_array($philippine_residences)) {
        $stmt_phil = $conn->prepare("INSERT INTO philippine_residences (accommodation_id, province, number_of_tourists) VALUES (?, ?, ?)");
        
        foreach ($philippine_residences as $residence) {
            $province = $conn->real_escape_string($residence['province']);
            $number_of_tourists_phil = intval($residence['number_of_tourists']);
            $stmt_phil->bind_param("isi", $accommodation_id, $province, $number_of_tourists_phil);
            $stmt_phil->execute();
        }
        
        $stmt_phil->close();
    } else {
        error_log("Error decoding philippine_residences JSON: " . json_last_error_msg());
    }
}

// Handle Non-Philippine Residences
if (isset($_POST['non_philippine_residences']) && !empty($_POST['non_philippine_residences'])) {
    $non_philippine_residences = json_decode($_POST['non_philippine_residences'], true);
    
    if (json_last_error() === JSON_ERROR_NONE && is_array($non_philippine_residences)) {
        $stmt_non_phil = $conn->prepare("INSERT INTO non_philippine_residences (accommodation_id, country, number_of_tourists) VALUES (?, ?, ?)");
        
        foreach ($non_philippine_residences as $residence) {
            $country = $conn->real_escape_string($residence['country']);
            $number_of_tourists_non_phil = intval($residence['number_of_tourists']);
            $stmt_non_phil->bind_param("isi", $accommodation_id, $country, $number_of_tourists_non_phil);
            $stmt_non_phil->execute();
        }
        
        $stmt_non_phil->close();
    } else {
        error_log("Error decoding non_philippine_residences JSON: " . json_last_error_msg());
    }
}


                echo "<script>alert('Accommodation data saved successfully');</script>";
            } else {
                echo "<script>alert('Error saving accommodation data');</script>";
            }
            $stmt->close();
        }
    }


// Fetch all tourist attractions for the dropdown
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
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Quarterly Tourist Attraction Monitoring</title>
    <link rel="stylesheet" href="style.css">
  
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
    <button onclick="location.href='../AddTouristAttraction/add_attraction.php'"><i class="fas fa-map-marker-alt"></i> Add Tourist Attraction</button>
    <button onclick="location.href='upload_data.php'"><i class="fas fa-upload"></i> Upload Offline Data</button>
    <button onclick="location.href='backup_restore.php'"><i class="fas fa-database"></i> Backup and Restore</button>
    <button onclick="location.href='generate_report.php'"><i class="fas fa-chart-bar"></i> Generate Report</button>
</div>

<div id="content">
    <div class="header-buttons">
        <button onclick="showAccommodationForm()">Accommodation</button>
        <button onclick="showAttractionForm()">Attraction</button>
    </div>
    <h1>Quarterly Tourist Attraction Monitoring</h1>
    

    <!-- Tourist Attraction Form -->
<div class="form-box">
    <form method="POST" id="attraction-form">
        <h3>Tourist Attraction Details</h3>
        <label for="attraction-select">Name of Tourist Attraction:</label>
        <select id="attraction-select" name="attraction_id" required>
            <option value="">Select an attraction</option>
            <?php foreach ($attractions as $attraction): ?>
                <option value="<?php echo htmlspecialchars($attraction['id']); ?>"><?php echo htmlspecialchars($attraction['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="number-of-tourists">Number of Visitors:</label>
        <input type="number" id="number-of-tourists" name="number_of_tourists" placeholder="Enter number" required>

        <div class="submit-container">
            <button type="submit">Submit</button>
        </div>
    </form>



    <!-- Accommodation Form -->
    <form method="POST" id="accommodation-form">
        <h3>Quarterly Monitoring of Accommodations</h3>
        <label for="accommodation-name">Name of Accommodation:</label>
        <input type="text" id="accommodation-name" name="accommodation_name" placeholder="Enter Accommodation Name" required>

        <label for="complete-address">Complete Address:</label>
        <input type="text" id="complete-address" name="complete_address" placeholder="Enter Complete Address" required>

        <label for="type-of-attraction">Type of Accommodation:</label>
        <select id="type-of-attraction" name="accommodation_type" required>
            <option value="">Select Type</option>
            <option value="hotel">Hotel</option>
            <option value="resort">Resort</option>
            <option value="motel">Motel</option>
            <option value="inn">Inn</option>
        </select>

        <label for="month-checked-in">Month Checked-in:</label>
        <input type="number" id="month-checked-in" name="month_checked_in" placeholder="Enter Month (1-12)" min="1" max="12" required>

        <!-- Philippine Residence Details -->
        <h4>Philippine Residence Details</h4>
        <button type="button" onclick="showPhilippinePopup()">Add Philippine Residence</button>
        <div id="philippine-residences-list" class="residence-list"></div>
        <!-- Hidden inputs to store residence data -->
        <input type="hidden" name="philippine_residences" id="philippine_residences_input">

        <!-- Non-Philippine Residence Details -->
        <h4>Non-Philippine Residence Details</h4>
        <button type="button" onclick="showCountryPopup()">Add Non-Philippine Residence</button>
        <div id="non-philippine-residences-list" class="residence-list"></div>
        <!-- Hidden inputs to store residence data -->
        <input type="hidden" name="non_philippine_residences" id="non_philippine_residences_input">

        <label for="remarks">Remarks (Optional):</label>
        <textarea id="remarks" name="remarks" placeholder="Any remarks..."></textarea>

        <div class="submit-container">
            <button type="submit">Submit</button>
        </div>
    </form>
</div>
    <!-- Overlay -->
    <div id="overlay"></div>

    <!-- Philippine Residence Popup -->
    <div id="philippine-popup" class="popup">
        <h3>Add Philippine Residence</h3>
        <label for="province-select">Select Province:</label>
        <select id="province-select">
            <option value="">Select a province</option>
            <option value="Luzon">Luzon</option>
            <option value="Visayas">Visayas</option>
            <option value="Mindanao">Mindanao</option>
            <!-- Add more provinces as needed -->
        </select>
        <label for="number-of-tourists-phil">Number of Tourists:</label>
        <input type="number" id="number-of-tourists-phil" placeholder="Enter number" min="0" required>
        <div class="submit-container">
            <button type="button" onclick="addPhilippineDetails()">Add</button>
            <button type="button" onclick="closePhilippinePopup()">Cancel</button>
        </div>
    </div>

    <!-- Non-Philippine Residence Popup -->
    <div id="country-popup" class="popup">
        <h3>Add Non-Philippine Residence</h3>
        <label for="country-select">Select Country:</label>
        <select id="country-select">
            <option value="">Select a country</option>
            <option value="USA">USA</option>
            <option value="Canada">Canada</option>
            <option value="UK">UK</option>
            <!-- Add more countries as needed -->
        </select>
        <label for="number-of-tourists-non-phil">Number of Tourists:</label>
        <input type="number" id="number-of-tourists-non-phil" placeholder="Enter number" min="0" required>
        <div class="submit-container">
            <button type="button" onclick="addNonPhilippineDetails()">Add</button>
            <button type="button" onclick="closeCountryPopup()">Cancel</button>
        </div>
    </div>

    <script>
        // Arrays to hold residence details
        let philippineResidences = [];
        let nonPhilippineResidences = [];

        // Functions to show and hide forms
        function showAccommodationForm() {
            document.getElementById('accommodation-form').style.display = 'block';
            document.getElementById('attraction-form').style.display = 'none';
        }

        function showAttractionForm() {
            document.getElementById('attraction-form').style.display = 'block';
            document.getElementById('accommodation-form').style.display = 'none';
        }

        // Functions to show and hide popups
        function showPhilippinePopup() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('philippine-popup').style.display = 'block';
        }

        function closePhilippinePopup() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('philippine-popup').style.display = 'none';
            // Clear popup inputs
            document.getElementById('province-select').value = '';
            document.getElementById('number-of-tourists-phil').value = '';
        }

        function showCountryPopup() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('country-popup').style.display = 'block';
        }

        function closeCountryPopup() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('country-popup').style.display = 'none';
            // Clear popup inputs
            document.getElementById('country-select').value = '';
            document.getElementById('number-of-tourists-non-phil').value = '';
        }

        // Function to add Philippine Residence
        function addPhilippineDetails() {
            const province = document.getElementById('province-select').value.trim();
            const tourists = document.getElementById('number-of-tourists-phil').value.trim();

            if (province === '' || tourists === '') {
                alert('Please select a province and enter the number of tourists.');
                return;
            }

            if (isNaN(tourists) || tourists < 0) {
                alert('Please enter a valid number of tourists.');
                return;
            }

            // Add to the array
            philippineResidences.push({
                province: province,
                number_of_tourists: parseInt(tourists)
            });

            // Update the display list
            updatePhilippineResidencesList();

            // Close the popup
            closePhilippinePopup();
        }

        // Function to add Non-Philippine Residence
        function addNonPhilippineDetails() {
            const country = document.getElementById('country-select').value.trim();
            const tourists = document.getElementById('number-of-tourists-non-phil').value.trim();

            if (country === '' || tourists === '') {
                alert('Please select a country and enter the number of tourists.');
                return;
            }

            if (isNaN(tourists) || tourists < 0) {
                alert('Please enter a valid number of tourists.');
                return;
            }

            // Add to the array
            nonPhilippineResidences.push({
                country: country,
                number_of_tourists: parseInt(tourists)
            });

            // Update the display list
            updateNonPhilippineResidencesList();

            // Close the popup
            closeCountryPopup();
        }

        // Function to update Philippine Residences List
        function updatePhilippineResidencesList() {
            const list = document.getElementById('philippine-residences-list');
            list.innerHTML = '';

            philippineResidences.forEach((residence, index) => {
                const div = document.createElement('div');
                div.className = 'residence-item';
                div.innerHTML = `
                    <span>${residence.province}: ${residence.number_of_tourists} tourists</span>
                    <button type="button" onclick="removePhilippineResidence(${index})" class="remove-button">Remove</button>
                `;
                list.appendChild(div);
            });

            // Update hidden input
            document.getElementById('philippine_residences_input').value = JSON.stringify(philippineResidences);
        }

        // Function to update Non-Philippine Residences List
        function updateNonPhilippineResidencesList() {
            const list = document.getElementById('non-philippine-residences-list');
            list.innerHTML = '';

            nonPhilippineResidences.forEach((residence, index) => {
                const div = document.createElement('div');
                div.className = 'residence-item';
                div.innerHTML = `
                    <span>${residence.country}: ${residence.number_of_tourists} tourists</span>
                    <button type="button" onclick="removeNonPhilippineResidence(${index})" class="remove-button">Remove</button>
                `;
                list.appendChild(div);
            });

            // Update hidden input
            document.getElementById('non_philippine_residences_input').value = JSON.stringify(nonPhilippineResidences);
        }

        // Function to remove Philippine Residence
        function removePhilippineResidence(index) {
            philippineResidences.splice(index, 1);
            updatePhilippineResidencesList();
        }

        // Function to remove Non-Philippine Residence
        function removeNonPhilippineResidence(index) {
            nonPhilippineResidences.splice(index, 1);
            updateNonPhilippineResidencesList();
        }

        // Before form submission, parse the JSON strings into arrays
        document.getElementById('accommodation-form').addEventListener('submit', function(e) {
            // Convert JSON strings to arrays if needed
            // The PHP script already handles JSON decoding
            // No additional action is required here
        });

        // Initial display setup
        document.addEventListener('DOMContentLoaded', function() {
            // Optionally, initialize the forms to hide or show based on certain conditions
        });
    </script>
</div>

</body>
</html>
