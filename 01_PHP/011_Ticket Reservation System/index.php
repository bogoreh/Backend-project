<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

// Get upcoming events
$stmt = $pdo->query("SELECT * FROM events WHERE date >= CURDATE() ORDER BY date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Reservation System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎫 Ticket Reservation System</h1>
            <nav>
                <?php if (is_logged_in()): ?>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="my_tickets.php">My Tickets</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </nav>
        </header>

        <main>
            <section class="hero">
                <h2>Book Your Tickets Online</h2>
                <p>Secure and easy ticket reservation for all your favorite events</p>
            </section>

            <section class="events">
                <h3>Upcoming Events</h3>
                <div class="event-grid">
                    <?php foreach ($events as $event): ?>
                        <div class="event-card">
                            <h4><?php echo htmlspecialchars($event['name']); ?></h4>
                            <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                            <div class="event-details">
                                <p><strong>Date:</strong> <?php echo date('M j, Y', strtotime($event['date'])); ?></p>
                                <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($event['time'])); ?></p>
                                <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                                <p><strong>Price:</strong> $<?php echo $event['price']; ?></p>
                                <p><strong>Tickets Available:</strong> <?php echo $event['available_tickets']; ?></p>
                            </div>
                            <?php if (is_logged_in()): ?>
                                <a href="book_ticket.php?event_id=<?php echo $event['id']; ?>" class="btn">Book Now</a>
                            <?php else: ?>
                                <a href="login.php" class="btn">Login to Book</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>