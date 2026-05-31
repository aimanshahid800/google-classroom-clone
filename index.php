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

<?php include 'includes/layout.php'; ?>

<main class="gc-main">
    <div class="classes-grid">
        <!-- Card 1: BSCS 6 -->
        <div class="class-card" style="--card-color: #37474f;">
            <div class="card-header">
                <div class="card-title-group">
                    <div class="card-title">BSCS 6</div>
                    <div class="card-section">(23-27) web</div>
                    <div class="card-teacher">Anila Majeed</div>
                </div>
            </div>
            <!-- Avatar overlapping -->
            <div class="card-avatar" style="background-color: #ff9800;">A</div>
            
            <div class="card-body"></div>
            
            <div class="card-footer">
                <span class="card-icon" title="Open your work">
                    <img src="<?= $icons ?>/Ai-Generate-Portrait-Image-Spark--Streamline-Core.svg" alt="Work">
                </span>
                <span class="card-icon" title="Open folder">
                    <img src="<?= $icons ?>/New-Folder--Streamline-Core.svg" alt="Folder">
                </span>
                <span class="card-icon" title="More options">
                    <img src="<?= $icons ?>/Vertical-Menu--Streamline-Plump.png" alt="Options">
                </span>
            </div>
        </div>

        <!-- Card 2: Bscs morning -->
        <div class="class-card" style="--card-color: #1a73e8;">
            <div class="card-header">
                <div class="card-title-group">
                    <div class="card-title">Bscs morning</div>
                    <div class="card-section">Semester 6</div>
                    <div class="card-teacher">hafsa hafeez</div>
                </div>
            </div>
            <div class="card-avatar" style="background-color: #e91e63;">h</div>
            
            <div class="card-body"></div>
            
            <div class="card-footer">
                <span class="card-icon" title="Open your work">
                    <img src="<?= $icons ?>/Ai-Generate-Portrait-Image-Spark--Streamline-Core.svg" alt="Work">
                </span>
                <span class="card-icon" title="Open folder">
                    <img src="<?= $icons ?>/New-Folder--Streamline-Core.svg" alt="Folder">
                </span>
                <span class="card-icon" title="More options">
                    <img src="<?= $icons ?>/Vertical-Menu--Streamline-Plump.png" alt="Options">
                </span>
            </div>
        </div>

        <!-- Card 3: PIAIC Batch-57 -->
        <div class="class-card" style="--card-color: #37474f;">
            <div class="card-header">
                <div class="card-title-group">
                    <div class="card-title">PIAIC Batch-57</div>
                    <div class="card-section">Q6</div>
                    <div class="card-teacher">Humera Aslam</div>
                </div>
            </div>
            <div class="card-avatar" style="background-color: #4caf50;">H</div>
            
            <div class="card-body"></div>
            
            <div class="card-footer">
                <span class="card-icon" title="Open your work">
                    <img src="<?= $icons ?>/Ai-Generate-Portrait-Image-Spark--Streamline-Core.svg" alt="Work">
                </span>
                <span class="card-icon" title="Open folder">
                    <img src="<?= $icons ?>/New-Folder--Streamline-Core.svg" alt="Folder">
                </span>
                <span class="card-icon" title="More options">
                    <img src="<?= $icons ?>/Vertical-Menu--Streamline-Plump.png" alt="Options">
                </span>
            </div>
        </div>
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