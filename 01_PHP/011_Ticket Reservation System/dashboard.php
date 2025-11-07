<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/database.php';

// Get user's recent tickets
$stmt = $pdo->prepare("
    SELECT t.*, e.name as event_name, e.date, e.venue 
    FROM tickets t 
    JOIN events e ON t.event_id = e.id 
    WHERE t.user_id = ? 
    ORDER BY t.booking_date DESC 
    LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$recent_tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ticket System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎫 Dashboard</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="my_tickets.php">My Tickets</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>

        <main>
            <div class="welcome">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            </div>

            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3>Quick Actions</h3>
                    <a href="index.php" class="btn">Browse Events</a>
                    <a href="my_tickets.php" class="btn">View My Tickets</a>
                </div>

                <div class="dashboard-card">
                    <h3>Recent Bookings</h3>
                    <?php if (empty($recent_tickets)): ?>
                        <p>No recent bookings found.</p>
                    <?php else: ?>
                        <?php foreach ($recent_tickets as $ticket): ?>
                            <div class="ticket-item">
                                <strong><?php echo htmlspecialchars($ticket['event_name']); ?></strong>
                                <br>Quantity: <?php echo $ticket['quantity']; ?>
                                <br>Total: $<?php echo $ticket['total_price']; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>