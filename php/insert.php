<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "yamifood_db";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Get input
$user = $_POST['username'];
$pass = $_POST['password'];
$email = $_POST['email'];

// Insert data
$sql = "INSERT INTO customer_details (username, password, email) VALUES ('$user', '$pass', '$email')";

if ($conn->query($sql) === TRUE) {
    echo "Registration successful! <a href='login.html'>Login here</a>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
