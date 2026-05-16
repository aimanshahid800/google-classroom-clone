<?php
require_once '../config.php';

$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 1;

// Get class info
$class_query = mysqli_query($conn, "SELECT * FROM classes WHERE id = $class_id");
$class = mysqli_fetch_assoc($class_query);

// Get all assignments for this class
$assignments_query = mysqli_query($conn, "
    SELECT * FROM assignments 
    WHERE class_id = $class_id
    ORDER BY due_date ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classwork - <?php echo $class ? htmlspecialchars($class['name']) : 'Class'; ?></title>
    <link rel="stylesheet" href="../style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Google Sans', Roboto, Arial, sans-serif; background: #f1f3f4; }

        .main-content { margin-left: 256px; padding: 20px; margin-top: 64px; }

        /* Top Bar */
        .top-bar {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 24px;
        }
        .top-bar h2 { font-size: 22px; color: #202124; font-weight: 400; }
        .create-btn {
            background: #1a73e8; color: white;
            border: none; padding: 10px 24px;
            border-radius: 24px; font-size: 14px;
            cursor: pointer; font-weight: 500;
            transition: background 0.2s;
        }
        .create-btn:hover { background: #1557b0; }

        /* Filter Tabs */
        .filter-tabs {
            display: flex; gap: 8px; margin-bottom: 24px;
            border-bottom: 1px solid #e0e0e0; padding-bottom: 0;
        }
        .tab-btn {
            padding: 10px 20px; border: none; background: none;
            font-size: 14px; color: #5f6368; cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.2s; font-weight: 500;
        }
        .tab-btn.active { color: #1a73e8; border-bottom-color: #1a73e8; }
        .tab-btn:hover { background: #f1f3f4; color: #202124; }

        /* Topic Section */
        .topic-section { margin-bottom: 32px; }
        .topic-header {
            display: flex; align-items: center; gap: 12px;
            padding: 16px 0; border-bottom: 1px solid #e0e0e0;
            margin-bottom: 8px;
        }
        .topic-header h3 { font-size: 16px; color: #202124; font-weight: 500; }
        .topic-icon { color: #1a73e8; font-size: 20px; }

        /* Assignment Row */
        .assignment-row {
            display: flex; align-items: center;
            padding: 14px 16px; background: white;
            border: 1px solid #e0e0e0; border-radius: 8px;
            margin-bottom: 8px; cursor: pointer;
            transition: box-shadow 0.2s;
            text-decoration: none; color: inherit;
        }
        .assignment-row:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.12); }
        .assign-icon {
            width: 40px; height: 40px; border-radius: 50%;
            background: #e8f0fe; color: #1a73e8;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; margin-right: 16px; flex-shrink: 0;
        }
        .assign-info { flex: 1; }
        .assign-info h4 { font-size: 14px; color: #202124; margin-bottom: 3px; }
        .assign-info p { font-size: 12px; color: #5f6368; }
        .assign-due {
            font-size: 12px; color: #5f6368;
            text-align: right; min-width: 100px;
        }
        .overdue { color: #d32f2f !important; }

        /* Status Badge */
        .status-badge {
            padding: 3px 10px; border-radius: 12px;
            font-size: 11px; font-weight: 500; margin-left: 8px;
        }
        .badge-due { background: #fce8e6; color: #d32f2f; }
        .badge-upcoming { background: #e8f0fe; color: #1a73e8; }

        /* Empty State */
        .empty-state { text-align: center; padding: 80px 20px; color: #5f6368; }
        .empty-state .icon { font-size: 64px; margin-bottom: 16px; }
        .empty-state h3 { font-size: 18px; margin-bottom: 8px; color: #202124; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main-content">

    <!-- Top Bar -->
    <div class="top-bar">
        <h2>Classwork</h2>
        <button class="create-btn">+ Create</button>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="tab-btn active" onclick="filterAssignments('all', this)">All Topics</button>
        <button class="tab-btn" onclick="filterAssignments('upcoming', this)">Upcoming</button>
        <button class="tab-btn" onclick="filterAssignments('missing', this)">Missing</button>
        <button class="tab-btn" onclick="filterAssignments('done', this)">Done</button>
    </div>

    <!-- Assignments List -->
    <?php
    mysqli_data_seek($assignments_query, 0);
    $total = mysqli_num_rows($assignments_query);
    ?>

    <?php if ($total > 0): ?>

        <!-- Topic: All Assignments -->
        <div class="topic-section" id="section-all">
            <div class="topic-header">
                <span class="topic-icon">📚</span>
                <h3>All Assignments</h3>
            </div>

            <?php while ($assignment = mysqli_fetch_assoc($assignments_query)): ?>
            <?php
                $is_overdue = $assignment['due_date'] && strtotime($assignment['due_date']) < time();
                $due_label = $assignment['due_date'] ? date('M d, Y', strtotime($assignment['due_date'])) : 'No due date';
            ?>
            <a href="../assignments/submit.php?assignment_id=<?php echo $assignment['id']; ?>" class="assignment-row">
                <div class="assign-icon">📋</div>
                <div class="assign-info">
                    <h4><?php echo htmlspecialchars($assignment['title']); ?></h4>
                    <p><?php echo $assignment['description'] ? htmlspecialchars(substr($assignment['description'], 0, 80)) . '...' : 'No description'; ?></p>
                </div>
                <div class="assign-due <?php echo $is_overdue ? 'overdue' : ''; ?>">
                    <?php if ($is_overdue): ?>
                        <span class="status-badge badge-due">Overdue</span><br>
                    <?php else: ?>
                        <span class="status-badge badge-upcoming">Upcoming</span><br>
                    <?php endif; ?>
                    <?php echo $due_label; ?>
                </div>
            </a>
            <?php endwhile; ?>
        </div>

    <?php else: ?>
        <div class="empty-state">
            <div class="icon">📝</div>
            <h3>No assignments yet</h3>
            <p>Assignments created by teacher will appear here.</p>
        </div>
    <?php endif; ?>

</div>

<script>
function filterAssignments(type, btn) {
    // Update active tab
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const rows = document.querySelectorAll('.assignment-row');
    const today = new Date();

    rows.forEach(row => {
        const dueBadge = row.querySelector('.status-badge');
        if (!dueBadge) { row.style.display = 'flex'; return; }

        const isOverdue = dueBadge.classList.contains('badge-due');

        if (type === 'all') {
            row.style.display = 'flex';
        } else if (type === 'upcoming') {
            row.style.display = !isOverdue ? 'flex' : 'none';
        } else if (type === 'missing') {
            row.style.display = isOverdue ? 'flex' : 'none';
        } else if (type === 'done') {
            row.style.display = 'none'; // needs submission check from DB
        }
    });
}
</script>

</body>
</html>
