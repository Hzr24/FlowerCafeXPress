<?php
session_start();
require_once 'dbconn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);

    if (empty($phone)) {
        $error = "Phone number cannot be empty.";
    } else {
        $stmt = $conn->prepare("UPDATE customer_details SET phone = ? WHERE user_id = ?");
        $stmt->bind_param("si", $phone, $user_id);

        if ($stmt->execute()) {
            $_SESSION['phone'] = $phone;
            $success = "Phone number updated successfully.";
        } else {
            $error = "Failed to update phone number.";
        }
    }
}

// Fetch current info
$user = $conn->query("SELECT name, email, phone FROM customer_details WHERE user_id = $user_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>📱 My Profile</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Name</label>
            <input class="form-control" value="<?= htmlspecialchars($user['name']) ?>" readonly>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Phone</button>
        <a href="index.php" class="btn btn-secondary">⬅ Back</a>
    </form>
</body>
</html>
