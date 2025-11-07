<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/database.php';

// Get user's tickets
$stmt = $pdo->prepare("
    SELECT t.*, e.name as event_name, e.date, e.time, e.venue 
    FROM tickets t 
    JOIN events e ON t.event_id = e.id 
    WHERE t.user_id = ? 
    ORDER BY t.booking_date DESC
");
$stmt->execute([$_SESSION['user_id']]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets - Ticket System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎫 My Tickets</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>

        <main>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <h2>Your Ticket Bookings</h2>
            
            <?php if (empty($tickets)): ?>
                <div class="no-tickets">
                    <p>You haven't booked any tickets yet.</p>
                    <a href="index.php" class="btn">Browse Events</a>
                </div>
            <?php else: ?>
                <div class="tickets-grid">
                    <?php foreach ($tickets as $ticket): ?>
                        <div class="ticket-card">
                            <h3><?php echo htmlspecialchars($ticket['event_name']); ?></h3>
                            <div class="ticket-details">
                                <p><strong>Booking Date:</strong> <?php echo date('M j, Y g:i A', strtotime($ticket['booking_date'])); ?></p>
                                <p><strong>Event Date:</strong> <?php echo date('M j, Y', strtotime($ticket['date'])); ?></p>
                                <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($ticket['time'])); ?></p>
                                <p><strong>Venue:</strong> <?php echo htmlspecialchars($ticket['venue']); ?></p>
                                <p><strong>Quantity:</strong> <?php echo $ticket['quantity']; ?></p>
                                <p><strong>Total Price:</strong> $<?php echo $ticket['total_price']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>