<?php
require_once "../includes/functions.php";

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Ticket Created</title>
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    background: #fff;
    padding: 40px;
    width: 420px;
    text-align: center;
    border-radius: 12px;
    box-shadow: 0 15px 30px rgba(0,0,0,.25);
    animation: zoomIn .6s ease;
}

@keyframes zoomIn {
    from { transform: scale(.5); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.icon {
    font-size: 60px;
    color: #28a745;
}

h2 {
    margin-top: 10px;
    color: #333;
}

p {
    color: #555;
    font-size: 16px;
    margin: 15px 0;
}

.btn {
    display: inline-block;
    background: #007bff;
    color: #fff;
    text-decoration: none;
    padding: 12px 25px;
    border-radius: 6px;
    margin-top: 20px;
    transition: .3s;
    font-weight: bold;
}

.btn:hover {
    background: #0056b3;
}
</style>
</head>
<body>

<div class="card">
    <div class="icon">✅</div>
    <h2>Success!</h2>
    <p>Thanks! Your ticket creation has been completed successfully.</p>
    <a href="tickets.php" class="btn">Back to Tickets</a>
</div>

</body>
</html>
