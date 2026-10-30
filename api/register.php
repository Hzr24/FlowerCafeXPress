<?php
include("../dbconn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $phone = trim($_POST["phone"]);

    if (empty($name) || empty($email) || empty($password) || empty($phone)) {
        echo "Please fill in all fields.";
        exit;
    }

    $name = $conn->real_escape_string($name);
    $email = $conn->real_escape_string($email);
    $phone = $conn->real_escape_string($phone);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT user_id FROM customer_details WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Email already registered.";
        $check->close();
        exit;
    }
    $check->close();

    $stmt = $conn->prepare("INSERT INTO customer_details (name, email, password, phone) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $phone);
        if ($stmt->execute()) {
            echo "success";
            $stmt->close();
            exit;
        } else {
            echo "Database error: " . $stmt->error;
            $stmt->close();
            exit;
        }
    } else {
        echo "SQL error: " . $conn->error;
        exit;
    }
}

$conn->close();
?>
