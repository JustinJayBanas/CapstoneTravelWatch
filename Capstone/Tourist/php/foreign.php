<?php
// Create a connection to the database
$conn = mysqli_connect("localhost", "root", "", "capstone");

// Check for connection errors
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables for success, email, and username error messages
$success_message = $email_error_message = $username_error_message = "";
$account_created = false; 
$email_error_class = "";  // To track the email error class
$username_error_class = ""; // To track the username error class

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize user input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $password = mysqli_real_escape_string($conn, password_hash($_POST['password'], PASSWORD_BCRYPT)); // Hash password for security
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email already exists (case-sensitive)
    $checkEmailQuery = "SELECT * FROM foreign_tourist WHERE BINARY email='$email'";
    $result = mysqli_query($conn, $checkEmailQuery);
    
    if (mysqli_num_rows($result) > 0) {
        $email_error_message = "Email already registered. Please use a different email.";
        $email_error_class = "email-error"; // Assign the error class to highlight the field
    } else {
        // Check if username already exists (case-sensitive)
        $checkUsernameQuery = "SELECT * FROM foreign_tourist WHERE BINARY username='$username'";
        $usernameResult = mysqli_query($conn, $checkUsernameQuery);
        
        if (mysqli_num_rows($usernameResult) > 0) {
            $username_error_message = "Username already taken. Please choose a different username.";
            $username_error_class = "username-error"; // Assign the error class to highlight the field
        } else {
            // Prepare SQL to insert user data into the 'local_tourist' table
            $sql = "INSERT INTO foreign_tourist (username, age, gender, country, password, email) 
                    VALUES ('$username', '$age', '$gender', '$country', '$password', '$email')";

            // Execute the query
            if (mysqli_query($conn, $sql)) {
                $success_message = "Signup successful!";
                $account_created = true;
            } else {
                $username_error_message = "Error: " . mysqli_error($conn); // Just in case of SQL errors
            }
        }
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
