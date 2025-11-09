<?php
class Book {
    private $conn;
    private $table_name = "books";

    public $id;
    public $title;
    public $author;
    public $isbn;
    public $description;
    public $cover_image;
    public $published_year;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function search($query) {
        $sql = "SELECT * FROM " . $this->table_name . " 
                WHERE title LIKE :query 
                OR author LIKE :query 
                OR isbn LIKE :query 
                OR category LIKE :query 
                ORDER BY title";
        
        $stmt = $this->conn->prepare($sql);
        $search_query = "%" . $query . "%";
        $stmt->bindParam(":query", $search_query);
        $stmt->execute();
        
        return $stmt;
    }

    public function getAll() {
        $sql = "SELECT * FROM " . $this->table_name . " ORDER BY title";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }
}
?>