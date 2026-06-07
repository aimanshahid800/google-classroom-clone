<!-- Loading Animation Line -->
<div class="gc-loading-line"></div>

<div class="gc-class-tabs-container">
    <a href="stream.php?class_id=<?= $class_id ?>" class="gc-class-tab <?= $current_page === 'stream.php' ? 'active' : '' ?>">Stream</a>
    <a href="classwork.php?class_id=<?= $class_id ?>" class="gc-class-tab <?= $current_page === 'classwork.php' ? 'active' : '' ?>">Classwork</a>
    <a href="people.php?class_id=<?= $class_id ?>" class="gc-class-tab <?= $current_page === 'people.php' ? 'active' : '' ?>">People</a>
</div>

<style>
.gc-loading-line {
    height: 4px;
    background: #1a73e8;
    width: 0;
    animation: loadLine 1.5s ease-out forwards;
    margin-top: -24px; /* pull up to offset the padding of .main-content */
    margin-left: -24px;
    margin-right: -24px;
}
@keyframes loadLine {
    0% { width: 0; }
    50% { width: 70%; }
    100% { width: 100%; opacity: 0; }
}

.gc-class-tabs-container {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #e0e0e0;
    margin-left: -24px;
    margin-right: -24px;
    padding-left: 24px;
    margin-bottom: 24px;
    background: #fff;
}
.gc-class-tab {
    display: flex;
    align-items: center;
    height: 48px;
    padding: 0 24px;
    color: var(--text-secondary, #5f6368);
    text-decoration: none;
    font-family: 'Google Sans', sans-serif;
    font-size: 14px;
    font-weight: 500;
    position: relative;
    transition: color 0.2s, background 0.2s;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
}
.gc-class-tab:hover {
    color: var(--text-primary, #202124);
    background: rgba(0,0,0,0.04);
}
.gc-class-tab.active {
    color: #1a73e8;
}
.gc-class-tab.active::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 3px;
    background-color: #1a73e8;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
}
</style>
