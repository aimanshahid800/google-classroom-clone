<aside class="sidebar" id="sidebar">
    <div class="sidebar-section">
        <a href="index.php" class="sidebar-item active">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
            Home
        </a>
        <a href="calendar.php" class="sidebar-item">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/></svg>
            Calendar
        </a>
        <a href="todo.php" class="sidebar-item">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            To-do
        </a>
    </div>

    <div class="sidebar-divider"></div>

    <div class="sidebar-section">
        <p class="sidebar-label">Enrolled classes</p>
        <!-- Dynamic classes load here -->
    </div>
</aside>

<style>
.sidebar {
    position: fixed;
    top: 64px; left: 0;
    width: 256px;
    height: calc(100vh - 64px);
    background: #ffffff;
    border-right: 1px solid #dadce0;
    overflow-y: auto;
    padding: 8px 0;
    z-index: 99;
    transition: transform 0.2s ease;
}
.sidebar.hidden { transform: translateX(-256px); }
.sidebar-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 0 16px;
    height: 48px;
    color: #202124;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    border-radius: 0 24px 24px 0;
    margin-right: 16px;
}
.sidebar-item:hover { background: #f1f3f4; }
.sidebar-item.active { background: #e8f0fe; color: #1a73e8; }
.sidebar-item.active svg { fill: #1a73e8; }
.sidebar-divider { height: 1px; background: #dadce0; margin: 8px 0; }
.sidebar-label { font-size: 11px; color: #5f6368; padding: 8px 16px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 500; }
</style>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('hidden');
}
</script>