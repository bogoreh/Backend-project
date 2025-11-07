<?php
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Get all transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE from_account = ? OR to_account = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['account_number'], $_SESSION['account_number']]);
$transactions = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<div class="history-container">
    <h2><i class="fas fa-history"></i> Transaction History</h2>
    
    <?php if (empty($transactions)): ?>
        <div class="no-transactions">
            <p>No transactions found.</p>
        </div>
    <?php else: ?>
        <div class="transactions-table">
            <table>
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>From/To</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo date('M d, Y H:i', strtotime($transaction['created_at'])); ?></td>
                            <td><?php echo ucfirst($transaction['transaction_type']); ?></td>
                            <td>
                                <?php if ($transaction['from_account'] == $_SESSION['account_number']): ?>
                                    To: <?php echo $transaction['to_account']; ?>
                                <?php else: ?>
                                    From: <?php echo $transaction['from_account']; ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $transaction['description'] ?: 'N/A'; ?></td>
                            <td class="<?php echo $transaction['from_account'] == $_SESSION['account_number'] ? 'debit' : 'credit'; ?>">
                                <?php echo $transaction['from_account'] == $_SESSION['account_number'] ? '-' : '+'; ?>
                                $<?php echo number_format($transaction['amount'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>