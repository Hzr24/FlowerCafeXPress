<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include '../db.php';
$result = mysqli_query($conn, "SELECT * FROM menu");

echo "<h2>Menu Management</h2>";
echo "<a href='add_menu_item.php'>Add New Item</a><br><br>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<p>{$row['item_name']} - RM{$row['price']} 
        <a href='edit_menu.php?id={$row['id']}'>Edit</a> | 
        <a href='delete_menu.php?id={$row['id']}'>Delete</a></p>";
}
echo "</ul>";
?>