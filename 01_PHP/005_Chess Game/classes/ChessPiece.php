<?php
class ChessPiece {
    const WHITE = 'white';
    const BLACK = 'black';
    
    const PAWN = 'pawn';
    const ROOK = 'rook';
    const KNIGHT = 'knight';
    const BISHOP = 'bishop';
    const QUEEN = 'queen';
    const KING = 'king';
    
    private $color;
    private $type;
    private $symbol;
    
    public function __construct($color, $type) {
        $this->color = $color;
        $this->type = $type;
        $this->symbol = $this->getSymbol();
    }
    
    private function getSymbol() {
        $symbols = [
            self::WHITE => [
                self::PAWN => '♙',
                self::ROOK => '♖',
                self::KNIGHT => '♘',
                self::BISHOP => '♗',
                self::QUEEN => '♕',
                self::KING => '♔'
            ],
            self::BLACK => [
                self::PAWN => '♟',
                self::ROOK => '♜',
                self::KNIGHT => '♞',
                self::BISHOP => '♝',
                self::QUEEN => '♛',
                self::KING => '♚'
            ]
        ];
        
        return $symbols[$this->color][$this->type] ?? '';
    }
    
    public function getColor() {
        return $this->color;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function getSymbol() {
        return $this->symbol;
    }
    
    public function isValidMove($from, $to, $board) {
        // Basic move validation - in a real game, this would be more complex
        $fromFile = $from[0];
        $fromRank = $from[1];
        $toFile = $to[0];
        $toRank = $to[1];
        
        // Simple movement rules for demonstration
        switch ($this->type) {
            case self::PAWN:
                $direction = ($this->color === self::WHITE) ? 1 : -1;
                $startRank = ($this->color === self::WHITE) ? '2' : '7';
                
                // Basic pawn move
                if ($fromFile === $toFile) {
                    $moveDistance = $toRank - $fromRank;
                    if ($moveDistance == $direction) {
                        return empty($board[$to]);
                    }
                    // Initial double move
                    if ($fromRank == $startRank && $moveDistance == 2 * $direction) {
                        return empty($board[$to]) && empty($board[$fromFile . ($fromRank + $direction)]);
                    }
                }
                break;
                
            case self::ROOK:
                return ($fromFile === $toFile || $fromRank === $toRank);
                
            case self::BISHOP:
                return abs(ord($fromFile) - ord($toFile)) == abs($fromRank - $toRank);
                
            case self::QUEEN:
                return ($fromFile === $toFile || $fromRank === $toRank) || 
                       (abs(ord($fromFile) - ord($toFile)) == abs($fromRank - $toRank));
                
            case self::KING:
                return abs(ord($fromFile) - ord($toFile)) <= 1 && abs($fromRank - $toRank) <= 1;
                
            case self::KNIGHT:
                $fileDiff = abs(ord($fromFile) - ord($toFile));
                $rankDiff = abs($fromRank - $toRank);
                return ($fileDiff == 1 && $rankDiff == 2) || ($fileDiff == 2 && $rankDiff == 1);
        }
        
        return false;
    }
}
?>