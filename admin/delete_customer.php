<?php
include '../check_access.php';
require_role(['admin']);
include '../dbconn.php';

if (!isset($_GET['user_id'])) {
    die("No user ID provided.");
}

$user_id = $_GET['user_id'];

$stmt = $conn->prepare("DELETE FROM customer_details WHERE user_id = ? LIMIT 1"); 
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: customer_details.php?message=User deleted");
    exit();
} else {
    die("Failed to delete user.");
}

