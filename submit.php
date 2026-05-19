<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// URL se assignment_id lo (e.g. submit.php?id=3)
$assignment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($assignment_id == 0) {
    die("Assignment not found.");
}

// Is assignment ki details fetch karo
$a_query = "SELECT a.*, c.name AS class_name FROM assignments a JOIN classes c ON a.class_id = c.id WHERE a.id = $assignment_id";
$a_result = mysqli_query($conn, $a_query);
$assignment = mysqli_fetch_assoc($a_result);

if (!$assignment) {
    die("Assignment not found.");
}

// Dekho kya student ne pehle se submit kar di hai
$check_query = "SELECT * FROM submissions WHERE assignment_id = $assignment_id AND student_id = $user_id";
$check_result = mysqli_query($conn, $check_query);
$already_submitted = mysqli_num_rows($check_result) > 0;

// -----------------------------------------------
// FORM SUBMIT HO TOH SAVE KARO
// -----------------------------------------------
$success_msg = "";
$error_msg   = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$already_submitted) {

    $answer_text = mysqli_real_escape_string($conn, trim($_POST['answer_text']));

    if (empty($answer_text)) {
        $error_msg = "Answer likhna zaroori hai.";
    } else {
        // Submission database mein save karo
        $insert_query = "
            INSERT INTO submissions (assignment_id, student_id, answer_text, submitted_at)
            VALUES ($assignment_id, $user_id, '$answer_text', NOW())
        ";
        if (mysqli_query($conn, $insert_query)) {
            $success_msg = "Assignment submit ho gayi! ✅";
            $already_submitted = true;
        } else {
            $error_msg = "Kuch problem hui. Dobara try karo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Assignment — Google Classroom</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .submit-container {
            max-width: 700px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .assignment-info {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px 24px;
            margin-bottom: 20px;
        }
        .assignment-info h1 {
            font-size: 20px;
            color: #202124;
            margin-bottom: 6px;
        }
        .meta {
            font-size: 13px;
            color: #5f6368;
        }
        .submit-form {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px 24px;
        }
        .submit-form label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #202124;
        }
        .submit-form textarea {
            width: 100%;
            min-height: 140px;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            resize: vertical;
            font-family: inherit;
        }
        .submit-form textarea:focus {
            outline: none;
            border-color: #1a73e8;
        }
        .submit-btn {
            margin-top: 14px;
            background: #1a73e8;
            color: white;
            border: none;
            padding: 10px 28px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #1557b0;
        }
        .alert-success {
            background: #e6f4ea;
            color: #137333;
            border: 1px solid #a8d5b5;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 16px;
        }
        .alert-error {
            background: #fce8e6;
            color: #c5221f;
            border: 1px solid #f5c6c4;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 16px;
        }
        .already-done {
            text-align: center;
            padding: 30px;
            color: #137333;
            font-size: 16px;
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="submit-container">

    <!-- Assignment Info -->
    <div class="assignment-info">
        <h1><?php echo htmlspecialchars($assignment['title']); ?></h1>
        <div class="meta">
            Class: <?php echo htmlspecialchars($assignment['class_name']); ?>
            <?php if ($assignment['due_date']): ?>
                &nbsp;|&nbsp; Due: <?php echo date("d M Y", strtotime($assignment['due_date'])); ?>
            <?php endif; ?>
        </div>
        <?php if (!empty($assignment['description'])): ?>
            <p style="margin-top:12px; font-size:14px; color:#3c4043;">
                <?php echo nl2br(htmlspecialchars($assignment['description'])); ?>
            </p>
        <?php endif; ?>
    </div>

    <!-- Success / Error Message -->
    <?php if ($success_msg): ?>
        <div class="alert-success"><?php echo $success_msg; ?></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert-error"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <!-- Submit Form ya Already Done message -->
    <?php if ($already_submitted): ?>
        <div class="submit-form">
            <div class="already-done">✅ Tumne yeh assignment pehle se submit kar di hai!</div>
        </div>
    <?php else: ?>
        <div class="submit-form">
            <form method="POST">
                <label for="answer_text">Apna jawab likhо:</label>
                <textarea name="answer_text" id="answer_text" placeholder="Yahan apna answer type karo..."></textarea>
                <button type="submit" class="submit-btn">Submit Assignment</button>
            </form>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
