<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers Management - Call Center</title>
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
                    <li><a href="../index.php?page=calls">Calls</a></li>
                    <li><a href="../index.php?page=customers" class="active">Customers</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <div class="page-header">
                <h2>Customers Management</h2>
                <a href="../index.php?page=customers&action=create" class="btn">Add New Customer</a>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?php echo $customer['id']; ?></td>
                            <td><?php echo htmlspecialchars($customer['name']); ?></td>
                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                            <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                            <td><?php echo htmlspecialchars(substr($customer['address'], 0, 50) . (strlen($customer['address']) > 50 ? '...' : '')); ?></td>
                            <td><?php echo date('M j, Y', strtotime($customer['created_at'])); ?></td>
                            <td>
                                <a href="../index.php?page=customers&action=view&id=<?php echo $customer['id']; ?>">View</a>
                                <a href="../index.php?page=customers&action=edit&id=<?php echo $customer['id']; ?>">Edit</a>
                                <a href="../index.php?page=customers&action=delete&id=<?php echo $customer['id']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this customer?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No customers found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="mt-3">
                <p>Total Customers: <strong><?php echo count($customers); ?></strong></p>
            </div>
        </main>
    </div>
</body>
</html>