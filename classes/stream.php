<?php
require_once '../config.php';
requireLogin();

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
        .class-code-copy {
            background: none; border: none; color: white;
            cursor: pointer; font-size: 12px; margin-top: 4px;
            opacity: 0.8; text-decoration: underline;
        }
        .class-code-copy:hover { opacity: 1; }
        .banner-actions {
            position: absolute; top: 16px; right: 16px;
            display: flex; gap: 8px;
        }
        .banner-btn {
            background: rgba(255,255,255,0.15);
            border: none; color: white; cursor: pointer;
            padding: 8px 16px; border-radius: 4px;
            font-size: 13px; font-family: 'Google Sans', sans-serif;
            transition: background 0.2s;
        }
        .banner-btn:hover { background: rgba(255,255,255,0.25); }

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

<?php 
$breadcrumb = $class ? $class['name'] : 'Class';
$breadcrumb_sub = $class ? $class['section'] : '';
include '../includes/layout.php'; 
?>

<div class="main-content">
    <?php include '../includes/class_tabs.php'; ?>

    <!-- Class Banner -->
    <div class="class-banner">
        <div class="banner-actions">
            <?php if ($class && $class['teacher_id'] == $_SESSION['user_id']): ?>
            <button class="banner-btn" onclick="openEditModal(<?= $class_id ?>)">Edit</button>
            <button class="banner-btn" onclick="if(confirm('Archive this class?'))window.location.href='<?= BASE_URL ?>/actions/archive_class.php?id=<?= $class_id ?>&action=archive'">Archive</button>
            <?php endif; ?>
        </div>
        <h1><?php echo $class ? htmlspecialchars($class['name']) : 'My Class'; ?></h1>
        <p><?php echo $class ? htmlspecialchars($class['section'] . ' • ' . $class['subject']) : ''; ?></p>
        <?php if ($class): ?>
        <div class="class-code-box">
            <span>Class Code</span>
            <strong id="classCodeDisplay"><?php echo htmlspecialchars($class['code']); ?></strong>
            <br><button class="class-code-copy" onclick="copyClassCode()">Copy code</button>
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

<!-- Edit Class Modal -->
<div class="gc-modal-overlay" id="editModalOverlay" onclick="closeEditModal()"></div>
<div class="gc-modal" id="editModal">
    <div class="gc-modal-header"><h2>Edit class</h2></div>
    <form action="<?= BASE_URL ?>/actions/edit_class.php" method="POST">
        <input type="hidden" name="class_id" id="editClassId" value="">
        <div class="gc-modal-body">
            <div class="gc-create-input">
                <input type="text" name="name" id="editName" required placeholder=" " autocomplete="off">
                <label for="editName">Class name<span class="req-star">*</span></label>
            </div>
            <p class="gc-required-text">*Required</p>
            <div class="gc-create-input">
                <input type="text" name="section" id="editSection" placeholder=" " autocomplete="off">
                <label for="editSection">Section</label>
            </div>
            <div class="gc-create-input">
                <input type="text" name="subject" id="editSubject" placeholder=" " autocomplete="off">
                <label for="editSubject">Subject</label>
            </div>
            <div class="gc-create-input">
                <input type="text" name="room" id="editRoom" placeholder=" " autocomplete="off">
                <label for="editRoom">Room</label>
            </div>
        </div>
        <div class="gc-modal-footer">
            <button type="button" class="gc-btn-text" onclick="closeEditModal()">Cancel</button>
            <button type="submit" class="gc-btn-text" id="editBtn">Save</button>
        </div>
    </form>
</div>

<script>
function copyClassCode() {
    const code = document.getElementById('classCodeDisplay').textContent;
    navigator.clipboard.writeText(code).then(() => {
        const btn = document.querySelector('.class-code-copy');
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy code', 2000);
    });
}
function openEditModal(id) {
    document.getElementById('editClassId').value = id;
    document.getElementById('editName').value = '<?= htmlspecialchars($class['name'] ?? '') ?>';
    document.getElementById('editSection').value = '<?= htmlspecialchars($class['section'] ?? '') ?>';
    document.getElementById('editSubject').value = '<?= htmlspecialchars($class['subject'] ?? '') ?>';
    document.getElementById('editRoom').value = '<?= htmlspecialchars($class['room'] ?? '') ?>';
    document.getElementById('editModalOverlay').classList.add('show');
    document.getElementById('editModal').classList.add('show');
}
function closeEditModal() {
    document.getElementById('editModalOverlay').classList.remove('show');
    document.getElementById('editModal').classList.remove('show');
}
</script>

</body>
</html>
