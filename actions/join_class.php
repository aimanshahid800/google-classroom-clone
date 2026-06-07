<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = mysqli_real_escape_string($conn, trim($_POST['code']));
    
    $student_id = $_SESSION['user_id'];

    if (!empty($code)) {
        // Find the class by code
        $sql = "SELECT id FROM classes WHERE code = '$code' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $class = mysqli_fetch_assoc($result);
            $class_id = $class['id'];
            
            // Check if already enrolled
            $check = mysqli_query($conn, "SELECT id FROM enrollments WHERE class_id = $class_id AND student_id = $student_id");
            
            if (mysqli_num_rows($check) == 0) {
                // Enroll the student
                mysqli_query($conn, "INSERT INTO enrollments (class_id, student_id) VALUES ($class_id, $student_id)");
            }
            
            header("Location: ../classes/stream.php?class_id=$class_id");
            exit;
        } else {
            // Invalid code, redirect back with error (for now just back to home)
            header("Location: ../index.php?error=invalid_code");
            exit;
        }
    }
}
header("Location: ../index.php");
exit;
?>
