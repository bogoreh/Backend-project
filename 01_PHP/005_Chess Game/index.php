<?php
session_start();
require_once 'classes/ChessPiece.php';
require_once 'classes/ChessBoard.php';
require_once 'classes/Game.php';

// Initialize or load game
if (!isset($_SESSION['chess_game'])) {
    $_SESSION['chess_game'] = new Game();
}
$game = $_SESSION['chess_game'];

// Handle moves
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['move'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $game->makeMove($from, $to);
}

// Reset game
if (isset($_GET['reset'])) {
    unset($_SESSION['chess_game']);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Chess Game</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Simple Chess Game</h1>
        
        <div class="game-info">
            <p>Current Player: <span class="player"><?php echo $game->getCurrentPlayer(); ?></span></p>
            <p>Game Status: <span class="status"><?php echo $game->getStatus(); ?></span></p>
        </div>

        <div class="chess-board">
            <?php echo $game->getBoard()->display(); ?>
        </div>

        <div class="controls">
            <form method="post" class="move-form">
                <div class="form-group">
                    <label>From:</label>
                    <input type="text" name="from" placeholder="e.g., e2" required>
                </div>
                <div class="form-group">
                    <label>To:</label>
                    <input type="text" name="to" placeholder="e.g., e4" required>
                </div>
                <button type="submit" name="move">Make Move</button>
            </form>
            
            <a href="?reset=true" class="reset-btn">New Game</a>
        </div>

        <div class="moves-history">
            <h3>Move History</h3>
            <ul>
                <?php
                $moves = $game->getMoveHistory();
                foreach ($moves as $move) {
                    echo "<li>{$move}</li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>