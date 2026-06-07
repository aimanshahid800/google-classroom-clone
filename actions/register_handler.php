<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$role = ($_POST['role'] ?? 'student') === 'teacher' ? 'teacher' : 'student';

if (empty($name) || empty($email) || empty($password)) {
    header('Location: ../register.php?error=empty');
    exit;
}

if (strlen($password) < 6) {
    header('Location: ../register.php?error=password');
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    header('Location: ../register.php?error=email_taken');
    exit;
}
mysqli_stmt_close($stmt);

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $hashed, $role);

if (mysqli_stmt_execute($stmt)) {
    header('Location: ../login.php?error=registered');
} else {
    header('Location: ../register.php?error=empty');
}
exit;
