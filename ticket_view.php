<?php
require_once "../includes/header.php";
require_once "../includes/functions.php";
require_once "../database/db_connection.php";
if (!isLoggedIn()) header("Location: login.php");
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT t.*, u1.name AS creator, u2.name AS assignee FROM tickets t LEFT JOIN users u1 ON u1.id=t.created_by LEFT JOIN users u2 ON u2.id=t.assigned_to WHERE t.id=?");
$stmt->bind_param("i",$id); $stmt->execute();
$t = $stmt->get_result()->fetch_assoc();
if (!$t) { echo "Ticket not found"; exit; }
if (!canViewTicket($t)) { echo "Not permitted to view this ticket"; exit; }
?>
<h2>Ticket #<?= $t['id'] ?></h2>
<p><strong>Title:</strong> <?= htmlspecialchars($t['title']) ?></p>
<p><strong>Description:</strong> <?= nl2br(htmlspecialchars($t['description'])) ?></p>
<p><strong>Status:</strong> <?= $t['status'] ?></p>
<p><strong>Created By:</strong> <?= htmlspecialchars($t['creator']) ?></p>
<p><strong>Assigned To:</strong> <?= htmlspecialchars($t['assignee']) ?></p>
<p><strong>File:</strong> <?= $t['file'] ? "<a href='../uploads/{$t['file']}' target='_blank'>Download</a>" : "None" ?></p>
<p><a href="tickets.php">Back</a></p>
<?php require_once "../includes/footer.php"; ?>
