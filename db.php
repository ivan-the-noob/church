<?php
// Database connection parameters
$servername = "localhost";  // Change this to your server name if necessary
$username = "root";         // Your MySQL username
$password = "";             // Your MySQL password (leave empty for localhost)
$dbname = "howchurch";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
