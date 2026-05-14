<?php
session_start();
require_once 'config.php';

// Temp: hardcode user for preview
$_SESSION['user_name'] = 'Aiman';
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

<?php include 'includes/navbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main class="main-content">
    <div class="dashboard-header">
        <h2>Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?> 👋</h2>
    </div>

    <div class="classes-grid">
        <!-- Sample class cards -->
        <div class="class-card" style="--card-color: #1a73e8">
            <div class="card-header">
                <div class="card-title">Web Engineering</div>
                <div class="card-section">BSCS-6A · Section A</div>
                <div class="card-teacher">Ms. Mehwish</div>
            </div>
            <div class="card-body"></div>
            <div class="card-footer">
                <span class="card-icon" title="Classwork">📋</span>
                <span class="card-icon" title="Meet">📹</span>
            </div>
        </div>

        <div class="class-card" style="--card-color: #e52592">
            <div class="card-header">
                <div class="card-title">Digital Image Processing</div>
                <div class="card-section">BSCS-6A · Section A</div>
                <div class="card-teacher">Dr. Sana</div>
            </div>
            <div class="card-body"></div>
            <div class="card-footer">
                <span class="card-icon">📋</span>
                <span class="card-icon">📹</span>
            </div>
        </div>

        <div class="class-card" style="--card-color: #137333">
            <div class="card-header">
                <div class="card-title">Theory of Automata</div>
                <div class="card-section">BSCS-6A · Section A</div>
                <div class="card-teacher">Mr. Ali</div>
            </div>
            <div class="card-body"></div>
            <div class="card-footer">
                <span class="card-icon">📋</span>
                <span class="card-icon">📹</span>
            </div>
        </div>
    </div>
</main>

<style>
.main-content {
    margin-left: 256px;
    margin-top: 64px;
    padding: 24px;
    background: #f0f4f9;
    min-height: calc(100vh - 64px);
}
.dashboard-header {
    margin-bottom: 24px;
}
.dashboard-header h2 {
    font-size: 22px;
    font-family: 'Google Sans', sans-serif;
    color: #202124;
    font-weight: 400;
}
.classes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
}
.class-card {
    background: white;
    border-radius: 8px;
    border: 1px solid #dadce0;
    overflow: hidden;
    cursor: pointer;
    transition: box-shadow 0.2s;
}
.class-card:hover {
    box-shadow: 0 2px 8px rgba(60,64,67,0.2);
}
.card-header {
    background: var(--card-color);
    padding: 20px 16px;
    color: white;
    height: 100px;
    position: relative;
}
.card-title {
    font-size: 18px;
    font-family: 'Google Sans', sans-serif;
    font-weight: 500;
    margin-bottom: 4px;
}
.card-section {
    font-size: 13px;
    opacity: 0.9;
}
.card-teacher {
    font-size: 13px;
    opacity: 0.85;
    position: absolute;
    bottom: 16px;
}
.card-body {
    height: 60px;
    background: white;
}
.card-footer {
    padding: 8px 16px;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    border-top: 1px solid #dadce0;
}
.card-icon {
    font-size: 18px;
    padding: 6px;
    border-radius: 50%;
    cursor: pointer;
}
.card-icon:hover { background: #f1f3f4; }
</style>

</body>
</html>