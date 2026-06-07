<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $section = mysqli_real_escape_string($conn, trim($_POST['section'] ?? ''));
    $subject = mysqli_real_escape_string($conn, trim($_POST['subject'] ?? ''));
    $room = mysqli_real_escape_string($conn, trim($_POST['room'] ?? ''));
    
    // Generate a random 6-7 character class code
    $code = strtolower(substr(md5(uniqid()), 0, 7));
    
    $teacher_id = $_SESSION['user_id'];

    if (!empty($name)) {
        $sql = "INSERT INTO classes (name, section, subject, room, code, teacher_id) 
                VALUES ('$name', '$section', '$subject', '$room', '$code', $teacher_id)";
        
        if (mysqli_query($conn, $sql)) {
            $class_id = mysqli_insert_id($conn);
            // Enroll the teacher automatically
            mysqli_query($conn, "INSERT INTO enrollments (class_id, student_id) VALUES ($class_id, $teacher_id)");
            header("Location: " . BASE_URL . "/classes/stream.php?class_id=$class_id");
            exit;
        } else {
            // Log error
            die("Error creating class: " . mysqli_error($conn));
        }
    }
}
header("Location: ../index.php");
exit;
?>
