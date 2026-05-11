// order_status.php
<?php
session_start();
if ($_SESSION['role'] != 'staff') {
    header("Location: login.php");
    exit();
}
include('db.php');

$result = mysqli_query($conn, "SELECT * FROM orders");
echo "<h2>Update Order Status</h2><form method='POST' action=''>";
echo "<select name='order_id'>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='" . $row['id'] . "'>Order #" . $row['id'] . "</option>";
}
echo "</select><select name='status'>
    <option value='Preparing'>Preparing</option>
    <option value='Ready'>Ready</option>
    <option value='Delivered'>Delivered</option>
</select><button type='submit'>Update</button></form>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['order_id'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id=$id");
    echo "<p>Status updated.</p>";
}
?>