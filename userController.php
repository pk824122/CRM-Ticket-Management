<?php
require_once __DIR__ . "/../database/db_connection.php";

if (session_status() === PHP_SESSION_NONE) {
    
}

$action = $_GET['action'] ?? '';

/* ================= REGISTER ================= */
if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = 'user';

    if (!$name || !$email || !$password) {
        $_SESSION['error'] = "All fields required";
        header("Location: ../views/register.php");
        exit;
    }

    // CHECK DUPLICATE EMAIL
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Email already exists";
        header("Location: ../views/register.php");
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users(name,email,password,role) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name, $email, $hash, $role);
    $stmt->execute();

    $_SESSION['success'] = "Registration successful. Login now.";
    header("Location: ../views/login.php");
    exit;
}

/* ================= LOGIN ================= */
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        header("Location: ../views/dashboard.php");
        exit;

    } else {
        $_SESSION['error'] = "Invalid credentials";
        header("Location: ../views/login.php");
        exit;
    }
}

/* ================= LOGOUT ================= */
if ($action === 'logout') {

    session_destroy();
    header("Location: ../views/login.php");
    exit;
}
