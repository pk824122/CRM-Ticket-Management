<?php
require_once dirname(__DIR__) . "/includes/header.php";
require_once dirname(__DIR__) . "/database/db_connection.php";
require_once dirname(__DIR__) . "/includes/functions.php";

$user_id  = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'user';

/* Admin sees all tickets, user sees only own & assigned */
if (isAdmin()) {
    $sql = "SELECT t.*, u.name AS assigned_name 
            FROM tickets t
            LEFT JOIN users u ON t.assigned_to = u.id
            WHERE t.deleted_at IS NULL
            ORDER BY t.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
} else {
    $sql = "SELECT t.*, u.name AS assigned_name 
            FROM tickets t
            LEFT JOIN users u ON t.assigned_to = u.id
            WHERE (t.created_by = ? OR t.assigned_to = ?)
            AND t.deleted_at IS NULL
            ORDER BY t.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
}

$result = $stmt->get_result();
?>

<h2>My Tickets</h2>

<a class="btn" href="<?= BASE_URL ?>/views/ticket_create.php">+ Create Ticket</a>

<br><br>

<table>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Description</th>
    <th>Status</th>
    <th>Assigned To</th>
    <th>File</th>
    <th>Created</th>
    <th>Actions</th>
</tr>

<?php if($result->num_rows > 0): ?>
<?php while($row = $result->fetch_assoc()): ?>

<?php
$is_author   = ($row['created_by'] == $user_id);
$is_assignee = ($row['assigned_to'] == $user_id);
?>

<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['description']) ?></td>

    <td>
        <span class="status <?= $row['status'] ?>">
            <?= ucfirst($row['status']) ?>
        </span>
    </td>

    <td><?= $row['assigned_name'] ?? 'Not Assigned' ?></td>

    <td>
        <?php if($row['file']): ?>
            <a href="<?= BASE_URL ?>/uploads/<?= $row['file'] ?>" class="btn" target="_blank">View</a>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>

    <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>

    <td>
        <!-- UPDATE button -->
        <?php if ($is_author || $is_assignee || isAdmin()): ?>
            <a class="btn" href="<?= BASE_URL ?>/views/ticket_update.php?id=<?= $row['id'] ?>">Edit</a>
        <?php endif; ?>

        <!-- DELETE button (only author/admin) -->
        <?php if ($is_author || isAdmin()): ?>
            <a class="btn btn-danger" onclick="return confirm('Delete this ticket?')"
               href="<?= BASE_URL ?>/controllers/ticketController.php?action=delete&id=<?= $row['id'] ?>">
                Delete
            </a>
        <?php endif; ?>
    </td>

</tr>

<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="8" style="text-align:center;">No tickets found</td>
</tr>
<?php endif; ?>

</table>

<?php require_once dirname(__DIR__) . "/includes/footer.php"; ?>
