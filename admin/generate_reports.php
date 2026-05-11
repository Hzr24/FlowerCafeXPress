<?php
require_once "../dbconn.php";
require_once "../check_access.php";
require_role(['admin']);

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$search = $_GET['search'] ?? '';

$filter = "";

// Apply search filter by item name
if (!empty($search)) {
    $filter .= "WHERE o.order_id IN (
        SELECT o2.order_id
        FROM order_details o2
        JOIN menu_details m2 ON o2.item_id = m2.item_id
        WHERE m2.name LIKE '%$search%'
    )";
}

// Apply date filter if provided
if (!empty($from) && !empty($to)) {
    $dateFilter = "o.order_time BETWEEN '$from' AND '$to 23:59:59'";
    if ($filter) {
        $filter .= " AND $dateFilter";
    } else {
        $filter = "WHERE $dateFilter";
    }
}

$query = "
    SELECT 
        o.order_id, 
        u.name, 
        o.status, 
        MAX(o.order_time) AS order_time,
        GROUP_CONCAT(m.name, ' x ', o.quantity SEPARATOR ', ') AS items,
        SUM(o.subtotal) AS total
    FROM order_details o
    JOIN customer_details u ON o.user_id = u.user_id
    JOIN menu_details m ON o.item_id = m.item_id
    $filter
    GROUP BY o.order_id
    ORDER BY order_time DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Generate Report</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    @media print {
      body {
        background: white;
        color: black;
      }
      .form-inline,
      button,
      a.btn {
        display: none !important;
      }
      .container {
        width: 100%;
        margin: 0;
        padding: 0;
      }
    }
  </style>
</head>
<body class="container mt-4">

<h2 class="mb-3">📈 System Reports</h2>

<form method="GET" class="form-inline mb-4">
  <label class="mr-2">From:</label>
  <input type="date" name="from" class="form-control mr-3" value="<?= htmlspecialchars($from) ?>">

  <label class="mr-2">To:</label>
  <input type="date" name="to" class="form-control mr-3" value="<?= htmlspecialchars($to) ?>">

  <label class="mr-2">Search Item:</label>
  <input type="text" name="search" class="form-control mr-3" placeholder="" value="<?= htmlspecialchars($search) ?>">

  <button type="submit" class="btn btn-primary mr-2">Filter</button>
  <a href="generate_reports.php" class="btn btn-secondary">Reset</a>
</form>

<button onclick="window.print()" class="btn btn-outline-secondary mb-4">
  🖨 Print / Save as PDF
</button>

<table class="table table-bordered table-striped">
  <thead class="thead-dark">
    <tr>
      <th>Order ID</th>
      <th>Customer</th>
      <th>Status</th>
      <th>Items Ordered</th>
      <th>Total (RM)</th>
      <th>Order Time</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $grandTotal = 0;
    while ($row = $result->fetch_assoc()): 
      $grandTotal += $row['total'];
    ?>
    <tr>
      <td><?= $row['order_id'] ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= ucfirst($row['status']) ?></td>
      <td><?= htmlspecialchars($row['items']) ?></td>
      <td><?= number_format($row['total'], 2) ?></td>
      <td><?= $row['order_time'] ?></td>
    </tr>
    <?php endwhile; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="4">Total Revenue</th>
      <th colspan="2">RM <?= number_format($grandTotal, 2) ?></th>
    </tr>
  </tfoot>
</table>

<a href="admin_dashboard.php" class="btn btn-secondary mt-3">⬅ Back</a>

</body>
</html>
