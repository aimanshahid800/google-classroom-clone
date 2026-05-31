<?php
$current = basename($_SERVER['PHP_SELF']);
$icon_path = 'streamline-core-flat---free--14x14-SVG';
?>
<aside class="sidebar" id="sidebar">

    <!-- Home -->
    <a href="index.php" class="sidebar-item <?= $current === 'index.php' ? 'active' : '' ?>">
        <span class="sidebar-icon">
            <img src="<?= $icon_path ?>/Home-4--Streamline-Core.svg" alt="Home" width="20" height="20">
        </span>
        <span class="sidebar-label">Home</span>
    </a>

    <!-- Calendar -->
    <a href="calendar.php" class="sidebar-item <?= $current === 'calendar.php' ? 'active' : '' ?>">
        <span class="sidebar-icon">
            <img src="<?= $icon_path ?>/Blank-Calendar--Streamline-Core.svg" alt="Calendar" width="20" height="20">
        </span>
        <span class="sidebar-label">Calendar</span>
    </a>

    <!-- Enrolled heading + To do -->
    <div class="sidebar-section-head">
        <span class="sidebar-label">Enrolled</span>
        <button class="enrolled-toggle" onclick="toggleEnrolled()" id="enrolledToggle" aria-label="Toggle enrolled">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="#454746">
                <path d="M7 10l5 5 5-5z"/>
            </svg>
        </button>
    </div>

    <div id="enrolledList">
        <!-- To do -->
        <a href="todo.php" class="sidebar-item <?= $current === 'todo.php' ? 'active' : '' ?>">
            <span class="sidebar-icon">
                <img src="<?= $icon_path ?>/Task-List--Streamline-Core.svg" alt="To do" width="20" height="20">
            </span>
            <span class="sidebar-label">To do</span>
        </a>

        <!-- BSCS 6 -->
        <a href="class.php?id=1" class="sidebar-item sidebar-class">
            <span class="sidebar-icon">
                <span class="class-letter" style="background:#1a73e8">B</span>
            </span>
            <span class="sidebar-label">
                <span class="class-name">BSCS 6</span>
                <span class="class-sub">(23-27) web</span>
            </span>
        </a>

        <!-- Bscs morning -->
        <a href="class.php?id=2" class="sidebar-item sidebar-class">
            <span class="sidebar-icon">
                <span class="class-letter" style="background:#1a73e8">B</span>
            </span>
            <span class="sidebar-label">
                <span class="class-name">Bscs morning</span>
                <span class="class-sub">Semester 6</span>
            </span>
        </a>

        <!-- PIAIC Batch-57 -->
        <a href="class.php?id=3" class="sidebar-item sidebar-class">
            <span class="sidebar-icon">
                <span class="class-letter" style="background:#454746">P</span>
            </span>
            <span class="sidebar-label">
                <span class="class-name">PIAIC Batch-57</span>
                <span class="class-sub">Q6</span>
            </span>
        </a>
    </div>

    <div class="sidebar-divider"></div>

    <!-- Archived -->
    <a href="archived.php" class="sidebar-item">
        <span class="sidebar-icon">
            <img src="<?= $icon_path ?>/Shipment-Download--Streamline-Core.svg" alt="Archived" width="20" height="20">
        </span>
        <span class="sidebar-label">Archived classes</span>
    </a>

    <!-- Settings -->
    <a href="settings/index.php" class="sidebar-item <?= strpos($_SERVER['PHP_SELF'], 'settings') !== false ? 'active' : '' ?>">
        <span class="sidebar-icon">
            <img src="<?= $icon_path ?>/Cog--Streamline-Core.svg" alt="Settings" width="20" height="20">
        </span>
        <span class="sidebar-label">Settings</span>
    </a>

</aside>