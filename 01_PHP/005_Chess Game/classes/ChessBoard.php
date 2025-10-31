<?php
class ChessBoard {
    private $board = [];
    private $files = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
    private $ranks = [8, 7, 6, 5, 4, 3, 2, 1];
    
    public function __construct() {
        $this->initializeBoard();
    }
    
    private function initializeBoard() {
        // Clear board
        $this->board = [];
        
        // Set up pawns
        for ($i = 0; $i < 8; $i++) {
            $file = $this->files[$i];
            $this->board[$file . '2'] = new ChessPiece(ChessPiece::WHITE, ChessPiece::PAWN);
            $this->board[$file . '7'] = new ChessPiece(ChessPiece::BLACK, ChessPiece::PAWN);
        }
        
        // Set up other pieces
        $backRowPieces = [
            ChessPiece::ROOK, ChessPiece::KNIGHT, ChessPiece::BISHOP, ChessPiece::QUEEN,
            ChessPiece::KING, ChessPiece::BISHOP, ChessPiece::KNIGHT, ChessPiece::ROOK
        ];
        
        for ($i = 0; $i < 8; $i++) {
            $file = $this->files[$i];
            $this->board[$file . '1'] = new ChessPiece(ChessPiece::WHITE, $backRowPieces[$i]);
            $this->board[$file . '8'] = new ChessPiece(ChessPiece::BLACK, $backRowPieces[$i]);
        }
    }
    
    public function getPieceAt($position) {
        return $this->board[$position] ?? null;
    }
    
    public function setPieceAt($position, $piece) {
        if ($piece === null) {
            unset($this->board[$position]);
        } else {
            $this->board[$position] = $piece;
        }
    }
    
    public function movePiece($from, $to) {
        $piece = $this->getPieceAt($from);
        if ($piece) {
            $this->setPieceAt($to, $piece);
            $this->setPieceAt($from, null);
            return true;
        }
        return false;
    }
    
    public function display() {
        $html = '<table class="chess-board">';
        
        for ($rank = 8; $rank >= 1; $rank--) {
            $html .= '<tr>';
            $html .= '<td class="rank-label">' . $rank . '</td>';
            
            for ($fileIndex = 0; $fileIndex < 8; $fileIndex++) {
                $file = $this->files[$fileIndex];
                $position = $file . $rank;
                $piece = $this->getPieceAt($position);
                
                $colorClass = (($rank + $fileIndex) % 2 == 0) ? 'white' : 'black';
                $pieceSymbol = $piece ? $piece->getSymbol() : '';
                $pieceColor = $piece ? $piece->getColor() : '';
                
                $html .= '<td class="square ' . $colorClass . ' ' . $pieceColor . '">';
                $html .= '<span class="piece">' . $pieceSymbol . '</span>';
                $html .= '</td>';
            }
            
            $html .= '</tr>';
        }
        
        // File labels
        $html .= '<tr><td></td>';
        foreach ($this->files as $file) {
            $html .= '<td class="file-label">' . $file . '</td>';
        }
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }
    
    public function getBoardState() {
        return $this->board;
    }
}
?>