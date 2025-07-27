<?php
$servername = "localhost";    // XAMPP default
$username = "root";           // default user
$password = "";               // default password is empty
$database = "caterlink_db";  // replace with your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>