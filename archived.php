<?php
require_once 'config.php';
requireLogin();

$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn, "SELECT * FROM classes WHERE teacher_id = $user_id AND is_archived = 1 ORDER BY created_at DESC");
$archived = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
$colors = ['#37474f', '#1a73e8', '#e91e63', '#4caf50', '#ff9800', '#9c27b0'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Archived Classes - Google Classroom</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Google Sans', 'Roboto', Arial, sans-serif; background: #f0f4f9; }
        .archived-header { padding: 24px 24px 0; font-size: 24px; font-weight: 400; color: #202124; }
        .archived-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; padding: 24px; }
        .archived-card { background: white; border-radius: 8px; border: 1px solid #dadce0; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .archived-card-top { height: 80px; padding: 16px; color: white; position: relative; }
        .archived-card-top h2 { font-size: 18px; font-weight: 500; margin: 0; }
        .archived-card-top p { font-size: 12px; opacity: 0.9; margin-top: 4px; }
        .archived-card-bottom { padding: 12px 16px; display: flex; justify-content: flex-end; gap: 8px; border-top: 1px solid #e0e0e0; }
        .archived-btn { padding: 8px 16px; border-radius: 4px; font-size: 13px; font-weight: 500; font-family: 'Google Sans', sans-serif; cursor: pointer; border: none; background: transparent; color: #1a73e8; transition: background 0.2s; }
        .archived-btn:hover { background: rgba(26,115,232,0.04); }
        .empty-state { grid-column: 1 / -1; text-align: center; padding: 80px 20px; color: #5f6368; }
        .empty-state h3 { font-size: 18px; margin-bottom: 8px; color: #202124; }
    </style>
</head>
<body>
<?php include 'includes/layout.php'; ?>
<main class="gc-main">
    <h1 class="archived-header">Archived classes</h1>
    <div class="archived-grid">
        <?php if (empty($archived)): ?>
            <div class="empty-state">
                <h3>No archived classes</h3>
                <p>Archived classes will appear here.</p>
            </div>
        <?php else: ?>
            <?php foreach ($archived as $i => $c):
                $color = $colors[$i % count($colors)];
            ?>
            <div class="archived-card">
                <div class="archived-card-top" style="background:<?= $color ?>">
                    <h2><?= htmlspecialchars($c['name']) ?></h2>
                    <p><?= htmlspecialchars($c['section'] ?? '') ?></p>
                </div>
                <div class="archived-card-bottom">
                    <a href="<?= BASE_URL ?>/actions/archive_class.php?id=<?= $c['id'] ?>&action=unarchive" class="archived-btn">Restore</a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
