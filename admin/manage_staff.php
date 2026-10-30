<?php
$base_path = '../';
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Staff - FlowerCaféXpress</title>

  <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" href="../images/apple-touch-icon.png">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/font-awesome.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/responsive.css">
  <link rel="stylesheet" href="../css/custom.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="all-page-title page-breadcrumb">
  <div class="container text-center">
    <div class="row">
      <div class="col-lg-12">
        <h1>Manage Staff</h1>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fa fa-check-circle mr-2"></i> Staff member registered successfully!
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php elseif (isset($_GET['updated'])): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
      <i class="fa fa-info-circle mr-2"></i> Staff profile updated successfully!
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php elseif (isset($_GET['deleted'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fa fa-trash-o mr-2"></i> Staff member deleted.
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div>
      <h2 class="font-weight-bold mb-1">Staff Team Directory</h2>
      <p class="text-muted mb-0">Register kitchen and counter staff or modify team permissions.</p>
    </div>
    <div class="mt-3 mt-sm-0">
      <a href="admin_dashboard.php" class="btn btn-outline-theme">
        <i class="fa fa-arrow-left mr-1"></i> Dashboard
      </a>
    </div>
  </div>

  <!-- Add New Staff Card -->
  <div class="card shadow-sm border-0 mb-5">
    <div class="card-header bg-transparent border-bottom p-4">
      <h5 class="mb-0 font-weight-bold" style="color: var(--fc-primary);">
        <i class="fa fa-user-plus mr-2"></i> Register New Staff Member
      </h5>
    </div>
    <div class="card-body p-4">
      <form method="POST">
        <div class="form-row">
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Email Address</label>
            <input type="email" name="email" class="form-control" required placeholder="staff@flowercafe.com">
          </div>
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Full Name</label>
            <input type="text" name="name" class="form-control" required placeholder="Staff Name">
          </div>
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Phone Number</label>
            <input type="text" name="phone" class="form-control" required placeholder="017-xxxxxxx">
          </div>
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Position</label>
            <input type="text" name="position" class="form-control" placeholder="e.g. Chef, Barista, Cashier">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label class="font-weight-bold">Date Joined</label>
            <input type="date" name="date_joined" class="form-control" required value="<?= date('Y-m-d') ?>">
          </div>
          <div class="form-group col-md-4">
            <label class="font-weight-bold">Initial Password</label>
            <input type="text" name="password" class="form-control" required placeholder="Secure temporary password">
          </div>
          <div class="form-group col-md-4">
            <label class="font-weight-bold">System Role</label>
            <select name="role" class="form-control" required>
              <option value="staff">Staff (Kitchen / Orders)</option>
              <option value="admin">Administrator (Full Access)</option>
            </select>
          </div>
        </div>
        <div class="text-right mt-2">
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-plus-circle mr-1"></i> Add Staff Member
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Staff List Table -->
  <div class="card shadow-sm border-0">
    <div class="card-header bg-transparent border-bottom p-4">
      <h5 class="mb-0 font-weight-bold">
        <i class="fa fa-users mr-2" style="color: var(--fc-primary);"></i> Active Staff Roster
      </h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Role</th>
              <th>Position</th>
              <th>Date Joined</th>
              <th style="width: 170px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td class="font-weight-bold"><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                  <a href="tel:<?= htmlspecialchars($row['phone']) ?>" class="text-decoration-none">
                    <i class="fa fa-phone mr-1 text-muted"></i> <?= htmlspecialchars($row['phone']) ?>
                  </a>
                </td>
                <td>
                  <span class="badge badge-warning role-badge text-uppercase"><?= htmlspecialchars($row['role']) ?></span>
                </td>
                <td><?= htmlspecialchars($row['position'] ?: 'Staff') ?></td>
                <td class="text-muted small">
                  <?= $row['date_joined'] ? date('d M Y', strtotime($row['date_joined'])) : 'N/A' ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-outline-primary mr-1" onclick='showEditModal(<?= json_encode($row) ?>)'>
                    <i class="fa fa-pencil"></i> Edit
                  </button>
                  <a href="?delete=<?= $row['user_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this staff member?')">
                    <i class="fa fa-trash"></i> Delete
                  </a>
                </td>
              </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="7" class="text-center py-4 text-muted">No staff accounts registered.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Edit Staff Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold">Edit Staff Profile</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body p-4">
        <input type="hidden" name="user_id" id="edit_user_id">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label class="font-weight-bold">Email Address</label>
            <input type="email" name="email" id="edit_email" class="form-control" required>
          </div>
          <div class="form-group col-md-4">
            <label class="font-weight-bold">Full Name</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>
          <div class="form-group col-md-4">
            <label class="font-weight-bold">Phone Number</label>
            <input type="text" name="phone" id="edit_phone" class="form-control" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Position</label>
            <input type="text" name="position" id="edit_position" class="form-control">
          </div>
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Date Joined</label>
            <input type="date" name="date_joined" id="edit_date_joined" class="form-control">
          </div>
          <div class="form-group col-md-3">
            <label class="font-weight-bold">New Password</label>
            <input type="text" name="password" class="form-control" placeholder="Leave blank to keep">
          </div>
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Role</label>
            <select name="role" id="edit_role" class="form-control" required>
              <option value="staff">Staff</option>
              <option value="admin">Admin</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>

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
