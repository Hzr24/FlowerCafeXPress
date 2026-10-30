<?php
$base_path = '../';
require_once "../dbconn.php";
require_once "../check_access.php";
require_role(['customer']);

$user_id = $_SESSION['user_id'];

$sql = "SELECT 
            o.order_id, 
            o.status, 
            MAX(o.order_time) as order_time,
            SUM(o.subtotal) as total_price
        FROM order_details o
        WHERE o.user_id = ?
        GROUP BY o.order_id
        ORDER BY order_time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Orders - FlowerCaféXpress</title>

    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="../images/apple-touch-icon.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet" href="../css/custom.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="all-page-title page-breadcrumb">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-12">
                <h1>My Orders</h1>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <h2 class="font-weight-bold mb-1">Receipt & Order History</h2>
            <p class="text-muted mb-0">Track your current meal preparation status and past orders.</p>
        </div>
        <div class="mt-3 mt-sm-0">
            <a href="menu.php" class="btn btn-primary">
                <i class="fa fa-cutlery mr-1"></i> Order More Food
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 120px;">Order ID</th>
                            <th>Items Ordered</th>
                            <th style="width: 130px;">Total</th>
                            <th style="width: 150px;">Status</th>
                            <th style="width: 180px;">Order Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($orders && $orders->num_rows > 0): ?>
                            <?php while ($order = $orders->fetch_assoc()): ?>
                                <?php
                                    $orderId = (int)$order['order_id'];
                                    $items = $conn->query("SELECT m.name, o.quantity 
                                                           FROM order_details o 
                                                           JOIN menu_details m ON o.item_id = m.item_id 
                                                           WHERE o.order_id = $orderId");

                                    $itemList = '';
                                    while ($item = $items->fetch_assoc()) {
                                        $itemList .= '<span class="d-inline-block mr-2 mb-1 badge badge-light border text-dark font-weight-normal px-2 py-1"><strong class="text-warning">' . htmlspecialchars($item['name']) . '</strong> &times; ' . $item['quantity'] . '</span>';
                                    }

                                    $status = strtolower($order['status']);
                                    $badgeClass = 'badge-secondary';
                                    if ($status === 'pending') {
                                        $badgeClass = 'badge-warning text-dark';
                                    } elseif ($status === 'preparing') {
                                        $badgeClass = 'badge-primary';
                                    } elseif ($status === 'ready' || $status === 'ready for pickup') {
                                        $badgeClass = 'badge-info';
                                    } elseif ($status === 'completed') {
                                        $badgeClass = 'badge-success';
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <span class="font-weight-bold" style="color: var(--fc-primary);">#<?= $orderId ?></span>
                                    </td>
                                    <td><?= $itemList ?></td>
                                    <td class="font-weight-bold">RM <?= number_format($order['total_price'], 2) ?></td>
                                    <td>
                                        <span class="badge <?= $badgeClass ?> px-3 py-2 text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">
                                            <?= htmlspecialchars($order['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        <i class="fa fa-clock-o mr-1"></i> <?= date('d M Y, h:i A', strtotime($order['order_time'])) ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4 text-muted">
                                        <i class="fa fa-shopping-basket fa-3x mb-3 text-muted"></i>
                                        <h5>No orders placed yet</h5>
                                        <p class="mb-3">Feeling hungry? Browse our fresh menu and grab your favorite meal.</p>
                                        <a href="menu.php" class="btn btn-outline-theme">Explore Menu</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
<?php include '../includes/modals.php'; ?>
</body>
</html>
