<?php
/**
 * layout.php — Unified Navbar + Sidebar
 * Include this ONCE after <body> in every page.
 * Pages should set $page_title and $page_breadcrumb before including.
 */
$current_page  = basename($_SERVER['PHP_SELF']);
$icons         = 'streamline-core-flat---free--14x14-SVG';
$user_name     = $_SESSION['user_name'] ?? 'User';
$user_initial  = strtoupper(substr($user_name, 0, 1));
$page_title    = $page_title ?? 'Google Classroom';
$breadcrumb    = $page_breadcrumb ?? '';
?>

<!-- ============================================================
     LAYOUT CSS — Navbar + Sidebar unified styles
     ============================================================ -->
<style>
/* ── CSS Variables ── */
body {
    margin: 0;
    background: var(--layout-bg);
}

:root {
    --nav-height: 64px;
    --sb-width: 256px;
    --sb-collapsed: 72px;
    --layout-bg: #f8fafd;
    --active-bg: #d3e3fd;
    --active-color: #1a73e8;
    --text-primary: #1f1f1f;
    --text-secondary: #444746;
    --icon-color: #444746;
    --border-color: #e0e0e0;
    --hover-bg: #e8eaed;
    --speed: 250ms;
    --ease: cubic-bezier(0.4, 0, 0.2, 1);
    --font: 'Google Sans', 'Roboto', Arial, sans-serif;
}

/* ─────────────────────────
   NAVBAR
   ───────────────────────── */
.gc-navbar {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: var(--nav-height);
    background: var(--layout-bg);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 8px;
    z-index: 200;
}

.gc-nav-left {
    display: flex;
    align-items: center;
    gap: 4px;
}

.gc-nav-right {
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Hamburger button */
.gc-hamburger {
    width: 48px; height: 48px;
    background: transparent;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 200ms;
    flex-shrink: 0;
}
.gc-hamburger:hover { background: var(--hover-bg); }

/* Brand / Logo */
.gc-brand {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    padding: 0 8px;
}
.gc-logo { height: 32px; width: 32px; flex-shrink: 0; }
.gc-brand-text {
    font-family: var(--font);
    font-size: 22px;
    color: var(--text-secondary);
    font-weight: 500;
    white-space: nowrap;
    position: relative;
    display: inline-block;
    transition: color 0.3s ease;
}
.gc-brand:hover .gc-brand-text {
    color: var(--active-color);
}
.gc-brand-text::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -2px;
    left: 0;
    background-color: var(--active-color);
    transition: width 0.3s ease;
}
.gc-brand:hover .gc-brand-text::after {
    width: 100%;
}

/* Breadcrumb (e.g. " > Settings") */
.gc-breadcrumb-sep {
    color: var(--text-secondary);
    font-size: 18px;
    margin: 0 4px;
    font-weight: 300;
}
.gc-breadcrumb-page {
    font-family: var(--font);
    font-size: 18px;
    color: var(--text-secondary);
    font-weight: 400;
}

/* Nav icon buttons (+ , grid) */
.gc-nav-btn {
    width: 40px; height: 40px;
    background: transparent;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 200ms;
}
.gc-nav-btn:hover { background: var(--hover-bg); }

/* Avatar */
.gc-avatar-btn {
    width: 40px; height: 40px;
    background: transparent;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    margin-left: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: box-shadow 200ms;
}
.gc-avatar-btn:hover { box-shadow: 0 0 0 2px var(--border-color); }

.gc-avatar {
    width: 32px; height: 32px;
    background: var(--active-color);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 500;
    font-family: var(--font);
}

/* ─────────────────────────
   SIDEBAR
   ───────────────────────── */
.gc-sidebar {
    position: fixed;
    top: var(--nav-height);
    left: 0; bottom: 0;
    width: var(--sb-width);
    background: var(--layout-bg);
    overflow-x: hidden;
    overflow-y: auto;
    z-index: 100;
    padding: 8px 0;
    transition: width var(--speed) var(--ease);
}

/* Hide scrollbar */
.gc-sidebar::-webkit-scrollbar { width: 0; }
.gc-sidebar { scrollbar-width: none; }

/* ── Sidebar Items ── */
.gc-sb-item, .gc-enrolled-hdr {
    display: flex;
    align-items: center;
    gap: 20px;
    height: 48px;
    padding: 0 12px 0 24px;
    margin: 4px 12px;
    border-radius: 24px;
    border: none;
    background: transparent;
    text-decoration: none;
    color: var(--text-primary);
    font-family: var(--font);
    font-size: 15px;
    font-weight: 500;
    white-space: nowrap;
    cursor: pointer;
    transition: background 150ms;
    text-align: left;
    box-sizing: border-box;
    width: auto;
}
.gc-sb-item:hover, .gc-enrolled-hdr:hover { background: var(--hover-bg); }

.gc-sb-item.active {
    background: var(--active-bg);
    color: var(--active-color);
}

/* Icon container */
.gc-sb-icon {
    min-width: 24px; width: 24px; height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.gc-sb-icon img { width: 22px; height: 22px; }

/* Label */
.gc-sb-label {
    opacity: 1;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 0;
    transition: opacity var(--speed) var(--ease);
}

.gc-enrolled-arrow {
    margin-left: auto;
    transition: transform var(--speed) var(--ease);
    flex-shrink: 0;
    display: flex;
    align-items: center;
}
.gc-enrolled-arrow.rotated { transform: rotate(180deg); }

.gc-enrolled-list {
    max-height: 500px;
    overflow: hidden;
    transition: max-height 300ms var(--ease);
}
.gc-enrolled-list.hidden { max-height: 0; }

/* Class letter circles */
.gc-class-letter {
    width: 24px; height: 24px;
    border-radius: 50%;
    color: #fff;
    font-size: 12px; font-weight: 600;
    font-family: var(--font);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.gc-class-name { font-size: 13px; font-weight: 500; color: var(--text-primary); line-height: 1.3; }
.gc-class-sub { font-size: 11px; color: var(--text-secondary); font-weight: 400; line-height: 1.2; }

.gc-sb-divider {
    height: 1px;
    background: var(--border-color);
    margin: 8px 12px;
}

/* ─────────────────────────
   COLLAPSED STATE
   ───────────────────────── */
body.sb-collapsed .gc-sidebar {
    width: var(--sb-collapsed);
}

/* Hide text labels, arrows, class info */
body.sb-collapsed .gc-sb-label,
body.sb-collapsed .gc-enrolled-arrow,
body.sb-collapsed .gc-class-sub {
    opacity: 0;
    pointer-events: none;
    display: none;
}

/* Shrink items to icon-only pills */
body.sb-collapsed .gc-sb-item,
body.sb-collapsed .gc-enrolled-hdr {
    justify-content: center;
    padding: 0;
    width: 48px;
    height: 48px;
    margin: 4px auto;
    border-radius: 24px;
    gap: 0;
}

/* Hide class items when collapsed */
body.sb-collapsed .gc-class-item {
    display: none;
}

body.sb-collapsed .gc-sb-divider {
    margin: 4px 14px;
}

/* ─────────────────────────
   MAIN CONTENT AREA
   (use class "gc-main" on <main> in every page)
   ───────────────────────── */
.gc-main {
    margin-left: var(--sb-width);
    margin-top: var(--nav-height);
    min-height: calc(100vh - var(--nav-height));
    padding: 24px;
    background: #ffffff; /* Make white to contrast with f8fafd bg */
    border-top-left-radius: 16px;
    transition: margin-left var(--speed) var(--ease);
}

body.sb-collapsed .gc-main {
    margin-left: var(--sb-collapsed);
}
</style>

<!-- ============================================================
     NAVBAR HTML
     ============================================================ -->
<nav class="gc-navbar">
    <div class="gc-nav-left">
        <!-- Hamburger -->
        <button class="gc-hamburger" onclick="toggleSidebar()" aria-label="Menu">
            <img src="<?= $icons ?>/Hamburger-Menu-1--Streamline-Core.png" alt="Menu" width="20" height="20">
        </button>

        <!-- Logo + Brand -->
        <a href="index.php" class="gc-brand">
            <img src="<?= $icons ?>/logo.png" alt="Classroom Logo" class="gc-logo" style="width: 32px; height: 32px; object-fit: contain;">
            <span class="gc-brand-text">Classroom</span>
        </a>

        <!-- Breadcrumb (if set) -->
        <?php if ($breadcrumb): ?>
        <span class="gc-breadcrumb-sep">›</span>
        <span class="gc-breadcrumb-page"><?= htmlspecialchars($breadcrumb) ?></span>
        <?php endif; ?>
    </div>

    <div class="gc-nav-right">
        <!-- + Create / Join -->
        <button class="gc-nav-btn" title="Create or join class">
            <img src="<?= $icons ?>/Add-1--Streamline-Core.svg" alt="Add" width="20" height="20">
        </button>

        <!-- Google Apps Grid -->
        <button class="gc-nav-btn" title="Google Apps">
            <img src="<?= $icons ?>/Page-Setting--Streamline-Core.svg" alt="Apps" width="20" height="20">
        </button>

        <!-- Avatar -->
        <button class="gc-avatar-btn" title="Google Account" style="width: 48px; height: 48px; border-radius: 50%;">
            <div class="gc-avatar" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; flex-shrink: 0; font-size: 18px; border-radius: 50%;"><?= $user_initial ?></div>
        </button>
    </div>
</nav>

<!-- ============================================================
     SIDEBAR HTML
     ============================================================ -->
<aside class="gc-sidebar" id="gcSidebar">

    <!-- Home -->
    <a href="index.php" class="gc-sb-item <?= $current_page === 'index.php' ? 'active' : '' ?>">
        <span class="gc-sb-icon">
            <img src="<?= $icons ?>/Home-4--Streamline-Core.svg" alt="Home" width="20" height="20">
        </span>
        <span class="gc-sb-label">Home</span>
    </a>

    <!-- Calendar -->
    <a href="calendar.php" class="gc-sb-item <?= $current_page === 'calendar.php' ? 'active' : '' ?>">
        <span class="gc-sb-icon">
            <img src="<?= $icons ?>/Blank-Calendar--Streamline-Core.svg" alt="Calendar" width="20" height="20">
        </span>
        <span class="gc-sb-label">Calendar</span>
    </a>

    <!-- Enrolled Section Header -->
    <button class="gc-enrolled-hdr" onclick="toggleEnrolled()">
        <span class="gc-sb-icon">
            <img src="<?= $icons ?>/Graduation-Cap--Streamline-Core.svg" alt="Enrolled" width="20" height="20">
        </span>
        <span class="gc-sb-label">Enrolled</span>
        <span class="gc-enrolled-arrow" id="enrolledArrow">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="#5f6368">
                <path d="M7 14l5-5 5 5z"/>
            </svg>
        </span>
    </button>

    <!-- Enrolled List -->
    <div class="gc-enrolled-list" id="enrolledList">

        <!-- To do -->
        <a href="todo.php" class="gc-sb-item <?= $current_page === 'todo.php' ? 'active' : '' ?>">
            <span class="gc-sb-icon">
                <img src="<?= $icons ?>/Task-List--Streamline-Core.svg" alt="To do" width="20" height="20">
            </span>
            <span class="gc-sb-label">To do</span>
        </a>

        <!-- BSCS 6 -->
        <a href="class.php?id=1" class="gc-sb-item gc-class-item">
            <span class="gc-sb-icon">
                <span class="gc-class-letter" style="background:#1a73e8">B</span>
            </span>
            <span class="gc-sb-label">
                <span class="gc-class-name">BSCS 6</span>
                <span class="gc-class-sub">(23-27) web</span>
            </span>
        </a>

        <!-- Bscs morning -->
        <a href="class.php?id=2" class="gc-sb-item gc-class-item">
            <span class="gc-sb-icon">
                <span class="gc-class-letter" style="background:#1a73e8">B</span>
            </span>
            <span class="gc-sb-label">
                <span class="gc-class-name">Bscs morning</span>
                <span class="gc-class-sub">Semester 6</span>
            </span>
        </a>

        <!-- PIAIC Batch-57 -->
        <a href="class.php?id=3" class="gc-sb-item gc-class-item">
            <span class="gc-sb-icon">
                <span class="gc-class-letter" style="background:#454746">P</span>
            </span>
            <span class="gc-sb-label">
                <span class="gc-class-name">PIAIC Batch-57</span>
                <span class="gc-class-sub">Q6</span>
            </span>
        </a>

    </div>

    <div class="gc-sb-divider"></div>

    <!-- Archived classes -->
    <a href="archived.php" class="gc-sb-item <?= $current_page === 'archived.php' ? 'active' : '' ?>">
        <span class="gc-sb-icon">
            <img src="<?= $icons ?>/Shipment-Download--Streamline-Core.svg" alt="Archived" width="20" height="20">
        </span>
        <span class="gc-sb-label">Archived classes</span>
    </a>

    <!-- Settings -->
    <a href="settings.php" class="gc-sb-item <?= ($current_page === 'settings.php' || strpos($_SERVER['PHP_SELF'], 'settings') !== false) ? 'active' : '' ?>">
        <span class="gc-sb-icon">
            <img src="<?= $icons ?>/Cog--Streamline-Core.svg" alt="Settings" width="20" height="20">
        </span>
        <span class="gc-sb-label">Settings</span>
    </a>

</aside>

<!-- ============================================================
     JAVASCRIPT — Toggle Sidebar + Enrolled
     ============================================================ -->
<script>
// Toggle sidebar expanded / collapsed
function toggleSidebar() {
    document.body.classList.toggle('sb-collapsed');
    // Remember user preference
    const collapsed = document.body.classList.contains('sb-collapsed');
    localStorage.setItem('gc-sidebar-collapsed', collapsed);
}

// Toggle enrolled class list open / closed
function toggleEnrolled() {
    // Don't toggle if sidebar is collapsed
    if (document.body.classList.contains('sb-collapsed')) return;

    const list  = document.getElementById('enrolledList');
    const arrow = document.getElementById('enrolledArrow');
    list.classList.toggle('hidden');
    arrow.classList.toggle('rotated');
}

// Restore sidebar state on page load
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('gc-sidebar-collapsed') === 'true') {
        document.body.classList.add('sb-collapsed');
    }
});
</script>
