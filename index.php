<?php
require_once 'config.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$page_title = 'Google Classroom';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Classroom</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'includes/layout.php'; ?>

<main class="gc-main">
<?php
// Fetch all classes where user is a teacher or student (exclude archived)
$sql = "
    SELECT c.*, u.name as teacher_name 
    FROM classes c
    LEFT JOIN users u ON c.teacher_id = u.id
    LEFT JOIN enrollments e ON c.id = e.class_id
    WHERE (c.teacher_id = $user_id OR e.student_id = $user_id) AND c.is_archived = 0
    GROUP BY c.id
";
$res_classes = mysqli_query($conn, $sql);
$classes = $res_classes ? mysqli_fetch_all($res_classes, MYSQLI_ASSOC) : [];
$colors = ['#37474f', '#1a73e8', '#e91e63', '#4caf50', '#ff9800', '#9c27b0'];
?>
    <div class="classes-grid">
        <?php if (empty($classes)): ?>
            <div class="empty-state-container">
                <img src="https://www.gstatic.com/classroom/empty_states_home.svg" alt="Add a class to get started" class="empty-state-img">
                <p class="empty-state-text">Add a class to get started</p>
                <div class="empty-state-actions">
                    <button type="button" class="btn-outline" onclick="openCreateModal()">Create class</button>
                    <button type="button" class="btn-filled" onclick="openJoinModal()">Join class</button>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($classes as $i => $class): 
                $color = $colors[$i % count($colors)];
                $teacher = htmlspecialchars($class['teacher_name'] ?? 'Unknown');
                $initial = strtoupper(substr($teacher, 0, 1));
            ?>
            <div class="class-card" style="--card-color: <?= $color ?>;" onclick="window.location.href='classes/stream.php?id=<?= $class['id'] ?>'">
                <div class="card-header">
                    <div class="card-title-group">
                        <div class="card-title"><?= htmlspecialchars($class['name']) ?></div>
                        <div class="card-section"><?= htmlspecialchars($class['section'] ?? '') ?></div>
                        <div class="card-teacher"><?= $teacher ?></div>
                    </div>
                </div>
                <!-- Avatar overlapping -->
                <div class="card-avatar" style="background-color: <?= $colors[($i+1) % count($colors)] ?>;"><?= $initial ?></div>
                
                <div class="card-body"></div>
                
                <div class="card-footer">
                    <span class="card-icon" title="Open your work" onclick="event.stopPropagation();">
                        <img src="<?= $icons ?>/Ai-Generate-Portrait-Image-Spark--Streamline-Core.svg" alt="Work">
                    </span>
                    <span class="card-icon" title="Open folder" onclick="event.stopPropagation();">
                        <img src="<?= $icons ?>/New-Folder--Streamline-Core.svg" alt="Folder">
                    </span>
                    <span class="card-icon" title="More options" onclick="event.stopPropagation();">
                        <img src="<?= $icons ?>/Vertical-Menu--Streamline-Plump.png" alt="Options">
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Help Icon at bottom right -->
    <button class="help-btn" title="Help">
        <img src="<?= $icons ?>/Help-Question-1--Streamline-Plump.png" alt="Help" width="24" height="24">
    </button>
</main>

<style>
.main-content {
    margin-left: 256px;
    margin-top: 64px;
    padding: 24px;
    background: #f0f4f9;
    min-height: calc(100vh - 64px);
}

.classes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 24px;
}
.class-card {
    background: white;
    border-radius: 8px;
    border: 1px solid #dadce0;
    overflow: hidden;
    cursor: pointer;
    transition: box-shadow 0.2s;
    display: flex;
    flex-direction: column;
    height: 320px;
    position: relative;
}
.class-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.card-header {
    background: var(--card-color);
    padding: 16px;
    color: white;
    height: 100px;
    position: relative;
    display: flex;
    flex-direction: column;
    box-sizing: border-box; /* Ensures the avatar correctly overlaps the bottom border */
}
.card-title-group {
    width: 75%;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.card-title {
    font-size: 22px;
    font-family: 'Google Sans', sans-serif;
    font-weight: 500;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    position: relative;
    display: inline-block;
    max-width: 100%;
}
.card-title::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 0;
    background-color: white;
    transition: width 0.3s ease;
}
.class-card:hover .card-title::after {
    width: 100%;
}
.card-section {
    font-size: 13px;
    font-family: 'Roboto', sans-serif;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    position: relative;
    display: inline-block;
    max-width: 100%;
}
.card-section::after {
    content: '';
    position: absolute;
    width: 0;
    height: 1px;
    bottom: 0;
    left: 0;
    background-color: white;
    transition: width 0.3s ease;
}
.class-card:hover .card-section::after {
    width: 100%;
}
.card-teacher {
    font-size: 13px;
    font-family: 'Roboto', sans-serif;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    position: relative;
    display: inline-block;
    max-width: 100%;
}
.card-teacher::after {
    content: '';
    position: absolute;
    width: 0;
    height: 1px;
    bottom: 0;
    left: 0;
    background-color: white;
    transition: width 0.3s ease;
}
.class-card:hover .card-teacher::after {
    width: 100%;
}
.card-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    position: absolute;
    right: 16px;
    top: 60px; /* Overlaps header and body */
    background-color: #ccc;
    color: #fff;
    font-family: 'Google Sans', sans-serif;
    font-size: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    z-index: 10;
}
.card-body {
    flex: 1; /* Fills remaining space */
    background: white;
}
.card-footer {
    padding: 12px 16px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 16px;
    border-top: 1px solid #e0e0e0;
}
.card-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}
.card-icon:hover { 
    background: #f1f3f4; 
}
.card-icon img {
    width: 20px;
    height: 20px;
    opacity: 0.7; /* Make icons greyish */
}
.card-icon:hover img {
    opacity: 1;
}

/* Empty State */
.empty-state-container {
    grid-column: 1 / -1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-top: 80px;
    font-family: 'Google Sans', 'Roboto', sans-serif;
}
.empty-state-img {
    width: 250px;
    margin-bottom: 24px;
}
.empty-state-text {
    font-size: 16px;
    color: #3c4043;
    font-weight: 500;
    margin-bottom: 24px;
}
.empty-state-actions {
    display: flex;
    gap: 8px;
}
.btn-outline {
    color: #1a73e8;
    background: transparent;
    font-weight: 500;
    font-size: 14px;
    padding: 10px 24px;
    border-radius: 4px;
    text-decoration: none;
    transition: background 0.2s;
    border: none;
    cursor: pointer;
}
.btn-outline:hover {
    background: rgba(26,115,232,0.04);
}
.btn-filled {
    color: #fff;
    background: #1a73e8;
    font-weight: 500;
    font-size: 14px;
    padding: 10px 24px;
    border-radius: 4px;
    text-decoration: none;
    transition: background 0.2s, box-shadow 0.2s;
    border: none;
    cursor: pointer;
}
.btn-filled:hover {
    background: #174ea6;
    box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
}

/* Help button */
.help-btn {
    position: fixed;
    bottom: 24px;
    right: 24px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: white;
    border: 1px solid #dadce0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    transition: background 0.2s, box-shadow 0.2s;
    z-index: 100;
}
.help-btn:hover {
    background: #f8fafd;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}
</style>

</body>
</html>