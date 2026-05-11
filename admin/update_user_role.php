<?php
session_start();
header('Content-Type: application/json');
require_once('../dbconn.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_POST['user_id'] ?? '';
$new_role = $_POST['new_role'] ?? '';

if (!$user_id || !$new_role) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

$stmt = $conn->prepare("UPDATE customer_details SET role = ? WHERE user_id = ?");
$stmt->bind_param("si", $new_role, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
