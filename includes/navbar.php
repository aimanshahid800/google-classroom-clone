<?php
$page_title = $page_title ?? 'Google Classroom';
$icon_path = 'streamline-core-flat---free--14x14-SVG';
?>
<nav class="navbar">
    <div class="nav-left">
        <button class="hamburger" onclick="toggleSidebar()" aria-label="Menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="#454746">
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
            </svg>
        </button>
        <a href="index.php" class="brand">
            <img src="<?= $icon_path ?>/Graduation-Cap--Streamline-Core.svg" alt="Classroom" class="nav-logo" width="24" height="24">
            <span class="nav-title">Classroom</span>
        </a>
    </div>
    <div class="nav-right">
        <!-- + Add button -->
        <button class="nav-icon-btn" title="Add or join class">
            <img src="<?= $icon_path ?>/Add-1--Streamline-Core.svg" alt="Add" width="24" height="24">
        </button>
        <!-- Grid apps -->
        <button class="nav-icon-btn" title="Google apps">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="#454746">
                <path d="M6 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zM6 14c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zM6 20c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
            </svg>
        </button>
        <!-- Avatar -->
        <button class="avatar-btn" title="Account">
            <img src="<?= $icon_path ?>/User-Circle-Single--Streamline-Core.svg" alt="Account" width="32" height="32" class="nav-avatar-img">
        </button>
    </div>

    <!-- Loading bar -->
    <div class="loading-bar" id="loadingBar">
        <div class="loading-progress" id="loadingProgress"></div>
    </div>
</nav>