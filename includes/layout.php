<?php
/**
 * layout.php — Unified Navbar + Sidebar
 * Include this ONCE after <body> in every page.
 * Pages should set $page_title and $page_breadcrumb before including.
 */
if (!isset($_SESSION['user_id'])) return;
$current_page  = basename($_SERVER['PHP_SELF']);
$icons         = 'streamline-core-flat---free--14x14-SVG';
$user_name     = $_SESSION['user_name'] ?? 'User';
$user_email    = $_SESSION['user_email'] ?? '';
$user_role     = $_SESSION['user_role'] ?? 'student';
$user_initial  = strtoupper(substr($user_name, 0, 1));
$page_title    = $page_title ?? 'Google Classroom';
$breadcrumb    = $page_breadcrumb ?? '';
$uid           = $_SESSION['user_id'];
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
    --layout-bg: #e9eef6;
    --modal-bg: #ffffff;
    --input-bg: #dde3ea;
    --active-bg: #d3e3fd;
    --active-color: #1a73e8;
    --text-primary: #1f1f1f;
    --text-secondary: #444746;
    --icon-color: #444746;
    --border-color: #dadce0;
    --hover-bg: #e8eaed;
    --speed: 250ms;
    --ease: cubic-bezier(0.4, 0, 0.2, 1);
    --font: 'Google Sans', 'Roboto', Arial, sans-serif;
    --pill-radius: 24px;
}

body {
    margin: 0;
    background: var(--layout-bg);
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

/* Breadcrumb (e.g. " > Class Name") */
.gc-breadcrumb-sep {
    color: var(--text-secondary);
    font-size: 14px;
    margin: 0 12px;
    font-weight: 500;
}
.gc-breadcrumb-wrapper {
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.gc-breadcrumb-page {
    font-family: var(--font);
    font-size: 16px;
    color: #202124;
    font-weight: 500;
    line-height: 1.2;
}
.gc-breadcrumb-sub {
    font-family: var(--font);
    font-size: 12px;
    color: #5f6368;
    font-weight: 400;
    line-height: 1.2;
}

/* Center Tabs (Stream, Classwork, People) */
.gc-nav-center {
    display: flex;
    align-items: center;
    gap: 16px;
    height: 100%;
}
.gc-tab {
    display: flex;
    align-items: center;
    height: 100%;
    padding: 0 16px;
    color: var(--text-secondary);
    text-decoration: none;
    font-family: var(--font);
    font-size: 14px;
    font-weight: 500;
    position: relative;
    transition: color 0.2s;
}
.gc-tab:hover {
    color: var(--text-primary);
    background: rgba(0,0,0,0.04);
}
.gc-tab.active {
    color: var(--active-color);
}
.gc-tab.active::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background-color: var(--active-color);
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
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

/* Dropdown Menu */
.gc-dropdown-wrapper { position: relative; }
.gc-dropdown-menu {
    position: absolute;
    top: 48px; right: 0;
    background: #fff;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1), 0 4px 8px rgba(0,0,0,0.1);
    padding: 8px 0;
    min-width: 180px;
    display: none;
    z-index: 1000;
}
.gc-dropdown-menu.show { display: block; }
.gc-dropdown-item {
    display: block;
    width: 100%;
    text-align: left;
    padding: 12px 24px;
    background: none;
    border: none;
    font-size: 14px;
    font-family: 'Google Sans', 'Roboto', sans-serif;
    color: #3c4043;
    cursor: pointer;
    line-height: 20px;
}
.gc-dropdown-item:hover { background: #f1f3f4; }

/* ── Modals ── */
.gc-modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.32);
    z-index: 2000;
    display: none;
}
.gc-modal-overlay.show { display: block; }

.gc-modal {
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    background: var(--modal-bg);
    border-radius: 24px;
    width: 560px;
    max-width: 90vw;
    max-height: 90vh;
    overflow-y: auto;
    z-index: 2001;
    display: none;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 8px 24px rgba(0,0,0,0.24);
}
.gc-modal.show { display: block; }

.gc-modal-header {
    padding: 24px 24px 20px;
}
.gc-modal-header h2 {
    margin: 0;
    font-family: 'Google Sans', sans-serif;
    font-size: 22px;
    font-weight: 400;
    color: #202124;
    line-height: 28px;
}
/* Join class title is blue like original */
#joinModal .gc-modal-header h2 {
    color: #1a73e8;
}

.gc-modal-body { padding: 0 24px 8px; }
.gc-modal-footer {
    padding: 16px 24px;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    border-top: none;
}

.gc-btn-text {
    background: transparent;
    border: none;
    color: #1a73e8;
    font-size: 14px;
    font-weight: 500;
    font-family: 'Google Sans', sans-serif;
    padding: 10px 24px;
    border-radius: var(--pill-radius);
    cursor: pointer;
    letter-spacing: 0.25px;
}
.gc-btn-text:hover { background: rgba(26,115,232,0.04); }
.gc-btn-text:disabled { color: rgba(0,0,0,0.26); cursor: default; background: transparent; }

/* ── Create Class Inputs (bottom-border style like original) ── */
.gc-create-input {
    position: relative;
    margin-bottom: 16px;
    padding: 8px 16px;
    background: var(--input-bg);
    border-radius: 8px 8px 0 0;
    border-bottom: 2px solid #5f6368;
    height: 56px;
}
.gc-create-input:focus-within {
    border-bottom: 3px solid var(--active-color);
}
.gc-create-input input {
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    font-size: 16px;
    font-family: 'Roboto', sans-serif;
    color: #202124;
    padding: 16px 0 0 0;
    line-height: 24px;
}
.gc-create-input label {
    position: absolute;
    top: 16px; left: 16px;
    font-size: 16px;
    font-family: 'Roboto', sans-serif;
    color: #5f6368;
    pointer-events: none;
    transition: all 0.15s ease;
}
.gc-create-input label {
    position: absolute;
    top: 24px; left: 0;
    font-size: 16px;
    font-family: 'Roboto', sans-serif;
    color: #5f6368;
    pointer-events: none;
    transition: all 0.15s ease;
}
.gc-create-input input:focus ~ label,
.gc-create-input input:not(:placeholder-shown) ~ label {
    top: 4px;
    font-size: 12px;
    color: #1a73e8;
}
.gc-create-input input:not(:focus):not(:placeholder-shown) ~ label {
    color: #5f6368;
}
/* First input special: "Class name*" red star */
.gc-create-input .req-star { color: #d93025; }
.gc-required-text {
    font-size: 12px;
    color: #5f6368;
    margin: 4px 0 16px;
    font-family: 'Roboto', sans-serif;
}

/* ── Join Class Styles ── */
.gc-join-section {
    margin-bottom: 24px;
}
.gc-join-user-card {
    background: var(--input-bg);
    border: none;
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
}
.gc-join-user-card .gc-join-signed {
    font-size: 14px;
    color: #5f6368;
    margin: 0 0 16px;
    font-family: 'Roboto', sans-serif;
}
.gc-join-user-row {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
}
.gc-join-avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: #1a73e8;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 500;
    font-family: 'Google Sans', sans-serif;
    flex-shrink: 0;
}
.gc-join-user-info {
    display: flex;
    flex-direction: column;
}
.gc-join-user-name {
    font-size: 14px;
    font-weight: 500;
    color: #202124;
    font-family: 'Google Sans', sans-serif;
}
.gc-join-user-email {
    font-size: 12px;
    color: #5f6368;
    font-family: 'Roboto', sans-serif;
}
.gc-btn-switch {
    display: inline-block;
    padding: 10px 24px;
    border: 1px solid #747775;
    border-radius: 20px;
    color: #1a73e8;
    font-size: 14px;
    font-weight: 500;
    font-family: 'Google Sans', sans-serif;
    background: #e9eef6;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s;
}
.gc-btn-switch:hover { background: #dfe3e7; }

/* Code section */
.gc-join-code-card {
    background: var(--input-bg);
    border: none;
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
}
.gc-join-code-card .gc-code-title {
    font-size: 14px; font-weight: 700;
    color: #202124; margin: 0 0 4px;
    font-family: 'Roboto', sans-serif;
}
.gc-join-code-card .gc-code-desc {
    font-size: 14px; color: #5f6368;
    margin: 0 0 20px;
    font-family: 'Roboto', sans-serif;
}

/* Outlined input for Join (box style) */
.gc-outlined-input {
    position: relative;
    margin-top: 10px;
}
.gc-outlined-input input {
    width: 100%;
    padding: 16px;
    border: 2px solid #1a73e8;
    border-radius: 8px;
    outline: none;
    font-size: 16px;
    font-family: 'Roboto', sans-serif;
    color: #202124;
    background: transparent;
}
.gc-outlined-input label {
    position: absolute;
    top: -10px; left: 10px;
    font-size: 12px;
    color: #1a73e8;
    font-family: 'Roboto', sans-serif;
    background: #fff;
    padding: 0 4px;
}

/* Join instructions */
.gc-join-instructions {
    margin-top: 16px;
}
.gc-join-instructions p {
    font-size: 14px; font-weight: 500;
    color: #202124; margin: 0 0 8px;
    font-family: 'Roboto', sans-serif;
}
.gc-join-instructions ul {
    margin: 0; padding-left: 24px;
}
.gc-join-instructions li {
    font-size: 13px; color: #5f6368;
    margin-bottom: 4px; line-height: 20px;
    font-family: 'Roboto', sans-serif;
}
.gc-join-instructions a {
    color: #1a73e8; text-decoration: none;
    font-size: 13px;
}
.gc-join-instructions a:hover { text-decoration: underline; }

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
        <span class="gc-breadcrumb-sep">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9.29 15.88L13.17 12 9.29 8.12a.996.996 0 1 1 1.41-1.41l4.59 4.59c.39.39.39 1.02 0 1.41l-4.59 4.59a.996.996 0 0 1-1.41 0c-.38-.39-.39-1.03 0-1.42z"/>
            </svg>
        </span>
        <div class="gc-breadcrumb-wrapper">
            <span class="gc-breadcrumb-page"><?= htmlspecialchars($breadcrumb) ?></span>
            <?php if (isset($breadcrumb_sub) && $breadcrumb_sub): ?>
            <span class="gc-breadcrumb-sub"><?= htmlspecialchars($breadcrumb_sub) ?></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="gc-nav-right">
        <!-- + Create / Join Dropdown -->
        <div class="gc-dropdown-wrapper">
            <button class="gc-nav-btn" title="Create or join class" onclick="toggleAddMenu()">
                <img src="<?= $icons ?>/Add-1--Streamline-Core.svg" alt="Add" width="20" height="20">
            </button>
            <div class="gc-dropdown-menu" id="addMenu">
                <button class="gc-dropdown-item" onclick="openJoinModal()">Join class</button>
                <button class="gc-dropdown-item" onclick="openCreateModal()">Create class</button>
            </div>
        </div>

        <!-- Google Apps Grid -->
        <button class="gc-nav-btn" title="Google Apps">
            <img src="<?= $icons ?>/Page-Setting--Streamline-Core.svg" alt="Apps" width="20" height="20">
        </button>

        <!-- Avatar with dropdown -->
        <div class="gc-dropdown-wrapper">
            <button class="gc-avatar-btn" title="Google Account" onclick="toggleAvatarMenu()" style="width: 48px; height: 48px; border-radius: 50%;">
                <div class="gc-avatar" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; flex-shrink: 0; font-size: 18px; border-radius: 50%;"><?= $user_initial ?></div>
            </button>
            <div class="gc-dropdown-menu" id="avatarMenu" style="right:0; left:auto; border-radius:16px; padding:16px; width:320px; text-align:center;">
                <div style="font-size:12px; margin-bottom:8px; color:#5f6368;"><?= htmlspecialchars($user_email) ?></div>
                <div class="gc-avatar" style="margin:0 auto 8px; width:64px; height:64px; min-width:64px; min-height:64px; font-size:24px; border-radius:50%;"><?= $user_initial ?></div>
                <div style="font-size:18px; font-weight:500; margin-bottom:16px; color:#202124;">Hi, <?= htmlspecialchars($user_name) ?>!</div>
                <button class="gc-btn-switch" style="width:100%; margin-bottom:16px; border:1px solid #dadce0;">Manage your Google Account</button>
                <div style="display:flex; gap:8px; border-top:1px solid #e0e0e0; padding-top:16px;">
                    <button class="gc-btn-switch" style="flex:1; border:1px solid #dadce0;">Add account</button>
                    <a href="<?= BASE_URL ?>/logout.php" class="gc-btn-switch" style="flex:1; border:1px solid #dadce0; text-decoration:none; display:flex; align-items:center; justify-content:center;">Sign out</a>
                </div>
                <div style="font-size:11px; color:#5f6368; margin-top:16px;">Privacy policy • Terms of Service</div>
            </div>
        </div>
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

        <?php 
        // Fetch enrolled classes for sidebar
        $sb_sql = "SELECT c.* FROM classes c JOIN enrollments e ON c.id = e.class_id WHERE e.student_id = $uid AND c.is_archived = 0";
        $sb_res = mysqli_query($conn, $sb_sql);
        $sb_classes = $sb_res ? mysqli_fetch_all($sb_res, MYSQLI_ASSOC) : [];
        $sb_colors = ['#1a73e8', '#e91e63', '#37474f', '#4caf50', '#ff9800', '#9c27b0'];
        
        foreach ($sb_classes as $i => $sb_class): 
            $sb_color = $sb_colors[$i % count($sb_colors)];
            $sb_initial = strtoupper(substr($sb_class['name'], 0, 1));
        ?>
        <a href="classes/stream.php?id=<?= $sb_class['id'] ?>" class="gc-sb-item gc-class-item">
            <span class="gc-sb-icon">
                <span class="gc-class-letter" style="background:<?= $sb_color ?>"><?= $sb_initial ?></span>
            </span>
            <span class="gc-sb-label">
                <span class="gc-class-name"><?= htmlspecialchars($sb_class['name']) ?></span>
                <span class="gc-class-sub"><?= htmlspecialchars($sb_class['section'] ?? '') ?></span>
            </span>
        </a>
        <?php endforeach; ?>

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
// Dropdown functionality
function toggleAddMenu() {
    document.getElementById('addMenu').classList.toggle('show');
    document.getElementById('avatarMenu').classList.remove('show');
}
function toggleAvatarMenu() {
    document.getElementById('avatarMenu').classList.toggle('show');
    document.getElementById('addMenu').classList.remove('show');
}
window.onclick = function(event) {
    if (!event.target.closest('.gc-dropdown-wrapper')) {
        document.getElementById('addMenu').classList.remove('show');
        document.getElementById('avatarMenu').classList.remove('show');
    }
}

// Modals
function openCreateModal() {
    document.getElementById('addMenu').classList.remove('show');
    document.getElementById('createModalOverlay').classList.add('show');
    document.getElementById('createModal').classList.add('show');
}
function openJoinModal() {
    document.getElementById('addMenu').classList.remove('show');
    document.getElementById('createModalOverlay').classList.add('show');
    document.getElementById('joinModal').classList.add('show');
}
function closeModals() {
    document.getElementById('createModalOverlay').classList.remove('show');
    document.getElementById('createModal').classList.remove('show');
    document.getElementById('joinModal').classList.remove('show');
}

// Enable/Disable Create Button based on Class Name
function checkClassName() {
    const nameInput = document.getElementById('className').value.trim();
    const btn = document.getElementById('createBtn');
    btn.disabled = nameInput === '';
}
document.getElementById('className')?.addEventListener('input', checkClassName);

// Enable/Disable Join Button based on Class Code
function checkClassCode() {
    const codeInput = document.getElementById('classCode').value.trim();
    const btn = document.getElementById('joinBtn');
    btn.disabled = codeInput === '';
}
document.getElementById('classCode')?.addEventListener('input', checkClassCode);
</script>

<!-- Modals HTML -->
<div class="gc-modal-overlay" id="createModalOverlay" onclick="closeModals()"></div>

<!-- Create Class Modal -->
<div class="gc-modal" id="createModal">
    <div class="gc-modal-header">
        <h2>Create class</h2>
    </div>
    <form action="<?= BASE_URL ?>/actions/create_class.php" method="POST">
        <div class="gc-modal-body">
            <div class="gc-create-input">
                <input type="text" name="name" id="className" required placeholder=" " autocomplete="off">
                <label for="className">Class name<span class="req-star">*</span></label>
            </div>
            <p class="gc-required-text">*Required</p>

            <div class="gc-create-input">
                <input type="text" name="section" id="classSection" placeholder=" " autocomplete="off">
                <label for="classSection">Section</label>
            </div>

            <div class="gc-create-input">
                <input type="text" name="subject" id="classSubject" placeholder=" " autocomplete="off">
                <label for="classSubject">Subject</label>
            </div>

            <div class="gc-create-input">
                <input type="text" name="room" id="classRoom" placeholder=" " autocomplete="off">
                <label for="classRoom">Room</label>
            </div>
        </div>
        <div class="gc-modal-footer">
            <button type="button" class="gc-btn-text" onclick="closeModals()">Cancel</button>
            <button type="submit" class="gc-btn-text" id="createBtn" disabled>Create</button>
        </div>
    </form>
</div>

<!-- Join Class Modal -->
<div class="gc-modal" id="joinModal">
    <div class="gc-modal-header">
        <h2>Join class</h2>
    </div>
    <form action="<?= BASE_URL ?>/actions/join_class.php" method="POST">
        <div class="gc-modal-body">
            <!-- User info card -->
            <div class="gc-join-user-card">
                <p class="gc-join-signed">You're currently signed in as</p>
                <div class="gc-join-user-row">
                    <div class="gc-join-avatar">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="gc-join-user-info">
                        <span class="gc-join-user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Aiman Shahid') ?></span>
                        <span class="gc-join-user-email"><?= htmlspecialchars($user_email) ?></span>
                    </div>
                </div>
                <button type="button" class="gc-btn-switch">Switch account</button>
            </div>

            <!-- Class code card -->
            <div class="gc-join-code-card">
                <p class="gc-code-title">Class code</p>
                <p class="gc-code-desc">Ask your teacher for the class code, then enter it here.</p>
                <div class="gc-outlined-input">
                    <input type="text" name="code" id="classCode" required placeholder=" " autocomplete="off">
                    <label for="classCode">Class code</label>
                </div>
            </div>

            <!-- Instructions -->
            <div class="gc-join-instructions">
                <p>To sign in with a class code</p>
                <ul>
                    <li>Use an authorised account</li>
                    <li>Use a class code with 5–8 letters or numbers, and no spaces or symbols</li>
                </ul>
                <br>
                <a href="#">If you have trouble joining the class, go to the Help Centre article</a>
            </div>
        </div>
        <div class="gc-modal-footer">
            <button type="button" class="gc-btn-text" onclick="closeModals()">Cancel</button>
            <button type="submit" class="gc-btn-text" id="joinBtn" disabled>Join</button>
        </div>
    </form>
</div>

