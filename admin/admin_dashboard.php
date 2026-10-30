<?php
$base_path = '../';
include '../check_access.php';
require_role(['admin']); // Ensure admin only
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - FlowerCaféXpress</title>

    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="../images/apple-touch-icon.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet" href="../css/custom.css">
    <style>
        .dashboard-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            border-radius: var(--fc-radius-md) !important;
            height: 100%;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--fc-shadow-lg) !important;
        }
        .icon-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
            background: var(--fc-primary-light);
            color: var(--fc-primary);
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="all-page-title page-breadcrumb">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Admin Control Center</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h2 class="font-weight-bold mb-1">Overview & Management</h2>
                <p class="text-muted mb-0">Manage customer accounts, staff privileges, and view cafe reports.</p>
            </div>
            <div class="mt-3 mt-sm-0">
                <a href="../pages/menu.php" class="btn btn-outline-theme">
                    <i class="fa fa-cutlery mr-1"></i> View Live Menu
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <a href="customer_details.php" class="text-decoration-none">
                    <div class="card dashboard-card shadow-sm p-3">
                        <div class="card-body text-center">
                            <div class="icon-circle">
                                <i class="fa fa-users"></i>
                            </div>
                            <h5 class="card-title font-weight-bold">Manage Customers</h5>
                            <p class="card-text">View customer lists, edit roles, search registered student accounts, or delete profiles.</p>
                            <span class="btn btn-sm btn-outline-theme mt-2">Open Customers &rarr;</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <a href="manage_staff.php" class="text-decoration-none">
                    <div class="card dashboard-card shadow-sm p-3">
                        <div class="card-body text-center">
                            <div class="icon-circle">
                                <i class="fa fa-user-plus"></i>
                            </div>
                            <h5 class="card-title font-weight-bold">Manage Staff</h5>
                            <p class="card-text">Register new kitchen or counter staff, monitor credentials, and update staff permissions.</p>
                            <span class="btn btn-sm btn-outline-theme mt-2">Manage Staff &rarr;</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <a href="generate_reports.php" class="text-decoration-none">
                    <div class="card dashboard-card shadow-sm p-3">
                        <div class="card-body text-center">
                            <div class="icon-circle">
                                <i class="fa fa-line-chart"></i>
                            </div>
                            <h5 class="card-title font-weight-bold">Sales & Reports</h5>
                            <p class="card-text">Generate comprehensive revenue breakdown, dish sales analytics, and printable daily reports.</p>
                            <span class="btn btn-sm btn-outline-theme mt-2">View Reports &rarr;</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <?php include '../includes/scripts.php'; ?>
</body>
</html>
