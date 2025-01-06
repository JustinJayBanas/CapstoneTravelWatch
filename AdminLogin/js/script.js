// Function to show error message if there's an error in the URL
function showModal() {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    if (error) {
        document.getElementById('errorMessage').textContent = decodeURIComponent(error);
        document.getElementById('errorMessage').style.display = "block";
    }
}

// Function to close the error message
function closeModal() {
    document.getElementById('errorMessage').style.display = "none";
}

// Function to handle the "Remember Me" functionality
function handleRememberMe() {
    const username = document.getElementById('username').value;
    const rememberMeChecked = document.getElementById('remember').checked;

    // Save username in localStorage if "Remember Me" is checked
    if (rememberMeChecked) {
        localStorage.setItem('username', username);
    } else {
        localStorage.removeItem('username');
    }
}

// Function to check localStorage for username on page load
function autoFillUsername() {
    const storedUsername = localStorage.getItem('username');
    if (storedUsername) {
        document.getElementById('username').value = storedUsername;
        document.getElementById('remember').checked = true; // Check "Remember Me"
    }
}

// Run autoFillUsername on page load
window.onload = function() {
    showModal();
    autoFillUsername(); // Autocomplete username if saved
};
