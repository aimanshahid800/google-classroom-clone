<?php
require_once '../config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Archived Classes</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .main-area { margin-left: 280px; margin-top: 80px; padding: 30px; }
        .main-area h1 { font-size: 24px; color: #202124; margin-bottom: 25px; }
        .empty-msg { color: #666; font-size: 15px; }
    </style>
</head>
<body>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-area">
    <h1>Archived Classes</h1>
    <p class="empty-msg">No archived classes found.</p>
</div>
</body>
</html>