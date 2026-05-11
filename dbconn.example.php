<?php
// Rename this file to dbconn.php and update with your actual database credentials
$host = "localhost";       // or your DB host
$username = "root";        // your DB username
$password = "";            // your DB password
$database = "flowercafe_db";    // your database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
