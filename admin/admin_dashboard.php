<?php
include '../check_access.php';
require_role(['admin']); // Ensure admin only
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
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .dashboard-card {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      border-radius: 10px;
    }
    .dashboard-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .card-title {
      font-size: 1.25rem;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4 text-center">🌟 Admin Dashboard</h2>

    <div class="row">
      <div class="col-md-6 col-lg-4 mb-4">
        <a href="customer_details.php" class="text-decoration-none text-dark">
          <div class="card dashboard-card shadow-sm p-3">
            <div class="card-body text-center">
              <h5 class="card-title">👤 Manage Users</h5>
              <p class="card-text">View, edit roles, or delete customer accounts.</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-6 col-lg-4 mb-4">
        <a href="manage_staff.php" class="text-decoration-none text-dark">
          <div class="card dashboard-card shadow-sm p-3">
            <div class="card-body text-center">
              <h5 class="card-title">👨‍🍳 Manage Staff</h5>
              <p class="card-text">Register, view or manage staff accounts.</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-6 col-lg-4 mb-4">
        <a href="generate_reports.php" class="text-decoration-none text-dark">
          <div class="card dashboard-card shadow-sm p-3">
            <div class="card-body text-center">
              <h5 class="card-title">📊 Generate Reports</h5>
              <p class="card-text">Create and download reports on system performance.</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
