<?php
require_once __DIR__ . "/../database/db_connection.php";
require_once __DIR__ . "/../includes/functions.php";

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'] ?? 0;

if (!isLoggedIn()) { header("Location: ../views/login.php"); exit; }

// CREATE TICKET
if ($action === 'create') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $assigned_to = $_POST['assigned_to'] ?: NULL;

    $file_name = NULL;
    if (isset($_FILES['file']) && $_FILES['file']['error']==0) {
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid().".".$ext;
        move_uploaded_file($_FILES['file']['tmp_name'], UPLOAD_PATH.$file_name);
    }

    $assigned_at = $assigned_to ? date("Y-m-d H:i:s") : NULL;
    $stmt = $conn->prepare("INSERT INTO tickets (name, description, status, file, created_by, assigned_to, assigned_at) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssiii", $name, $description, $status, $file_name, $user_id, $assigned_to, $assigned_at);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success'] = "Ticket created!";
    header("Location: ../views/tickets.php");
    exit;
}

// UPDATE TICKET
if ($action === 'update') {
    $ticket_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM tickets WHERE id=?");
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $ticket = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$ticket || (!canEditTicket($ticket) && !canUpdateStatus($ticket))) {
        $_SESSION['error'] = "Not allowed!";
        header("Location: ../views/tickets.php");
        exit;
    }

    $name = canEditTicket($ticket) ? $_POST['name'] : $ticket['name'];
    $description = canEditTicket($ticket) ? $_POST['description'] : $ticket['description'];
    $assigned_to = canEditTicket($ticket) ? ($_POST['assigned_to'] ?: NULL) : $ticket['assigned_to'];
    $status = $_POST['status'];

    $assigned_at = ($assigned_to && canEditTicket($ticket)) ? date("Y-m-d H:i:s") : $ticket['assigned_at'];
    $completed_at = ($status === 'completed' && !$ticket['completed_at']) ? date("Y-m-d H:i:s") : $ticket['completed_at'];

    $file_name = $ticket['file'];
    if (canEditTicket($ticket) && isset($_FILES['file']) && $_FILES['file']['error']==0) {
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid().".".$ext;
        move_uploaded_file($_FILES['file']['tmp_name'], UPLOAD_PATH.$file_name);
    }

    $stmt = $conn->prepare("UPDATE tickets SET name=?, description=?, status=?, file=?, assigned_to=?, assigned_at=?, completed_at=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("sssssssi", $name, $description, $status, $file_name, $assigned_to, $assigned_at, $completed_at, $ticket_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success'] = "Ticket updated!";
    header("Location: ../views/tickets.php");
    exit;
}

// DELETE TICKET (Soft delete)
if ($action === 'delete') {
    $ticket_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM tickets WHERE id=?");
    $stmt->bind_param("i",$ticket_id);
    $stmt->execute();
    $ticket = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if($ticket && canEditTicket($ticket)) {
        $stmt = $conn->prepare("UPDATE tickets SET deleted_at=NOW() WHERE id=?");
        $stmt->bind_param("i",$ticket_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['success'] = "Ticket deleted!";
    } else {
        $_SESSION['error'] = "Not allowed!";
    }
    header("Location: ../views/tickets.php");
    exit;
}
