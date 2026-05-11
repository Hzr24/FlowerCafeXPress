<?php
session_start();
header('Content-Type: application/json');
require_once('../dbconn.php');

// Check if admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Collect and sanitize input
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = floatval($_POST['price'] ?? 0);
$category = trim($_POST['category'] ?? '');
$availability = trim($_POST['availability'] ?? '1'); // Default to available

// Check required fields
if (!$name || !$description || !$price || !$category) {
    echo json_encode(['success' => false, 'message' => 'Missing fields']);
    exit;
}

// Handle image upload
$image_url = 'uploads/default.jpg'; // Fallback if no image uploaded

if (!empty($_FILES['image']['name'])) {
    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $unique_name = uniqid() . '_' . basename($_FILES["image"]["name"]);
    $target_file = $upload_dir . $unique_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_url = 'uploads/' . $unique_name;
    } else {
        echo json_encode(['success' => false, 'message' => 'Image upload failed']);
        exit;
    }
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO menu_details (name, description, price, category, image_url, availability) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssdssi", $name, $description, $price, $category, $image_url, $availability);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Menu item added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();

