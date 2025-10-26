const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const path = require('path');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

// Serve static files
app.use(express.static(path.join(__dirname, 'public')));

// Game state
let gameState = {
    board: initializeBoard(),
    currentPlayer: 'white',
    gameOver: false
};

// Initialize chess board
function initializeBoard() {
    return [
        ['br', 'bn', 'bb', 'bq', 'bk', 'bb', 'bn', 'br'],
        ['bp', 'bp', 'bp', 'bp', 'bp', 'bp', 'bp', 'bp'],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['', '', '', '', '', '', '', ''],
        ['wp', 'wp', 'wp', 'wp', 'wp', 'wp', 'wp', 'wp'],
        ['wr', 'wn', 'wb', 'wq', 'wk', 'wb', 'wn', 'wr']
    ];
}

// Socket.io connection handling
io.on('connection', (socket) => {
    console.log('User connected:', socket.id);

    // Send current game state to new client
    socket.emit('gameState', gameState);

    // Handle move attempts
    socket.on('move', (data) => {
        const { from, to } = data;
        
        if (isValidMove(from, to, gameState)) {
            // Make the move
            const piece = gameState.board[from.row][from.col];
            gameState.board[from.row][from.col] = '';
            gameState.board[to.row][to.col] = piece;
            
            // Switch player
            gameState.currentPlayer = gameState.currentPlayer === 'white' ? 'black' : 'white';
            
            // Broadcast updated game state to all clients
            io.emit('gameState', gameState);
        }
    });

    // Handle reset game
    socket.on('resetGame', () => {
        gameState = {
            board: initializeBoard(),
            currentPlayer: 'white',
            gameOver: false
        };
        io.emit('gameState', gameState);
    });

    socket.on('disconnect', () => {
        console.log('User disconnected:', socket.id);
    });
});

// Simple move validation (basic rules)
function isValidMove(from, to, gameState) {
    const piece = gameState.board[from.row][from.col];
    
    // Check if there's a piece to move
    if (!piece) return false;
    
    // Check if it's the correct player's turn
    const pieceColor = piece[0];
    if ((pieceColor === 'w' && gameState.currentPlayer !== 'white') ||
        (pieceColor === 'b' && gameState.currentPlayer !== 'black')) {
        return false;
    }
    
    // Basic boundary checks
    if (from.row < 0 || from.row > 7 || from.col < 0 || from.col > 7 ||
        to.row < 0 || to.row > 7 || to.col < 0 || to.col > 7) {
        return false;
    }
    
    // Can't capture own pieces
    const targetPiece = gameState.board[to.row][to.col];
    if (targetPiece && targetPiece[0] === piece[0]) {
        return false;
    }
    
    // For this simple version, allow any move (you can add proper chess rules here)
    return true;
}

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log(`Chess game server running on http://localhost:${PORT}`);
});