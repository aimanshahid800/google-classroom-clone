<?php
$page_title = $page_title ?? 'Google Classroom';
?>
<nav class="navbar">
    <div class="nav-left">
        <button class="hamburger" onclick="toggleSidebar()">&#9776;</button>
        <img src="assets/classroom-logo.png" alt="Classroom" class="nav-logo">
        <span class="nav-title"><?= htmlspecialchars($page_title) ?></span>
    </div>
    <div class="nav-right">
        <button class="nav-icon-btn" title="Apps">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="#5f6368">
                <path d="M6 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zM6 14c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
            </svg>
        </button>
        <div class="nav-avatar">
            <?= isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'A' ?>
        </div>
    </div>
</nav>

<style>
.navbar {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 64px;
    background: #ffffff;
    border-bottom: 1px solid #dadce0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 16px;
    z-index: 100;
    box-shadow: 0 1px 3px rgba(60,64,67,0.15);
}
.nav-left { display: flex; align-items: center; gap: 12px; }
.hamburger { background: none; border: none; font-size: 20px; cursor: pointer; color: #5f6368; padding: 8px; border-radius: 50%; }
.hamburger:hover { background: #f1f3f4; }
.nav-logo { height: 30px; }
.nav-title { font-size: 22px; color: #5f6368; font-family: 'Google Sans', sans-serif; }
.nav-right { display: flex; align-items: center; gap: 8px; }
.nav-icon-btn { background: none; border: none; padding: 8px; border-radius: 50%; cursor: pointer; }
.nav-icon-btn:hover { background: #f1f3f4; }
.nav-avatar {
    width: 36px; height: 36px;
    background: #1a73e8;
    color: white;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 500;
    cursor: pointer;
}
</style>