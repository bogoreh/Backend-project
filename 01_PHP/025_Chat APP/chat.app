<?php
include 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Update online status
$stmt = $pdo->prepare("REPLACE INTO online_users (user_id) VALUES (?)");
$stmt->execute([$_SESSION['user_id']]);

// Remove users who haven't been seen for 5 minutes
$pdo->exec("DELETE FROM online_users WHERE last_seen < NOW() - INTERVAL 5 MINUTE");
?>

<?php include 'includes/header.php'; ?>
<div class="chat-container">
    <div class="chat-header">
        <h2>Chat Room</h2>
        <div class="user-info">
            <span>Welcome, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="chat-layout">
        <div class="online-users">
            <h3>Online Users</h3>
            <div id="online-users-list">
                <!-- Online users will be loaded here -->
            </div>
        </div>
        
        <div class="chat-main">
            <div class="messages-container">
                <div id="messages">
                    <!-- Messages will be loaded here -->
                </div>
            </div>
            
            <div class="message-input">
                <form id="message-form">
                    <input type="text" id="message" placeholder="Type your message..." autocomplete="off" required>
                    <button type="submit">Send</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const userId = <?php echo $_SESSION['user_id']; ?>;
const username = "<?php echo $_SESSION['username']; ?>";
</script>
<?php include 'includes/footer.php'; ?>