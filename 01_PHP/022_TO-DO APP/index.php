<?php
require_once 'config.php';
$tasks = getTasks();

// Separate completed and pending tasks
$pendingTasks = array_filter($tasks, function($task) {
    return !$task['completed'];
});

$completedTasks = array_filter($tasks, function($task) {
    return $task['completed'];
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 My To-Do List</h1>
            <p>Organize your tasks and boost your productivity</p>
        </div>

        <form action="add_task.php" method="POST" class="add-task-form">
            <div class="task-input-group">
                <input type="text" name="task" class="task-input" placeholder="What do you need to accomplish today?" required>
                <button type="submit" class="add-btn">Add Task</button>
            </div>
        </form>

        <div class="tasks-section">
            <h2 class="section-title">Pending Tasks (<?php echo count($pendingTasks); ?>)</h2>
            
            <?php if (empty($pendingTasks)): ?>
                <div class="empty-state">
                    <div>📋</div>
                    <h3>No pending tasks</h3>
                    <p>Add a task above to get started!</p>
                </div>
            <?php else: ?>
                <ul class="task-list">
                    <?php foreach ($pendingTasks as $id => $task): ?>
                        <li class="task-item">
                            <form action="toggle_task.php" method="POST" style="display: inline;">
                                <input type="hidden" name="task_id" value="<?php echo $id; ?>">
                                <button type="submit" class="toggle-btn">✓ Complete</button>
                            </form>
                            <span class="task-text"><?php echo htmlspecialchars($task['text']); ?></span>
                            <div class="task-actions">
                                <form action="delete_task.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="task_id" value="<?php echo $id; ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <?php if (!empty($completedTasks)): ?>
        <div class="tasks-section">
            <h2 class="section-title">Completed Tasks (<?php echo count($completedTasks); ?>)</h2>
            <ul class="task-list">
                <?php foreach ($completedTasks as $id => $task): ?>
                    <li class="task-item completed">
                        <form action="toggle_task.php" method="POST" style="display: inline;">
                            <input type="hidden" name="task_id" value="<?php echo $id; ?>">
                            <button type="submit" class="toggle-btn">↶ Undo</button>
                        </form>
                        <span class="task-text"><?php echo htmlspecialchars($task['text']); ?></span>
                        <div class="task-actions">
                            <form action="delete_task.php" method="POST" style="display: inline;">
                                <input type="hidden" name="task_id" value="<?php echo $id; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="stats">
            <span>Total Tasks: <?php echo count($tasks); ?></span>
            <span>Pending: <?php echo count($pendingTasks); ?></span>
            <span>Completed: <?php echo count($completedTasks); ?></span>
        </div>
    </div>
</body>
</html>