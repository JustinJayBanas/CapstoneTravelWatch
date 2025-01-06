// Example: Update user location every 10 seconds
if (navigator.geolocation) {
    setInterval(() => {
        navigator.geolocation.getCurrentPosition(position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Send the location to the server using a POST request
            fetch('update_user_location.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&lat=${lat}&lng=${lng}`,
            })
                .then(response => response.text())
                .then(data => console.log('Location updated:', data))
                .catch(error => console.error('Error updating location:', error));
        });
    }, 10000); // Update every 10 seconds
}
