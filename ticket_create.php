<?php
require_once "../includes/header.php";
require_once "../includes/functions.php";
require_once "../database/db_connection.php";

if (!isLoggedIn()) header("Location: login.php");

// Fetch all users for assignment dropdown
$users = $conn->query("SELECT id, name FROM users ORDER BY name");

$msg = $_SESSION['error'] ?? $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }
    .ticket-form-container {
        max-width: 600px;
        margin: 40px auto;
        padding: 25px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .ticket-form-container h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }
    .ticket-form-container label {
        font-weight: bold;
    }
    .ticket-form-container input[type="text"],
    .ticket-form-container textarea,
    .ticket-form-container select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 14px;
    }
    .ticket-form-container textarea {
        resize: vertical;
        height: 100px;
    }
    .ticket-form-container button {
        background-color: #007bff;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
    }
    .ticket-form-container button:hover {
        background-color: #0056b3;
    }
    .message {
        text-align: center;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    .message.success { background-color: #d4edda; color: #155724; }
    .message.error { background-color: #f8d7da; color: #721c24; }
</style>

<div class="ticket-form-container">
    <h2>Create Ticket</h2>

    <?php if ($msg): ?>
        <div class="message <?= isset($_SESSION['success']) ? 'success' : 'error' ?>">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="../controllers/ticketController.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create">

        <label>Title:</label>
        <input type="text" name="name" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Assign to:</label>
        <select name="assigned_to">
            <option value="">-- none --</option>
            <?php while($u = $users->fetch_assoc()): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>File:</label>
        <input type="file" name="file">

        <label>Status:</label>
        <select name="status">
            <?php foreach(['pending','inprogress','completed','onhold'] as $s): ?>
                <option value="<?= $s ?>"><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Create Ticket</button>
    </form>
</div>

<?php require_once "../includes/footer.php"; ?>
