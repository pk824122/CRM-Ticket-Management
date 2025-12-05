<?php
require_once "../includes/functions.php";
require_once "../database/db_connection.php";

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user_name = $_SESSION['name'];
$user_id   = $_SESSION['user_id'];

// Initialize ticket counts to avoid errors
$totalTickets = 0;
$pendingTickets = 0;
$inprogressTickets = 0;
$completedTickets = 0;

try {
    // TOTAL TICKETS
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM tickets WHERE created_by = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $totalTickets = $stmt->get_result()->fetch_assoc()['total'];

    // PENDING
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM tickets WHERE status='pending' AND created_by = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $pendingTickets = $stmt->get_result()->fetch_assoc()['total'];

    // IN PROGRESS
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM tickets WHERE status='inprogress' AND created_by = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $inprogressTickets = $stmt->get_result()->fetch_assoc()['total'];

    // COMPLETED
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM tickets WHERE status='completed' AND created_by = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $completedTickets = $stmt->get_result()->fetch_assoc()['total'];

} catch (Exception $e) {
    // Safe fallback, prevents host error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CRM System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100vh;
            background-color: #0984e3;
            color: white;
            padding-top: 20px;
        }
        .sidebar h2 { text-align: center; margin-bottom: 30px; }
        .sidebar a { display: block; color: white; padding: 12px 20px; text-decoration: none; margin: 5px 0; border-radius: 5px; }
        .sidebar a:hover { background-color: #74b9ff; }
        .main { margin-left: 220px; padding: 20px; }
        .top-bar {
            display: flex; justify-content: space-between; align-items: center;
            background-color: #fff; padding: 15px 20px; border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px;
        }
        .top-bar h1 { margin: 0; font-size: 24px; }
        .top-bar a { color: #0984e3; text-decoration: none; font-weight: 600; }
        .top-bar a:hover { text-decoration: underline; }
        .card-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .card {
            background-color: #fff; padding: 20px; border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.2s;
        }
        .card:hover { transform: translateY(-5px); }
        .card h3 { margin-top: 0; font-size: 18px; color: #2d3436; }
        .card p { font-size: 28px; font-weight: bold; color: #0984e3; margin: 10px 0 0; }
        .quick-links { margin-top: 20px; }
        .quick-links a { margin-right: 15px; color: #0984e3; text-decoration: none; font-weight: 600; }
        .quick-links a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>CRM System</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="tickets.php">My Tickets</a>
    <a href="../controllers/userController.php?action=logout">Logout</a>
</div>

<div class="main">
    <div class="top-bar">
        <h1>Welcome, <?= htmlspecialchars($user_name) ?></h1>
        <a href="../controllers/userController.php?action=logout">Logout</a>
    </div>

    <div class="card-container">
        <div class="card">
            <h3>Total Tickets</h3>
            <p><?= $totalTickets ?></p>
        </div>
        <div class="card">
            <h3>Pending</h3>
            <p><?= $pendingTickets ?></p>
        </div>
        <div class="card">
            <h3>In Progress</h3>
            <p><?= $inprogressTickets ?></p>
        </div>
        <div class="card">
            <h3>Completed</h3>
            <p><?= $completedTickets ?></p>
        </div>
    </div>

    <div class="quick-links">
        <a href="tickets.php">View All Tickets</a>
        <a href="../controllers/userController.php?action=logout">Logout</a>
    </div>
</div>

</body>
</html>
