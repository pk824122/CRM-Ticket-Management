<?php
require_once "../config/config.php";

// Redirect user if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>CRM Login</title>

<style>
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(to right, #2575fc, #6a11cb);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-box {
    width: 360px;
    padding: 30px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0px 10px 25px rgba(0,0,0,0.2);
    text-align: center;
}

.icon {
    font-size: 40px;
    margin-bottom: 5px;
}

.login-box h2 {
    margin-bottom: 5px;
    color: #333;
}

.login-box h4 {
    margin-bottom: 15px;
    color: #666;
}

.input-group {
    margin: 15px 0;
    text-align: left;
}

.input-group input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.btn {
    width: 100%;
    padding: 10px;
    background: #2575fc;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 5px;
    margin-top: 15px;
    cursor: pointer;
}

.btn:hover {
    background: #174abd;
}

.register-box {
    margin-top: 15px;
    font-size: 14px;
}

.register-box a {
    text-decoration: none;
    color: #2575fc;
    font-weight: bold;
}

.error {
    background: #ffd2d2;
    color: #a40000;
    padding: 8px;
    margin-bottom: 15px;
    border-radius: 5px;
}
</style>

</head>
<body>

<div class="login-box">

    <div class="icon">🔐</div>
    <h2>CRM SYSTEM</h2>
    <h4>User Login</h4>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="error"><?= $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="../controllers/userController.php?action=login">

        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button class="btn">Login</button>
    </form>

    <div class="register-box">
        ❗ Not registered?
        <a href="register.php">Register Here</a>
    </div>

</div>

</body>
</html>
