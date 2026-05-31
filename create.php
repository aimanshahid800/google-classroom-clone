<?php
require_once '../config.php';
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $subject = mysqli_real_escape_string($conn, trim($_POST['subject']));
    $teacher_id = 1;
    $code = strtoupper(substr(md5(uniqid()), 0, 6));
    if (empty($name)) {
        $error = 'Class name is required!';
    } else {
        mysqli_query($conn, "INSERT INTO classes (name, subject, teacher_id, code) VALUES ('$name','$subject',$teacher_id,'$code')");
        header('Location: ../home/dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Class</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .main-area { margin-left: 280px; margin-top: 80px; padding: 30px; }
        .main-area h1 { font-size: 24px; color: #202124; margin-bottom: 25px; }
        .main-area label { display: block; font-size: 14px; font-weight: 600; color: #202124; margin-bottom: 6px; }
        .main-area input { display: block; width: 400px; padding: 12px; margin-bottom: 20px; border: 1px solid #dadce0; border-radius: 6px; font-size: 14px; }
        .main-area button { padding: 10px 28px; background: #1a73e8; color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer; }
        .error { color: red; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-area">
    <h1>Create Class</h1>
    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <form method="POST">
        <label>Class Name *</label>
        <input type="text" name="name" placeholder="e.g. Web Engineering" required>
        <label>Subject</label>
        <input type="text" name="subject" placeholder="e.g. CS408">
        <button type="submit">Create Class</button>
    </form>
</div>
</body>
</html>