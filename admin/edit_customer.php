<?php
include '../check_access.php';
require_role(['admin']);
include '../dbconn.php';

if (!isset($_GET['user_id'])) {
    die("No user selected.");
}

$user_id = $_GET['user_id'];
$user = $conn->query("SELECT * FROM customer_details WHERE user_id = $user_id AND role = 'customer'")->fetch_assoc();

if (!$user) {
    die("Customer not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (empty($name) || empty($email)) {
        $error = "Name and email are required.";
    } else {
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE customer_details SET name=?, email=?, password=? WHERE user_id=?");
            $stmt->bind_param("sssi", $name, $email, $password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE customer_details SET name=?, email=? WHERE user_id=?");
            $stmt->bind_param("ssi", $name, $email, $user_id);
        }

        if ($stmt->execute()) {
            header("Location: customer_details.php?message=Customer updated");
            exit();
        } else {
            $error = "Failed to update customer.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="p-4">
    <div class="container">
        <h2>Edit Customer</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="post">
            <div class="form-group">
                <label>Name</label>
                <input name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Password (leave blank to keep current)</label>
                <input name="password" type="password" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="customer_details.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

