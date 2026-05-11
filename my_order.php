<?php
require_once "dbconn.php";
require_once "check_access.php";
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

<header class="top-navbar">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo flowercafexpress.png" alt="" />
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars-rs-food">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbars-rs-food">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <button id="darkModeToggle" class="btn btn-dark btn-sm px-2 py-1" style="font-size: 0.9rem; line-height: 1;">🌓</button>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders - FlowerCafeXpress</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-5">
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
      <?php while ($order = $orders->fetch_assoc()): ?>
        <?php
          $orderId = $order['order_id'];
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
    </tbody>
  </table>
</body>
</html>
