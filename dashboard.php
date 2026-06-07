<?php
require_once 'config.php';
requireLogin();
$user_id = $_SESSION['user_id'];

// Delete class
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM classes WHERE id = $del_id AND teacher_id = $user_id");
    header('Location: dashboard.php');
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM classes WHERE teacher_id = $user_id");
$classes = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
$colors = ['#1e6091','#6a1e91','#91501e','#1e9168','#911e4a','#4a911e'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Google Classroom</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .main-area { margin-left: 280px; margin-top: 80px; padding: 30px; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .top-bar h1 { font-size: 22px; color: #202124; }
        .btn-create { padding: 10px 20px; background: #1a73e8; color: white; border-radius: 6px; font-size: 14px; text-decoration: none; color: white; }
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .class-card { border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.15); background: white; position: relative; }
        .card-top { height: 100px; padding: 16px; color: white; position: relative; }
        .card-top h2 { font-size: 20px; font-weight: bold; margin: 0; }
        .card-top p { font-size: 13px; margin: 4px 0 0 0; opacity: 0.9; }
        .card-bottom { padding: 12px 16px; font-size: 13px; color: #555; border-top: 1px solid #e0e0e0; }
        .empty-msg { color: #666; font-size: 15px; }

        /* 3 dots menu */
        .dots-menu { position: absolute; top: 10px; right: 10px; }
        .dots-btn { background: none; border: none; color: white; font-size: 20px; cursor: pointer; padding: 4px 8px; border-radius: 50%; }
        .dots-btn:hover { background: rgba(255,255,255,0.2); }
        .dropdown { display: none; position: absolute; right: 0; top: 30px; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); min-width: 150px; z-index: 100; }
        .dropdown a { display: block; padding: 12px 16px; color: #333; text-decoration: none; font-size: 14px; }
        .dropdown a:hover { background: #f1f3f4; }
        .dropdown.show { display: block; }
    </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="main-area">
    <div class="top-bar">
        <h1>My Classes</h1>
        <a href="../classes/create.php" class="btn-create">+ Create Class</a>
    </div>

    <div class="cards-grid">
        <?php if (empty($classes)): ?>
            <p class="empty-msg">No classes found. Create a class!</p>
        <?php else: ?>
            <?php foreach ($classes as $i => $class): ?>
                <div class="class-card">
                    <div class="card-top" style="background:<?= $colors[$i % count($colors)] ?>">
                        <h2><?= htmlspecialchars($class['name']) ?></h2>
                        <p><?= htmlspecialchars($class['subject'] ?? '') ?></p>

                        <!-- 3 dots menu -->
                        <div class="dots-menu">
                            <button class="dots-btn" onclick="toggleMenu(<?= $class['id'] ?>)">⋮</button>
                            <div class="dropdown" id="menu-<?= $class['id'] ?>">
                                <a href="?delete=<?= $class['id'] ?>" 
                                   onclick="return confirm('Delete this class?')">🗑️ Delete</a>
                                <a href="../classes/archived.php">📦 Archive</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-bottom">Teacher</div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleMenu(id) {
    // Close all menus first
    document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('show'));
    // Open clicked menu
    document.getElementById('menu-' + id).classList.toggle('show');
}
// Close menu when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.classList.contains('dots-btn')) {
        document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('show'));
    }
});
</script>

</body>
</html>