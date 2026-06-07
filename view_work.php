<?php
require_once 'config.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// URL se assignment_id lo
$assignment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($assignment_id == 0) {
    die("Assignment not found.");
}

// Assignment ki details fetch karo
$a_query = "SELECT a.*, c.name AS class_name FROM assignments a JOIN classes c ON a.class_id = c.id WHERE a.id = $assignment_id";
$a_result = mysqli_query($conn, $a_query);
$assignment = mysqli_fetch_assoc($a_result);

if (!$assignment) {
    die("Assignment not found.");
}

// Check karo: sirf teacher dekh sake (jo us class ka owner hai)
$teacher_check = "SELECT * FROM classes WHERE id = {$assignment['class_id']} AND teacher_id = $user_id";
$teacher_result = mysqli_query($conn, $teacher_check);

if (mysqli_num_rows($teacher_result) == 0) {
    die("Access denied. Sirf teacher dekh sakta hai.");
}

// Saare submissions fetch karo
$sub_query = "
    SELECT s.*, u.name AS student_name, u.email AS student_email
    FROM submissions s
    JOIN users u ON u.id = s.student_id
    WHERE s.assignment_id = $assignment_id
    ORDER BY s.submitted_at DESC
";
$sub_result = mysqli_query($conn, $sub_query);
$total_submitted = mysqli_num_rows($sub_result);

// Total students in class (from enrollments)
$total_query = "SELECT COUNT(*) AS total FROM enrollments WHERE class_id = {$assignment['class_id']}";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_students = $total_row['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Work — Google Classroom</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .view-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .assignment-header {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px 24px;
            margin-bottom: 20px;
        }
        .assignment-header h1 {
            font-size: 20px;
            color: #202124;
            margin-bottom: 6px;
        }
        .stats-row {
            display: flex;
            gap: 20px;
            margin-top: 12px;
        }
        .stat-box {
            background: #f1f3f4;
            border-radius: 8px;
            padding: 10px 18px;
            text-align: center;
        }
        .stat-box .num {
            font-size: 22px;
            font-weight: 700;
            color: #1a73e8;
        }
        .stat-box .label {
            font-size: 12px;
            color: #5f6368;
        }
        .section-title {
            font-size: 15px;
            font-weight: 600;
            color: #202124;
            margin-bottom: 12px;
        }
        .submission-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 12px;
        }
        .student-name {
            font-weight: 600;
            color: #202124;
            font-size: 15px;
        }
        .student-email {
            font-size: 12px;
            color: #5f6368;
            margin-bottom: 10px;
        }
        .answer-box {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 12px;
            font-size: 14px;
            color: #3c4043;
            line-height: 1.6;
        }
        .submitted-time {
            font-size: 12px;
            color: #5f6368;
            margin-top: 8px;
        }
        .no-submissions {
            text-align: center;
            padding: 40px;
            color: #5f6368;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="view-container">

    <!-- Assignment Header + Stats -->
    <div class="assignment-header">
        <h1><?php echo htmlspecialchars($assignment['title']); ?></h1>
        <div style="font-size:13px; color:#5f6368;">
            Class: <?php echo htmlspecialchars($assignment['class_name']); ?>
            <?php if ($assignment['due_date']): ?>
                &nbsp;|&nbsp; Due: <?php echo date("d M Y", strtotime($assignment['due_date'])); ?>
            <?php endif; ?>
        </div>

        <!-- Numbers: Submitted vs Total -->
        <div class="stats-row">
            <div class="stat-box">
                <div class="num"><?php echo $total_submitted; ?></div>
                <div class="label">Submitted</div>
            </div>
            <div class="stat-box">
                <div class="num"><?php echo $total_students - $total_submitted; ?></div>
                <div class="label">Not Submitted</div>
            </div>
            <div class="stat-box">
                <div class="num"><?php echo $total_students; ?></div>
                <div class="label">Total Students</div>
            </div>
        </div>
    </div>

    <!-- Submissions List -->
    <div class="section-title">Student Submissions</div>

    <?php if ($total_submitted == 0): ?>
        <div class="no-submissions">Abhi tak kisi ne submit nahi kiya.</div>
    <?php else: ?>
        <?php while ($sub = mysqli_fetch_assoc($sub_result)): ?>
            <div class="submission-card">
                <div class="student-name"><?php echo htmlspecialchars($sub['student_name']); ?></div>
                <div class="student-email"><?php echo htmlspecialchars($sub['student_email']); ?></div>

                <div class="answer-box">
                    <?php echo nl2br(htmlspecialchars($sub['answer_text'])); ?>
                </div>

                <div class="submitted-time">
                    Submitted: <?php echo date("d M Y, h:i A", strtotime($sub['submitted_at'])); ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

</div>

</body>
</html>