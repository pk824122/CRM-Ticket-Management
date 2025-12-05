<?php
require_once "config/config.php";

// Check logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . BASE_URL . "/views/login.php");
    exit;
}

// Redirect logged-in users to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/views/dashboard.php");
    exit;
} else {
    header("Location: " . BASE_URL . "/views/login.php");
    exit;
}
