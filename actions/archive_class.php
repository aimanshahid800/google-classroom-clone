<?php
require_once '../config.php';

$class_id = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';
$user_id = $_SESSION['user_id'];

if ($class_id <= 0 || !in_array($action, ['archive', 'unarchive'])) {
    header('Location: ../index.php');
    exit;
}

$check = mysqli_query($conn, "SELECT id FROM classes WHERE id = $class_id AND teacher_id = $user_id");
if (mysqli_num_rows($check) == 0) {
    header('Location: ../index.php');
    exit;
}

$val = ($action === 'archive') ? 1 : 0;
mysqli_query($conn, "UPDATE classes SET is_archived = $val WHERE id = $class_id AND teacher_id = $user_id");

if ($action === 'archive') {
    header("Location: " . BASE_URL . "/index.php");
} else {
    header("Location: " . BASE_URL . "/archived.php");
}
exit;
