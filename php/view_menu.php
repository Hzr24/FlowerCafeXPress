// view_menu.php
<?php
session_start();
if ($_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}
include '../db.php';
$result = mysqli_query($conn, "SELECT * FROM menu");

echo "<h2>Available Menu</h2>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<p>{$row['item_name']} - RM{$row['price']}</p>";
}
echo "</ul>";
?>