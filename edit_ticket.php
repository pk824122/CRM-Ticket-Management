<?php
require_once "../config/config.php";
require_once "../database/db_connection.php";
session_start();

/* ======================
   AUTH CHECK
====================== */
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/views/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$ticket_id = intval($_GET['id'] ?? 0);

/* ======================
   FETCH TICKET
====================== */
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ? AND deleted_at IS NULL");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();
$stmt->close();

/* ======================
   VALIDATION
====================== */
if (!$ticket) {
    die("Ticket not found or deleted.");
}

/* ======================
   PERMISSION CHECK
====================== */
if ($ticket['created_by'] != $user_id && $ticket['assigned_to'] != $user_id) {
    die("Access Denied: You are not allowed to edit this ticket.");
}

/* ======================
   USERS LIST FOR ASSIGNMENT
====================== */
$users = $conn->query("SELECT id, name FROM users WHERE deleted_at IS NULL OR deleted_at IS NULL");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Ticket</title>
    <style>
        body { font-family: Arial; background:#f7f7f7; }
        .box { width:420px; margin:30px auto; background:white; padding:20px; border-radius:6px; }
        input, textarea, select { width:100%; padding:7px; margin-bottom:10px; }
        button { background:#007BFF; color:white; padding:10px; border:none; width:100%; }
    </style>
</head>
<body>

<div class="box">
<h3>Edit Ticket</h3>

<form method="POST" action="<?= BASE_URL ?>/controllers/ticketController.php" enctype="multipart/form-data">

<input type="hidden" name="action" value="update">
<input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">

<label>Title</label>
<input type="text" name="name" required value="<?= htmlspecialchars($ticket['name']) ?>">

<label>Description</label>
<textarea name="description"><?= htmlspecialchars($ticket['description']) ?></textarea>

<label>Status</label>
<select name="status">
<?php
$statuses = ['pending','inprogress','completed','onhold'];
foreach ($statuses as $s) {
    $selected = ($ticket['status'] === $s) ? "selected" : "";
    echo "<option value='$s' $selected>".ucfirst($s)."</option>";
}
?>
</select>

<label>Assign To</label>
<select name="assigned_to">
    <option value="">-- Not Assigned --</option>
    <?php while($u = $users->fetch_assoc()): ?>
        <option value="<?= $u['id'] ?>" <?= ($ticket['assigned_to'] == $u['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($u['name']) ?>
        </option>
    <?php endwhile; ?>
</select>

<label>New File (Optional)</label>
<input type="file" name="file">

<?php if($ticket['file']): ?>
    <small>Current File:</small><br>
    <a href="<?= BASE_URL ?>/uploads/<?= $ticket['file'] ?>" target="_blank">View File</a><br><br>
<?php endif; ?>

<button type="submit">Update Ticket</button>

</form>
</div>

</body>
</html>
