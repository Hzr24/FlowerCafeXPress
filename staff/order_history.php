<?php
$base_path = '../';
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
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Order History - FlowerCaféXpress</title>

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
        <h1>Completed Orders</h1>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div>
      <h2 class="font-weight-bold mb-1">Order History Log</h2>
      <p class="text-muted mb-0">Record of completed orders handed off to customers.</p>
    </div>
    <div class="mt-3 mt-sm-0">
      <a href="staff_dashboard.php" class="btn btn-outline-theme">
        <i class="fa fa-cutlery mr-1"></i> Active Kitchen Orders
      </a>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th style="width: 110px;">Order ID</th>
              <th>Customer</th>
              <th>Phone</th>
              <th>Items</th>
              <th style="width: 130px;">Total</th>
              <th style="width: 180px;">Completion Time</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                  $orderId = $row['order_id'];
                  $itemQuery = $conn->prepare("SELECT m.name, o.quantity FROM order_details o JOIN menu_details m ON o.item_id = m.item_id WHERE o.order_id = ?");
                  $itemQuery->bind_param("i", $orderId);
                  $itemQuery->execute();
                  $itemResult = $itemQuery->get_result();
                  $itemList = '';
                  while ($item = $itemResult->fetch_assoc()) {
                      $itemList .= '<span class="d-inline-block mr-2 mb-1 badge badge-light border text-dark font-weight-normal px-2 py-1"><strong class="text-warning">' . htmlspecialchars($item['name']) . '</strong> &times; ' . $item['quantity'] . '</span>';
                  }
                  $itemQuery->close();
                ?>
                <tr>
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
                  <td class="font-weight-bold">RM <?= number_format($row['total_price'], 2) ?></td>
                  <td class="text-muted small">
                    <i class="fa fa-clock-o mr-1"></i> <?= date('d M Y, h:i A', strtotime($row['order_time'])) ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="fa fa-folder-open-o fa-3x mb-3"></i>
                  <h5>No past orders recorded</h5>
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

</body>
</html>
