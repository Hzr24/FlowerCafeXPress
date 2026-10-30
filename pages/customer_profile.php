<?php
session_start();
require_once '../dbconn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../index.php");
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
$stmt = $conn->prepare("SELECT name, email, phone FROM customer_details WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch order statistics
$stmt_orders = $conn->prepare("SELECT COUNT(DISTINCT order_id) as order_count FROM order_details WHERE user_id = ?");
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$order_stats = $stmt_orders->get_result()->fetch_assoc();
$order_count = $order_stats['order_count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile - FlowerCaféXpress</title>

    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="../images/apple-touch-icon.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet" href="../css/custom.css">
</head>

<body>
    <?php $base_path = '../'; include '../includes/header.php'; ?>

    <div class="all-page-title page-breadcrumb">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-12">
                    <h1>My Profile</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle mr-2"></i> <?= htmlspecialchars($success) ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-triangle mr-2"></i> <?= htmlspecialchars($error) ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 70px; height: 70px; background: var(--fc-primary-light); color: var(--fc-primary); font-size: 32px;">
                                <i class="fa fa-user"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 font-weight-bold"><?= htmlspecialchars($user['name'] ?? 'Student') ?></h3>
                                <p class="text-muted mb-0"><i class="fa fa-envelope-o mr-1"></i> <?= htmlspecialchars($user['email'] ?? '') ?></p>
                            </div>
                            <div class="ml-auto text-right d-none d-sm-block">
                                <span class="badge badge-warning role-badge px-3 py-2">Customer</span>
                            </div>
                        </div>

                        <!-- Quick Stats Row -->
                        <div class="row mb-4">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <div class="p-3 rounded" style="background: var(--fc-primary-light); border: 1px solid rgba(208, 167, 114, 0.2);">
                                    <small class="text-muted text-uppercase font-weight-bold">Total Orders Placed</small>
                                    <h2 class="mt-1 mb-0 font-weight-bold" style="color: var(--fc-primary);"><?= $order_count ?></h2>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded" style="background: var(--fc-bg-elevated); border: 1px solid var(--fc-border);">
                                    <small class="text-muted text-uppercase font-weight-bold">Primary Campus</small>
                                    <h5 class="mt-1 mb-0 font-weight-bold">UiTM Kedah</h5>
                                </div>
                            </div>
                        </div>

                        <form method="POST">
                            <h5 class="font-weight-bold mb-3"><i class="fa fa-id-card-o mr-2" style="color: var(--fc-primary);"></i> Account Details</h5>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Full Name</label>
                                <input class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" readonly style="background-color: var(--fc-bg-elevated) !important;">
                                <small class="form-text text-muted">Name is linked to your student account and cannot be modified.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Email Address</label>
                                <input class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly style="background-color: var(--fc-bg-elevated) !important;">
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Phone Number (For Order Notifications)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background: var(--fc-bg-elevated); border-color: var(--fc-border);"><i class="fa fa-phone"></i></span>
                                    </div>
                                    <input name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required placeholder="e.g. 017-1234567">
                                </div>
                                <small class="form-text text-muted">Used by cafe staff to contact you when your food is ready.</small>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-2">
                                <a href="../pages/my_order.php" class="btn btn-outline-secondary">
                                    <i class="fa fa-list-alt mr-1"></i> View My Orders
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save mr-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <?php include '../includes/scripts.php'; ?>
</body>
</html>
