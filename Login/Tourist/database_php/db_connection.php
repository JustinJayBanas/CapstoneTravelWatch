<?php
// Create a connection to the database
$conn = mysqli_connect("localhost", "root", "", "capstone");

// Check for connection errors
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}