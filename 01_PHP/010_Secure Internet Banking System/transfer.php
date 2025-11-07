<?php
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Get user balance
$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to_account = sanitizeInput($_POST['to_account']);
    $amount = floatval($_POST['amount']);
    $description = sanitizeInput($_POST['description']);
    
    // Validation
    if ($amount <= 0) {
        $error = "Amount must be greater than 0!";
    } elseif ($amount > $user['balance']) {
        $error = "Insufficient balance!";
    } else {
        // Check if recipient account exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE account_number = ?");
        $stmt->execute([$to_account]);
        $recipient = $stmt->fetch();
        
        if (!$recipient) {
            $error = "Recipient account not found!";
        } elseif ($to_account == $_SESSION['account_number']) {
            $error = "Cannot transfer to your own account!";
        } else {
            try {
                $pdo->beginTransaction();
                
                // Deduct from sender
                $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
                $stmt->execute([$amount, $_SESSION['user_id']]);
                
                // Add to recipient
                $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE account_number = ?");
                $stmt->execute([$amount, $to_account]);
                
                // Record transaction
                $stmt = $pdo->prepare("INSERT INTO transactions (from_account, to_account, amount, transaction_type, description) VALUES (?, ?, ?, 'transfer', ?)");
                $stmt->execute([$_SESSION['account_number'], $to_account, $amount, $description]);
                
                $pdo->commit();
                
                $_SESSION['message'] = "Transfer successful!";
                $_SESSION['message_type'] = 'success';
                header('Location: dashboard.php');
                exit();
                
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Transfer failed: " . $e->getMessage();
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="transfer-container">
    <div class="transfer-card">
        <h2><i class="fas fa-exchange-alt"></i> Transfer Money</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="balance-info">
            Available Balance: <strong>$<?php echo number_format($user['balance'], 2); ?></strong>
        </div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="to_account">Recipient Account Number</label>
                <input type="text" id="to_account" name="to_account" required class="form-control" placeholder="Enter account number">
            </div>
            
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0.01" max="<?php echo $user['balance']; ?>" required class="form-control" placeholder="0.00">
            </div>
            
            <div class="form-group">
                <label for="description">Description (Optional)</label>
                <textarea id="description" name="description" class="form-control" placeholder="Add a note for this transfer"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-paper-plane"></i> Transfer Now
            </button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>