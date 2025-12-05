<?php
require_once dirname(__DIR__) . "/includes/header.php";
require_once dirname(__DIR__) . "/database/db_connection.php";

$user_id = $_SESSION['user_id'];
$ticket_id = $_GET['id'] ?? 0;

// Fetch ticket
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id=? AND deleted_at IS NULL");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$ticket) {
    $_SESSION['error'] = "Ticket not found or already deleted.";
    header("Location: " . BASE_URL . "/views/tickets.php");
    exit;
}

// Only author can delete
if ($ticket['created_by'] != $user_id) {
    $_SESSION['error'] = "You are not allowed to delete this ticket.";
    header("Location: " . BASE_URL . "/views/tickets.php");
    exit;
}

// Soft delete the ticket
$stmt = $conn->prepare("UPDATE tickets SET deleted_at=NOW() WHERE id=?");
$stmt->bind_param("i", $ticket_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Ticket deleted successfully.";
} else {
    $_SESSION['error'] = "Error deleting ticket: " . $stmt->error;
}

$stmt->close();

// Redirect back to tickets list
header("Location: " . BASE_URL . "/views/tickets.php");
exit;
