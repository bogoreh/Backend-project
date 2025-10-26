const socket = io();
let selectedSquare = null;
let gameState = {};

// Initialize the game
socket.on('gameState', (state) => {
    gameState = state;
    renderBoard();
    updateGameInfo();
});

// Render the chess board
function renderBoard() {
    const board = document.getElementById('chess-board');
    board.innerHTML = '';
    
    for (let row = 0; row < 8; row++) {
        const rowElement = document.createElement('div');
        rowElement.className = 'row';
        
        for (let col = 0; col < 8; col++) {
            const square = document.createElement('div');
            const isLight = (row + col) % 2 === 0;
            square.className = `square ${isLight ? 'light' : 'dark'}`;
            square.dataset.row = row;
            square.dataset.col = col;
            
            const piece = gameState.board[row][col];
            if (piece) {
                const pieceElement = document.createElement('div');
                pieceElement.className = 'piece';
                pieceElement.textContent = getPieceSymbol(piece);
                pieceElement.style.color = piece[0] === 'w' ? 'white' : 'black';
                square.appendChild(pieceElement);
            }
            
            square.addEventListener('click', () => handleSquareClick(row, col));
            rowElement.appendChild(square);
        }
        
        board.appendChild(rowElement);
    }
}

// Get Unicode symbols for chess pieces
function getPieceSymbol(piece) {
    const symbols = {
        'wp': '♙', 'wr': '♖', 'wn': '♘', 'wb': '♗', 'wq': '♕', 'wk': '♔',
        'bp': '♟', 'br': '♜', 'bn': '♞', 'bb': '♝', 'bq': '♛', 'bk': '♚'
    };
    return symbols[piece] || '';
}

// Handle square clicks
function handleSquareClick(row, col) {
    const piece = gameState.board[row][col];
    
    if (selectedSquare) {
        // Attempt to move
        socket.emit('move', {
            from: selectedSquare,
            to: { row, col }
        });
        selectedSquare = null;
        clearSelection();
    } else if (piece) {
        // Check if it's the correct player's piece
        const pieceColor = piece[0];
        const currentPlayerColor = gameState.currentPlayer[0];
        
        if (pieceColor === currentPlayerColor) {
            selectedSquare = { row, col };
            highlightSelectedSquare(row, col);
        }
    }
}

// Highlight selected square
function highlightSelectedSquare(row, col) {
    clearSelection();
    const squares = document.querySelectorAll('.square');
    squares.forEach(square => {
        if (parseInt(square.dataset.row) === row && 
            parseInt(square.dataset.col) === col) {
            square.classList.add('selected');
        }
    });
}

// Clear selection highlights
function clearSelection() {
    const squares = document.querySelectorAll('.square');
    squares.forEach(square => {
        square.classList.remove('selected', 'valid-move');
    });
}

// Update game information display
function updateGameInfo() {
    const currentPlayerElement = document.getElementById('current-player');
    currentPlayerElement.textContent = `Current Player: ${gameState.currentPlayer.charAt(0).toUpperCase() + gameState.currentPlayer.slice(1)}`;
    currentPlayerElement.style.color = gameState.currentPlayer === 'white' ? '#2c3e50' : '#e74c3c';
}

// Reset game button
document.getElementById('reset-btn').addEventListener('click', () => {
    socket.emit('resetGame');
});

// Handle connection events
socket.on('connect', () => {
    console.log('Connected to server');
});

socket.on('disconnect', () => {
    console.log('Disconnected from server');
});