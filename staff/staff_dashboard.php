<?php
require_once "../dbconn.php";
require_once "../check_access.php";
require_role(['staff']); // Only staff allowed

// Fetch incomplete orders
$sql = "SELECT 
  o.order_id, 
  o.user_id,
  u.name, 
  u.phone, 
  o.status, 
  MAX(o.order_time) as order_time,
  SUM(o.subtotal) as total_price
FROM order_details o
JOIN customer_details u ON o.user_id = u.user_id
WHERE o.status != 'completed'
GROUP BY o.order_id
ORDER BY order_time DESC;";
$orders = $conn->query($sql);
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
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="order_history.php">Order History</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
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
  <title>Staff Dashboard - FlowerCafeXpress</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
  <h2 class="mb-4">📦 Current Orders (Staff View)</h2>

  <table class="table table-bordered table-striped">
    <thead class="thead-dark">
      <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Items</th>
        <th>Status</th>
        <th>Update</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $orders->fetch_assoc()): ?>
        <?php
          $orderId = $row['order_id'];
          $items = $conn->prepare("SELECT m.name, o.quantity 
                         FROM order_details o 
                         JOIN menu_details m ON o.item_id = m.item_id 
                         WHERE o.order_id = ?");
          $items->bind_param("i", $orderId);
          $items->execute();
          $itemResult = $items->get_result();

          $itemList = '';
          while ($item = $itemResult->fetch_assoc()) {
              $itemList .= htmlspecialchars($item['name']) . ' x ' . $item['quantity'] . '<br>';
          }
          $items->close();

        ?>
        <tr id="order-<?= $orderId ?>">
          <td><?= $orderId ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['phone']) ?></td>
          <td><?= $itemList ?></td>
          <td><span class="badge badge-info"><?= $row['status'] ?></span></td>
          <td>
            <select class="form-control" onchange="updateStatus(<?= $orderId ?>, this.value)">
              <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
              <option value="preparing" <?= $row['status'] === 'preparing' ? 'selected' : '' ?>>Preparing</option>
              <option value="completed">Completed</option>
            </select>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script>
function updateStatus(orderId, newStatus) {
  fetch('update_order_status.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ order_id: orderId, status: newStatus })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      if (newStatus === 'completed') {
        document.getElementById('order-' + orderId).remove();
      } else {
        alert("Status updated to " + newStatus);
        location.reload();
      }
    } else {
      alert("Failed to update status.");
    }
  })
  .catch(err => {
    console.error(err);
    alert("Error contacting server.");
  });
}
</script>

</body>
</html>
