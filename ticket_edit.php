<?php
require_once "../includes/header.php";
require_once "../includes/functions.php";
require_once "../database/db_connection.php";

if (!isLoggedIn()) header("Location: login.php");
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id=? AND deleted_at IS NULL");
$stmt->bind_param("i",$id); $stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();
if (!$ticket) { echo "Ticket not found"; exit; }
if (!canEditTicket($ticket)) { echo "Not permitted"; exit; }

$users = $conn->query("SELECT id, name FROM users ORDER BY name");
$msg = $_SESSION['error'] ?? $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>
<h2>Edit Ticket #<?= $ticket['id'] ?></h2>
<?php if ($msg) echo "<p>$msg</p>"; ?>
<form method="POST" action="../controllers/ticketController.php" enctype="multipart/form-data">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
    Title: <input name="title" value="<?= htmlspecialchars($ticket['title']) ?>" required><br><br>
    Description: <textarea name="description" required><?= htmlspecialchars($ticket['description']) ?></textarea><br><br>
    Assign to: <select name="assigned_to">
        <option value="">-- none --</option>
        <?php while($u = $users->fetch_assoc()): ?>
            <option value="<?= $u['id'] ?>" <?= ($ticket['assigned_to']==$u['id'] ? 'selected' : '') ?>><?= htmlspecialchars($u['name']) ?></option>
        <?php endwhile; ?>
    </select><br><br>
    Replace File: <input type="file" name="file"><br>
    Current file: <?= $ticket['file'] ? "<a href='../uploads/{$ticket['file']}' target='_blank'>View</a>" : "None" ?><br><br>
    <button>Save Changes</button>
</form>
<?php require_once "../includes/footer.php"; ?>
