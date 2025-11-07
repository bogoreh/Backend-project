<?php
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Get recent transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE from_account = ? OR to_account = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION['account_number'], $_SESSION['account_number']]);
$transactions = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<div class="dashboard">
    <div class="welcome-section">
        <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
        <p>Account Number: <?php echo $_SESSION['account_number']; ?></p>
    </div>
    
    <div class="dashboard-grid">
        <div class="balance-card">
            <div class="balance-header">
                <i class="fas fa-wallet"></i>
                <h3>Current Balance</h3>
            </div>
            <div class="balance-amount">
                $<?php echo number_format($user['balance'], 2); ?>
            </div>
        </div>
        
        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <div class="action-buttons">
                <a href="transfer.php" class="btn btn-primary">
                    <i class="fas fa-exchange-alt"></i> Transfer Money
                </a>
                <a href="transaction_history.php" class="btn btn-secondary">
                    <i class="fas fa-history"></i> View History
                </a>
            </div>
        </div>
    </div>
    
    <div class="recent-transactions">
        <h3>Recent Transactions</h3>
        <?php if (empty($transactions)): ?>
            <p>No transactions found.</p>
        <?php else: ?>
            <div class="transactions-list">
                <?php foreach ($transactions as $transaction): ?>
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-type">
                                <?php echo ucfirst($transaction['transaction_type']); ?>
                            </div>
                            <div class="transaction-account">
                                To: <?php echo $transaction['to_account']; ?>
                            </div>
                            <div class="transaction-date">
                                <?php echo date('M d, Y H:i', strtotime($transaction['created_at'])); ?>
                            </div>
                        </div>
                        <div class="transaction-amount <?php echo $transaction['from_account'] == $_SESSION['account_number'] ? 'debit' : 'credit'; ?>">
                            <?php echo $transaction['from_account'] == $_SESSION['account_number'] ? '-' : '+'; ?>
                            $<?php echo number_format($transaction['amount'], 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>