<?php
// Get call data for editing
require_once '../../models/Database.php';
require_once '../../models/Call.php';

$database = new Database();
$db = $database->getConnection();
$call = new CallRecord($db);

if(isset($_GET['id'])) {
    $call->id = $_GET['id'];
    $stmt = $call->readSingle();
    $callData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch customers and agents for dropdowns
require_once '../../models/Customer.php';
require_once '../../models/Agent.php';

$customer = new Customer($db);
$customers = $customer->read()->fetchAll(PDO::FETCH_ASSOC);

$agent = new Agent($db);
$agents = $agent->read()->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Call - Call Center</title>
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
                <h2>Edit Call Record</h2>
                <a href="../index.php?page=calls" class="btn">Back to Calls</a>
            </div>

            <form method="POST" action="../index.php?page=calls&action=update">
                <input type="hidden" name="id" value="<?php echo $callData['id']; ?>">
                
                <div class="form-group">
                    <label for="customer_id">Customer:</label>
                    <select id="customer_id" name="customer_id" required>
                        <option value="">Select Customer</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?php echo $customer['id']; ?>" 
                                <?php echo ($callData['customer_id'] == $customer['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($customer['name'] . ' - ' . $customer['phone']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="agent_id">Agent:</label>
                    <select id="agent_id" name="agent_id" required>
                        <option value="">Select Agent</option>
                        <?php foreach ($agents as $agent): ?>
                            <option value="<?php echo $agent['id']; ?>" 
                                <?php echo ($callData['agent_id'] == $agent['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($agent['name'] . ' - ' . $agent['department']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="call_type">Call Type:</label>
                    <select id="call_type" name="call_type" required>
                        <option value="incoming" <?php echo ($callData['call_type'] == 'incoming') ? 'selected' : ''; ?>>Incoming</option>
                        <option value="outgoing" <?php echo ($callData['call_type'] == 'outgoing') ? 'selected' : ''; ?>>Outgoing</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="duration">Duration (minutes):</label>
                    <input type="number" id="duration" name="duration" min="0" 
                           value="<?php echo $callData['duration']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="completed" <?php echo ($callData['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="missed" <?php echo ($callData['status'] == 'missed') ? 'selected' : ''; ?>>Missed</option>
                        <option value="callback" <?php echo ($callData['status'] == 'callback') ? 'selected' : ''; ?>>Callback</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="call_time">Call Time:</label>
                    <input type="datetime-local" id="call_time" name="call_time" 
                           value="<?php echo date('Y-m-d\TH:i', strtotime($callData['call_time'])); ?>" required>
                </div>

                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea id="notes" name="notes" rows="4"><?php echo htmlspecialchars($callData['notes']); ?></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-success">Update Call Record</button>
                    <a href="../index.php?page=calls" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>