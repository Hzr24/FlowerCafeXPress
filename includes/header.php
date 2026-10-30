<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_path = $base_path ?? '';

$current_script = basename($_SERVER['PHP_SELF'] ?? '');
$current_dir = basename(dirname($_SERVER['PHP_SELF'] ?? ''));

$is_home = ($current_script === 'index.php' && $current_dir !== 'admin' && $current_dir !== 'staff');
$is_menu = ($current_script === 'menu.php');
$is_about = ($current_script === 'about.php');
$is_gallery = ($current_script === 'gallery.php');
$is_contact = ($current_script === 'contact.php');
$is_orders = ($current_script === 'my_order.php');
$is_profile = ($current_script === 'customer_profile.php');
$is_admin = ($current_dir === 'admin');
$is_staff = ($current_dir === 'staff');
?>

<header class="top-navbar">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="<?= $base_path ?>index.php">
                <img src="<?= $base_path ?>images/logo flowercafexpress.png" alt="FlowerCaféXpress Logo" />
            </a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars-rs-food" aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbars-rs-food">
                <ul class="navbar-nav ml-auto align-items-lg-center">
                    <li class="nav-item <?= $is_home ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= $base_path ?>index.php">Home</a>
                    </li>
                    <li class="nav-item <?= $is_menu ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= $base_path ?>pages/menu.php">Menu</a>
                    </li>
                    <li class="nav-item <?= $is_about ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= $base_path ?>pages/about.php">About</a>
                    </li>
                    <li class="nav-item <?= $is_gallery ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= $base_path ?>pages/gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item <?= $is_contact ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= $base_path ?>pages/contact.php">Contact</a>
                    </li>

                    <?php if (isset($_SESSION['role'])): ?>
                        <!-- User Dropdown Menu -->
                        <li class="nav-item dropdown user-dropdown ml-lg-2">
                            <a class="nav-link dropdown-toggle user-pill" href="#" id="userNavbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user-circle"></i>
                                <span><?= htmlspecialchars($_SESSION['name'] ?? 'Account') ?></span>
                                <span class="badge badge-warning role-badge ml-1"><?= htmlspecialchars(ucfirst($_SESSION['role'])) ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userNavbarDropdown">
                                <?php if ($_SESSION['role'] === 'customer'): ?>
                                    <a class="dropdown-item <?= $is_orders ? 'active' : '' ?>" href="<?= $base_path ?>pages/my_order.php">
                                        <i class="fa fa-list-alt"></i> My Orders
                                    </a>
                                    <a class="dropdown-item <?= $is_profile ? 'active' : '' ?>" href="<?= $base_path ?>pages/customer_profile.php">
                                        <i class="fa fa-user"></i> My Profile
                                    </a>
                                <?php elseif ($_SESSION['role'] === 'admin'): ?>
                                    <a class="dropdown-item <?= ($current_script === 'admin_dashboard.php') ? 'active' : '' ?>" href="<?= $base_path ?>admin/admin_dashboard.php">
                                        <i class="fa fa-tachometer"></i> Dashboard
                                    </a>
                                    <a class="dropdown-item <?= ($current_script === 'customer_details.php') ? 'active' : '' ?>" href="<?= $base_path ?>admin/customer_details.php">
                                        <i class="fa fa-users"></i> Manage Customers
                                    </a>
                                    <a class="dropdown-item <?= ($current_script === 'manage_staff.php') ? 'active' : '' ?>" href="<?= $base_path ?>admin/manage_staff.php">
                                        <i class="fa fa-user-plus"></i> Manage Staff
                                    </a>
                                    <a class="dropdown-item <?= ($current_script === 'generate_reports.php') ? 'active' : '' ?>" href="<?= $base_path ?>admin/generate_reports.php">
                                        <i class="fa fa-line-chart"></i> Reports
                                    </a>
                                <?php elseif ($_SESSION['role'] === 'staff'): ?>
                                    <a class="dropdown-item <?= ($current_script === 'staff_dashboard.php') ? 'active' : '' ?>" href="<?= $base_path ?>staff/staff_dashboard.php">
                                        <i class="fa fa-cutlery"></i> Current Orders
                                    </a>
                                    <a class="dropdown-item <?= ($current_script === 'order_history.php') ? 'active' : '' ?>" href="<?= $base_path ?>staff/order_history.php">
                                        <i class="fa fa-history"></i> Order History
                                    </a>
                                <?php endif; ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="<?= $base_path ?>api/logout.php">
                                    <i class="fa fa-sign-out"></i> Logout
                                </a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ml-lg-2">
                            <a class="nav-link btn btn-outline-theme px-3 py-1 my-1" href="#" data-toggle="modal" data-target="#loginModal">
                                <i class="fa fa-sign-in"></i> Login
                            </a>
                        </li>
                        <li class="nav-item ml-lg-1">
                            <a class="nav-link btn btn-primary text-white px-3 py-1 my-1" href="#" data-toggle="modal" data-target="#registerModal">
                                Sign Up
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <button id="darkModeToggle" title="Toggle Dark/Light Mode" aria-label="Toggle theme">
                            <span id="darkModeIcon">🌓</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
