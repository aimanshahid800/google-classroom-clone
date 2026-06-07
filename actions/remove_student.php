<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$class_id = intval($_POST['class_id'] ?? 0);
$student_id = intval($_POST['student_id'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($class_id <= 0 || $student_id <= 0) {
    header("Location: " . BASE_URL . "/classes/people.php?class_id=$class_id");
    exit;
}

$check = mysqli_query($conn, "SELECT id FROM classes WHERE id = $class_id AND teacher_id = $user_id");
if (mysqli_num_rows($check) == 0) {
    header('Location: ../index.php');
    exit;
}

mysqli_query($conn, "DELETE FROM enrollments WHERE class_id = $class_id AND student_id = $student_id");
mysqli_query($conn, "DELETE FROM submissions WHERE assignment_id IN (SELECT id FROM assignments WHERE class_id = $class_id) AND student_id = $student_id");

header("Location: " . BASE_URL . "/classes/people.php?class_id=$class_id");
exit;
