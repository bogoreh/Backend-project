<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Agent - Call Center</title>
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
                    <li><a href="../index.php?page=customers">Customers</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <div class="page-header">
                <h2>Add New Agent</h2>
                <a href="../index.php?page=agents" class="btn">Back to Agents</a>
            </div>

            <form method="POST" action="../index.php?page=agents&action=create">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="department">Department:</label>
                    <select id="department" name="department" required>
                        <option value="">Select Department</option>
                        <option value="Sales">Sales</option>
                        <option value="Support">Support</option>
                        <option value="Billing">Billing</option>
                        <option value="Technical">Technical</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn">Add Agent</button>
                <a href="../index.php?page=agents" class="btn btn-secondary">Cancel</a>
            </form>
        </main>
    </div>
</body>
</html>