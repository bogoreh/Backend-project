<?php
// Get agent data for editing
require_once '../../models/Database.php';
require_once '../../models/Agent.php';

$database = new Database();
$db = $database->getConnection();
$agent = new Agent($db);

if(isset($_GET['id'])) {
    $agent->id = $_GET['id'];
    // You'll need to add a readSingle method to Agent model
    $stmt = $agent->readSingle();
    $agentData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agent - Call Center</title>
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
                <h2>Edit Agent</h2>
                <a href="../index.php?page=agents" class="btn">Back to Agents</a>
            </div>

            <form method="POST" action="../index.php?page=agents&action=update">
                <input type="hidden" name="id" value="<?php echo $agentData['id']; ?>">
                
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($agentData['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($agentData['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($agentData['phone']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="department">Department:</label>
                    <select id="department" name="department" required>
                        <option value="Sales" <?php echo ($agentData['department'] == 'Sales') ? 'selected' : ''; ?>>Sales</option>
                        <option value="Support" <?php echo ($agentData['department'] == 'Support') ? 'selected' : ''; ?>>Support</option>
                        <option value="Billing" <?php echo ($agentData['department'] == 'Billing') ? 'selected' : ''; ?>>Billing</option>
                        <option value="Technical" <?php echo ($agentData['department'] == 'Technical') ? 'selected' : ''; ?>>Technical</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="active" <?php echo ($agentData['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($agentData['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn">Update Agent</button>
                <a href="../index.php?page=agents" class="btn btn-secondary">Cancel</a>
            </form>
        </main>
    </div>
</body>
</html>