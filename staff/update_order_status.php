<?php
require_once "../dbconn.php";

$data = json_decode(file_get_contents('php://input'), true);
$order_id = $data['order_id'] ?? null;
$status = $data['status'] ?? null;

if (!$order_id || !$status) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$stmt = $conn->prepare("UPDATE order_details SET status = ? WHERE order_id = ?");
$stmt->bind_param("si", $status, $order_id);
$success = $stmt->execute();
$stmt->close();

echo json_encode(['success' => $success]);
