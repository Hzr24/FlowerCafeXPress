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

// Get input safely
$user = $_POST['username'];
$pass = $_POST['password'];
$email = $_POST['email'];
$role = $_POST['role'] ?? ''; // Avoid undefined key error

// Check if username exists
$check = $conn->query("SELECT * FROM customer_details WHERE username = '$user'");
if ($check->num_rows > 0) {
    echo "Username already exists. <a href='signup.html'>Try another one</a>";
    exit;
}

// Insert data
$sql = "INSERT INTO customer_details (username, password, email, role) VALUES ('$user', '$pass', '$email', '$role')";
if ($conn->query($sql) === TRUE) {
    echo "Registration successful! <a href='login.html'>Login here</a>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
