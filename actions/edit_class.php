<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$class_id = intval($_POST['class_id'] ?? 0);
$name = mysqli_real_escape_string($conn, trim($_POST['name'] ?? ''));
$section = mysqli_real_escape_string($conn, trim($_POST['section'] ?? ''));
$subject = mysqli_real_escape_string($conn, trim($_POST['subject'] ?? ''));
$room = mysqli_real_escape_string($conn, trim($_POST['room'] ?? ''));
$user_id = $_SESSION['user_id'];

if ($class_id <= 0 || empty($name)) {
    header("Location: " . BASE_URL . "/classes/stream.php?class_id=$class_id&error=empty");
    exit;
}

$check = mysqli_query($conn, "SELECT id FROM classes WHERE id = $class_id AND teacher_id = $user_id");
if (mysqli_num_rows($check) == 0) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

mysqli_query($conn, "UPDATE classes SET name='$name', section='$section', subject='$subject', room='$room' WHERE id=$class_id AND teacher_id=$user_id");

header("Location: " . BASE_URL . "/classes/stream.php?class_id=$class_id");
exit;
