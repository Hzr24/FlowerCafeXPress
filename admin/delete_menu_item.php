<?php
session_start();
require_once('../dbconn.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../menu.php");
    exit;
}

$item_id = $_GET['id'] ?? null;
if ($item_id) {
    $stmt = $conn->prepare("DELETE FROM menu_details WHERE item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
}

header("Location: ../menu.php");
exit;

