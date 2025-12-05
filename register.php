<?php
require_once dirname(__DIR__) . "/config/config.php";
require_once dirname(__DIR__) . "/database/db_connection.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect logged-in users to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/views/dashboard.php");
    exit;
}

// Show messages
$msg = $_SESSION['error'] ?? $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CRM System</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2f3640;
        }

        .register-container label {
            font-weight: 600;
            color: #2f3640;
        }

        .register-container input {
            width: 100%;
            padding: 10px 12px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #dcdde1;
            border-radius: 5px;
            outline: none;
            font-size: 16px;
        }

        .register-container input:focus {
            border-color: #0984e3;
        }

        .register-container button {
            width: 100%;
            padding: 12px;
            background: #0984e3;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .register-container button:hover {
            background: #74b9ff;
        }

        .register-container p {
            text-align: center;
            margin-top: 20px;
            color: #2f3640;
        }

        .register-container p a {
            color: #0984e3;
            text-decoration: none;
        }

        .register-container p a:hover {
            text-decoration: underline;
        }

        .message {
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: 600;
        }

        .message.error {
            background-color: #ffe6e6;
            color: #e74c3c;
        }

        .message.success {
            background-color: #e0f7e9;
            color: #27ae60;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Register</h2>

    <?php if ($msg): ?>
        <div class="message <?= isset($_SESSION['error']) ? 'error' : 'success' ?>">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/controllers/userController.php?action=register">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter your full name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="<?= BASE_URL ?>/views/login.php">Login here</a></p>
</div>

</body>
</html>
