<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - Google Classroom</title>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Google Sans', 'Roboto', Arial, sans-serif;
            background: #f0f4f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 4px 12px rgba(0,0,0,0.12);
            padding: 48px 40px 36px;
            width: 448px;
            max-width: 90vw;
            text-align: center;
        }
        .auth-logo {
            width: 48px;
            height: 48px;
            margin-bottom: 16px;
        }
        .auth-title {
            font-size: 28px;
            font-weight: 400;
            color: #202124;
            margin-bottom: 4px;
        }
        .auth-subtitle {
            font-size: 16px;
            font-weight: 400;
            color: #5f6368;
            margin-bottom: 32px;
        }
        .auth-input-group {
            position: relative;
            margin-bottom: 20px;
            text-align: left;
        }
        .auth-input-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #dadce0;
            border-radius: 4px;
            outline: none;
            font-size: 16px;
            font-family: 'Roboto', sans-serif;
            color: #202124;
            background: transparent;
            transition: border-color 0.15s;
        }
        .auth-input-group input:focus {
            border-color: #1a73e8;
        }
        .auth-input-group label {
            position: absolute;
            top: 16px;
            left: 14px;
            font-size: 16px;
            color: #5f6368;
            font-family: 'Roboto', sans-serif;
            pointer-events: none;
            background: #fff;
            padding: 0 4px;
            transition: all 0.15s ease;
        }
        .auth-input-group input:focus ~ label,
        .auth-input-group input:not(:placeholder-shown) ~ label {
            top: -8px;
            font-size: 12px;
            color: #1a73e8;
        }
        .auth-input-group input:not(:focus):not(:placeholder-shown) ~ label {
            color: #5f6368;
        }
        .auth-btn {
            width: 100%;
            padding: 12px 24px;
            background: #1a73e8;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Google Sans', sans-serif;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            margin-top: 8px;
        }
        .auth-btn:hover {
            background: #174ea6;
            box-shadow: 0 1px 2px rgba(0,0,0,0.3), 0 1px 3px rgba(0,0,0,0.15);
        }
        .auth-btn:disabled {
            background: #ccc;
            cursor: default;
            box-shadow: none;
        }
        .auth-footer {
            margin-top: 24px;
            font-size: 14px;
            color: #5f6368;
        }
        .auth-footer a {
            color: #1a73e8;
            text-decoration: none;
            font-weight: 500;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        .auth-error {
            background: #fce8e6;
            border-radius: 4px;
            padding: 12px 16px;
            margin-bottom: 20px;
            text-align: left;
            font-size: 13px;
            color: #d93025;
            font-family: 'Roboto', sans-serif;
            line-height: 1.4;
        }
        .auth-role-select {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 24px;
        }
        .auth-role-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: 2px solid #dadce0;
            border-radius: 24px;
            cursor: pointer;
            font-size: 14px;
            font-family: 'Google Sans', sans-serif;
            color: #5f6368;
            transition: all 0.15s;
            background: #fff;
        }
        .auth-role-option:hover {
            border-color: #1a73e8;
            color: #1a73e8;
        }
        .auth-role-option.selected {
            border-color: #1a73e8;
            background: #e8f0fe;
            color: #1a73e8;
        }
        .auth-role-option input {
            display: none;
        }
        .auth-extra-link {
            display: block;
            margin-top: 12px;
            font-size: 13px;
            color: #5f6368;
        }
        .auth-extra-link a {
            color: #1a73e8;
            text-decoration: none;
            font-weight: 500;
        }
        .auth-extra-link a:hover {
            text-decoration: underline;
        }
        @media (max-width: 480px) {
            .auth-card { padding: 32px 24px 28px; }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <img src="streamline-core-flat---free--14x14-SVG/Graduation-Cap--Streamline-Core.svg" alt="Classroom" class="auth-logo">
        <h1 class="auth-title">Google Classroom</h1>
        <p class="auth-subtitle">Sign in</p>

        <?php if ($error === 'invalid'): ?>
            <div class="auth-error">Invalid email or password. Please try again.</div>
        <?php elseif ($error === 'empty'): ?>
            <div class="auth-error">Please fill in all fields.</div>
        <?php elseif ($error === 'registered'): ?>
            <div class="auth-error" style="background:#e6f4ea; color:#1e8e3e;">Account created successfully! Please sign in.</div>
        <?php endif; ?>

        <form action="actions/login_handler.php" method="POST">
            <div class="auth-input-group">
                <input type="email" name="email" id="email" required placeholder=" " autocomplete="email">
                <label for="email">Email</label>
            </div>
            <div class="auth-input-group">
                <input type="password" name="password" id="password" required placeholder=" " autocomplete="current-password">
                <label for="password">Password</label>
            </div>
            <button type="submit" class="auth-btn" id="loginBtn">Sign in</button>
        </form>

        <div class="auth-footer">
            <a href="register.php">Create account</a>
        </div>
    </div>

    <script>
        document.querySelectorAll('.auth-input-group input').forEach(input => {
            input.addEventListener('input', function() {
                const btn = document.getElementById('loginBtn');
                const email = document.getElementById('email').value.trim();
                const pass = document.getElementById('password').value.trim();
                btn.disabled = email === '' || pass === '';
            });
        });
    </script>
</body>
</html>
