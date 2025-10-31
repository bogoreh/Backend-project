<?php
class Game {
    private $board;
    private $currentPlayer;
    private $moveHistory;
    private $status;
    
    public function __construct() {
        $this->board = new ChessBoard();
        $this->currentPlayer = ChessPiece::WHITE;
        $this->moveHistory = [];
        $this->status = 'In Progress';
    }
    
    public function makeMove($from, $to) {
        $from = strtolower(trim($from));
        $to = strtolower(trim($to));
        
        // Validate position format
        if (!$this->isValidPosition($from) || !$this->isValidPosition($to)) {
            $this->status = 'Invalid position format';
            return false;
        }
        
        $piece = $this->board->getPieceAt($from);
        
        // Check if there's a piece at the starting position
        if (!$piece) {
            $this->status = 'No piece at starting position';
            return false;
        }
        
        // Check if it's the correct player's turn
        if ($piece->getColor() !== $this->currentPlayer) {
            $this->status = "It's " . $this->currentPlayer . "'s turn";
            return false;
        }
        
        // Check if move is valid
        if (!$piece->isValidMove($from, $to, $this->board->getBoardState())) {
            $this->status = 'Invalid move for ' . $piece->getType();
            return false;
        }
        
        // Execute the move
        $this->board->movePiece($from, $to);
        
        // Record the move
        $moveNotation = $this->currentPlayer . ': ' . $piece->getType() . ' from ' . $from . ' to ' . $to;
        $this->moveHistory[] = $moveNotation;
        
        // Switch players
        $this->currentPlayer = ($this->currentPlayer === ChessPiece::WHITE) ? ChessPiece::BLACK : ChessPiece::WHITE;
        $this->status = 'Move successful';
        
        return true;
    }
    
    private function isValidPosition($position) {
        return preg_match('/^[a-h][1-8]$/', $position);
    }
    
    public function getBoard() {
        return $this->board;
    }
    
    public function getCurrentPlayer() {
        return ucfirst($this->currentPlayer);
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    public function getMoveHistory() {
        return $this->moveHistory;
    }
}
?>