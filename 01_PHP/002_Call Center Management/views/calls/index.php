<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calls Management - Call Center</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Call Center Management System</h1>
            <nav>
                <ul>
                    <li><a href="../index.php">Dashboard</a></li>
                    <li><a href="../index.php?page=agents">Agents</a></li>
                    <li><a href="../index.php?page=calls" class="active">Calls</a></li>
                    <li><a href="../index.php?page=customers">Customers</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <div class="page-header">
                <h2>Calls Management</h2>
                <a href="../index.php?page=calls&action=create" class="btn">Add New Call</a>
            </div>

            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                </div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Agent</th>
                        <th>Type</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Call Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($calls)): ?>
                        <?php foreach ($calls as $call): ?>
                        <tr>
                            <td><?php echo $call['id']; ?></td>
                            <td><?php echo htmlspecialchars($call['customer_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($call['agent_name'] ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $call['call_type'] === 'incoming' ? 'info' : 'warning'; ?>">
                                    <?php echo ucfirst($call['call_type']); ?>
                                </span>
                            </td>
                            <td><?php echo $call['duration']; ?> min</td>
                            <td>
                                <span class="status-<?php echo $call['status']; ?>">
                                    <?php echo ucfirst($call['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y g:i A', strtotime($call['call_time'])); ?></td>
                            <td>
                                <a href="../index.php?page=calls&action=view&id=<?php echo $call['id']; ?>">View</a>
                                <a href="../index.php?page=calls&action=edit&id=<?php echo $call['id']; ?>">Edit</a>
                                <a href="../index.php?page=calls&action=delete&id=<?php echo $call['id']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this call record?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No calls found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="mt-3">
                <p>Total Calls: <strong><?php echo count($calls); ?></strong></p>
            </div>
        </main>
    </div>
</body>
</html>