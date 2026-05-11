<?php
session_start();

// Function to check if a user is logged in with a specific role
function require_role($allowed_roles = []) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        // Redirect unauthorized customer_details
        header("Location: index.php");
        exit();
    }
}
