<?php
require_once "../dbconn.php";
require_once "../check_access.php";
require_role(['staff']);

// Fetch all completed orders (grouped by order_id)
$sql = "SELECT 
            o.order_id,
            o.user_id,
            u.name,
            u.phone,
            MAX(o.order_time) AS order_time,
            SUM(o.subtotal) AS total_price
        FROM order_details o
        JOIN customer_details u ON o.user_id = u.user_id
        WHERE o.status = 'completed'
        GROUP BY o.order_id
        ORDER BY order_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order History - FlowerCafeXpress</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-4">

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
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="staff_dashboard.php">Staff Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<h2 class="my-4">✅ Completed Order History</h2>

<table class="table table-bordered table-hover">
  <thead class="thead-dark">
    <tr>
      <th>Order ID</th>
      <th>Customer</th>
      <th>Phone</th>
      <th>Items</th>
      <th>Total</th>
      <th>Time</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <?php
        // Get item list for this order
        $orderId = $row['order_id'];
        $itemQuery = $conn->prepare("SELECT m.name, o.quantity FROM order_details o JOIN menu_details m ON o.item_id = m.item_id WHERE o.order_id = ?");
        $itemQuery->bind_param("i", $orderId);
        $itemQuery->execute();
        $itemResult = $itemQuery->get_result();
        $itemList = '';
        while ($item = $itemResult->fetch_assoc()) {
            $itemList .= htmlspecialchars($item['name']) . ' x ' . $item['quantity'] . '<br>';
        }
        $itemQuery->close();
      ?>
      <tr>
        <td><?= $orderId ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= $itemList ?></td>
        <td>RM <?= number_format($row['total_price'], 2) ?></td>
        <td><?= $row['order_time'] ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>
