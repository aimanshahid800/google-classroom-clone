<?php
// Aiman ki config file include karo (database connection)
require_once '../config.php';

// Session start karo (login check ke liye)
session_start();

// Agar login nahi hai to login page pe bhejo
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// -----------------------------------------------
// DATABASE SE DATA FETCH KARO
// -----------------------------------------------

// ASSIGNED: woh assignments jo student ko mili hain aur submit nahi ki
$assigned_query = "
    SELECT a.id, a.title, a.due_date, c.name AS class_name
    FROM assignments a
    JOIN classes c ON a.class_id = c.id
    JOIN class_members cm ON cm.class_id = c.id
    WHERE cm.user_id = $user_id
    AND a.id NOT IN (
        SELECT assignment_id FROM submissions WHERE student_id = $user_id
    )
    AND (a.due_date >= CURDATE() OR a.due_date IS NULL)
    ORDER BY a.due_date ASC
";
$assigned_result = mysqli_query($conn, $assigned_query);

// MISSING: due date guzar gayi aur submit nahi ki
$missing_query = "
    SELECT a.id, a.title, a.due_date, c.name AS class_name
    FROM assignments a
    JOIN classes c ON a.class_id = c.id
    JOIN class_members cm ON cm.class_id = c.id
    WHERE cm.user_id = $user_id
    AND a.id NOT IN (
        SELECT assignment_id FROM submissions WHERE student_id = $user_id
    )
    AND a.due_date < CURDATE()
    ORDER BY a.due_date DESC
";
$missing_result = mysqli_query($conn, $missing_query);

// DONE: woh assignments jo student ne submit kar di hain
$done_query = "
    SELECT a.id, a.title, a.due_date, c.name AS class_name, s.submitted_at
    FROM assignments a
    JOIN classes c ON a.class_id = c.id
    JOIN submissions s ON s.assignment_id = a.id
    WHERE s.student_id = $user_id
    ORDER BY s.submitted_at DESC
";
$done_result = mysqli_query($conn, $done_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do — Google Classroom</title>
    <!-- Aiman ki CSS file include karo -->
    <link rel="stylesheet" href="../style.css">
    <style>
        /* Page ka apna thoda styling */
        .todo-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .tab-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            background: #e0e0e0;
            color: #333;
        }
        .tab-btn.active {
            background: #1a73e8;
            color: white;
        }
        .tab-content {
            display: none; /* sab chupaao */
        }
        .tab-content.active {
            display: block; /* sirf active wala dikhaao */
        }
        .assignment-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .assignment-title {
            font-weight: 600;
            color: #202124;
            font-size: 15px;
        }
        .class-name {
            color: #5f6368;
            font-size: 13px;
            margin-top: 4px;
        }
        .due-date {
            font-size: 13px;
            color: #5f6368;
        }
        .due-date.overdue {
            color: #d93025; /* red for missing */
        }
        .empty-msg {
            text-align: center;
            color: #5f6368;
            padding: 40px;
            font-size: 15px;
        }
        h1 {
            font-size: 22px;
            color: #202124;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Aiman ka Navbar include karo -->
<?php include '../includes/navbar.php'; ?>

<!-- Aiman ka Sidebar include karo -->
<?php include '../includes/sidebar.php'; ?>

<div class="todo-container">
    <h1>To Do</h1>

    <!-- Tab Buttons -->
    <div class="tab-buttons">
        <button class="tab-btn active" onclick="showTab('assigned')">Assigned</button>
        <button class="tab-btn" onclick="showTab('missing')">Missing</button>
        <button class="tab-btn" onclick="showTab('done')">Done</button>
    </div>

    <!-- TAB 1: ASSIGNED -->
    <div id="assigned" class="tab-content active">
        <?php if (mysqli_num_rows($assigned_result) == 0): ?>
            <p class="empty-msg">🎉 Koi pending assignment nahi hai!</p>
        <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($assigned_result)): ?>
                <div class="assignment-card">
                    <div>
                        <div class="assignment-title"><?php echo htmlspecialchars($row['title']); ?></div>
                        <div class="class-name"><?php echo htmlspecialchars($row['class_name']); ?></div>
                    </div>
                    <div class="due-date">
                        <?php echo $row['due_date'] ? "Due: " . date("d M", strtotime($row['due_date'])) : "No due date"; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <!-- TAB 2: MISSING -->
    <div id="missing" class="tab-content">
        <?php if (mysqli_num_rows($missing_result) == 0): ?>
            <p class="empty-msg">✅ Koi missing assignment nahi!</p>
        <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($missing_result)): ?>
                <div class="assignment-card">
                    <div>
                        <div class="assignment-title"><?php echo htmlspecialchars($row['title']); ?></div>
                        <div class="class-name"><?php echo htmlspecialchars($row['class_name']); ?></div>
                    </div>
                    <div class="due-date overdue">
                        Due: <?php echo date("d M", strtotime($row['due_date'])); ?> (Missed)
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <!-- TAB 3: DONE -->
    <div id="done" class="tab-content">
        <?php if (mysqli_num_rows($done_result) == 0): ?>
            <p class="empty-msg">Abhi koi completed assignment nahi.</p>
        <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($done_result)): ?>
                <div class="assignment-card">
                    <div>
                        <div class="assignment-title"><?php echo htmlspecialchars($row['title']); ?></div>
                        <div class="class-name"><?php echo htmlspecialchars($row['class_name']); ?></div>
                    </div>
                    <div class="due-date">
                        ✅ Submitted: <?php echo date("d M", strtotime($row['submitted_at'])); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    // Yeh function tab switch karta hai
    function showTab(tabName) {
        // Pehle sab tab chupaao
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        // Sab buttons se active class hatao
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        // Sirf clicked wala tab dikhaao
        document.getElementById(tabName).classList.add('active');
        // Clicked button ko active karo
        event.target.classList.add('active');
    }
</script>

</body>
</html>
