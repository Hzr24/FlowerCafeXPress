<?php
require_once "../dbconn.php";
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['items']) || empty($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'No items selected']);
    exit;
}

$name = $data['name'] ?? '';
$phone = $data['phone'] ?? '';
$items = $data['items'] ?? [];

if (empty($name) || empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Name and phone required']);
    exit;
}

// Get user ID
$stmt = $conn->prepare("SELECT user_id FROM customer_details WHERE name = ? AND phone = ?");
$stmt->bind_param("ss", $name, $phone);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

// Get next order_id manually
$res = $conn->query("SELECT MAX(order_id) AS max_id FROM order_details");
$row = $res->fetch_assoc();
$order_id = $row['max_id'] ? $row['max_id'] + 1 : 1;

$status = 'pending';
$order_time = date("Y-m-d H:i:s");

$stmt = $conn->prepare("INSERT INTO order_details (order_id, user_id, item_id, quantity, subtotal, status, order_time) VALUES (?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

foreach ($items as $item) {
    $item_id = $item['item_id'];
    $qty = $item['quantity'];
    $price = $item['price'];
    $subtotal = $qty * $price;

    $stmt->bind_param("iiiidss", $order_id, $user_id, $item_id, $qty, $subtotal, $status, $order_time);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Insert failed: ' . $stmt->error]);
        exit;
    }
}

$stmt->close();
echo json_encode(['success' => true, 'message' => 'Order placed successfully']);