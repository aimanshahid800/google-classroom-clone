<?php
require_once '../config.php';
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = strtoupper(mysqli_real_escape_string($conn, trim($_POST['code'])));
    $student_id = 1;
    $result = mysqli_query($conn, "SELECT * FROM classes WHERE code = '$code'");
    $class = mysqli_fetch_assoc($result);
    if (!$class) {
        $error = 'Invalid class code!';
    } else {
        $check = mysqli_query($conn, "SELECT * FROM enrollments WHERE class_id = {$class['id']} AND student_id = $student_id");
        if (mysqli_fetch_assoc($check)) {
            $error = 'You are already in this class!';
        } else {
            mysqli_query($conn, "INSERT INTO enrollments (class_id, student_id) VALUES ({$class['id']}, $student_id)");
            header('Location: ../home/dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join Class</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .main-area { margin-left: 280px; margin-top: 80px; padding: 30px; }
        .main-area h1 { font-size: 24px; color: #202124; margin-bottom: 25px; }
        .main-area label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; }
        .main-area input { display: block; width: 400px; padding: 12px; margin-bottom: 20px; border: 1px solid #dadce0; border-radius: 6px; font-size: 14px; }
        .main-area button { padding: 10px 28px; background: #1a73e8; color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer; }
        .error { color: red; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-area">
    <h1>Join a Class</h1>
    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <form method="POST">
        <label>Class Code</label>
        <input type="text" name="code" placeholder="e.g. AB12CD" required>
        <button type="submit">Join Class</button>
    </form>
</div>
</body>
</html>