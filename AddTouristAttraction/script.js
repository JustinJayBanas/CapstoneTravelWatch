let map;
let markers = []; // Array to store markers

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 13.7267, lng: 123.8854 }, // Initial center (Legazpi City)
        zoom: 10
    });

    // Load attractions from database on map load
    loadAttractions();
}

function loadAttractions() {
    // Fetch attractions from the database using AJAX
    fetch('http://localhost/travelwatch/get_attractions.php') // Replace with your actual URL
        .then(response => response.json())
        .then(data => {
            data.forEach(attraction => {
                // Create markers for each attraction
                let marker = new google.maps.Marker({
                    position: { lat: parseFloat(attraction.latitude), lng: parseFloat(attraction.longitude) },
                    map: map,
                    title: attraction.attraction_name // Use a unique identifier if necessary
                });

                // Add a click event to open an info window
                marker.addListener('click', () => {
                    let infoWindow = new google.maps.InfoWindow({
                        content: `<b>${attraction.attraction_name}</b><br>
                                  <img src="${attraction.photo}" width="100px"><br>${attraction.description}<br>
                                  <button onclick="deleteAttraction(${attraction.id})">Delete</button>`
                    });
                    infoWindow.open(map, marker);
                });

                // Store markers in an array to manage them
                markers.push(marker); 
            });
        })
        .catch(error => console.error('Error loading attractions:', error));
}

function deleteAttraction(id) {
    if (confirm("Are you sure you want to delete this attraction?")) {
        fetch('delete_attraction.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            console.log('Server response:', data); // Log server response
            if (data === 'Success') {
                alert('Attraction deleted successfully!');
                location.reload(); // Reload the page to refresh markers
            } else {
                alert('Error: ' + data); // Show error if delete fails
            }
        })
        .catch(error => {
            console.error('Error deleting attraction:', error);
            alert('An error occurred: ' + error.message);
        });
    }
}


document.getElementById('save-attraction').addEventListener('click', () => {
    // Get form data
    let attractionName = document.getElementById('attraction-name').value;
    let latitude = parseFloat(document.getElementById('latitude').value);
    let longitude = parseFloat(document.getElementById('longitude').value);
    let photo = document.getElementById('attraction-photo').files[0]; 
    let description = document.getElementById('description').value;
    let category = document.getElementById('category').value;
    let proximitySize = parseInt(document.getElementById('proximity-size').value);

    // Create form data object
    let formData = new FormData();
    formData.append('attraction_name', attractionName);
    formData.append('latitude', latitude);
    formData.append('longitude', longitude);
    formData.append('photo', photo);
    formData.append('description', description);
    formData.append('category', category);
    formData.append('proximity_size', proximitySize);

    // Send data to the server
    fetch('http://localhost/travelwatch/save_attraction.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Refresh the map to show the new marker
            loadAttractions();
            // Clear the form
            document.getElementById('attraction-form').reset();
        } else {
            console.error('Error saving attraction:', response.statusText);
            alert('Error saving attraction. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error saving attraction:', error);
        alert('Error saving attraction. Please check your connection.');
    });
});
