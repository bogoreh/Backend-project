<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Call - Call Center</title>
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
                <h2>Add New Call Record</h2>
                <a href="../index.php?page=calls" class="btn">Back to Calls</a>
            </div>

            <form method="POST" action="../index.php?page=calls&action=create">
                <div class="form-group">
                    <label for="customer_id">Customer:</label>
                    <select id="customer_id" name="customer_id" required>
                        <option value="">Select Customer</option>
                        <?php
                        // You'll need to fetch customers from database
                        require_once '../../models/Database.php';
                        require_once '../../models/Customer.php';
                        $database = new Database();
                        $db = $database->getConnection();
                        $customer = new Customer($db);
                        $stmt = $customer->read();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['id']}'>{$row['name']} - {$row['phone']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="agent_id">Agent:</label>
                    <select id="agent_id" name="agent_id" required>
                        <option value="">Select Agent</option>
                        <?php
                        // Fetch agents from database
                        require_once '../../models/Agent.php';
                        $agent = new Agent($db);
                        $stmt = $agent->read();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['id']}'>{$row['name']} - {$row['department']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="call_type">Call Type:</label>
                    <select id="call_type" name="call_type" required>
                        <option value="incoming">Incoming</option>
                        <option value="outgoing">Outgoing</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="duration">Duration (minutes):</label>
                    <input type="number" id="duration" name="duration" min="0" required>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="completed">Completed</option>
                        <option value="missed">Missed</option>
                        <option value="callback">Callback</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="call_time">Call Time:</label>
                    <input type="datetime-local" id="call_time" name="call_time" required>
                </div>

                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea id="notes" name="notes" rows="4"></textarea>
                </div>

                <button type="submit" class="btn">Add Call Record</button>
                <a href="../index.php?page=calls" class="btn btn-secondary">Cancel</a>
            </form>
        </main>
    </div>
</body>
</html>