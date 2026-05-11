<?php
session_start();
header('Content-Type: application/json');
require_once('../dbconn.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Use null coalescing to avoid issues with empty fields
$item_id = $_POST['item_id'] ?? null;
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = $_POST['price'] ?? '';
$availability = $_POST['availability'] ?? 1;

if (!$item_id || $name === '' || $description === '' || $price === '') {
    echo json_encode(['success' => false, 'message' => 'Missing fields']);
    exit;
}

// Optional image upload
$image_url = null;
if (!empty($_FILES['image']['name'])) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $filename = uniqid() . '_' . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_url = 'uploads/' . $filename;
    } else {
        echo json_encode(['success' => false, 'message' => 'Image upload failed']);
        exit;
    }
}

// Prepare and bind the appropriate SQL statement
if ($image_url) {
    $stmt = $conn->prepare("UPDATE menu_details SET name=?, description=?, price=?, availability=?, image_url=? WHERE item_id=?");
    $stmt->bind_param("ssdisi", $name, $description, $price, $availability, $image_url, $item_id);
} else {
    $stmt = $conn->prepare("UPDATE menu_details SET name=?, description=?, price=?, availability=? WHERE item_id=?");
    $stmt->bind_param("ssdii", $name, $description, $price, $availability, $item_id);
}

// Execute and return result
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Menu item updated']);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed']);
}

$stmt->close();
$conn->close();
?>