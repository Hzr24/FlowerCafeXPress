<?php
require_once "../dbconn.php";
require_once "../check_access.php";
require_role(['admin']);

// Handle deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM customer_details WHERE user_id = $id");
    header("Location: manage_staff.php?deleted=1");
    exit;
}

// Handle add or edit form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'] ?? null;
    $email = $_POST['email'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $position = $_POST['position'] ?? null;
    $date_joined = $_POST['date_joined'] ?? null;
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

    if ($user_id) {
        // Update
        if ($password) {
            $stmt = $conn->prepare("UPDATE customer_details SET email=?, name=?, phone=?, role=?, password=? WHERE user_id=?");
            $stmt->bind_param("sssssi", $email, $name, $phone, $role, $password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE customer_details SET email=?, name=?, phone=?, role=? WHERE user_id=?");
            $stmt->bind_param("ssssi", $email, $name, $phone, $role, $user_id);
        }
        $stmt->execute();
        $stmt->close();

        $stmt2 = $conn->prepare("UPDATE staff_details SET full_name=?, phone=?, position=?, date_joined=? WHERE user_id=?");
        $stmt2->bind_param("ssssi", $name, $phone, $position, $date_joined, $user_id);
        $stmt2->execute();
        $stmt2->close();

        header("Location: manage_staff.php?updated=1");
        exit;
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO customer_details (email, password, role, name, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $email, $password, $role, $name, $phone);
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();

        $stmt2 = $conn->prepare("INSERT INTO staff_details (user_id, full_name, phone, position, date_joined) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("issss", $user_id, $name, $phone, $position, $date_joined);
        $stmt2->execute();
        $stmt2->close();

        header("Location: manage_staff.php?success=1");
        exit;
    }
}

// Fetch staff list
$result = $conn->query("
    SELECT s.user_id, u.email, u.name, u.phone, u.role, s.position, s.date_joined
    FROM staff_details s
    JOIN customer_details u ON s.user_id = u.user_id
    ORDER BY s.user_id DESC
");
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
          <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
          <button id="darkModeToggle" class="btn btn-dark btn-sm px-2 py-1">🌓</button>
        </ul>
      </div>
    </div>
  </nav>
</header>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Staff</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-4">
<h2 class="mb-4">👨‍🍳 Manage Staff</h2>

<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">✅ Staff added successfully!</div>
<?php elseif (isset($_GET['updated'])): ?>
  <div class="alert alert-info">✅ Staff updated successfully!</div>
<?php elseif (isset($_GET['deleted'])): ?>
  <div class="alert alert-danger">🗑️ Staff deleted!</div>
<?php endif; ?>

<!-- Add New Staff -->
<form method="POST" class="mb-4 border p-3 rounded bg-light shadow-sm">
  <h4>Add New Staff</h4>
  <div class="form-row">
    <div class="form-group col-md-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="form-group col-md-3">
      <label>Full Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="form-group col-md-3">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" required>
    </div>
    <div class="form-group col-md-3">
      <label>Position</label>
      <input type="text" name="position" class="form-control">
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-3">
      <label>Date Joined</label>
      <input type="date" name="date_joined" class="form-control" required>
    </div>
    <div class="form-group col-md-3">
      <label>Password</label>
      <input type="text" name="password" class="form-control" required>
    </div>
    <div class="form-group col-md-3">
      <label>Role</label>
      <select name="role" class="form-control" required>
        <option value="staff">Staff</option>
        <option value="admin">Admin</option>
      </select>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">➕ Add Staff</button>
</form>

<!-- Staff List -->
<h4>📋 Staff List</h4>
<table class="table table-bordered table-hover">
  <thead class="thead-dark">
    <tr>
      <th>Email</th>
      <th>Name</th>
      <th>Phone</th>
      <th>Role</th>
      <th>Position</th>
      <th>Date Joined</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['email']) ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['phone']) ?></td>
      <td><?= htmlspecialchars($row['role']) ?></td>
      <td><?= htmlspecialchars($row['position']) ?></td>
      <td><?= htmlspecialchars($row['date_joined']) ?></td>
      <td>
        <button class="btn btn-warning btn-sm" onclick='showEditModal(<?= json_encode($row) ?>)'>Edit</button>
        <a href="?delete=<?= $row['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this staff?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<!-- Edit Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Staff</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="user_id" id="edit_user_id">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label>Email</label>
            <input type="email" name="email" id="edit_email" class="form-control" required>
          </div>
          <div class="form-group col-md-4">
            <label>Name</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>
          <div class="form-group col-md-4">
            <label>Phone</label>
            <input type="text" name="phone" id="edit_phone" class="form-control" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label>Position</label>
            <input type="text" name="position" id="edit_position" class="form-control">
          </div>
          <div class="form-group col-md-3">
            <label>Date Joined</label>
            <input type="date" name="date_joined" id="edit_date_joined" class="form-control">
          </div>
          <div class="form-group col-md-3">
            <label>New Password (optional)</label>
            <input type="text" name="password" class="form-control">
          </div>
          <div class="form-group col-md-3">
            <label>Role</label>
            <select name="role" id="edit_role" class="form-control" required>
              <option value="staff">Staff</option>
              <option value="admin">Admin</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">💾 Save Changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showEditModal(data) {
  document.getElementById('edit_user_id').value = data.user_id;
  document.getElementById('edit_email').value = data.email;
  document.getElementById('edit_name').value = data.name;
  document.getElementById('edit_phone').value = data.phone;
  document.getElementById('edit_position').value = data.position || '';
  document.getElementById('edit_date_joined').value = data.date_joined || '';
  document.getElementById('edit_role').value = data.role;
  $('#editStaffModal').modal('show');
}
</script>
</body>
</html>
