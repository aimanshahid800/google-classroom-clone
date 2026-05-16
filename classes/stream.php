<?php
require_once '../config.php';

// Get class_id from URL
$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 1;

// Get class info
$class_query = mysqli_query($conn, "SELECT * FROM classes WHERE id = $class_id");
$class = mysqli_fetch_assoc($class_query);

// Get assignments for this class (stream feed)
$assignments_query = mysqli_query($conn, "
    SELECT a.*, u.name as teacher_name 
    FROM assignments a
    JOIN classes c ON a.class_id = c.id
    JOIN users u ON c.teacher_id = u.id
    WHERE a.class_id = $class_id
    ORDER BY a.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stream - <?php echo $class ? htmlspecialchars($class['name']) : 'Class'; ?></title>
    <link rel="stylesheet" href="../style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Google Sans', Roboto, Arial, sans-serif; background: #f1f3f4; }

        .main-content { margin-left: 256px; padding: 20px; margin-top: 64px; }

        /* Class Banner */
        .class-banner {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            border-radius: 12px;
            padding: 40px;
            color: white;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }
        .class-banner::after {
            content: '';
            position: absolute;
            right: -20px; bottom: -20px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .class-banner h1 { font-size: 32px; font-weight: 400; margin-bottom: 8px; }
        .class-banner p { font-size: 16px; opacity: 0.9; }
        .class-code-box {
            position: absolute; right: 40px; top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.15);
            border-radius: 8px; padding: 16px 24px;
            text-align: center;
        }
        .class-code-box span { display: block; font-size: 12px; opacity: 0.8; margin-bottom: 4px; }
        .class-code-box strong { font-size: 28px; letter-spacing: 4px; }

        /* Stream Layout */
        .stream-layout { display: grid; grid-template-columns: 1fr 300px; gap: 20px; }

        /* Announcement Box */
        .announce-box {
            background: white; border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 20px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 16px;
            cursor: pointer; transition: box-shadow 0.2s;
        }
        .announce-box:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.12); }
        .avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: #1a73e8; color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 500; flex-shrink: 0;
        }
        .announce-box input {
            border: none; outline: none; width: 100%;
            color: #5f6368; font-size: 14px; cursor: pointer;
            background: transparent;
        }

        /* Post Cards */
        .post-card {
            background: white; border-radius: 8px;
            border: 1px solid #e0e0e0;
            margin-bottom: 16px; overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .post-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.12); }
        .post-header { padding: 16px 20px; display: flex; align-items: center; gap: 12px; }
        .post-header .avatar { background: #1a73e8; font-size: 14px; }
        .post-meta { flex: 1; }
        .post-meta strong { display: block; font-size: 14px; color: #202124; }
        .post-meta span { font-size: 12px; color: #5f6368; }
        .post-body { padding: 0 20px 16px; }
        .post-body h3 {
            font-size: 16px; color: #1a73e8;
            margin-bottom: 8px; font-weight: 500;
        }
        .post-body p { font-size: 14px; color: #5f6368; line-height: 1.5; }
        .post-footer {
            border-top: 1px solid #e0e0e0;
            padding: 12px 20px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .due-badge {
            background: #e8f0fe; color: #1a73e8;
            padding: 4px 12px; border-radius: 12px; font-size: 12px;
        }
        .view-btn {
            color: #1a73e8; font-size: 13px;
            text-decoration: none; font-weight: 500;
        }
        .view-btn:hover { text-decoration: underline; }

        /* Sidebar Cards */
        .side-card {
            background: white; border-radius: 8px;
            border: 1px solid #e0e0e0; padding: 20px;
            margin-bottom: 16px;
        }
        .side-card h4 { font-size: 14px; color: #5f6368; margin-bottom: 12px; }
        .side-card .code-display {
            font-size: 24px; color: #1a73e8;
            letter-spacing: 3px; font-weight: 500;
        }
        .upcoming-item {
            padding: 8px 0; border-bottom: 1px solid #f1f3f4;
            font-size: 13px;
        }
        .upcoming-item:last-child { border-bottom: none; }
        .upcoming-item .title { color: #202124; margin-bottom: 2px; }
        .upcoming-item .date { color: #5f6368; font-size: 12px; }

        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; color: #5f6368; }
        .empty-state .icon { font-size: 64px; margin-bottom: 16px; }
        .empty-state h3 { font-size: 18px; margin-bottom: 8px; color: #202124; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main-content">

    <!-- Class Banner -->
    <div class="class-banner">
        <h1><?php echo $class ? htmlspecialchars($class['name']) : 'My Class'; ?></h1>
        <p><?php echo $class ? htmlspecialchars($class['section'] . ' • ' . $class['subject']) : ''; ?></p>
        <?php if ($class): ?>
        <div class="class-code-box">
            <span>Class Code</span>
            <strong><?php echo htmlspecialchars($class['code']); ?></strong>
        </div>
        <?php endif; ?>
    </div>

    <div class="stream-layout">

        <!-- Left: Feed -->
        <div class="stream-feed">

            <!-- Announce Box -->
            <div class="announce-box">
                <div class="avatar">T</div>
                <input type="text" placeholder="Announce something to your class..." readonly>
            </div>

            <!-- Posts Feed -->
            <?php if (mysqli_num_rows($assignments_query) > 0): ?>
                <?php while ($post = mysqli_fetch_assoc($assignments_query)): ?>
                <div class="post-card">
                    <div class="post-header">
                        <div class="avatar"><?php echo strtoupper(substr($post['teacher_name'], 0, 1)); ?></div>
                        <div class="post-meta">
                            <strong><?php echo htmlspecialchars($post['teacher_name']); ?></strong>
                            <span>Posted <?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                        </div>
                    </div>
                    <div class="post-body">
                        <h3>📋 <?php echo htmlspecialchars($post['title']); ?></h3>
                        <p><?php echo $post['description'] ? htmlspecialchars(substr($post['description'], 0, 150)) . '...' : 'No description provided.'; ?></p>
                    </div>
                    <div class="post-footer">
                        <span class="due-badge">
                            Due: <?php echo $post['due_date'] ? date('M d, Y', strtotime($post['due_date'])) : 'No due date'; ?>
                        </span>
                        <a href="../assignments/submit.php?assignment_id=<?php echo $post['id']; ?>" class="view-btn">View Assignment →</a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="icon">📢</div>
                    <h3>Stream is empty</h3>
                    <p>Assignments posted by teacher will appear here.</p>
                </div>
            <?php endif; ?>

        </div>

        <!-- Right: Sidebar Info -->
        <div class="stream-sidebar">

            <!-- Class Code Card -->
            <div class="side-card">
                <h4>Class Code</h4>
                <div class="code-display"><?php echo $class ? htmlspecialchars($class['code']) : '------'; ?></div>
                <p style="font-size:12px; color:#5f6368; margin-top:8px;">Share this code with students to join</p>
            </div>

            <!-- Upcoming Work Card -->
            <div class="side-card">
                <h4>📅 Upcoming</h4>
                <?php
                $upcoming = mysqli_query($conn, "
                    SELECT title, due_date FROM assignments 
                    WHERE class_id = $class_id AND due_date >= CURDATE()
                    ORDER BY due_date ASC LIMIT 5
                ");
                if (mysqli_num_rows($upcoming) > 0):
                    while ($item = mysqli_fetch_assoc($upcoming)):
                ?>
                <div class="upcoming-item">
                    <div class="title">📋 <?php echo htmlspecialchars($item['title']); ?></div>
                    <div class="date">Due: <?php echo date('M d', strtotime($item['due_date'])); ?></div>
                </div>
                <?php endwhile; else: ?>
                <p style="font-size:13px; color:#5f6368;">No upcoming assignments 🎉</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

</body>
</html>
