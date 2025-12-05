<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../database/db_connection.php";

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function currentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function currentUserRole() {
    return $_SESSION['role'] ?? 'user';
}

// Optional Admin
function isAdmin() {
    return currentUserRole() === 'admin';
}

// Who can view?
function canViewTicket($ticket) {
    if (isAdmin()) return true;
    $uid = currentUserId();
    return $ticket['created_by'] == $uid || $ticket['assigned_to'] == $uid;
}

// Who can edit fully?
function canEditTicket($ticket) {
    return isAdmin() || $ticket['created_by'] == currentUserId();
}

// Who can update status?
function canUpdateStatus($ticket) {
    return isAdmin() || $ticket['assigned_to'] == currentUserId();
}
