<?php
$base_path = '../';
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
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>System Reports - FlowerCaféXpress</title>

  <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" href="../images/apple-touch-icon.png">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/font-awesome.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/responsive.css">
  <link rel="stylesheet" href="../css/custom.css">

  <style>
    @media print {
      body {
        background: white !important;
        color: black !important;
      }
      .top-navbar,
      .footer-area,
      .page-breadcrumb,
      .filter-card,
      .no-print,
      button,
      a.btn {
        display: none !important;
      }
      .container {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
      }
      .card {
        box-shadow: none !important;
        border: none !important;
      }
      .table {
        color: black !important;
        background: white !important;
      }
      .table th, .table td {
        border-color: #ddd !important;
      }
    }
  </style>
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="all-page-title page-breadcrumb">
  <div class="container text-center">
    <div class="row">
      <div class="col-lg-12">
        <h1>Sales & Order Reports</h1>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap no-print">
    <div>
      <h2 class="font-weight-bold mb-1">Performance Analytics</h2>
      <p class="text-muted mb-0">Filter orders by date range or dish name, review totals, and generate invoices or PDF summaries.</p>
    </div>
    <div class="mt-3 mt-sm-0">
      <button onclick="window.print()" class="btn btn-outline-theme mr-2">
        <i class="fa fa-print mr-1"></i> Print / Save PDF
      </button>
      <a href="admin_dashboard.php" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left mr-1"></i> Dashboard
      </a>
    </div>
  </div>

  <!-- Filter Card -->
  <div class="card shadow-sm border-0 mb-4 filter-card">
    <div class="card-body p-4">
      <form method="GET" class="form-row align-items-end">
        <div class="form-group col-md-3 mb-md-0">
          <label class="font-weight-bold small text-muted">From Date:</label>
          <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
        </div>
        <div class="form-group col-md-3 mb-md-0">
          <label class="font-weight-bold small text-muted">To Date:</label>
          <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
        </div>
        <div class="form-group col-md-3 mb-md-0">
          <label class="font-weight-bold small text-muted">Search Menu Item:</label>
          <input type="text" name="search" class="form-control" placeholder="e.g. Buttermilk Chicken" value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="form-group col-md-3 mb-md-0 d-flex">
          <button type="submit" class="btn btn-primary flex-fill mr-2">
            <i class="fa fa-filter mr-1"></i> Filter
          </button>
          <a href="generate_reports.php" class="btn btn-secondary">
            Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Table Card -->
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th style="width: 100px;">Order ID</th>
              <th>Customer</th>
              <th style="width: 140px;">Status</th>
              <th>Items Ordered</th>
              <th style="width: 130px;">Total</th>
              <th style="width: 170px;">Date & Time</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $grandTotal = 0;
            $orderCount = 0;
            if ($result && $result->num_rows > 0):
              while ($row = $result->fetch_assoc()): 
                $grandTotal += $row['total'];
                $orderCount++;

                $status = strtolower($row['status']);
                $badgeClass = 'badge-secondary';
                if ($status === 'pending') {
                    $badgeClass = 'badge-warning text-dark';
                } elseif ($status === 'preparing') {
                    $badgeClass = 'badge-primary';
                } elseif ($status === 'ready') {
                    $badgeClass = 'badge-info';
                } elseif ($status === 'completed') {
                    $badgeClass = 'badge-success';
                }
            ?>
            <tr>
              <td><span class="font-weight-bold" style="color: var(--fc-primary);">#<?= $row['order_id'] ?></span></td>
              <td class="font-weight-bold"><?= htmlspecialchars($row['name']) ?></td>
              <td>
                <span class="badge <?= $badgeClass ?> px-3 py-1 text-uppercase" style="font-size: 11px;">
                  <?= htmlspecialchars($row['status']) ?>
                </span>
              </td>
              <td><?= htmlspecialchars($row['items']) ?></td>
              <td class="font-weight-bold">RM <?= number_format($row['total'], 2) ?></td>
              <td class="text-muted small">
                <i class="fa fa-clock-o mr-1"></i> <?= date('d M Y, h:i A', strtotime($row['order_time'])) ?>
              </td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="6" class="text-center py-4 text-muted">No orders match the filter criteria.</td></tr>
            <?php endif; ?>
          </tbody>
          <tfoot style="background: var(--fc-bg-elevated);">
            <tr>
              <th colspan="4" class="text-right font-weight-bold">
                Total Revenue (<?= $orderCount ?> Orders):
              </th>
              <th colspan="2" class="font-weight-bold text-success" style="font-size: 1.15rem;">
                RM <?= number_format($grandTotal, 2) ?>
              </th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body>
</html>
