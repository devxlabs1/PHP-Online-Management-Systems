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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .container {
            margin-top: 50px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .add-btn {
            font-size: 24px;
            background-color: #6200EA;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .add-btn:hover {
            background-color: #4e00b0;
        }
        table {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th {
            background-color: #6200EA;
            color: #fff;
        }
        .btn-edit {
            background-color: #FFA726;
        }
        .btn-delete {
            background-color: #E53935;
        }
        .btn {
            margin: 0 5px;
        }
        .form-inline input, .form-inline select {
            margin: 5px 0;
        }
        .form-inline button {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Task Management System</h1>
            <a href="create.php" class="add-btn">+</a>
        </div>
        
        <form method="GET" action="" class="form-inline d-flex mb-3">
            <input type="text" name="search" value="<?php echo $search; ?>" class="form-control" placeholder="Search tasks...">
            <select name="status_filter" class="form-control">
                <option value="">All</option>
                <option value="Pending" <?php echo $statusFilter == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Completed" <?php echo $statusFilter == 'Completed' ? 'selected' : ''; ?>>Completed</option>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td>
                                <form method="POST" action="" class="form-inline">
                                    <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                                    <select name="new_status" class="form-control" onchange="this.form.submit()">
                                        <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Completed" <?php echo $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-delete">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No tasks found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
