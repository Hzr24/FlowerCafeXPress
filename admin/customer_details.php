<?php
$base_path = '../';
include '../check_access.php';
require_role(['admin']);
require_once '../dbconn.php';

// Show only customers
$result = $conn->query("SELECT * FROM customer_details WHERE role = 'customer' ORDER BY user_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Customers - FlowerCaféXpress</title>

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
        <h1>Manage Customers</h1>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
  <?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fa fa-check-circle mr-2"></i> <?= htmlspecialchars($_GET['message']) ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div>
      <h2 class="font-weight-bold mb-1">Customer Accounts</h2>
      <p class="text-muted mb-0">Search registered students & staff, view records, edit details or manage accounts.</p>
    </div>
    <div class="mt-3 mt-sm-0">
      <a href="admin_dashboard.php" class="btn btn-outline-theme">
        <i class="fa fa-arrow-left mr-1"></i> Dashboard
      </a>
    </div>
  </div>

  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" style="background: var(--fc-bg-elevated); border-color: var(--fc-border);"><i class="fa fa-search text-muted"></i></span>
        </div>
        <input type="text" class="form-control" id="searchInput" placeholder="Search customer by name or email address...">
      </div>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th style="width: 90px;">#ID</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th style="width: 170px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                  <td><span class="font-weight-bold" style="color: var(--fc-primary);">#<?= $user['user_id'] ?></span></td>
                  <td class="font-weight-bold"><?= htmlspecialchars($user['name']) ?></td>
                  <td><?= htmlspecialchars($user['email']) ?></td>
                  <td>
                    <a href="tel:<?= htmlspecialchars($user['phone']) ?>" class="text-decoration-none">
                      <i class="fa fa-phone mr-1 text-muted"></i> <?= htmlspecialchars($user['phone']) ?>
                    </a>
                  </td>
                  <td>
                    <a href="edit_customer.php?user_id=<?= $user['user_id'] ?>" class="btn btn-sm btn-outline-primary mr-1">
                      <i class="fa fa-pencil"></i> Edit
                    </a>
                    <a href="delete_customer.php?user_id=<?= $user['user_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this customer?')">
                      <i class="fa fa-trash"></i> Delete
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="5" class="text-center py-4 text-muted">No customers found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>

<!-- Search Filter Script -->
<script>
  document.getElementById('searchInput').addEventListener('input', function () {
    const search = this.value.toLowerCase();
    document.querySelectorAll('table tbody tr').forEach(row => {
      const name = row.cells[1]?.innerText.toLowerCase();
      const email = row.cells[2]?.innerText.toLowerCase();
      row.style.display = (name.includes(search) || email.includes(search)) ? '' : 'none';
    });
  });
</script>
</body>
</html>
