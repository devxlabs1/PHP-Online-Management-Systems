<?php
include 'db.php';

$search = '';
$statusFilter = '';

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
if (isset($_GET['status_filter'])) {
    $statusFilter = $_GET['status_filter'];
}

$sql = "SELECT * FROM tasks WHERE (title LIKE '%$search%' OR description LIKE '%$search%')";

if ($statusFilter) {
    $sql .= " AND status='$statusFilter'";
}

$result = $conn->query($sql);

// Check for the last ID
$lastIdSql = "SELECT id FROM tasks ORDER BY id DESC LIMIT 1";
$lastIdResult = $conn->query($lastIdSql);
$nextId = 1; // Default ID if there are no tasks

if ($lastIdResult->num_rows > 0) {
    $lastRow = $lastIdResult->fetch_assoc();
    $nextId = $lastRow['id'] + 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['task_id'];
    $newStatus = $_POST['new_status'];

    $updateSql = "UPDATE tasks SET status='$newStatus' WHERE id=$taskId";
    $conn->query($updateSql);
    header("Location: index.php?search=$search&status_filter=$statusFilter");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        a {
            text-decoration: none;
            color: #6200EA;
        }
        a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #6200EA;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f4f4f4;
        }
        form {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        input[type="text"], select {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button, .btn {
            padding: 10px 20px;
            background-color: #6200EA;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #4e00b0;
        }
        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
        }
        .btn-edit {
            background-color: #FFA726;
        }
        .btn-edit:hover {
            background-color: #fb8c00;
        }
        .btn-delete {
            background-color: #E53935;
        }
        .btn-delete:hover {
            background-color: #d32f2f;
        }
        .add-btn {
            font-size: 24px;
            padding: 10px;
            background-color: #6200EA;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-left: 10px;
        }
        .add-btn:hover {
            background-color: #4e00b0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Task Management System</h1>
        <a href="create.php" class="add-btn">+</a>
    </div>
    
    <form method="GET" action="">
        <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Search tasks...">
        <select name="status_filter">
            <option value="">All</option>
            <option value="Pending" <?php echo $statusFilter == 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Completed" <?php echo $statusFilter == 'Completed' ? 'selected' : ''; ?>>Completed</option>
        </select>
        <button type="submit" class="btn">Search</button>
    </form>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                            <select name="new_status" onchange="this.form.submit()">
                                <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Completed" <?php echo $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn-delete">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No tasks found</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
