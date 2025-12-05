<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/functions.php";

// STOP ACCESS FOR GUEST USERS
$public_pages = ["login.php", "register.php"];
$current_page = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['user_id']) && !in_array($current_page, $public_pages)) {
    header("Location: " . BASE_URL . "/views/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRM System</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<header>
    <h2>CRM Ticket System</h2>

    <?php if(isset($_SESSION['user_id'])): ?>
        <nav>
            <a href="<?= BASE_URL ?>/views/dashboard.php">Dashboard</a>
            <a href="<?= BASE_URL ?>/views/tickets.php">My Tickets</a>
            <a href="<?= BASE_URL ?>/views/ticket_create.php">Create Ticket</a>
            <a href="<?= BASE_URL ?>/index.php?logout=1">Logout</a>
        </nav>
    <?php endif; ?>
</header>

<div class="container">
