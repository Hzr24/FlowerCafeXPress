<?php
$base_path = '../';
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Staff Dashboard - FlowerCaféXpress</title>

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
        <h1>Kitchen & Orders Desk</h1>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div>
      <h2 class="font-weight-bold mb-1">Active Kitchen Orders</h2>
      <p class="text-muted mb-0">Update orders as kitchen staff begins preparation and hands off meals to students.</p>
    </div>
    <div class="mt-3 mt-sm-0">
      <a href="order_history.php" class="btn btn-outline-theme">
        <i class="fa fa-history mr-1"></i> View Order History
      </a>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th style="width: 100px;">Order ID</th>
              <th>Customer</th>
              <th>Contact</th>
              <th>Items Ordered</th>
              <th style="width: 140px;">Current Status</th>
              <th style="width: 170px;">Change Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($orders && $orders->num_rows > 0): ?>
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
                      $itemList .= '<span class="d-inline-block mr-2 mb-1 badge badge-light border text-dark font-weight-normal px-2 py-1"><strong class="text-warning">' . htmlspecialchars($item['name']) . '</strong> &times; ' . $item['quantity'] . '</span>';
                  }
                  $items->close();

                  $status = strtolower($row['status']);
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
                <tr id="order-<?= $orderId ?>">
                  <td>
                    <span class="font-weight-bold" style="color: var(--fc-primary);">#<?= $orderId ?></span>
                  </td>
                  <td class="font-weight-bold"><?= htmlspecialchars($row['name']) ?></td>
                  <td>
                    <a href="tel:<?= htmlspecialchars($row['phone']) ?>" class="text-decoration-none">
                      <i class="fa fa-phone mr-1 text-muted"></i> <?= htmlspecialchars($row['phone']) ?>
                    </a>
                  </td>
                  <td><?= $itemList ?></td>
                  <td>
                    <span class="badge <?= $badgeClass ?> px-3 py-2 text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">
                      <?= htmlspecialchars($row['status']) ?>
                    </span>
                  </td>
                  <td>
                    <select class="form-control form-control-sm" onchange="updateStatus(<?= $orderId ?>, this.value)">
                      <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                      <option value="preparing" <?= $status === 'preparing' ? 'selected' : '' ?>>🍳 Preparing</option>
                      <option value="completed">✅ Completed</option>
                    </select>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center py-5">
                  <div class="py-4 text-muted">
                    <i class="fa fa-check-circle-o fa-3x mb-3 text-success"></i>
                    <h5>All caught up!</h5>
                    <p class="mb-0">There are no pending orders in the kitchen right now.</p>
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
        const row = document.getElementById('order-' + orderId);
        if (row) {
          row.style.transition = 'all 0.3s ease';
          row.style.opacity = '0';
          setTimeout(() => row.remove(), 300);
        }
      } else {
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
