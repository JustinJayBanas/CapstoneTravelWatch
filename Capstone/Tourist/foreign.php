<?php 
// Include the database connection file at the beginning
include 'php/foreign.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelWatch Signup</title>
    <link rel="stylesheet" href="css/local.css">

    <script>
    // Show the modal when the account is created and redirect to login.php
    function showSuccessModal() {
        const modal = document.getElementById('successModal');
        const modalOkBtn = document.getElementById('modalOkBtn');

        // Show the modal
        modal.style.display = 'block';

        // Clear local storage when the user successfully signs up
        localStorage.clear();

        // On clicking OK, redirect to login.php
        modalOkBtn.addEventListener('click', function() {
            window.location.href = 'login.php';
        });
    }

    // Save form data to localStorage
    function saveFormData() {
        const username = document.getElementById('username').value;
        const age = document.getElementById('age').value;
        const gender = document.getElementById('gender').value;
        const country = document.getElementById('country').value;
        const password = document.getElementById('password').value;
        const email = document.getElementById('email').value;

        localStorage.setItem('username', username);
        localStorage.setItem('age', age);
        localStorage.setItem('gender', gender);
        localStorage.setItem('country', country);
        localStorage.setItem('password', password);
        localStorage.setItem('email', email);
    }

    // Load form data from localStorage
    function loadFormData() {
        if (localStorage.getItem('username')) {
            document.getElementById('username').value = localStorage.getItem('username');
        }
        if (localStorage.getItem('age')) {
            document.getElementById('age').value = localStorage.getItem('age');
        }
        if (localStorage.getItem('gender')) {
            document.getElementById('gender').value = localStorage.getItem('gender');
        }
        if (localStorage.getItem('country')) {
            document.getElementById('country').value = localStorage.getItem('country');
        }
        if (localStorage.getItem('password')) {
            document.getElementById('password').value = localStorage.getItem('password');
        }
        if (localStorage.getItem('email')) {
            document.getElementById('email').value = localStorage.getItem('email');
        }
    }

    // Wait until the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Load saved data from localStorage
        loadFormData();

        const signupButton = document.querySelector('.signup-btn');
        const termsCheckbox = document.getElementById('terms');

        // Initially disable the signup button
        signupButton.disabled = true;

        // Enable the "Signup" button only when the checkbox is checked
        termsCheckbox.addEventListener('change', function() {
            signupButton.disabled = !this.checked;
        });

        // Redirect to signup.php when the "Back" button is clicked
        document.querySelector('.back-btn').addEventListener('click', function() {
            window.location.href = 'signup.php';
        });

        // Save form data on any input change
        document.querySelectorAll('input, select').forEach(function(element) {
            element.addEventListener('input', saveFormData);
        });
    });
    
    </script>
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <img src="illustration.png" alt="Illustration">
        </div>
        <h2>Welcome!</h2>
        <p>Signup to continue with TravelWatch</p>
        <form method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="<?php echo $username_error_class; ?>" required>
                <!-- Display username error message if it exists -->
                <?php if (!empty($username_error_message)) { echo "<p class='error'>$username_error_message</p>"; } ?>
                <!-- Case sensitivity warning for username -->
                <p class="case-sensitive-warning">Username is case-sensitive. Example: "username" is different from "Username".</p>
            </div>
            <div class="input-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" required min="1">
            </div>
            <script>
                const ageInput = document.getElementById('age');
                
                ageInput.addEventListener('input', function() {
                    if (this.value < 1) {
                        this.value = 1; // Set the value to 1 if it's below the minimum
                    }
                });
            </script>
            <div class="input-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="input-group">
                <label for="country">List of Countries</label>
                <select id="country" name="country" required>
                    <option value="">Select country</option>
                </select>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const countries = [
                        "Australia", "Belgium", "Brunei", "Cambodia", "Canada", "China", "France", "Germany", "Guam", "Hong Kong", 
                        "India", "Indonesia", "Ireland", "Israel", "Italy", "Japan", "Laos", "Malaysia", "Myanmar", "Netherlands", 
                        "New Zealand", "Norway", "Russia", "Saudi Arabia", "Singapore", "South Korea", "Spain", "Sweden", "Switzerland", 
                        "Taiwan", "Thailand", "United Arab Emirates", "United Kingdom", "United States", "Vietnam", "Others"
                    ];

                    const countrySelect = document.getElementById("country");

                    // Populate the dropdown
                    countries.forEach(country => {
                        const option = document.createElement("option");
                        option.value = country.toLowerCase().replace(/\s+/g, '-');  // Format value as lowercase with hyphens
                        option.textContent = country;
                        countrySelect.appendChild(option);
                    });

                    // After populating the dropdown, set the value from localStorage
                    const savedCountry = localStorage.getItem('country');
                    if (savedCountry) {
                        countrySelect.value = savedCountry;
                    }

                    // Save the selected country to localStorage when the value changes
                    countrySelect.addEventListener('change', function() {
                        localStorage.setItem('country', countrySelect.value);
                    });
                });
            </script>

            <div class="input-group" style="position: relative; width: 100%;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required style="padding-right: 40px;">
                <span id="togglePassword" style="position: absolute; right: 10px; top: 35px; cursor: pointer;">
                    👁️
                </span>
            </div>
            <script>
                const passwordInput = document.getElementById('password');
                const togglePassword = document.getElementById('togglePassword');
                togglePassword.addEventListener('click', function () {
                    // Toggle the type attribute
                    const type = passwordInput.type === 'password' ? 'text' : 'password';
                    passwordInput.type = type; 
                    // Toggle the eye icon
                    togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
                });
            </script>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="<?php echo $email_error_class; ?>" required>
                <!-- Display email error message if it exists -->
                <?php if (!empty($email_error_message)) { echo "<p class='error'>$email_error_message</p>"; } ?>
                <!-- Case sensitivity warning for email -->
                <p class="case-sensitive-warning">Email is case-sensitive. Example: "user@example.com" is different from "User@example.com".</p>
            </div>
            <div class="input-group checkbox-group">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">Accept Terms and Conditions</label>
            </div>
            <div class="btn-group">
                <button type="button" class="back-btn">Back</button>
                <button type="submit" class="signup-btn">Signup</button>
            </div>
        </form>
        <div class="links">
            <a href="login.php">I already have an Account</a>
        </div>
        <!-- Success Modal -->
        <div id="successModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Account Created!</h2>
                <p>Your account has been successfully created.</p>
                <button id="modalOkBtn">OK</button>
            </div>
        </div>

        <!-- The Modal -->
        <div id="termsModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Terms and Conditions</h2>
                <p>By accepting these terms, you agree that the information you provided will be kept 
                    confidential and safe. We will not share your data with third parties without your consent.</p>
            </div>
        </div>
    </div>

    <script>
            // Get modal element
    var modal = document.getElementById("termsModal");
    var checkbox = document.getElementById("terms");
    var closeBtn = document.getElementsByClassName("close")[0];

    // When the checkbox is clicked, show the modal
    checkbox.addEventListener('click', function() {
        if (checkbox.checked) {
            modal.style.display = "block";
        }
    });

    // When the user clicks on (x), close the modal
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    // Close the modal when clicking outside of the modal content
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    } 
    </script>

    <!-- Trigger the success modal and redirect if account created -->
    <?php if ($account_created): ?>
        <script>
            showSuccessModal();
        </script>
    <?php endif; ?>
</body>
</html>