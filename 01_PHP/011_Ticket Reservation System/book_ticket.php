<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/database.php';

if (!isset($_GET['event_id'])) {
    redirect('index.php');
}

$event_id = (int)$_GET['event_id'];
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Event not found!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = (int)$_POST['quantity'];
    
    if ($quantity <= 0 || $quantity > $event['available_tickets']) {
        $error = "Invalid quantity selected!";
    } else {
        $total_price = $quantity * $event['price'];
        
        // Start transaction
        $pdo->beginTransaction();
        
        try {
            // Insert ticket
            $stmt = $pdo->prepare("INSERT INTO tickets (user_id, event_id, quantity, total_price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $event_id, $quantity, $total_price]);
            
            // Update available tickets
            $stmt = $pdo->prepare("UPDATE events SET available_tickets = available_tickets - ? WHERE id = ?");
            $stmt->execute([$quantity, $event_id]);
            
            $pdo->commit();
            $_SESSION['success'] = "Ticket booked successfully!";
            redirect('my_tickets.php');
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Booking failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ticket - Ticket System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎫 Book Ticket</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>

        <main>
            <div class="booking-form">
                <h2>Book: <?php echo htmlspecialchars($event['name']); ?></h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert error"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="event-details">
                    <p><strong>Date:</strong> <?php echo date('M j, Y', strtotime($event['date'])); ?></p>
                    <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($event['time'])); ?></p>
                    <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                    <p><strong>Price per ticket:</strong> $<?php echo $event['price']; ?></p>
                    <p><strong>Available tickets:</strong> <?php echo $event['available_tickets']; ?></p>
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label for="quantity">Number of Tickets:</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $event['available_tickets']; ?>" required>
                    </div>
                    <button type="submit" class="btn">Book Now</button>
                    <a href="index.php" class="btn secondary">Cancel</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>