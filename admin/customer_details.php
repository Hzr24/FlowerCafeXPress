<?php
include '../check_access.php';
require_role(['admin']);
require_once '../dbconn.php';

// ✅ Show only customers
$result = $conn->query("SELECT * FROM customer_details WHERE role = 'customer' ORDER BY user_id DESC");
?>

<?php if (isset($_GET['message'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($_GET['message']) ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Customer Details</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body { background-color: #f8f9fa; }
    .table thead { background-color: #343a40; color: #fff; }
    .table td, .table th { vertical-align: middle !important; }
  </style>
</head>
<body>

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
          <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
          <button id="darkModeToggle" class="btn btn-dark btn-sm px-2 py-1">🌓</button>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="container mt-5">
  <h2 class="mb-4 text-center">👥 Manage Customer Details</h2>

  <div class="form-group row">
    <label for="searchInput" class="col-sm-2 col-form-label font-weight-bold">Search Customer:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="searchInput" placeholder="Type name or email...">
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover shadow-sm bg-white">
      <thead>
        <tr>
          <th>#ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $user['user_id'] ?></td>
              <td><?= htmlspecialchars($user['name']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td><?= htmlspecialchars($user['phone']) ?></td>
              <td>
                <a href="edit_customer.php?user_id=<?= $user['user_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="delete_customer.php?user_id=<?= $user['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this customer?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center">No customers found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

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

<!-- JS Includes -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
