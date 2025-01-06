<?php 
// Include the database connection file at the beginning
include 'database_php/create_account.php'; 
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

    // Save form data to localStorage whenever the form changes
    function saveFormData() {
    const formData = {
        username: document.getElementById('username').value,
        birthdate: document.getElementById('birthdate').value,
        gender: document.getElementById('gender').value,
        country: document.getElementById('country').value,
        province: document.getElementById('province').value,
        password: document.getElementById('password').value,
        email: document.getElementById('email').value
    };
    sessionStorage.setItem('formData', JSON.stringify(formData));
    }

    // Load form data from sessionStorage when the page loads
    function loadFormData() {
        const savedFormData = sessionStorage.getItem('formData');
        if (savedFormData) {
            const formData = JSON.parse(savedFormData);

            document.getElementById('username').value = formData.username || '';
            document.getElementById('birthdate').value = formData.birthdate || '';
            document.getElementById('gender').value = formData.gender || '';
            document.getElementById('country').value = formData.country || '';
            document.getElementById('province').value = formData.province || '';
            document.getElementById('password').value = formData.password || '';
            document.getElementById('email').value = formData.email || '';
        }
    }

    // Add event listeners to automatically save data on form input changes
    window.onload = function () {
        loadFormData();

        const formElements = document.querySelectorAll('#signupForm input, #signupForm select');
        formElements.forEach(element => {
            element.addEventListener('input', saveFormData);
        });
    };

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
        <form id="signupForm", method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="<?php echo $username_error_class; ?>" required>
                <!-- Display username error message if it exists -->
                <?php if (!empty($username_error_message)) { echo "<p class='error'>$username_error_message</p>"; } ?>
                <!-- Case sensitivity warning for username -->
                <p class="case-sensitive-warning">Username is case-sensitive. Example: "username" is different from "Username".</p>
            </div>
            <div class="input-group">
                <label for="birthdate">Birthdate</label> 
                <input type="date" id="birthdate" name="birthdate" required>
            </div>  

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

            <div class="input-group">
                <label for="province">List of Provinces</label>
                <select id="province" name="province" required disabled>
                    <option value="">Select province</option>
                </select>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const countries = [
                        "Australia", "Belgium", "Brunei", "Cambodia", "Canada", "China", "France", "Germany", "Guam", "Hong Kong", 
                        "India", "Indonesia", "Ireland", "Israel", "Italy", "Japan", "Laos", "Malaysia", "Myanmar", "Netherlands", 
                        "New Zealand", "Norway", "Philippines", "Russia", "Saudi Arabia", "Singapore", "South Korea", "Spain", "Sweden", "Switzerland", 
                        "Taiwan", "Thailand", "United Arab Emirates", "United Kingdom", "United States", "Vietnam", "Others"
                    ];

                    const provinces = [
                        "Abra", "Albay", "Antique", "Aklan", "Apayao", "Aurora", "Bataan", "Batangas", "Benguet", "Biliran", "Bohol", "Bulacan", "Cagayan",
                        "Camarines Norte", "Camarines Sur", "Capiz", "Catanduanes", "Cavite", "Cebu", "Eastern Samar", "Guimaras", "Ifugao", "Ilocos Norte",
                        "Ilocos Sur", "Iloilo", "Isabela", "Kalinga", "La Union", "Laguna", "Leyte", "Marinduque", "Masbate", "Mountain Province",
                        "Negros Occidental", "Negros Oriental", "Nueva Ecija", "Nueva Vizcaya", "Occidental Mindoro", "Oriental Mindoro", "Palawan", "Pampanga",
                        "Pangasinan", "Quezon", "Quirino", "Rizal", "Romblon", "Samar", "Sorsogon", "Southern Leyte", "Tarlac", "Zambales", "Others"
                    ];

                    const countrySelect = document.getElementById("country");
                    const provinceSelect = document.getElementById("province");

                    // Populate the country dropdown
                    countries.forEach(country => {
                        const option = document.createElement("option");
                        option.value = country.toLowerCase().replace(/\s+/g, '-');  // Format value as lowercase with hyphens
                        option.textContent = country;
                        countrySelect.appendChild(option);
                    });

                    // After populating the country dropdown, set the value from localStorage
                    const savedCountry = localStorage.getItem('country');
                    if (savedCountry) {
                        countrySelect.value = savedCountry;
                        toggleProvinceField(savedCountry);  // Enable/Disable province based on saved country
                    }

                    // Save the selected country to localStorage when the value changes
                    countrySelect.addEventListener('change', function() {
                        localStorage.setItem('country', countrySelect.value);
                        toggleProvinceField(countrySelect.value);
                    });

                    // Populate the province dropdown
                    provinces.forEach(province => {
                        const option = document.createElement("option");
                        option.value = province.toLowerCase().replace(/\s+/g, '-');  // Format value as lowercase with hyphens
                        option.textContent = province;
                        provinceSelect.appendChild(option);
                    });

                    // After populating the province dropdown, set the value from localStorage
                    const savedProvince = localStorage.getItem('province');
                    if (savedProvince) {
                        provinceSelect.value = savedProvince;
                    }

                    // Save the selected province to localStorage when the value changes
                    provinceSelect.addEventListener('change', function() {
                        localStorage.setItem('province', provinceSelect.value);
                    });

                    // Enable or disable the province field based on the selected country
                    function toggleProvinceField(selectedCountry) {
                        if (selectedCountry === 'philippines') {
                            provinceSelect.disabled = false; // Enable province field
                        } else {
                            provinceSelect.disabled = true;  // Disable province field
                            provinceSelect.value = "";  // Clear province selection
                        }
                    }
                });
            </script>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="showPassword">
                <label for="showPassword">Show Password</label>
            </div>
            <script>
                const passwordInput = document.getElementById('password');
                const showPasswordCheckbox = document.getElementById('showPassword');

                showPasswordCheckbox.addEventListener('change', function () {
                    // Toggle the type attribute based on checkbox state
                    const type = showPasswordCheckbox.checked ? 'text' : 'password';
                    passwordInput.type = type;
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
            <div class="checkbox-group">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">Accept Terms and Conditions</label>
            </div>
            <div class="btn-group">
                <button type="submit" class="signup-btn">Create Account</button>
            </div>
            <script>
                document.getElementById('signupForm').addEventListener('submit', function(event) {
                    const birthdateInput = document.getElementById('birthdate');
                    const birthdate = new Date(birthdateInput.value);
                    const today = new Date();

                    // Reset time to 00:00:00 for comparison
                    today.setHours(0, 0, 0, 0);

                    // Check if the birthdate is in the future
                    if (birthdate > today) {
                        alert('Birthdate cannot exceed the current date.');
                        event.preventDefault(); // Prevent form submission
                        return; // Exit the function
                    }

                    // Calculate age
                    const age = today.getFullYear() - birthdate.getFullYear();
                    const monthDifference = today.getMonth() - birthdate.getMonth();
                    
                    // Adjust age if the birth date hasn't occurred yet this year
                    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthdate.getDate())) {
                        age--;
                    }

                    // Check if the age is less than or equal to 5
                    if (age <= 5) {
                        alert('You must be older than 5 years to create an account.');
                        event.preventDefault(); // Prevent form submission
                    }
                });
            </script>
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