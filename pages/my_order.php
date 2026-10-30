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
  <title>My Orders - FlowerCafeXpress</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/custom.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
  <h2 class="mb-4">🧾 My Orders</h2>

  <table class="table table-bordered">
    <thead class="thead-dark">
      <tr>
        <th>Order ID</th>
        <th>Items</th>
        <th>Total</th>
        <th>Status</th>
        <th>Order Time</th>
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
                $itemList .= htmlspecialchars($item['name']) . ' x ' . $item['quantity'] . '<br>';
            }
          ?>
          <tr>
            <td><?= $orderId ?></td>
            <td><?= $itemList ?></td>
            <td>RM<?= number_format($order['total_price'], 2) ?></td>
            <td><span class="badge badge-info"><?= htmlspecialchars($order['status']) ?></span></td>
            <td><?= $order['order_time'] ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center">No orders found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/scripts.php'; ?>
<?php include '../includes/modals.php'; ?>
</body>
</html>
