<?php
// Get customer data for editing
require_once '../../models/Database.php';
require_once '../../models/Customer.php';

$database = new Database();
$db = $database->getConnection();
$customer = new Customer($db);

if(isset($_GET['id'])) {
    $customer->id = $_GET['id'];
    $stmt = $customer->readSingle();
    $customerData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer - Call Center</title>
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
                <h2>Edit Customer</h2>
                <a href="../index.php?page=customers" class="btn">Back to Customers</a>
            </div>

            <form method="POST" action="../index.php?page=customers&action=update">
                <input type="hidden" name="id" value="<?php echo $customerData['id']; ?>">
                
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" 
                           value="<?php echo htmlspecialchars($customerData['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($customerData['email']); ?>">
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" 
                           value="<?php echo htmlspecialchars($customerData['phone']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($customerData['address']); ?></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-success">Update Customer</button>
                    <a href="../index.php?page=customers" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>