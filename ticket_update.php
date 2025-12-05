<?php
require_once dirname(__DIR__) . "/includes/header.php";
require_once dirname(__DIR__) . "/includes/functions.php";
require_once dirname(__DIR__) . "/database/db_connection.php";

$user_id = $_SESSION['user_id'];
$ticket_id = $_GET['id'] ?? 0;

/* Fetch ticket */
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id=? AND deleted_at IS NULL");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$ticket) {
    echo "<p class='error'>Ticket not found</p>";
    require_once dirname(__DIR__) . "/includes/footer.php";
    exit;
}

/* Permission check */
$is_author = ($ticket['created_by'] == $user_id);
$is_assignee = ($ticket['assigned_to'] == $user_id);

if (!($is_author || $is_assignee || isAdmin())) {
    echo "<p class='error'>Access denied</p>";
    require_once dirname(__DIR__) . "/includes/footer.php";
    exit;
}

/* Get users for dropdown */
$users = $conn->query("SELECT id, name FROM users ORDER BY name");


/* Handle submit */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name        = $is_author || isAdmin() ? $_POST['name'] : $ticket['name'];
    $description = $is_author || isAdmin() ? $_POST['description'] : $ticket['description'];
    $assigned_to = ($is_author || isAdmin()) ? ($_POST['assigned_to'] ?: NULL) : $ticket['assigned_to'];
    $status      = $_POST['status'];

    /* Completed timestamp */
    $completed_at = ($status == 'completed') ? date("Y-m-d H:i:s") : NULL;

    /* File upload */
    $file_name = $ticket['file'];
    if (($is_author || isAdmin()) && isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['file']['tmp_name'], UPLOAD_PATH . $file_name);
    }

    /* Assignment time */
    $assigned_at = ($assigned_to && ($is_author || isAdmin())) ? date("Y-m-d H:i:s") : $ticket['assigned_at'];

    /* UPDATE */
    $stmt = $conn->prepare("UPDATE tickets 
        SET name=?, description=?, status=?, file=?, assigned_to=?, assigned_at=?, completed_at=?, updated_at=NOW()
        WHERE id=?");

    $stmt->bind_param(
        "sssssssi",
        $name, $description, $status, $file_name,
        $assigned_to, $assigned_at, $completed_at, $ticket_id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Ticket updated successfully";
    } else {
        $_SESSION['error'] = "Update failed: " . $stmt->error;
    }

    $stmt->close();
    header("Location: " . BASE_URL . "/views/tickets.php");
    exit;
}
?>

<h2>Update Ticket #<?= $ticket['id'] ?></h2>

<?php
$msg = $_SESSION['success'] ?? $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
if ($msg) echo "<p class='success'>" . htmlspecialchars($msg) . "</p>";
?>

<form method="POST" enctype="multipart/form-data">

<?php if ($is_author || isAdmin()): ?>

<label>Title</label>
<input type="text" name="name" value="<?= htmlspecialchars($ticket['name']) ?>" required>

<label>Description</label>
<textarea name="description" required><?= htmlspecialchars($ticket['description']) ?></textarea>

<label>Assign To</label>
<select name="assigned_to">
    <option value="">-- none --</option>
    <?php while ($u = $users->fetch_assoc()): ?>
        <option value="<?= $u['id'] ?>" <?= ($ticket['assigned_to']==$u['id'])?'selected':'' ?>>
            <?= htmlspecialchars($u['name']) ?>
        </option>
    <?php endwhile; ?>
</select>

<label>File Upload</label>
<input type="file" name="file">
<?php if ($ticket['file']): ?>
    <br>Current: <a href="<?= BASE_URL ?>/uploads/<?= $ticket['file'] ?>" target="_blank">View</a><br>
<?php endif; ?>

<?php endif; ?>

<label>Status</label>
<select name="status">
    <?php
    $statuses = ['pending','inprogress','completed','onhold'];
    foreach($statuses as $s){
        $sel = ($ticket['status']==$s) ? 'selected' : '';
        echo "<option value='$s' $sel>" . ucfirst($s) . "</option>";
    }
    ?>
</select>

<br><br>

<button class="btn">Update Ticket</button>

</form>

<?php require_once dirname(__DIR__) . "/includes/footer.php"; ?>
