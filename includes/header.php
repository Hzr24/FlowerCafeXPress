<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_path = $base_path ?? '';
?>

<header class="top-navbar">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="<?= $base_path ?>index.php">
                <img src="<?= $base_path ?>images/logo flowercafexpress.png" alt="" />
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars-rs-food">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbars-rs-food">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>pages/menu.php">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>pages/about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>pages/gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>pages/contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['role'])): ?>
                        <?php if ($_SESSION['role'] === 'customer'): ?>
                            <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>pages/my_order.php">My Orders</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>pages/customer_profile.php">My Profile</a></li>
                        <?php elseif ($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link btn btn-danger text-white ml-2" href="<?= $base_path ?>admin/admin_dashboard.php">Admin Panel</a></li>
                        <?php elseif ($_SESSION['role'] === 'staff'): ?>
                            <li class="nav-item"><a class="nav-link btn btn-warning text-white ml-2" href="<?= $base_path ?>staff/staff_dashboard.php">Staff Panel</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>api/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#loginModal">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#registerModal">Sign Up</a></li>
                    <?php endif; ?> 
                    <button id="darkModeToggle" class="btn btn-dark btn-sm px-2 py-1" style="font-size: 0.9rem; line-height: 1;">🌓</button>
                </ul>
            </div>
        </div>
    </nav>
</header>
