<?php
require_once '../config.php';

$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 1;

// Get class info
$class_query = mysqli_query($conn, "SELECT c.*, u.name as teacher_name, u.email as teacher_email 
    FROM classes c 
    JOIN users u ON c.teacher_id = u.id 
    WHERE c.id = $class_id");
$class = mysqli_fetch_assoc($class_query);

// Get students
$students_query = mysqli_query($conn, "
    SELECT DISTINCT u.id, u.name, u.email, u.created_at
    FROM users u
    JOIN submissions s ON u.id = s.student_id
    JOIN assignments a ON s.assignment_id = a.id
    WHERE a.class_id = $class_id AND u.role = 'student'
    ORDER BY u.name ASC
");

$teacher_count = 1;
$student_count = mysqli_num_rows($students_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People - <?php echo $class ? htmlspecialchars($class['name']) : 'Class'; ?></title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Google Sans', Roboto, Arial, sans-serif;
            background: #f1f3f4;
            color: #202124;
        }

        .main-content {
            margin-left: 256px;
            padding: 24px 24px 40px 24px;
            margin-top: 64px;
            max-width: 1000px;
        }

        /* Stats Bar */
        .stats-bar {
            display: flex;
            margin-bottom: 24px;
            background: white;
            border-radius: 8px;
            border: 1px solid #dadce0;
            overflow: hidden;
        }
        .stat-card {
            flex: 1;
            padding: 24px 16px;
            text-align: center;
            border-right: 1px solid #dadce0;
        }
        .stat-card:last-child { border-right: none; }
        .stat-card .number {
            font-size: 40px;
            color: #1a73e8;
            font-weight: 400;
            line-height: 1;
            margin-bottom: 6px;
        }
        .stat-card .label {
            font-size: 13px;
            color: #5f6368;
            font-family: Roboto, sans-serif;
        }

        /* Section Card */
        .people-section {
            background: white;
            border-radius: 8px;
            border: 1px solid #dadce0;
            margin-bottom: 24px;
            overflow: hidden;
        }

        /* Section Header */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px 0 24px;
        }
        .section-header h3 {
            font-size: 22px;
            color: #1a73e8;
            font-weight: 400;
            font-family: 'Google Sans', sans-serif;
        }
        .section-divider {
            height: 2px;
            background: #1a73e8;
            margin: 12px 24px 0 24px;
        }

        /* Invite Button */
        .invite-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            border: 1px solid #dadce0;
            color: #1a73e8;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 13px;
            font-family: 'Google Sans', sans-serif;
            font-weight: 500;
            cursor: pointer;
            letter-spacing: 0.25px;
            transition: background 0.15s, box-shadow 0.15s;
        }
        .invite-btn:hover {
            background: #e8f0fe;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .invite-btn svg {
            width: 16px; height: 16px; fill: #1a73e8;
        }

        /* Person Row */
        .person-row {
            display: flex;
            align-items: center;
            padding: 10px 24px;
            min-height: 56px;
            transition: background 0.1s;
        }
        .person-row:hover { background: #f8f9fa; }

        /* Avatar */
        .avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 500;
            color: white; flex-shrink: 0;
            margin-right: 16px;
            text-transform: uppercase;
        }

        /* Person Info */
        .person-info { flex: 1; min-width: 0; }
        .person-info .name {
            font-size: 14px; color: #202124;
            font-weight: 400;
        }
        .person-info .email {
            font-size: 12px; color: #5f6368; margin-top: 1px;
        }

        /* Search Bar */
        .search-wrap {
            padding: 12px 24px;
            border-bottom: 1px solid #f1f3f4;
        }
        .search-inner {
            display: flex; align-items: center;
            background: #f1f3f4;
            border-radius: 24px;
            padding: 8px 16px;
            width: 280px; gap: 8px;
            border: 1px solid transparent;
            transition: border 0.2s, background 0.2s;
        }
        .search-inner:focus-within {
            background: white;
            border-color: #1a73e8;
        }
        .search-inner svg { width: 18px; height: 18px; fill: #5f6368; flex-shrink: 0; }
        .search-inner input {
            border: none; background: transparent;
            font-size: 14px; color: #202124;
            outline: none; width: 100%;
            font-family: Roboto, sans-serif;
        }
        .search-inner input::placeholder { color: #5f6368; }

        /* Empty State */
        .empty-state {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 56px 24px; text-align: center; gap: 12px;
        }
        .empty-state svg { width: 56px; height: 56px; fill: #bdc1c6; }
        .empty-state p {
            font-size: 14px; color: #5f6368;
            line-height: 1.6; font-family: Roboto, sans-serif;
        }
        .empty-state strong { color: #202124; }

        /* Three-dot menu */
        .more-btn {
            background: none; border: none; cursor: pointer;
            padding: 8px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: background 0.15s, opacity 0.15s;
        }
        .person-row:hover .more-btn { opacity: 1; }
        .more-btn:hover { background: #e8eaed; }
        .more-btn svg { width: 20px; height: 20px; fill: #5f6368; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main-content">

    <!-- Stats Bar -->
    <div class="stats-bar">
        <div class="stat-card">
            <div class="number"><?php echo $teacher_count; ?></div>
            <div class="label">Teacher</div>
        </div>
        <div class="stat-card">
            <div class="number"><?php echo $student_count; ?></div>
            <div class="label">Students</div>
        </div>
        <div class="stat-card">
            <div class="number"><?php echo $teacher_count + $student_count; ?></div>
            <div class="label">Total Members</div>
        </div>
    </div>

    <!-- Teachers Section -->
    <div class="people-section">
        <div class="section-header">
            <h3>Teachers</h3>
            <button class="invite-btn">
                <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                Invite Teachers
            </button>
        </div>
        <div class="section-divider"></div>

        <?php if ($class):
            $initial = strtoupper(substr($class['teacher_name'], 0, 1));
            $colors  = ['#1a73e8','#ea4335','#34a853','#fbbc04','#9c27b0'];
            $bg      = $colors[ord($initial) % count($colors)];
        ?>
        <div class="person-row">
            <div class="avatar" style="background:<?php echo $bg; ?>">
                <?php echo $initial; ?>
            </div>
            <div class="person-info">
                <div class="name"><?php echo htmlspecialchars($class['teacher_name']); ?></div>
                <div class="email"><?php echo htmlspecialchars($class['teacher_email']); ?></div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Students Section -->
    <div class="people-section">
        <div class="section-header">
            <h3>Students (<?php echo $student_count; ?>)</h3>
            <button class="invite-btn">
                <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                Invite Students
            </button>
        </div>
        <div class="section-divider"></div>

        <!-- Search -->
        <div class="search-wrap">
            <div class="search-inner">
                <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                <input type="text" id="studentSearch" placeholder="Search students..." onkeyup="searchStudents()">
            </div>
        </div>

        <!-- Students List -->
        <div id="studentsList">
        <?php if ($student_count > 0):
            mysqli_data_seek($students_query, 0);
            $palette = ['#1a73e8','#ea4335','#34a853','#fbbc04','#9c27b0','#00acc1','#e67c22'];
            $i = 0;
            while ($student = mysqli_fetch_assoc($students_query)):
                $init  = strtoupper(substr($student['name'], 0, 1));
                $color = $palette[$i % count($palette)];
                $i++;
        ?>
        <div class="person-row student-item">
            <div class="avatar" style="background:<?php echo $color; ?>">
                <?php echo $init; ?>
            </div>
            <div class="person-info">
                <div class="name student-name"><?php echo htmlspecialchars($student['name']); ?></div>
                <div class="email"><?php echo htmlspecialchars($student['email']); ?></div>
            </div>
            <button class="more-btn" title="More options">
                <svg viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
            </button>
        </div>
        <?php endwhile; ?>

        <?php else: ?>
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            <p>
                No students have joined yet.<br>
                Share the class code: <strong><?php echo $class ? htmlspecialchars($class['code']) : ''; ?></strong>
            </p>
        </div>
        <?php endif; ?>
        </div>
    </div>

</div>

<script>
function searchStudents() {
    const val = document.getElementById('studentSearch').value.toLowerCase();
    document.querySelectorAll('.student-item').forEach(row => {
        const name = row.querySelector('.student-name').textContent.toLowerCase();
        row.style.display = name.includes(val) ? 'flex' : 'none';
    });
}
</script>

</body>
</html>
